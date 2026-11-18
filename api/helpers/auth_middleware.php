<?php
/**
 * AsetLab Platform - Pure PHP HS256 JWT & RBAC Auth Middleware
 * Tanpa dependensi eksternal / Composer — langsung jalan di PHP 8+ DomaiNesia.
 */

function base64UrlEncode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode(string $data): string {
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/')) ?: '';
}

function getJwtSecret(): string {
    $secret = (string) appConfig('JWT_SECRET', '');
    if ($secret === '' || str_starts_with($secret, 'GANTI_DENGAN_')) {
        // Fallback deterministik berbasis DB_PASS + host hanya jika admin belum set JWT_SECRET
        // Tetap sangat disarankan mengisi JWT_SECRET di api/secrets.php
        $secret = hash('sha256', (string) appConfig('DB_NAME', 'asetlab') . '|' . (string) appConfig('DB_PASS', 'dev') . '|asetlab-jwt-v2');
    }
    return $secret;
}

function jwtEncode(array $payload, ?int $ttlSeconds = null): string {
    $ttl = $ttlSeconds ?? (int) appConfig('JWT_TTL_SECONDS', 60 * 60 * 24 * 7);
    $now = time();

    $header = ['typ' => 'JWT', 'alg' => 'HS256'];
    $payload['iat'] = $payload['iat'] ?? $now;
    $payload['exp'] = $payload['exp'] ?? ($now + $ttl);

    $headerEncoded  = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", getJwtSecret(), true);
    $signatureEncoded = base64UrlEncode($signature);

    return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
}

function jwtDecode(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }

    [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;
    $expectedSig = base64UrlEncode(
        hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", getJwtSecret(), true)
    );

    if (!hash_equals($expectedSig, $signatureEncoded)) {
        return null;
    }

    $payload = json_decode(base64UrlDecode($payloadEncoded), true);
    if (!is_array($payload)) {
        return null;
    }

    if (isset($payload['exp']) && time() > (int) $payload['exp']) {
        return null;
    }

    return $payload;
}

/**
 * Mengambil user yang sedang login berdasarkan Bearer token (null jika tidak login / token invalid)
 */
function getCurrentUser(PDO $pdo): ?array {
    $token = getBearerToken();
    if (!$token) {
        return null;
    }

    $payload = jwtDecode($token);
    if (!$payload || empty($payload['sub'])) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT id, name, email, whatsapp, role, is_active, created_at
         FROM users
         WHERE id = ? AND is_active = 1
         LIMIT 1"
    );
    $stmt->execute([(int) $payload['sub']]);
    $user = $stmt->fetch();

    return $user ?: null;
}

/**
 * Wajib login (admin atau client)
 */
function requireAuth(PDO $pdo): array {
    $user = getCurrentUser($pdo);
    if (!$user) {
        sendResponse([
            'status'  => 'error',
            'message' => 'Sesi tidak valid atau telah berakhir. Silakan masuk kembali.'
        ], 401);
    }
    return $user;
}

/**
 * Wajib login dengan role admin
 */
function requireAdmin(PDO $pdo): array {
    $user = requireAuth($pdo);
    if (($user['role'] ?? '') !== 'admin') {
        sendResponse([
            'status'  => 'error',
            'message' => 'Akses ditolak. Endpoint ini khusus Administrator.'
        ], 403);
    }
    return $user;
}
