<?php
/**
 * AsetLab Platform - Admin Dashboard Overview Aggregated Metrics API
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

requireAdmin($pdo);

try {
    // 1. Produk Digital Summary
    $prodStats = $pdo->query(
        "SELECT COUNT(*) AS total_products,
                SUM(CASE WHEN status = 'Live' THEN 1 ELSE 0 END) AS live_products,
                COALESCE(SUM(downloads), 0) AS total_downloads
         FROM products"
    )->fetch() ?: [];

    // 2. Transaksi Keuangan Summary
    $trxStats = $pdo->query(
        "SELECT COALESCE(SUM(CASE WHEN status = 'Berhasil' THEN amount ELSE 0 END), 0) AS gross_revenue,
                COALESCE(SUM(CASE WHEN status = 'Berhasil' THEN net_amount ELSE 0 END), 0) AS net_revenue,
                COALESCE(SUM(CASE WHEN status = 'Menunggu' THEN amount ELSE 0 END), 0) AS pending_amount,
                SUM(CASE WHEN status = 'Berhasil' THEN 1 ELSE 0 END) AS paid_count,
                SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) AS pending_count
         FROM transactions"
    )->fetch() ?: [];

    // 3. Proyek Jasa Summary
    $orderStats = $pdo->query(
        "SELECT COUNT(*) AS total_orders,
                SUM(CASE WHEN status IN ('Sedang Dikerjakan', 'Review') THEN 1 ELSE 0 END) AS active_projects,
                SUM(CASE WHEN status = 'Menunggu Konfirmasi' THEN 1 ELSE 0 END) AS pending_projects
         FROM orders"
    )->fetch() ?: [];

    // 4. Transaksi Terbaru (8 baris)
    $recentTransactions = $pdo->query(
        "SELECT id, order_id, ref_invoice, date, client, client_email, category, gateway, method, amount, status, created_at
         FROM transactions
         ORDER BY created_at DESC
         LIMIT 8"
    )->fetchAll();

    foreach ($recentTransactions as &$t) {
        $t['amount'] = (float) $t['amount'];
    }
    unset($t);

    // 5. Proyek Aktif Terbaru (5 baris)
    $activeProjects = $pdo->query(
        "SELECT id, client, project, scope, progress, status, deadline
         FROM orders
         ORDER BY updated_at DESC
         LIMIT 5"
    )->fetchAll();

    foreach ($activeProjects as &$p) {
        $p['progress'] = (int) $p['progress'];
    }
    unset($p);

    // 6. Lead Konsultasi Baru
    $newConsultations = 0;
    try {
        $newConsultations = (int) $pdo->query("SELECT COUNT(*) FROM consultations WHERE status = 'new'")->fetchColumn();
    } catch (PDOException $e) {
        // Abaikan jika tabel consultations belum ada
    }

    sendResponse([
        'status' => 'success',
        'data'   => [
            'kpis' => [
                'gross_revenue'     => (float) ($trxStats['gross_revenue'] ?? 0),
                'net_revenue'       => (float) ($trxStats['net_revenue'] ?? 0),
                'pending_amount'    => (float) ($trxStats['pending_amount'] ?? 0),
                'paid_count'        => (int) ($trxStats['paid_count'] ?? 0),
                'pending_trx_count' => (int) ($trxStats['pending_count'] ?? 0),
                'total_products'    => (int) ($prodStats['total_products'] ?? 0),
                'live_products'     => (int) ($prodStats['live_products'] ?? 0),
                'total_downloads'   => (int) ($prodStats['total_downloads'] ?? 0),
                'active_projects'   => (int) ($orderStats['active_projects'] ?? 0),
                'pending_projects'  => (int) ($orderStats['pending_projects'] ?? 0),
                'new_consultations' => $newConsultations,
            ],
            'recent_transactions' => $recentTransactions,
            'active_projects'     => $activeProjects,
        ],
    ]);
} catch (PDOException $e) {
    error_log('[AsetLab Dashboard API Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memuat ringkasan dashboard.',
    ], 500);
}
