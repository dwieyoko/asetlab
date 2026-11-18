<?php
/**
 * AsetLab Platform - Consultations / Project Leads API
 * - POST (Public): Pengunjung mengajukan konsultasi gratis dari Beranda / Layanan
 * - GET / PUT (Admin): Melihat & memperbarui status lead konsultasi
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'POST') {
        $input = getJsonInput();
        $name     = trim((string) ($input['name'] ?? ''));
        $email    = strtolower(trim((string) ($input['email'] ?? '')));
        $whatsapp = preg_replace('/[^0-9+]/', '', (string) ($input['whatsapp'] ?? ''));
        $service  = trim((string) ($input['service'] ?? ($input['service_category'] ?? 'Web & SaaS Platform')));
        $notes    = trim((string) ($input['notes'] ?? ''));

        if ($name === '' || $email === '') {
            sendResponse(['status' => 'error', 'message' => 'Nama lengkap dan alamat email wajib diisi'], 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendResponse(['status' => 'error', 'message' => 'Format alamat email tidak valid'], 400);
        }

        $stmt = $pdo->prepare(
            "INSERT INTO consultations (name, email, whatsapp, service_category, notes, status)
             VALUES (?, ?, ?, ?, ?, 'new')"
        );
        $stmt->execute([$name, $email, $whatsapp ?: null, $service, $notes ?: null]);

        sendResponse([
            'status'  => 'success',
            'message' => 'Pengajuan konsultasi Anda telah diterima! Tim Lead Architect kami akan menghubungi Anda dalam 1x24 jam.',
            'data'    => ['id' => (int) $pdo->lastInsertId()],
        ], 201);
    }

    if ($method === 'GET') {
        requireAdmin($pdo);
        $stmt = $pdo->query("SELECT * FROM consultations ORDER BY created_at DESC LIMIT 100");
        sendResponse(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'PUT') {
        requireAdmin($pdo);
        $input  = getJsonInput();
        $id     = (int) ($_GET['id'] ?? ($input['id'] ?? 0));
        $status = (string) ($input['status'] ?? '');

        if ($id <= 0 || !in_array($status, ['new', 'contacted', 'closed'], true)) {
            sendResponse(['status' => 'error', 'message' => 'ID atau status tidak valid'], 400);
        }

        $stmt = $pdo->prepare("UPDATE consultations SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        sendResponse(['status' => 'success', 'message' => 'Status konsultasi diperbarui']);
    }

    sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
} catch (PDOException $e) {
    error_log('[AsetLab Consultations Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memproses data konsultasi.',
    ], 500);
}
