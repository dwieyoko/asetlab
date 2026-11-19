<?php
/**
 * AsetLab Platform - Financial Transactions Ledger API (Admin Protected)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

// Data finansial hanya boleh diakses oleh Administrator
requireAdmin($pdo);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// ====================================================
// DELETE: Hapus Transaksi (Satuan atau Bersihkan Dummy)
// ====================================================
if ($method === 'DELETE') {
    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? '';

    if (empty($id) && empty($action)) {
        $body = getJsonInput();
        $id = $body['id'] ?? '';
        $action = $body['action'] ?? '';
    }

    try {
        if ($action === 'clear_dummy') {
            // Hapus data transaksi dummy / seed awal
            $stmt = $pdo->prepare(
                "DELETE FROM transactions 
                 WHERE id LIKE 'TRX-948%' 
                    OR ref_invoice LIKE '%20261022%' 
                    OR ref_invoice LIKE '%20261021%' 
                    OR ref_invoice LIKE '%20261020%'
                    OR client LIKE '%PT FinPay%' 
                    OR client LIKE '%Budi Santoso%' 
                    OR client LIKE '%Dian Permana%' 
                    OR client LIKE '%LogistiKu%' 
                    OR client LIKE '%Ahmad Fauzi%'"
            );
            $stmt->execute();
            $deletedCount = $stmt->rowCount();

            sendResponse([
                'status' => 'success',
                'message' => "Berhasil membersihkan {$deletedCount} data transaksi dummy dari database.",
                'deleted_count' => $deletedCount,
            ]);
        }

        if (empty($id)) {
            sendResponse([
                'status' => 'error',
                'message' => 'ID transaksi wajib disertakan.',
            ], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            sendResponse([
                'status' => 'error',
                'message' => 'Data transaksi tidak ditemukan atau sudah dihapus.',
            ], 404);
        }

        sendResponse([
            'status' => 'success',
            'message' => "Transaksi {$id} berhasil dihapus dari database.",
        ]);
    } catch (PDOException $e) {
        error_log('[AsetLab Delete Transaction Error] ' . $e->getMessage());
        sendResponse([
            'status' => 'error',
            'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal menghapus transaksi dari database.',
        ], 500);
    }
}

// ====================================================
// GET: Ambil Seluruh Ledger Transaksi & KPI Finansial
// ====================================================
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
