<?php
/**
 * AsetLab Platform - Orders & Projects API (CRUD)
 */
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Read Single or All
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $order = $stmt->fetch();
            if ($order) {
                $order['budget'] = (float)$order['budget'];
                $order['progress'] = (int)$order['progress'];
                sendResponse(['status' => 'success', 'data' => $order]);
            } else {
                sendResponse(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'], 404);
            }
        } else {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
            $orders = $stmt->fetchAll();
            foreach ($orders as &$o) {
                $o['budget'] = (float)$o['budget'];
                $o['progress'] = (int)$o['progress'];
            }
            sendResponse(['status' => 'success', 'data' => $orders]);
        }
        break;

    case 'POST':
        // Create Order
        $input = getJsonInput();
        if (empty($input['client']) || empty($input['project'])) {
            sendResponse(['status' => 'error', 'message' => 'Nama Klien dan Nama Proyek wajib diisi'], 400);
        }

        $id = $input['id'] ?? ('ORD-' . date('Ymd') . '-' . rand(100, 999));
        $client = $input['client'];
        $client_email = $input['clientEmail'] ?? ($input['client_email'] ?? 'corporate@client.id');
        $client_avatar = $input['clientAvatar'] ?? ($input['client_avatar'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A');
        $project = $input['project'];
        $scope = $input['scope'] ?? 'Custom Web Application';
        $assignee = $input['assignee'] ?? 'Bramantyo & Tim Arsitek';
        $budget = (float)($input['budget'] ?? 0);
        $deadline = $input['deadline'] ?? date('Y-m-d', strtotime('+14 days'));
        $progress = (int)($input['progress'] ?? 10);
        $status = $input['status'] ?? 'Menunggu Konfirmasi';

        $sql = "INSERT INTO orders (id, client, client_email, client_avatar, project, scope, assignee, budget, deadline, progress, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $client, $client_email, $client_avatar, $project, $scope, $assignee, $budget, $deadline, $progress, $status]);

        sendResponse([
            'status'  => 'success',
            'message' => 'Pesanan proyek berhasil disimpan ke database DomaiNesia',
            'data'    => array_merge($input, ['id' => $id])
        ], 201);
        break;

    case 'PUT':
        // Update Order
        $input = getJsonInput();
        $id = $_GET['id'] ?? ($input['id'] ?? null);

        if (!$id) {
            sendResponse(['status' => 'error', 'message' => 'ID Pesanan diperlukan untuk update'], 400);
        }

        $fields = [];
        $values = [];

        $map = [
            'client'       => 'client',
            'clientEmail'  => 'client_email',
            'client_email' => 'client_email',
            'project'      => 'project',
            'scope'        => 'scope',
            'assignee'     => 'assignee',
            'budget'       => 'budget',
            'deadline'     => 'deadline',
            'progress'     => 'progress',
            'status'       => 'status'
        ];

        foreach ($map as $inKey => $col) {
            if (isset($input[$inKey])) {
                $fields[] = "`$col` = ?";
                $values[] = $input[$inKey];
            }
        }

        if (empty($fields)) {
            sendResponse(['status' => 'error', 'message' => 'Tidak ada data untuk diperbarui'], 400);
        }

        $values[] = $id;
        $sql = "UPDATE orders SET " . implode(', ', array_unique($fields)) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        sendResponse([
            'status'  => 'success',
            'message' => "Pesanan $id berhasil diperbarui"
        ]);
        break;

    case 'DELETE':
        // Delete Order
        $input = getJsonInput();
        $id = $_GET['id'] ?? ($input['id'] ?? null);

        if (!$id) {
            sendResponse(['status' => 'error', 'message' => 'ID Pesanan diperlukan untuk menghapus'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$id]);

        sendResponse([
            'status'  => 'success',
            'message' => "Pesanan $id berhasil dihapus dari database DomaiNesia"
        ]);
        break;

    default:
        sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
}
