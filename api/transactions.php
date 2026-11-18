<?php
/**
 * AsetLab Platform - Financial Transactions Ledger API (Admin Protected)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

// Data finansial hanya boleh diakses oleh Administrator
requireAdmin($pdo);

try {
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
    $transactions = $stmt->fetchAll();

    $grossRevenue  = 0.0;
    $totalFees     = 0.0;
    $netRevenue    = 0.0;
    $pendingAmount = 0.0;
    $pendingCount  = 0;
    $successCount  = 0;
    $failedCount   = 0;

    foreach ($transactions as &$t) {
        $t['amount']     = (float) ($t['amount'] ?? 0);
        $t['fee_amount'] = (float) ($t['fee_amount'] ?? 0);
        $t['net_amount'] = isset($t['net_amount']) && (float) $t['net_amount'] > 0
            ? (float) $t['net_amount']
            : max(0, $t['amount'] - $t['fee_amount']);
        $t['refInvoice'] = $t['ref_invoice'] ?? '';

        if (($t['status'] ?? '') === 'Berhasil') {
            $grossRevenue += $t['amount'];
            $totalFees    += $t['fee_amount'];
            $netRevenue   += $t['net_amount'];
            $successCount++;
        } elseif (($t['status'] ?? '') === 'Menunggu') {
            $pendingAmount += $t['amount'];
            $pendingCount++;
        } else {
            $failedCount++;
        }
    }
    unset($t);

    $totalCount  = count($transactions);
    $successRate = $totalCount > 0 ? round(($successCount / $totalCount) * 100, 1) : 100.0;

    sendResponse([
        'status'  => 'success',
        'data'    => $transactions,
        'summary' => [
            'gross_revenue'  => $grossRevenue,
            'total_fees'     => $totalFees,
            'net_revenue'    => $netRevenue,
            'pending_amount' => $pendingAmount,
            'pending_count'  => $pendingCount,
            'success_count'  => $successCount,
            'failed_count'   => $failedCount,
            'total_count'    => $totalCount,
            'success_rate'   => $successRate,
        ],
    ]);
} catch (PDOException $e) {
    error_log('[AsetLab Transactions Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memuat data transaksi.',
    ], 500);
}
