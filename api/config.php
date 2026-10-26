<?php
/**
 * AsetLab Platform - Database Configuration for DomaiNesia Hosting
 */

// Header CORS agar frontend dapat mengakses API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

// Tangani Preflight OPTIONS Request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Konfigurasi Database DomaiNesia (Sesuaikan dengan kredensial cPanel Anda)
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'asetlab_db';     // Contoh di DomaiNesia: u1234567_asetlab
$DB_USER = getenv('DB_USER') ?: 'asetlab_user';   // Contoh di DomaiNesia: u1234567_admin
$DB_PASS = getenv('DB_PASS') ?: 'PasswordKuat123!';

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Koneksi database DomaiNesia gagal: ' . $e->getMessage()
    ]);
    exit;
}

/**
 * Helper untuk response JSON
 */
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Helper untuk mengambil input JSON dari body request
 */
function getJsonInput() {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?: [];
}
