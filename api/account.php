<?php
/**
 * AsetLab Platform - Customer Account & Digital Library API
 * Mengembalikan seluruh lisensi produk yang dimiliki & riwayat transaksi pembeli.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

$user = requireAuth($pdo);

try {
    // 1. Ambil seluruh lisensi aktif milik user (berdasarkan user_id ATAU buyer_email)
    $stmtLic = $pdo->prepare(
        "SELECT ul.id, ul.license_key, ul.order_id, ul.product_id, ul.download_count, ul.max_downloads,
                ul.last_downloaded_at, ul.created_at,
                o.invoice_number, o.access_token, o.paid_at,
                p.title AS product_title, p.slug, p.category, p.version, p.license_type, p.image,
                p.file_name, p.file_size,
                (p.file_path IS NOT NULL AND p.file_path != '') AS has_file
         FROM user_licenses ul
         INNER JOIN digital_orders o ON o.id = ul.order_id AND o.status = 'paid'
         LEFT JOIN products p ON p.id = ul.product_id
         WHERE ul.is_active = 1
           AND (ul.user_id = ? OR LOWER(ul.buyer_email) = LOWER(?))
         ORDER BY ul.created_at DESC"
    );
    $stmtLic->execute([(int) $user['id'], $user['email']]);
    $licenses = $stmtLic->fetchAll();

    foreach ($licenses as &$lic) {
        $lic['download_count'] = (int) $lic['download_count'];
        $lic['max_downloads']  = (int) $lic['max_downloads'];
        $lic['file_size']      = (int) ($lic['file_size'] ?? 0);
        $lic['has_file']       = (bool) ($lic['has_file'] ?? false);
    }
    unset($lic);

    // 2. Ambil riwayat seluruh pesanan digital milik user
    $stmtOrders = $pdo->prepare(
        "SELECT o.id, o.invoice_number, o.access_token, o.subtotal, o.discount_amount, o.total_amount,
                o.coupon_code, o.payment_gateway, o.payment_method, o.status, o.paid_at, o.expired_at, o.created_at,
                (SELECT COUNT(*) FROM digital_order_items i WHERE i.order_id = o.id) AS item_count
         FROM digital_orders o
         WHERE o.user_id = ? OR LOWER(o.buyer_email) = LOWER(?)
         ORDER BY o.created_at DESC"
    );
    $stmtOrders->execute([(int) $user['id'], $user['email']]);
    $orders = $stmtOrders->fetchAll();

    foreach ($orders as &$ord) {
        $ord['subtotal']        = (float) $ord['subtotal'];
        $ord['discount_amount'] = (float) $ord['discount_amount'];
        $ord['total_amount']    = (float) $ord['total_amount'];
        $ord['item_count']      = (int) $ord['item_count'];
    }
    unset($ord);

    sendResponse([
        'status' => 'success',
        'data'   => [
            'user'     => $user,
            'licenses' => $licenses,
            'orders'   => $orders,
        ],
    ]);
} catch (PDOException $e) {
    error_log('[AsetLab Account API Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memuat library akun Anda.',
    ], 500);
}
