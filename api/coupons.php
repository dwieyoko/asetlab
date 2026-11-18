<?php
/**
 * AsetLab Platform - Coupons Validation & Management API
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function evaluateCoupon(PDO $pdo, string $code, float $subtotal): array {
    $cleanCode = strtoupper(trim($code));
    if ($cleanCode === '') {
        return ['valid' => false, 'message' => 'Kode kupon wajib diisi'];
    }

    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$cleanCode]);
    $coupon = $stmt->fetch();

    if (!$coupon) {
        return ['valid' => false, 'message' => 'Kode kupon tidak ditemukan atau tidak aktif'];
    }

    if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
        return ['valid' => false, 'message' => 'Kode kupon sudah kedaluwarsa'];
    }

    if ($coupon['max_uses'] !== null && (int) $coupon['used_count'] >= (int) $coupon['max_uses']) {
        return ['valid' => false, 'message' => 'Kuota penggunaan kupon telah habis'];
    }

    $minPurchase = (float) ($coupon['min_purchase'] ?? 0);
    if ($subtotal < $minPurchase) {
        return [
            'valid'   => false,
            'message' => 'Minimal pembelian untuk kupon ini adalah Rp ' . number_format($minPurchase, 0, ',', '.'),
        ];
    }

    $discountValue = (float) $coupon['discount_value'];
    if ($coupon['discount_type'] === 'percent') {
        $discountAmount = round($subtotal * ($discountValue / 100));
    } else {
        $discountAmount = $discountValue;
    }

    $discountAmount = min($subtotal, max(0, $discountAmount));

    return [
        'valid'           => true,
        'coupon'          => [
            'id'             => (int) $coupon['id'],
            'code'           => $coupon['code'],
            'discount_type'  => $coupon['discount_type'],
            'discount_value' => $discountValue,
            'min_purchase'   => $minPurchase,
        ],
        'discount_amount' => $discountAmount,
        'final_total'     => max(0, $subtotal - $discountAmount),
    ];
}

try {
    if ($method === 'GET') {
        if (isset($_GET['code'])) {
            $subtotal = max(0, (float) ($_GET['subtotal'] ?? 0));
            $result   = evaluateCoupon($pdo, (string) $_GET['code'], $subtotal);
            if (!$result['valid']) {
                sendResponse(['status' => 'error', 'message' => $result['message']], 400);
            }
            sendResponse(['status' => 'success', 'data' => $result]);
        }

        // Daftar seluruh kupon khusus Admin
        requireAdmin($pdo);
        $stmt = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC");
        sendResponse(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        requireAdmin($pdo);
        $input = getJsonInput();
        $code  = strtoupper(trim((string) ($input['code'] ?? '')));
        if ($code === '') {
            sendResponse(['status' => 'error', 'message' => 'Kode kupon wajib diisi'], 400);
        }

        $type     = ($input['discount_type'] ?? 'percent') === 'fixed' ? 'fixed' : 'percent';
        $value    = max(0, (float) ($input['discount_value'] ?? 0));
        $minPurch = max(0, (float) ($input['min_purchase'] ?? 0));
        $maxUses  = isset($input['max_uses']) && $input['max_uses'] !== '' ? (int) $input['max_uses'] : null;
        $expires  = !empty($input['expires_at']) ? $input['expires_at'] : null;

        $stmt = $pdo->prepare(
            "INSERT INTO coupons (code, discount_type, discount_value, min_purchase, max_uses, expires_at, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->execute([$code, $type, $value, $minPurch, $maxUses, $expires]);

        sendResponse(['status' => 'success', 'message' => "Kupon {$code} berhasil dibuat."], 201);
    }

    if ($method === 'DELETE') {
        requireAdmin($pdo);
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            sendResponse(['status' => 'error', 'message' => 'ID kupon tidak valid'], 400);
        }
        $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        sendResponse(['status' => 'success', 'message' => 'Kupon berhasil dihapus']);
    }

    sendResponse(['status' => 'error', 'message' => 'Metode tidak didukung'], 405);
} catch (PDOException $e) {
    error_log('[AsetLab Coupons Error] ' . $e->getMessage());
    sendResponse(['status' => 'error', 'message' => 'Gagal memproses data kupon'], 500);
}
