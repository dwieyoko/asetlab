<?php
/**
 * AsetLab Platform - Payment Gateway Adapter Service
 * Mendukung:
 *   1. Midtrans Snap (QRIS 0.7%, VA Rp4.000, tanpa biaya bulanan)
 *   2. Tripay (Populer untuk perorangan/UMKM Indonesia)
 *   3. Simulator (Testing end-to-end tanpa akun gateway)
 */

class PaymentGatewayService
{
    public static function getActiveGateway(): string
    {
        $gw = strtolower((string) appConfig('PAYMENT_GATEWAY', 'simulator'));
        return in_array($gw, ['midtrans', 'tripay', 'simulator'], true) ? $gw : 'simulator';
    }

    public static function getClientConfig(): array
    {
        $gw = self::getActiveGateway();
        return [
            'gateway'                => $gw,
            'midtrans_client_key'    => $gw === 'midtrans' ? (string) appConfig('MIDTRANS_CLIENT_KEY', '') : '',
            'midtrans_is_production' => (bool) appConfig('MIDTRANS_IS_PRODUCTION', false),
            'tripay_is_production'   => (bool) appConfig('TRIPAY_IS_PRODUCTION', false),
        ];
    }

    /**
     * Inisiasi transaksi ke gateway pembayaran aktif
     */
    public static function createPayment(array $order, array $items): array
    {
        $gw = self::getActiveGateway();

        if ((float) ($order['total_amount'] ?? 0) <= 0) {
            return [
                'gateway'           => 'free',
                'snap_token'        => null,
                'payment_url'       => null,
                'gateway_reference' => 'FREE-' . $order['invoice_number'],
            ];
        }

        if ($gw === 'midtrans') {
            return self::createMidtransSnap($order, $items);
        }

        if ($gw === 'tripay') {
            return self::createTripayTransaction($order, $items);
        }

        // Mode Simulator (default sebelum API key diisi)
        $invoiceUrl = APP_URL . '/transaksi/' . rawurlencode($order['invoice_number']) . '?token=' . rawurlencode($order['access_token']);
        return [
            'gateway'           => 'simulator',
            'snap_token'        => null,
            'payment_url'       => $invoiceUrl,
            'gateway_reference' => 'SIM-' . strtoupper(bin2hex(random_bytes(4))),
        ];
    }

