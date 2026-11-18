<?php
/**
 * AsetLab Platform - Orders & Custom Projects API (Admin Protected CRUD)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

// Seluruh manajemen pesanan proyek bersifat privat khusus Admin
requireAdmin($pdo);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function formatOrderRow(array $row): array {
    $row['budget']       = (float) ($row['budget'] ?? 0);
    $row['progress']     = (int) ($row['progress'] ?? 0);
    // Alias camelCase untuk kompatibilitas mulus dengan frontend Vue
    $row['clientEmail']  = $row['client_email'] ?? '';
    $row['clientAvatar'] = $row['client_avatar'] ?? '';
    return $row;
}

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $stmt->execute([$_GET['id']]);
                $order = $stmt->fetch();
                if ($order) {
                    sendResponse(['status' => 'success', 'data' => formatOrderRow($order)]);
                } else {
                    sendResponse(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'], 404);
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                $orders = array_map('formatOrderRow', $stmt->fetchAll());
                sendResponse(['status' => 'success', 'data' => $orders]);
            }
            break;

        case 'POST':
            $input = getJsonInput();
            if (empty($input['client']) || empty($input['project'])) {
                sendResponse(['status' => 'error', 'message' => 'Nama Klien dan Nama Proyek wajib diisi'], 400);
            }

            $id            = $input['id'] ?? ('ORD-' . date('Ymd') . '-' . random_int(100, 999));
            $client        = trim((string) $input['client']);
            $client_email  = trim((string) ($input['clientEmail'] ?? ($input['client_email'] ?? 'corporate@client.id')));
            $client_avatar = trim((string) ($input['clientAvatar'] ?? ($input['client_avatar'] ?? '')));
            $project       = trim((string) $input['project']);
            $scope         = trim((string) ($input['scope'] ?? 'Custom Web Application'));
            $assignee      = trim((string) ($input['assignee'] ?? 'Tim Arsitek AsetLab'));
            $budget        = max(0, (float) ($input['budget'] ?? 0));
            $deadline      = $input['deadline'] ?? date('Y-m-d', strtotime('+14 days'));
            $progress      = max(0, min(100, (int) ($input['progress'] ?? 10)));
            $status        = $input['status'] ?? 'Menunggu Konfirmasi';

            $sql = "INSERT INTO orders (id, client, client_email, client_avatar, project, scope, assignee, budget, deadline, progress, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $client, $client_email, $client_avatar, $project, $scope, $assignee, $budget, $deadline, $progress, $status]);

            $stmtGet = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
            $stmtGet->execute([$id]);
            $created = $stmtGet->fetch();

            sendResponse([
                'status'  => 'success',
                'message' => 'Pesanan proyek berhasil disimpan.',
                'data'    => $created ? formatOrderRow($created) : ['id' => $id],
            ], 201);
            break;

        case 'PUT':
            $input = getJsonInput();
            $id    = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Pesanan diperlukan untuk update'], 400);
            }

            $map = [
                'client'        => 'client',
                'clientEmail'   => 'client_email',
                'client_email'  => 'client_email',
                'clientAvatar'  => 'client_avatar',
                'client_avatar' => 'client_avatar',
                'project'       => 'project',
                'scope'         => 'scope',
                'assignee'      => 'assignee',
                'budget'        => 'budget',
                'deadline'      => 'deadline',
                'progress'      => 'progress',
                'status'        => 'status',
            ];

            // Gunakan associative array berdasarkan nama kolom agar tidak duplikat parameter
            $updates = [];
            foreach ($map as $inKey => $col) {
                if (array_key_exists($inKey, $input)) {
                    $updates[$col] = $input[$inKey];
                }
            }

            if (empty($updates)) {
                sendResponse(['status' => 'error', 'message' => 'Tidak ada data untuk diperbarui'], 400);
            }

            $fields = [];
            $values = [];
            foreach ($updates as $col => $val) {
                $fields[] = "`{$col}` = ?";
                $values[] = $val;
            }
            $values[] = $id;

            $sql = "UPDATE orders SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            sendResponse([
                'status'  => 'success',
                'message' => "Pesanan {$id} berhasil diperbarui",
            ]);
            break;

        case 'DELETE':
            $input = getJsonInput();
            $id    = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Pesanan diperlukan untuk menghapus'], 400);
            }

            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$id]);

            sendResponse([
                'status'  => 'success',
                'message' => "Pesanan {$id} berhasil dihapus",
            ]);
            break;

        default:
            sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
    }
} catch (PDOException $e) {
    error_log('[AsetLab Orders Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memproses data pesanan.',
    ], 500);
}
