<?php
/**
 * AsetLab Platform - Secure Private File Download Streaming Endpoint
 * Melayani unduhan file dari storage/private/products/ hanya setelah memverifikasi:
 *   1. License Key aktif & valid
 *   2. Order berstatus 'paid'
 *   3. Pemilik sah (via access_token guest ATAU JWT member/admin)
 *   4. Kuota download masih tersedia
 */
define('SKIP_JSON_HEADER', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

function sendDownloadError(string $message, int $code = 403): void {
    header('Content-Type: application/json; charset=UTF-8');
    sendResponse(['status' => 'error', 'message' => $message], $code);
}

$licenseKey  = trim((string) ($_GET['license'] ?? ''));
$accessToken = trim((string) ($_GET['token'] ?? ''));

// Izinkan juga JWT dikirim lewat query ?jwt= saat klik link <a href> langsung
if (empty($_SERVER['HTTP_AUTHORIZATION']) && !empty($_GET['jwt'])) {
    $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . trim((string) $_GET['jwt']);
}

if ($licenseKey === '') {
    sendDownloadError('License key wajib disertakan untuk mengunduh file.', 400);
}

try {
    $stmt = $pdo->prepare(
        "SELECT ul.*,
                o.status AS order_status,
                o.access_token AS order_access_token,
                o.buyer_email AS order_buyer_email,
                o.user_id AS order_user_id,
                p.title AS product_title,
                p.file_path,
                p.file_name
         FROM user_licenses ul
         INNER JOIN digital_orders o ON o.id = ul.order_id
         INNER JOIN products p ON p.id = ul.product_id
         WHERE ul.license_key = ?
         LIMIT 1"
    );
    $stmt->execute([$licenseKey]);
    $row = $stmt->fetch();

    if (!$row) {
        sendDownloadError('License key tidak ditemukan.', 404);
    }

    if ((int) ($row['is_active'] ?? 0) !== 1) {
        sendDownloadError('Lisensi ini sudah tidak aktif atau telah dibatalkan.', 403);
    }

    if (($row['order_status'] ?? '') !== 'paid') {
        sendDownloadError('Pembayaran untuk pesanan ini belum terverifikasi lunas.', 403);
    }

    // Verifikasi kepemilikan: Admin, Member pemilik, atau Guest dengan access_token cocok
    $currentUser   = getCurrentUser($pdo);
    $isAdmin       = $currentUser && ($currentUser['role'] ?? '') === 'admin';
    $isOwnerUser   = $currentUser && (
        ((int) ($row['user_id'] ?? 0) === (int) $currentUser['id']) ||
        ((int) ($row['order_user_id'] ?? 0) === (int) $currentUser['id']) ||
        (strtolower((string) $row['buyer_email']) === strtolower((string) $currentUser['email']))
    );
    $hasValidToken = $accessToken !== '' && hash_equals((string) $row['order_access_token'], $accessToken);

    if (!$isAdmin && !$isOwnerUser && !$hasValidToken) {
        sendDownloadError('Anda tidak memiliki izin untuk mengunduh file lisensi ini.', 403);
    }

    $downloadCount = (int) ($row['download_count'] ?? 0);
    $maxDownloads  = (int) ($row['max_downloads'] ?? 15);
    if (!$isAdmin && $downloadCount >= $maxDownloads) {
        sendDownloadError("Batas kuota unduhan ({$maxDownloads}x) untuk lisensi ini telah tercapai. Hubungi tim support AsetLab.", 429);
    }

    if (empty($row['file_path'])) {
        sendDownloadError('File digital untuk produk ini belum diunggah oleh administrator.', 404);
    }

    // Proteksi path traversal (ketat hanya di dalam STORAGE_PRIVATE_DIR)
    $safeBase = basename((string) $row['file_path']);
    $fullPath = realpath(STORAGE_PRIVATE_DIR . '/' . $safeBase);
    $realRoot = realpath(STORAGE_PRIVATE_DIR);

    if (!$fullPath || !$realRoot || !str_starts_with($fullPath, $realRoot) || !is_file($fullPath)) {
        sendDownloadError('File fisik produk tidak ditemukan di penyimpanan privat server.', 404);
    }

    // Catat log & update counter unduhan
    $stmtUpd = $pdo->prepare(
        "UPDATE user_licenses
         SET download_count = download_count + 1,
             last_downloaded_at = NOW()
         WHERE id = ?"
    );
    $stmtUpd->execute([$row['id']]);

    try {
        $stmtLog = $pdo->prepare(
            "INSERT INTO download_logs (license_id, ip_address, user_agent) VALUES (?, ?, ?)"
        );
        $stmtLog->execute([
            $row['id'],
            $_SERVER['REMOTE_ADDR'] ?? null,
            substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        ]);
    } catch (PDOException $logErr) {
        error_log('[AsetLab Download Log Error] ' . $logErr->getMessage());
    }

    // Siapkan nama file unduhan yang bersih
    $downloadName = trim((string) ($row['file_name'] ?? '')) ?: $safeBase;
    $downloadName = preg_replace('/[^a-zA-Z0-9._ -]/', '_', $downloadName);
    $fileSize     = filesize($fullPath);

    // Bersihkan output buffer agar file biner tidak korup
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: private, must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $fileSize);
    header('X-Content-Type-Options: nosniff');

    // Stream chunked 8KB agar hemat RAM di shared hosting DomaiNesia
    $handle = fopen($fullPath, 'rb');
    if ($handle === false) {
        sendDownloadError('Gagal membaca file dari server.', 500);
    }
    while (!feof($handle)) {
        echo fread($handle, 8192);
        flush();
    }
    fclose($handle);
    exit;
} catch (Throwable $e) {
    error_log('[AsetLab Download Error] ' . $e->getMessage());
    sendDownloadError('Terjadi kesalahan saat memproses unduhan.', 500);
}
