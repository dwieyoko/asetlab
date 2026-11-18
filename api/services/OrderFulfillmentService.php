<?php
/**
 * AsetLab Platform - Idempotent Order Fulfillment Service
 * Menangani pelunasan order, penerbitan License Key, update counter penjualan,
 * pencatatan buku besar transaksi, dan pengiriman email notifikasi.
 */

require_once __DIR__ . '/MailService.php';

class OrderFulfillmentService
{
    /**
     * Proses pelunasan order secara atomic & idempotent (aman dari webhook ganda)
     */
    public static function fulfillPaidOrder(PDO $pdo, string $invoiceNumber, array $meta = []): array
    {
        $pdo->beginTransaction();

        try {
            // Row-level lock agar webhook paralel tidak menerbitkan lisensi ganda
            $stmtLock = $pdo->prepare("SELECT * FROM digital_orders WHERE invoice_number = ? FOR UPDATE");
            $stmtLock->execute([$invoiceNumber]);
            $order = $stmtLock->fetch();

            if (!$order) {
                $pdo->rollBack();
                throw new RuntimeException('Invoice tidak ditemukan: ' . $invoiceNumber);
            }

            $alreadyPaid = ($order['status'] === 'paid');

            $paymentMethod = trim((string) ($meta['payment_method'] ?? $order['payment_method'] ?? 'QRIS Dinamis'));
            $gatewayRef    = trim((string) ($meta['gateway_reference'] ?? $order['gateway_reference'] ?? ''));
            $feeAmount     = isset($meta['fee_amount']) ? max(0, (float) $meta['fee_amount']) : self::estimateGatewayFee((float) $order['total_amount'], $paymentMethod);
            $netAmount     = max(0, (float) $order['total_amount'] - $feeAmount);
            $rawWebhook    = isset($meta['raw_payload']) ? json_encode($meta['raw_payload'], JSON_UNESCAPED_UNICODE) : null;

            if (!$alreadyPaid) {
                $stmtUpdOrder = $pdo->prepare(
                    "UPDATE digital_orders
                     SET status = 'paid',
                         paid_at = NOW(),
                         payment_method = ?,
                         gateway_reference = COALESCE(NULLIF(?, ''), gateway_reference)
                     WHERE id = ?"
                );
                $stmtUpdOrder->execute([$paymentMethod, $gatewayRef, $order['id']]);

                // Update buku besar `transactions`
                $stmtUpdTrx = $pdo->prepare(
                    "UPDATE transactions
                     SET status = 'Berhasil',
                         method = ?,
                         gateway_ref = COALESCE(NULLIF(?, ''), gateway_ref),
                         fee_amount = ?,
                         net_amount = ?,
                         paid_at = NOW(),
                         raw_webhook = COALESCE(?, raw_webhook)
                     WHERE order_id = ? OR ref_invoice = ?"
                );
                $stmtUpdTrx->execute([
                    $paymentMethod,
                    $gatewayRef,
                    $feeAmount,
                    $netAmount,
                    $rawWebhook,
                    $order['id'],
                    $invoiceNumber,
                ]);

                // Ambil item order & terbitkan License Key jika belum ada
                $stmtItems = $pdo->prepare("SELECT * FROM digital_order_items WHERE order_id = ?");
                $stmtItems->execute([$order['id']]);
                $items = $stmtItems->fetchAll();

                $stmtCheckLic = $pdo->prepare("SELECT id FROM user_licenses WHERE order_item_id = ? LIMIT 1");
                $stmtInsLic = $pdo->prepare(
                    "INSERT INTO user_licenses
                     (license_key, order_id, order_item_id, user_id, buyer_email, product_id, download_count, max_downloads, is_active)
                     VALUES (?, ?, ?, ?, ?, ?, 0, 15, 1)"
                );
                $stmtIncSales = $pdo->prepare("UPDATE products SET downloads = downloads + 1 WHERE id = ?");

                foreach ($items as $item) {
                    $stmtCheckLic->execute([$item['id']]);
                    if (!$stmtCheckLic->fetch()) {
                        $licenseKey = generateLicenseKey();
                        $stmtInsLic->execute([
                            $licenseKey,
                            $order['id'],
                            $item['id'],
                            $order['user_id'] ?: null,
                            $order['buyer_email'],
                            $item['product_id'],
                        ]);
                        $stmtIncSales->execute([$item['product_id']]);
                    }
                }
            }

            $pdo->commit();

            // Muat ulang order & lisensi terbaru
            $stmtReload = $pdo->prepare("SELECT * FROM digital_orders WHERE id = ? LIMIT 1");
            $stmtReload->execute([$order['id']]);
            $updatedOrder = $stmtReload->fetch();

            $stmtItemsReload = $pdo->prepare("SELECT * FROM digital_order_items WHERE order_id = ?");
            $stmtItemsReload->execute([$order['id']]);
            $orderItems = $stmtItemsReload->fetchAll();

            $stmtLicReload = $pdo->prepare(
                "SELECT ul.*, p.title AS product_title
                 FROM user_licenses ul
                 LEFT JOIN products p ON p.id = ul.product_id
                 WHERE ul.order_id = ?"
            );
            $stmtLicReload->execute([$order['id']]);
            $licenses = $stmtLicReload->fetchAll();

            // Kirim email otomatis hanya pada transisi pertama kali menjadi lunas
            if (!$alreadyPaid) {
                try {
                    MailService::sendOrderPaidEmail($updatedOrder, $orderItems, $licenses);
                } catch (Throwable $mailErr) {
                    error_log('[AsetLab Mail Error] ' . $mailErr->getMessage());
                }
            }

            return [
                'order'        => $updatedOrder,
                'licenses'     => $licenses,
                'already_paid' => $alreadyPaid,
            ];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function markOrderClosed(PDO $pdo, string $invoiceNumber, string $newStatus, array $rawPayload = []): void
    {
        if (!in_array($newStatus, ['expired', 'failed', 'refunded'], true)) {
            return;
        }

        $stmt = $pdo->prepare("SELECT id, status FROM digital_orders WHERE invoice_number = ? LIMIT 1");
        $stmt->execute([$invoiceNumber]);
        $order = $stmt->fetch();
        if (!$order) {
            return;
        }

        // Jangan timpa order yang sudah paid kecuali statusnya refunded
        if ($order['status'] === 'paid' && $newStatus !== 'refunded') {
            return;
        }

        $pdo->beginTransaction();
        try {
            $stmtUpd = $pdo->prepare("UPDATE digital_orders SET status = ? WHERE id = ?");
            $stmtUpd->execute([$newStatus, $order['id']]);

            $rawJson = !empty($rawPayload) ? json_encode($rawPayload, JSON_UNESCAPED_UNICODE) : null;
            $stmtTrx = $pdo->prepare(
                "UPDATE transactions SET status = 'Gagal', raw_webhook = COALESCE(?, raw_webhook) WHERE order_id = ? OR ref_invoice = ?"
            );
            $stmtTrx->execute([$rawJson, $order['id'], $invoiceNumber]);

            if ($newStatus === 'refunded') {
                $stmtRevoke = $pdo->prepare("UPDATE user_licenses SET is_active = 0 WHERE order_id = ?");
                $stmtRevoke->execute([$order['id']]);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function estimateGatewayFee(float $amount, string $method): float
    {
        if ($amount <= 0) {
            return 0.0;
        }
        $lower = strtolower($method);
        if (str_contains($lower, 'qris') || str_contains($lower, 'gopay') || str_contains($lower, 'dana') || str_contains($lower, 'shopeepay')) {
            return round($amount * 0.007); // 0.7% MDR QRIS
        }
        if (str_contains($lower, 'card') || str_contains($lower, 'kredit') || str_contains($lower, 'visa')) {
            return round($amount * 0.029) + 2000.0;
        }
        if (str_contains($lower, 'va') || str_contains($lower, 'virtual') || str_contains($lower, 'bca') || str_contains($lower, 'mandiri') || str_contains($lower, 'bri') || str_contains($lower, 'bni')) {
            return 4000.0; // Flat VA fee
        }
        return 0.0;
    }
}
