<?php
/**
 * AsetLab Platform - Secure Upload API (Admin Only)
 * - type=image        -> uploads/products/ (public cover image)
 * - type=product_file -> storage/private/products/ (private digital asset, blocked by .htaccess)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

requireAdmin($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    sendResponse(['status' => 'error', 'message' => 'Metode harus POST (multipart/form-data)'], 405);
}

$type = $_POST['type'] ?? $_GET['type'] ?? 'image';
if (!in_array($type, ['image', 'product_file'], true)) {
    sendResponse(['status' => 'error', 'message' => 'Tipe upload tidak valid (gunakan image atau product_file)'], 400);
}

if (empty($_FILES['file'])) {
    sendResponse(['status' => 'error', 'message' => 'File tidak ditemukan pada request'], 400);
}

$file = $_FILES['file'];
if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $errorMap = [
        UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas upload_max_filesize server.',
        UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas formulir.',
        UPLOAD_ERR_PARTIAL    => 'File hanya terunggah sebagian. Silakan coba lagi.',
        UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang dipilih.',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan.',
        UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file ke disk.',
    ];
    $msg = $errorMap[$file['error']] ?? 'Gagal mengunggah file.';
    sendResponse(['status' => 'error', 'message' => $msg], 400);
}

$originalName = basename((string) ($file['name'] ?? 'file'));
$ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$size         = (int) ($file['size'] ?? 0);
$tmpPath      = (string) ($file['tmp_name'] ?? '');

if (!is_uploaded_file($tmpPath)) {
    sendResponse(['status' => 'error', 'message' => 'File upload tidak valid'], 400);
}

// Deteksi MIME asli via finfo
$detectedMime = '';
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detectedMime = (string) finfo_file($finfo, $tmpPath);
        finfo_close($finfo);
    }
}

if ($type === 'image') {
    $allowedExt  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxBytes    = 5 * 1024 * 1024; // 5 MB

    if (!in_array($ext, $allowedExt, true)) {
        sendResponse(['status' => 'error', 'message' => 'Format gambar harus JPG, PNG, WEBP, atau GIF'], 400);
    }
    if ($detectedMime !== '' && !in_array($detectedMime, $allowedMime, true)) {
        sendResponse(['status' => 'error', 'message' => 'Konten file bukan gambar yang valid'], 400);
    }
    if ($size > $maxBytes) {
        sendResponse(['status' => 'error', 'message' => 'Ukuran gambar maksimal 5 MB'], 400);
    }

    $storedName = 'cover_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $destPath   = UPLOADS_PUBLIC_DIR . '/' . $storedName;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        sendResponse(['status' => 'error', 'message' => 'Gagal menyimpan gambar ke direktori uploads/products/'], 500);
    }

    sendResponse([
        'status'  => 'success',
        'message' => 'Cover gambar berhasil diunggah.',
        'data'    => [
            'url'       => '/uploads/products/' . $storedName,
            'file_name' => $originalName,
            'file_size' => $size,
            'mime'      => $detectedMime,
        ],
    ], 201);
}

// type === 'product_file' (Private Digital Asset Storage)
$blockedExt = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phps', 'phar', 'exe', 'bat', 'cmd', 'sh', 'cgi', 'pl', 'py', 'js', 'html', 'htm', 'htaccess'];
$allowedExt = ['zip', 'rar', '7z', 'tar', 'gz', 'pdf', 'fig', 'sketch', 'xd', 'ai', 'psd', 'blend', 'epub', 'mp4', 'json', 'csv', 'xlsx', 'docx', 'pptx', 'txt', 'md'];
$maxBytes   = 100 * 1024 * 1024; // 100 MB

if (in_array($ext, $blockedExt, true) || !in_array($ext, $allowedExt, true)) {
    sendResponse([
        'status'  => 'error',
        'message' => 'Ekstensi file tidak diizinkan. Gunakan arsip (.zip, .rar, .7z), desain (.fig, .psd, .ai, .blend), atau dokumen (.pdf, .epub).'
    ], 400);
}

if ($size <= 0 || $size > $maxBytes) {
    sendResponse(['status' => 'error', 'message' => 'Ukuran file produk digital maksimal 100 MB'], 400);
}

$storedName = 'asset_' . date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
$destPath   = STORAGE_PRIVATE_DIR . '/' . $storedName;

if (!move_uploaded_file($tmpPath, $destPath)) {
    sendResponse(['status' => 'error', 'message' => 'Gagal menyimpan file ke storage privat'], 500);
}

// Bersihkan nama file asli untuk Content-Disposition saat diunduh nanti
$safeOriginalName = preg_replace('/[^a-zA-Z0-9._ -]/', '_', $originalName);

// Opsional: langsung ikat ke produk jika product_id dikirim
$productId = trim((string) ($_POST['product_id'] ?? ''));
if ($productId !== '') {
    try {
        $stmtOld = $pdo->prepare("SELECT file_path FROM products WHERE id = ? LIMIT 1");
        $stmtOld->execute([$productId]);
        $old = $stmtOld->fetch();

        $stmtUpd = $pdo->prepare("UPDATE products SET file_path = ?, file_name = ?, file_size = ? WHERE id = ?");
        $stmtUpd->execute([$storedName, $safeOriginalName, $size, $productId]);

        // Hapus file lama jika ada dan berbeda
        if (!empty($old['file_path']) && $old['file_path'] !== $storedName) {
            $oldFull = STORAGE_PRIVATE_DIR . '/' . basename($old['file_path']);
            if (is_file($oldFull)) {
                @unlink($oldFull);
            }
        }
    } catch (PDOException $e) {
        error_log('[AsetLab Upload Bind Error] ' . $e->getMessage());
    }
}

sendResponse([
    'status'  => 'success',
    'message' => 'File produk digital berhasil disimpan di storage privat.',
    'data'    => [
        'file_path' => $storedName,
        'file_name' => $safeOriginalName,
        'file_size' => $size,
        'mime'      => $detectedMime,
    ],
], 201);
