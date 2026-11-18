<?php
/**
 * AsetLab Platform - Core Backend Configuration & Helpers
 * Optimal untuk PHP 8+ di DomaiNesia cPanel Hosting maupun lokal.
 */

// --------------------------------------------------------
// 1. Load Konfigurasi Privat (api/secrets.php + env fallback)
// --------------------------------------------------------
$__SECRETS = [];
$secretsFile = __DIR__ . '/secrets.php';
if (file_exists($secretsFile)) {
    $loaded = require $secretsFile;
    if (is_array($loaded)) {
        $__SECRETS = $loaded;
    }
}

/**
 * Ambil nilai konfigurasi dari api/secrets.php atau environment variable
 */
function appConfig(string $key, $default = null) {
    global $__SECRETS;
    if (array_key_exists($key, $__SECRETS) && $__SECRETS[$key] !== '' && $__SECRETS[$key] !== null) {
        return $__SECRETS[$key];
    }
    $envVal = getenv($key);
    if ($envVal !== false && $envVal !== '') {
        return $envVal;
    }
    return $default;
}

define('APP_ENV', (string) appConfig('APP_ENV', 'production'));
define('APP_URL', rtrim((string) appConfig('APP_URL', 'https://asetlab.web.id'), '/'));
define('PROJECT_ROOT', realpath(__DIR__ . '/..') ?: dirname(__DIR__));
define('STORAGE_PRIVATE_DIR', PROJECT_ROOT . '/storage/private/products');
define('UPLOADS_PUBLIC_DIR', PROJECT_ROOT . '/uploads/products');

// Pastikan direktori storage tersedia saat runtime
if (!is_dir(STORAGE_PRIVATE_DIR)) {
    @mkdir(STORAGE_PRIVATE_DIR, 0750, true);
}
if (!is_dir(UPLOADS_PUBLIC_DIR)) {
    @mkdir(UPLOADS_PUBLIC_DIR, 0755, true);
}

// --------------------------------------------------------
// 2. CORS & Security Headers
// --------------------------------------------------------
$allowedOriginsRaw = (string) appConfig(
    'ALLOWED_ORIGINS',
    'https://asetlab.web.id,https://www.asetlab.web.id,https://asetlab.id,https://www.asetlab.id,http://localhost:5173,http://127.0.0.1:5173'
);
$allowedOrigins = array_filter(array_map('trim', explode(',', $allowedOriginsRaw)));
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($requestOrigin !== '' && in_array($requestOrigin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $requestOrigin);
    header('Vary: Origin');
} elseif (APP_ENV === 'development' && $requestOrigin !== '') {
    header('Access-Control-Allow-Origin: ' . $requestOrigin);
    header('Vary: Origin');
}

header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('X-Content-Type-Options: nosniff');

// Default Content-Type JSON (endpoint download.php akan menimpa header ini)
if (!defined('SKIP_JSON_HEADER')) {
    header('Content-Type: application/json; charset=UTF-8');
}

// Tangani Preflight OPTIONS Request
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// --------------------------------------------------------
// 3. Helper Response & Input
// --------------------------------------------------------
function sendResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function getJsonInput(): array {
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function getBearerToken(): ?string {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? '';

    if ($authHeader === '' && function_exists('getallheaders')) {
        $headers = getallheaders();
        foreach ($headers as $k => $v) {
            if (strtolower($k) === 'authorization') {
                $authHeader = $v;
                break;
            }
        }
    }

    if (preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim((string) $text, '-');
    return $text !== '' ? $text : ('produk-' . substr(bin2hex(random_bytes(3)), 0, 6));
}

function generateSecureToken(int $bytes = 24): string {
    return bin2hex(random_bytes($bytes));
}

function generateLicenseKey(): string {
    $segments = [];
    for ($i = 0; $i < 4; $i++) {
        $segments[] = strtoupper(bin2hex(random_bytes(2)));
    }
    return 'ASET-' . implode('-', $segments);
}

// --------------------------------------------------------
// 4. Koneksi Database MySQL / MariaDB via PDO
// --------------------------------------------------------
$DB_HOST = (string) appConfig('DB_HOST', 'localhost');
$DB_NAME = (string) appConfig('DB_NAME', '');
$DB_USER = (string) appConfig('DB_USER', '');
$DB_PASS = (string) appConfig('DB_PASS', '');

if ($DB_NAME === '' || $DB_USER === '') {
    sendResponse([
        'status'  => 'error',
        'message' => 'Konfigurasi database belum diatur. Silakan salin api/secrets.example.php menjadi api/secrets.php dan isi kredensial database.'
    ], 500);
}

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('[AsetLab DB Error] ' . $e->getMessage());
    $msg = APP_ENV === 'development'
        ? 'Koneksi database gagal: ' . $e->getMessage()
        : 'Layanan database sedang tidak tersedia. Silakan coba beberapa saat lagi.';
    sendResponse([
        'status'  => 'error',
        'message' => $msg
    ], 500);
}
