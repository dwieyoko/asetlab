<?php
/**
 * AsetLab Platform - Authentication API
 */
require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? 'login';
$input = getJsonInput();

if ($action === 'login') {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        sendResponse(['status' => 'error', 'message' => 'Email dan kata sandi wajib diisi'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        unset($user['password_hash']);
        sendResponse([
            'status'  => 'success',
            'message' => 'Login berhasil!',
            'user'    => $user
        ]);
    } else {
        // Fallback untuk admin default jika database baru di-import
        if ($email === 'admin@asetlab.id' && $password === 'admin123') {
            sendResponse([
                'status'  => 'success',
                'message' => 'Login berhasil via default credentials!',
                'user'    => ['id' => 1, 'name' => 'Bramantyo Wardhana', 'email' => $email, 'role' => 'admin']
            ]);
        }
        sendResponse(['status' => 'error', 'message' => 'Email atau kata sandi tidak sesuai'], 401);
    }
} elseif ($action === 'register') {
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        sendResponse(['status' => 'error', 'message' => 'Nama, email, dan kata sandi wajib diisi'], 400);
    }

    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendResponse(['status' => 'error', 'message' => 'Email sudah terdaftar'], 409);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute([$name, $email, $hash]);

    sendResponse([
        'status'  => 'success',
        'message' => 'Pendaftaran akun berhasil! Silakan login.'
    ], 201);
} else {
    sendResponse(['status' => 'error', 'message' => 'Aksi tidak valid'], 400);
}