    private static function createMidtransSnap(array $order, array $items): array
    {
        $serverKey    = (string) appConfig('MIDTRANS_SERVER_KEY', '');
        $isProduction = (bool) appConfig('MIDTRANS_IS_PRODUCTION', false);

        if ($serverKey === '') {
            throw new RuntimeException('MIDTRANS_SERVER_KEY belum diatur di api/secrets.php');
        }

        $endpoint = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id'       => substr((string) $item['product_id'], 0, 50),
                'price'    => (int) round((float) $item['price']),
                'quantity' => 1,
                'name'     => substr((string) $item['product_title'], 0, 50),
            ];
        }

        $discount = (int) round((float) ($order['discount_amount'] ?? 0));
        if ($discount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => -$discount,
                'quantity' => 1,
                'name'     => 'Diskon Kupon ' . ($order['coupon_code'] ?? ''),
            ];
        }

        $finishUrl = APP_URL . '/transaksi/' . rawurlencode($order['invoice_number']) . '?token=' . rawurlencode($order['access_token']);

        $payload = [
            'transaction_details' => [
                'order_id'     => $order['invoice_number'],
                'gross_amount' => (int) round((float) $order['total_amount']),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order['buyer_name'],
                'email'      => $order['buyer_email'],
                'phone'      => $order['buyer_whatsapp'],
            ],
            'callbacks' => [
                'finish' => $finishUrl,
            ],
            'expiry' => [
                'unit'     => 'hours',
                'duration' => 24,
            ],
        ];

        $response = self::httpPostJson($endpoint, $payload, [
            'Authorization: Basic ' . base64_encode($serverKey . ':'),
            'Accept: application/json',
        ]);

        if (empty($response['token']) && empty($response['redirect_url'])) {
            $errMsg = is_array($response['error_messages'] ?? null)
                ? implode('; ', $response['error_messages'])
                : 'Midtrans gagal membuat transaksi Snap.';
            throw new RuntimeException($errMsg);
        }

        return [
            'gateway'           => 'midtrans',
            'snap_token'        => $response['token'] ?? null,
            'payment_url'       => $response['redirect_url'] ?? null,
            'gateway_reference' => $order['invoice_number'],
        ];
    }

    private static function createTripayTransaction(array $order, array $items): array
    {
        $apiKey       = (string) appConfig('TRIPAY_API_KEY', '');
        $privateKey   = (string) appConfig('TRIPAY_PRIVATE_KEY', '');
        $merchantCode = (string) appConfig('TRIPAY_MERCHANT_CODE', '');
        $isProduction = (bool) appConfig('TRIPAY_IS_PRODUCTION', false);

        if ($apiKey === '' || $privateKey === '' || $merchantCode === '') {
            throw new RuntimeException('Kredensial Tripay (API_KEY, PRIVATE_KEY, MERCHANT_CODE) belum lengkap di api/secrets.php');
        }

        $endpoint = $isProduction
            ? 'https://tripay.co.id/api/transaction/create'
            : 'https://tripay.co.id/api-sandbox/transaction/create';

        $methodMap = [
            'QRIS Dinamis'             => 'QRIS2',
            'BCA Virtual Account'      => 'BCAVA',
            'Mandiri Virtual Account'  => 'MANDIRIVA',
            'BRI / BNI Virtual Account' => 'BRIVA',
        ];
        $channelCode = $methodMap[$order['payment_method'] ?? ''] ?? 'QRIS2';
        $amount      = (int) round((float) $order['total_amount']);
        $merchantRef = $order['invoice_number'];
        $signature   = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[] = [
                'sku'      => (string) $item['product_sku'],
                'name'     => (string) $item['product_title'],
                'price'    => (int) round((float) $item['price']),
                'quantity' => 1,
            ];
        }
        $discount = (int) round((float) ($order['discount_amount'] ?? 0));
        if ($discount > 0) {
            $orderItems[] = [
                'sku'      => 'COUPON',
                'name'     => 'Diskon Kupon',
                'price'    => -$discount,
                'quantity' => 1,
            ];
        }

        $returnUrl = APP_URL . '/transaksi/' . rawurlencode($order['invoice_number']) . '?token=' . rawurlencode($order['access_token']);

        $payload = [
            'method'         => $channelCode,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $order['buyer_name'],
            'customer_email' => $order['buyer_email'],
            'customer_phone' => $order['buyer_whatsapp'],
            'order_items'    => $orderItems,
            'callback_url'   => APP_URL . '/api/webhook_payment.php?gateway=tripay',
            'return_url'     => $returnUrl,
            'expired_time'   => time() + (24 * 60 * 60),
            'signature'      => $signature,
        ];

        $response = self::httpPostJson($endpoint, $payload, [
            'Authorization: Bearer ' . $apiKey,
            'Accept: application/json',
        ]);

        if (empty($response['success']) || empty($response['data'])) {
            throw new RuntimeException($response['message'] ?? 'Tripay gagal membuat transaksi pembayaran.');
        }

        return [
            'gateway'           => 'tripay',
            'snap_token'        => null,
            'payment_url'       => $response['data']['checkout_url'] ?? null,
            'gateway_reference' => $response['data']['reference'] ?? $merchantRef,
        ];
    }

    public static function verifyMidtransSignature(array $payload): bool
    {
        $serverKey   = (string) appConfig('MIDTRANS_SERVER_KEY', '');
        $orderId     = (string) ($payload['order_id'] ?? '');
        $statusCode  = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $receivedSig = (string) ($payload['signature_key'] ?? '');

        if ($serverKey === '' || $orderId === '' || $receivedSig === '') {
            return false;
        }

        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expectedSig, $receivedSig);
    }

    public static function verifyTripaySignature(string $rawBody, string $receivedSig): bool
    {
        $privateKey = (string) appConfig('TRIPAY_PRIVATE_KEY', '');
        if ($privateKey === '' || $receivedSig === '') {
            return false;
        }

        $expectedSig = hash_hmac('sha256', $rawBody, $privateKey);
        return hash_equals($expectedSig, $receivedSig);
    }

    private static function httpPostJson(string $url, array $payload, array $extraHeaders = []): array
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('Ekstensi PHP cURL tidak aktif di server.');
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_HTTPHEADER     => array_merge(['Content-Type: application/json'], $extraHeaders),
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);

        $raw      = curl_exec($ch);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            throw new RuntimeException('Gagal terhubung ke Payment Gateway: ' . $curlErr);
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}
