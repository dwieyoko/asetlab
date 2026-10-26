<?php
/**
 * AsetLab Platform - Transactions API
 */
require_once __DIR__ . '/config.php';

$stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
$transactions = $stmt->fetchAll();

foreach ($transactions as &$t) {
    $t['amount'] = (float)$t['amount'];
}

sendResponse(['status' => 'success', 'data' => $transactions]);
