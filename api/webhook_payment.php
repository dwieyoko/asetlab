<?php
/**
 * AsetLab Platform - Payment Webhook & Settlement Endpoint
 * Mendukung:
 *   - Midtrans Notification Webhook  (?gateway=midtrans)
 *   - Tripay Callback Webhook        (?gateway=tripay)
 *   - Mode Simulator (Sandbox Test)  (?gateway=simulator — hanya aktif bila PAYMENT_GATEWAY='simulator')
 *   - Konfirmasi Manual oleh Admin   (?action=admin_confirm — wajib JWT Admin)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';
require_once __DIR__ . '/services/PaymentGatewayService.php';
require_once __DIR__ . '/services/OrderFulfillmentService.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    sendResponse(['status' => 'error', 'message' => 'Webhook hanya menerima metode POST'], 405);
}

$rawBody = file_get_contents('php://input') ?: '';
$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    $payload = [];
}

$action  = $_GET['action'] ?? '';
$gateway = strtolower((string) ($_GET['gateway'] ?? ''));

// Auto-deteksi gateway dari payload/header jika query ?gateway= tidak diset
if ($gateway === '' && $action === '') {
    if (!empty($_SERVER['HTTP_X_CALLBACK_SIGNATURE'])) {
        $gateway = 'tripay';
    } elseif (!empty($payload['signature_key']) && !empty($payload['order_id'])) {
        $gateway = 'midtrans';
    }
}

try {
    // ------------------------------------------------------------
    // 1. Konfirmasi Manual oleh Admin (berlaku di semua mode)
    // ------------------------------------------------------------
    if ($action === 'admin_confirm') {
        requireAdmin($pdo);
        $invoice = trim((string) ($payload['invoice'] ?? ''));
        $outcome = strtolower((string) ($payload['outcome'] ?? 'paid'));

        if ($invoice === '') {
            sendResponse(['status' => 'error', 'message' => 'Nomor invoice wajib diisi'], 400);
        }

        if ($outcome === 'paid') {
            $result = OrderFulfillmentService::fulfillPaidOrder($pdo, $invoice, [
                'payment_method'    => (string) ($payload['payment_method'] ?? 'Konfirmasi Manual Admin'),
                'gateway_reference' => 'ADM-' . date('YmdHis'),
                'fee_amount'        => 0,
                'raw_payload'       => ['source' => 'admin_manual_confirm', 'at' => date('c')],
            ]);
            sendResponse([
                'status'  => 'success',
                'message' => 'Pembayaran dikonfirmasi lunas & lisensi produk telah diterbitkan.',
                'data'    => $result,
            ]);
        } else {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, $outcome, ['source' => 'admin_manual']);
            sendResponse([
                'status'  => 'success',
                'message' => "Status invoice {$invoice} diperbarui menjadi {$outcome}.",
            ]);
        }
    }

    // ------------------------------------------------------------
    // 2. Mode Simulator (Hanya aktif jika PAYMENT_GATEWAY='simulator')
    // ------------------------------------------------------------
    if ($gateway === 'simulator') {
        if (PaymentGatewayService::getActiveGateway() !== 'simulator') {
            sendResponse([
                'status'  => 'error',
                'message' => 'Mode simulator dinonaktifkan karena payment gateway live/sandbox aktif.'
            ], 403);
        }

        $invoice = trim((string) ($payload['invoice'] ?? ''));
        $token   = trim((string) ($payload['token'] ?? ''));
        $outcome = strtolower((string) ($payload['outcome'] ?? 'paid'));

        if ($invoice === '' || $token === '') {
            sendResponse(['status' => 'error', 'message' => 'Invoice dan access token wajib disertakan'], 400);
        }

        $stmt = $pdo->prepare("SELECT * FROM digital_orders WHERE invoice_number = ? LIMIT 1");
        $stmt->execute([$invoice]);
        $order = $stmt->fetch();

        if (!$order || !hash_equals((string) $order['access_token'], $token)) {
            sendResponse(['status' => 'error', 'message' => 'Token akses invoice tidak valid'], 403);
        }

        if ($outcome === 'paid') {
            $result = OrderFulfillmentService::fulfillPaidOrder($pdo, $invoice, [
                'payment_method'    => $order['payment_method'] ?: 'QRIS Simulator',
                'gateway_reference' => 'SIM-PAID-' . strtoupper(bin2hex(random_bytes(3))),
                'raw_payload'       => ['source' => 'simulator', 'outcome' => 'paid', 'at' => date('c')],
            ]);
            sendResponse([
                'status'  => 'success',
                'message' => 'Simulasi pembayaran BERHASIL! Lisensi & file unduhan telah dibuka.',
                'data'    => $result,
            ]);
        }

        OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'expired', ['source' => 'simulator', 'outcome' => 'expired']);
        sendResponse([
            'status'  => 'success',
            'message' => 'Simulasi status kedaluwarsa berhasil diterapkan.',
        ]);
    }

    // ------------------------------------------------------------
    // 3. Midtrans Notification Webhook
    // ------------------------------------------------------------
    if ($gateway === 'midtrans') {
        if (!PaymentGatewayService::verifyMidtransSignature($payload)) {
            error_log('[AsetLab Midtrans Webhook] Invalid signature for order_id=' . ($payload['order_id'] ?? 'unknown'));
            sendResponse(['status' => 'error', 'message' => 'Invalid Midtrans signature'], 403);
        }

        $invoice           = (string) ($payload['order_id'] ?? '');
        $transactionStatus = strtolower((string) ($payload['transaction_status'] ?? ''));
        $fraudStatus       = strtolower((string) ($payload['fraud_status'] ?? 'accept'));
        $paymentType       = strtoupper((string) ($payload['payment_type'] ?? 'MIDTRANS'));
        $gatewayRef        = (string) ($payload['transaction_id'] ?? $invoice);

        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            OrderFulfillmentService::fulfillPaidOrder($pdo, $invoice, [
                'payment_method'    => 'Midtrans ' . $paymentType,
                'gateway_reference' => $gatewayRef,
                'raw_payload'       => $payload,
            ]);
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $mapped = $transactionStatus === 'expire' ? 'expired' : 'failed';
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, $mapped, $payload);
        } elseif ($transactionStatus === 'refund') {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'refunded', $payload);
        }

        sendResponse(['status' => 'success', 'message' => 'Midtrans webhook processed']);
    }

    // ------------------------------------------------------------
    // 4. Tripay Callback Webhook
    // ------------------------------------------------------------
    if ($gateway === 'tripay') {
        $receivedSig = (string) ($_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '');
        if (!PaymentGatewayService::verifyTripaySignature($rawBody, $receivedSig)) {
            error_log('[AsetLab Tripay Webhook] Invalid callback signature');
            sendResponse(['status' => 'error', 'message' => 'Invalid Tripay signature'], 403);
        }

        $event = strtolower((string) ($_SERVER['HTTP_X_CALLBACK_EVENT'] ?? 'payment_status'));
        if ($event !== 'payment_status') {
            sendResponse(['status' => 'success', 'message' => 'Event ignored']);
        }

        $invoice       = (string) ($payload['merchant_ref'] ?? '');
        $tripayStatus  = strtoupper((string) ($payload['status'] ?? ''));
        $paymentMethod = (string) ($payload['payment_method'] ?? 'Tripay');
        $gatewayRef    = (string) ($payload['reference'] ?? $invoice);
        $feeMerchant   = isset($payload['fee_merchant']) ? (float) $payload['fee_merchant'] : null;

        if ($tripayStatus === 'PAID') {
            OrderFulfillmentService::fulfillPaidOrder($pdo, $invoice, [
                'payment_method'    => 'Tripay ' . $paymentMethod,
                'gateway_reference' => $gatewayRef,
                'fee_amount'        => $feeMerchant,
                'raw_payload'       => $payload,
            ]);
        } elseif ($tripayStatus === 'EXPIRED') {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'expired', $payload);
        } elseif ($tripayStatus === 'FAILED') {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'failed', $payload);
        } elseif ($tripayStatus === 'REFUND') {
            OrderFulfillmentService::markOrderClosed($pdo, $invoice, 'refunded', $payload);
        }

        sendResponse(['success' => true, 'status' => 'success', 'message' => 'Tripay callback processed']);
    }

    sendResponse(['status' => 'error', 'message' => 'Gateway webhook tidak dikenali'], 400);
} catch (Throwable $e) {
    error_log('[AsetLab Webhook Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memproses webhook pembayaran.',
    ], 500);
}
