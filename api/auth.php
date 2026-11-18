<?php
/**
 * AsetLab Platform - Authentication & Account API (JWT + RBAC)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? 'login';
$input  = getJsonInput();

try {
    if ($action === 'login') {
        if ($method !== 'POST') {
            sendResponse(['status' => 'error', 'message' => 'Metode harus POST'], 405);
        }

        $email    = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');
        $remember = !empty($input['remember']);

        if ($email === '' || $password === '') {
            sendResponse(['status' => 'error', 'message' => 'Email dan kata sandi wajib diisi'], 400);
        }

        $stmt = $pdo->prepare(
            "SELECT id, name, email, whatsapp, password_hash, role, is_active, created_at
             FROM users
             WHERE email = ?
             LIMIT 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            sendResponse(['status' => 'error', 'message' => 'Email atau kata sandi tidak sesuai'], 401);
        }

        if ((int) ($user['is_active'] ?? 1) !== 1) {
            sendResponse(['status' => 'error', 'message' => 'Akun Anda sedang dinonaktifkan. Hubungi administrator.'], 403);
        }

        unset($user['password_hash']);
        $ttl = $remember ? (60 * 60 * 24 * 30) : (60 * 60 * 24 * 7);
        $token = jwtEncode([
            'sub'   => (int) $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ], $ttl);

        sendResponse([
            'status'  => 'success',
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    if ($action === 'register') {
        if ($method !== 'POST') {
            sendResponse(['status' => 'error', 'message' => 'Metode harus POST'], 405);
        }

        $name     = trim((string) ($input['name'] ?? ''));
        $email    = strtolower(trim((string) ($input['email'] ?? '')));
        $whatsapp = preg_replace('/[^0-9+]/', '', (string) ($input['whatsapp'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            sendResponse(['status' => 'error', 'message' => 'Nama lengkap, email, dan kata sandi wajib diisi'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendResponse(['status' => 'error', 'message' => 'Format alamat email tidak valid'], 400);
        }

        if (strlen($password) < 8) {
            sendResponse(['status' => 'error', 'message' => 'Kata sandi minimal 8 karakter'], 400);
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            sendResponse(['status' => 'error', 'message' => 'Email sudah terdaftar. Silakan masuk ke akun Anda.'], 409);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pdo->beginTransaction();

        // Selalu set role = 'client' untuk registrasi publik (anti privilege escalation)
        $stmt = $pdo->prepare(
            "INSERT INTO users (name, email, whatsapp, password_hash, role, is_active)
             VALUES (?, ?, ?, ?, 'client', 1)"
        );
        $stmt->execute([$name, $email, $whatsapp ?: null, $hash]);
        $userId = (int) $pdo->lastInsertId();

        // Klaim otomatis riwayat guest checkout & lisensi sebelumnya yang memakai email yang sama
        try {
            $stmtLinkOrders = $pdo->prepare("UPDATE digital_orders SET user_id = ? WHERE buyer_email = ? AND user_id IS NULL");
            $stmtLinkOrders->execute([$userId, $email]);

            $stmtLinkLic = $pdo->prepare("UPDATE user_licenses SET user_id = ? WHERE buyer_email = ? AND user_id IS NULL");
            $stmtLinkLic->execute([$userId, $email]);
        } catch (PDOException $e) {
            // Abaikan jika tabel v2 belum dimigrasi
        }

        $pdo->commit();

        $user = [
            'id'        => $userId,
            'name'      => $name,
            'email'     => $email,
            'whatsapp'  => $whatsapp ?: null,
            'role'      => 'client',
            'is_active' => 1,
        ];

        $token = jwtEncode([
            'sub'   => $userId,
            'email' => $email,
            'role'  => 'client',
        ]);

        sendResponse([
            'status'  => 'success',
            'message' => 'Pendaftaran akun berhasil! Selamat datang di AsetLab.',
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    if ($action === 'me') {
        $user = requireAuth($pdo);
        sendResponse([
            'status' => 'success',
            'user'   => $user,
        ]);
    }

    if ($action === 'change-password') {
        if ($method !== 'POST') {
            sendResponse(['status' => 'error', 'message' => 'Metode harus POST'], 405);
        }

        $authUser        = requireAuth($pdo);
        $currentPassword = (string) ($input['current_password'] ?? '');
        $newPassword     = (string) ($input['new_password'] ?? '');

        if (strlen($newPassword) < 8) {
            sendResponse(['status' => 'error', 'message' => 'Kata sandi baru minimal 8 karakter'], 400);
        }

        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$authUser['id']]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($currentPassword, $row['password_hash'])) {
            sendResponse(['status' => 'error', 'message' => 'Kata sandi saat ini tidak sesuai'], 400);
        }

        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmtUpd = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmtUpd->execute([$newHash, $authUser['id']]);

        sendResponse([
            'status'  => 'success',
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    sendResponse(['status' => 'error', 'message' => 'Aksi autentikasi tidak valid'], 400);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[AsetLab Auth Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Terjadi kesalahan pada server autentikasi.',
    ], 500);
}
