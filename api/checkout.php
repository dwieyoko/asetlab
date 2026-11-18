<?php
/**
 * AsetLab Platform - Digital Product Checkout & Invoice Status API
 * Mendukung Guest Checkout (via access_token), Member Checkout (via JWT),
 * serta integrasi Midtrans Snap, Tripay, dan Simulator.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';
require_once __DIR__ . '/services/PaymentGatewayService.php';
require_once __DIR__ . '/services/OrderFulfillmentService.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function fetchOrderDetail(PDO $pdo, array $order): array {
    $stmtItems = $pdo->prepare(
        "SELECT i.*, p.slug, p.image, p.category, p.version, p.file_name, p.file_size,
                (p.file_path IS NOT NULL AND p.file_path != '') AS has_file
         FROM digital_order_items i
         LEFT JOIN products p ON p.id = i.product_id
         WHERE i.order_id = ?"
    );
    $stmtItems->execute([$order['id']]);
    $items = $stmtItems->fetchAll();

    foreach ($items as &$item) {
        $item['price']     = (float) $item['price'];
        $item['file_size'] = (int) ($item['file_size'] ?? 0);
        $item['has_file']  = (bool) ($item['has_file'] ?? false);
    }
    unset($item);

    $licenses = [];
    if ($order['status'] === 'paid') {
        $stmtLic = $pdo->prepare(
            "SELECT ul.id, ul.license_key, ul.order_item_id, ul.product_id, ul.download_count, ul.max_downloads, ul.last_downloaded_at,
                    p.title AS product_title, p.version, p.file_name, p.file_size,
                    (p.file_path IS NOT NULL AND p.file_path != '') AS has_file
             FROM user_licenses ul
             LEFT JOIN products p ON p.id = ul.product_id
             WHERE ul.order_id = ? AND ul.is_active = 1"
        );
        $stmtLic->execute([$order['id']]);
        $licenses = $stmtLic->fetchAll();
        foreach ($licenses as &$lic) {
            $lic['download_count'] = (int) $lic['download_count'];
            $lic['max_downloads']  = (int) $lic['max_downloads'];
            $lic['file_size']      = (int) ($lic['file_size'] ?? 0);
            $lic['has_file']       = (bool) ($lic['has_file'] ?? false);
        }
        unset($lic);
    }

    $order['subtotal']        = (float) $order['subtotal'];
    $order['discount_amount'] = (float) $order['discount_amount'];
    $order['admin_fee']       = (float) $order['admin_fee'];
    $order['total_amount']    = (float) $order['total_amount'];
    $order['items']           = $items;
    $order['licenses']        = $licenses;
    $order['gateway_config']  = PaymentGatewayService::getClientConfig();

    return $order;
}

try {
    // ------------------------------------------------------------
    // GET: Cek Detail Invoice & Status Pembayaran
    // ------------------------------------------------------------
    if ($method === 'GET') {
        $invoice = trim((string) ($_GET['invoice'] ?? ''));
        $token   = trim((string) ($_GET['token'] ?? ''));

        if ($invoice === '') {
            sendResponse(['status' => 'error', 'message' => 'Nomor invoice wajib disertakan'], 400);
        }

        $stmt = $pdo->prepare("SELECT * FROM digital_orders WHERE invoice_number = ? LIMIT 1");
        $stmt->execute([$invoice]);
        $order = $stmt->fetch();

        if (!$order) {
            sendResponse(['status' => 'error', 'message' => 'Invoice tidak ditemukan'], 404);
        }

        $currentUser = getCurrentUser($pdo);
        $isAdmin     = $currentUser && ($currentUser['role'] ?? '') === 'admin';
        $isOwner     = $currentUser && (
            ((int) ($order['user_id'] ?? 0) === (int) $currentUser['id']) ||
            (strtolower($order['buyer_email']) === strtolower($currentUser['email']))
        );
        $hasValidToken = $token !== '' && hash_equals((string) $order['access_token'], $token);

        if (!$isAdmin && !$isOwner && !$hasValidToken) {
            sendResponse(['status' => 'error', 'message' => 'Akses invoice tidak diizinkan. Pastikan tautan token valid atau masuk ke akun Anda.'], 403);
        }

        if ($order['status'] === 'pending' && !empty($order['expired_at']) && strtotime($order['expired_at']) < time()) {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'expired');
            $order['status'] = 'expired';
        }

        sendResponse([
            'status' => 'success',
            'data'   => fetchOrderDetail($pdo, $order),
        ]);
    }

    // ------------------------------------------------------------
    // POST: Buat Transaksi Checkout Produk Digital Baru
    // ------------------------------------------------------------
    if ($method === 'POST') {
        $input       = getJsonInput();
        $currentUser = getCurrentUser($pdo);

        $buyerName     = trim((string) ($input['buyer_name'] ?? ($currentUser['name'] ?? '')));
        $buyerEmail    = strtolower(trim((string) ($input['buyer_email'] ?? ($currentUser['email'] ?? ''))));
        $buyerWhatsapp = preg_replace('/[^0-9+]/', '', (string) ($input['buyer_whatsapp'] ?? ($currentUser['whatsapp'] ?? '')));
        $paymentMethod = trim((string) ($input['payment_method'] ?? 'QRIS Dinamis'));
        $couponCode    = strtoupper(trim((string) ($input['coupon_code'] ?? '')));
        $productIds    = $input['product_ids'] ?? [];

        if ($buyerName === '' || $buyerEmail === '' || $buyerWhatsapp === '') {
            sendResponse(['status' => 'error', 'message' => 'Nama lengkap, email, dan nomor WhatsApp wajib diisi'], 400);
        }

        if (!filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            sendResponse(['status' => 'error', 'message' => 'Format alamat email tidak valid'], 400);
        }

        if (!is_array($productIds) || count($productIds) === 0) {
            sendResponse(['status' => 'error', 'message' => 'Keranjang belanja masih kosong'], 400);
        }

        $uniqueIds = array_values(array_unique(array_filter(array_map('strval', $productIds))));
        if (count($uniqueIds) === 0) {
            sendResponse(['status' => 'error', 'message' => 'Daftar produk tidak valid'], 400);
        }

        $placeholders = implode(',', array_fill(0, count($uniqueIds), '?'));
        $stmtProd = $pdo->prepare("SELECT * FROM products WHERE id IN ({$placeholders}) AND status = 'Live'");
        $stmtProd->execute($uniqueIds);
        $dbProducts = $stmtProd->fetchAll();

        if (count($dbProducts) === 0) {
            sendResponse(['status' => 'error', 'message' => 'Produk yang dipilih tidak tersedia atau belum Live'], 404);
        }

        $subtotal = 0.0;
        foreach ($dbProducts as $p) {
            $subtotal += (float) $p['price'];
        }

        $discountAmount = 0.0;
        $appliedCoupon  = null;
        if ($couponCode !== '') {
            $stmtC = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 LIMIT 1");
            $stmtC->execute([$couponCode]);
            $coupon = $stmtC->fetch();
            if (!$coupon) {
                sendResponse(['status' => 'error', 'message' => 'Kode kupon tidak valid atau sudah tidak aktif'], 400);
            }
            if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
                sendResponse(['status' => 'error', 'message' => 'Kode kupon sudah kedaluwarsa'], 400);
            }
            if ($coupon['max_uses'] !== null && (int) $coupon['used_count'] >= (int) $coupon['max_uses']) {
                sendResponse(['status' => 'error', 'message' => 'Kuota kupon telah habis'], 400);
            }
            if ($subtotal < (float) $coupon['min_purchase']) {
                sendResponse(['status' => 'error', 'message' => 'Minimal pembelian kupon belum terpenuhi'], 400);
            }

            if ($coupon['discount_type'] === 'percent') {
                $discountAmount = round($subtotal * ((float) $coupon['discount_value'] / 100));
            } else {
                $discountAmount = (float) $coupon['discount_value'];
            }
            $discountAmount = min($subtotal, max(0, $discountAmount));
            $appliedCoupon  = $coupon;
        }

        $adminFee    = 0.0;
        $totalAmount = max(0, $subtotal - $discountAmount + $adminFee);

        $linkedUserId = $currentUser ? (int) $currentUser['id'] : null;
        if ($linkedUserId === null) {
            $stmtFindUser = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmtFindUser->execute([$buyerEmail]);
            $existingUser = $stmtFindUser->fetch();
            if ($existingUser) {
                $linkedUserId = (int) $existingUser['id'];
            }
        }

        $orderId       = 'DORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $invoiceNumber = 'INV-' . date('Ymd') . '-DIGI-' . random_int(1000, 9999);
        $accessToken   = generateSecureToken(24);
        $gateway       = PaymentGatewayService::getActiveGateway();
        $expiredAt     = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $pdo->beginTransaction();

        $stmtOrder = $pdo->prepare(
            "INSERT INTO digital_orders
             (id, invoice_number, access_token, user_id, buyer_name, buyer_email, buyer_whatsapp,
              subtotal, discount_amount, admin_fee, total_amount, coupon_code,
              payment_gateway, payment_method, status, expired_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)"
        );
        $stmtOrder->execute([
            $orderId,
            $invoiceNumber,
            $accessToken,
            $linkedUserId,
            $buyerName,
            $buyerEmail,
            $buyerWhatsapp,
            $subtotal,
            $discountAmount,
            $adminFee,
            $totalAmount,
            $appliedCoupon ? $appliedCoupon['code'] : null,
            $gateway,
            $paymentMethod,
            $expiredAt,
        ]);

        $stmtItem = $pdo->prepare(
            "INSERT INTO digital_order_items (order_id, product_id, product_title, product_sku, license_type, price)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $itemTitles = [];
        $orderItemsPayload = [];
        foreach ($dbProducts as $p) {
            $licType = $p['license_type'] ?: 'Commercial Single Project';
            $stmtItem->execute([
                $orderId,
                $p['id'],
                $p['title'],
                $p['sku'],
                $licType,
                (float) $p['price'],
            ]);
            $itemTitles[] = $p['title'];
            $orderItemsPayload[] = [
                'product_id'    => $p['id'],
                'product_title' => $p['title'],
                'product_sku'   => $p['sku'],
                'price'         => (float) $p['price'],
            ];
        }

        if ($appliedCoupon) {
            $stmtIncCoupon = $pdo->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
            $stmtIncCoupon->execute([$appliedCoupon['id']]);
        }

        $trxId       = 'TRX-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $trxCategory = count($itemTitles) === 1
            ? $itemTitles[0]
            : ($itemTitles[0] . ' (+' . (count($itemTitles) - 1) . ' produk lainnya)');

        $stmtTrx = $pdo->prepare(
            "INSERT INTO transactions
             (id, order_id, ref_invoice, date, client, client_email, category, gateway, method, amount, fee_amount, net_amount, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, 'Menunggu')"
        );
        $stmtTrx->execute([
            $trxId,
            $orderId,
            $invoiceNumber,
            date('d M Y, H:i') . ' WIB',
            $buyerName,
            $buyerEmail,
            $trxCategory,
            $gateway,
            $paymentMethod,
            $totalAmount,
            $totalAmount,
        ]);

        $pdo->commit();

        // Reload order untuk inisiasi Payment Gateway atau Auto-Fulfill jika gratis (Rp 0)
        $stmtReload = $pdo->prepare("SELECT * FROM digital_orders WHERE id = ? LIMIT 1");
        $stmtReload->execute([$orderId]);
        $createdOrder = $stmtReload->fetch();

        if ($totalAmount <= 0) {
            OrderFulfillmentService::fulfillPaidOrder($pdo, $invoiceNumber, [
                'payment_method'    => 'Kupon Gratis 100%',
                'gateway_reference' => 'FREE-' . $invoiceNumber,
                'fee_amount'        => 0,
            ]);
        } else {
            try {
                $gwResult = PaymentGatewayService::createPayment($createdOrder, $orderItemsPayload);
                $stmtUpdGw = $pdo->prepare(
                    "UPDATE digital_orders
                     SET payment_gateway = ?, snap_token = ?, payment_url = ?, gateway_reference = ?
                     WHERE id = ?"
                );
                $stmtUpdGw->execute([
                    $gwResult['gateway'],
                    $gwResult['snap_token'],
                    $gwResult['payment_url'],
                    $gwResult['gateway_reference'],
                    $orderId,
                ]);
            } catch (Throwable $gwErr) {
                error_log('[AsetLab Payment Gateway Error] ' . $gwErr->getMessage());
                sendResponse([
                    'status'  => 'error',
                    'message' => 'Pesanan tercatat, namun koneksi payment gateway gagal: ' . $gwErr->getMessage(),
                ], 502);
            }
        }

        $stmtReload->execute([$orderId]);
        $finalOrder = $stmtReload->fetch();

        sendResponse([
            'status'  => 'success',
            'message' => $totalAmount <= 0
                ? 'Pesanan gratis berhasil diproses! Aset digital langsung siap diunduh.'
                : 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.',
            'data'    => fetchOrderDetail($pdo, $finalOrder),
        ], 201);
    }

    sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[AsetLab Checkout Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memproses checkout. Silakan coba kembali.',
    ], 500);
}
