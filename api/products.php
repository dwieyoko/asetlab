<?php
/**
 * AsetLab Platform - Products API (Public Read Live + Admin Protected CRUD)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function formatProductRow(array $row, bool $isAdmin = false): array {
    $row['price']          = (float) ($row['price'] ?? 0);
    $row['original_price'] = isset($row['original_price']) && $row['original_price'] !== null
        ? (float) $row['original_price']
        : null;
    $row['downloads']      = (int) ($row['downloads'] ?? 0);
    $row['rating']         = (float) ($row['rating'] ?? 5.0);
    $row['file_size']      = (int) ($row['file_size'] ?? 0);
    $row['tags']           = is_string($row['tags'] ?? null) ? (json_decode($row['tags'], true) ?: []) : ($row['tags'] ?? []);
    $row['highlights']     = is_string($row['highlights'] ?? null) ? (json_decode($row['highlights'], true) ?: []) : ($row['highlights'] ?? []);
    $row['has_file']       = !empty($row['file_path']);

    // Jangan pernah bocorkan nama file fisik private storage ke publik!
    if (!$isAdmin) {
        unset($row['file_path']);
    }

    return $row;
}

try {
    switch ($method) {
        case 'GET':
            $currentUser = getCurrentUser($pdo);
            $isAdmin     = $currentUser && ($currentUser['role'] ?? '') === 'admin';
            $includeAll  = $isAdmin && (($_GET['scope'] ?? '') === 'admin' || isset($_GET['all']));

            if (isset($_GET['id']) || isset($_GET['slug'])) {
                if (isset($_GET['slug'])) {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ? LIMIT 1");
                    $stmt->execute([trim((string) $_GET['slug'])]);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                    $stmt->execute([trim((string) $_GET['id'])]);
                }
                $product = $stmt->fetch();

                if (!$product || (!$isAdmin && ($product['status'] ?? '') !== 'Live')) {
                    sendResponse(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
                }

                sendResponse([
                    'status' => 'success',
                    'data'   => formatProductRow($product, $isAdmin),
                ]);
            }

            if ($includeAll) {
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
            } else {
                $stmt = $pdo->query("SELECT * FROM products WHERE status = 'Live' ORDER BY created_at DESC");
            }

            $rows = $stmt->fetchAll();
            $products = array_map(fn($p) => formatProductRow($p, $isAdmin), $rows);

            sendResponse([
                'status' => 'success',
                'data'   => $products,
            ]);
            break;

        case 'POST':
            requireAdmin($pdo);
            $input = getJsonInput();
            $title = trim((string) ($input['title'] ?? ''));

            if ($title === '') {
                sendResponse(['status' => 'error', 'message' => 'Judul produk wajib diisi'], 400);
            }

            $id            = trim((string) ($input['id'] ?? '')) ?: ('PROD-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6)));
            $sku           = trim((string) ($input['sku'] ?? '')) ?: ('SKU-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)));
            $baseSlug      = slugify((string) ($input['slug'] ?? $title));
            $slug          = $baseSlug;

            // Pastikan slug unik
            $stmtCheckSlug = $pdo->prepare("SELECT id FROM products WHERE slug = ? LIMIT 1");
            $stmtCheckSlug->execute([$slug]);
            if ($stmtCheckSlug->fetch()) {
                $slug = $baseSlug . '-' . strtolower(substr(bin2hex(random_bytes(2)), 0, 4));
            }

            $category      = (string) ($input['category'] ?? 'UI Kit');
            $price         = max(0, (float) ($input['price'] ?? 0));
            $originalPrice = isset($input['original_price']) && $input['original_price'] !== ''
                ? max(0, (float) $input['original_price'])
                : null;
            $status        = in_array($input['status'] ?? 'Live', ['Live', 'Draft'], true) ? $input['status'] : 'Live';
            $version       = trim((string) ($input['version'] ?? 'v1.0.0')) ?: 'v1.0.0';
            $licenseType   = trim((string) ($input['license_type'] ?? 'Commercial Single Project')) ?: 'Commercial Single Project';
            $description   = (string) ($input['description'] ?? '');
            $previewUrl    = trim((string) ($input['preview_url'] ?? ''));
            $image         = trim((string) ($input['image'] ?? ''));
            $filePath      = !empty($input['file_path']) ? basename((string) $input['file_path']) : null;
            $fileName      = !empty($input['file_name']) ? trim((string) $input['file_name']) : null;
            $fileSize      = (int) ($input['file_size'] ?? 0);
            $tags          = json_encode(is_array($input['tags'] ?? null) ? $input['tags'] : [$category], JSON_UNESCAPED_UNICODE);
            $highlights    = json_encode(is_array($input['highlights'] ?? null) ? $input['highlights'] : [], JSON_UNESCAPED_UNICODE);

            $sql = "INSERT INTO products
                    (id, slug, sku, title, category, price, original_price, downloads, rating, status, version, license_type, description, highlights, preview_url, image, file_path, file_name, file_size, tags)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0, 5.0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $id, $slug, $sku, $title, $category, $price, $originalPrice,
                $status, $version, $licenseType, $description, $highlights,
                $previewUrl ?: null, $image ?: null, $filePath, $fileName, $fileSize, $tags
            ]);

            $stmtGet = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmtGet->execute([$id]);
            $created = $stmtGet->fetch();

            sendResponse([
                'status'  => 'success',
                'message' => 'Produk digital berhasil ditambahkan.',
                'data'    => $created ? formatProductRow($created, true) : ['id' => $id, 'slug' => $slug, 'sku' => $sku],
            ], 201);
            break;

        case 'PUT':
            requireAdmin($pdo);
            $input = getJsonInput();
            $id    = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Produk diperlukan untuk update'], 400);
            }

            $updates = [];
            $allowedScalars = [
                'title', 'slug', 'sku', 'category', 'price', 'original_price',
                'status', 'version', 'license_type', 'description', 'preview_url',
                'image', 'file_path', 'file_name', 'file_size', 'downloads', 'rating'
            ];

            foreach ($allowedScalars as $field) {
                if (array_key_exists($field, $input)) {
                    $val = $input[$field];
                    if ($field === 'file_path' && $val !== null && $val !== '') {
                        $val = basename((string) $val);
                    }
                    if ($field === 'slug' && is_string($val) && trim($val) !== '') {
                        $val = slugify($val);
                    }
                    if ($field === 'original_price' && $val === '') {
                        $val = null;
                    }
                    $updates[$field] = $val;
                }
            }

            if (array_key_exists('tags', $input)) {
                $updates['tags'] = json_encode(is_array($input['tags']) ? $input['tags'] : [], JSON_UNESCAPED_UNICODE);
            }
            if (array_key_exists('highlights', $input)) {
                $updates['highlights'] = json_encode(is_array($input['highlights']) ? $input['highlights'] : [], JSON_UNESCAPED_UNICODE);
            }

            if (empty($updates)) {
                sendResponse(['status' => 'error', 'message' => 'Tidak ada data untuk diperbarui'], 400);
            }

            $setClauses = [];
            $values     = [];
            foreach ($updates as $col => $val) {
                $setClauses[] = "`{$col}` = ?";
                $values[]     = $val;
            }
            $values[] = $id;

            $sql = "UPDATE products SET " . implode(', ', $setClauses) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            $stmtGet = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmtGet->execute([$id]);
            $updated = $stmtGet->fetch();

            sendResponse([
                'status'  => 'success',
                'message' => "Produk {$id} berhasil diperbarui",
                'data'    => $updated ? formatProductRow($updated, true) : null,
            ]);
            break;

        case 'DELETE':
            requireAdmin($pdo);
            $input = getJsonInput();
            $id    = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Produk diperlukan untuk menghapus'], 400);
            }

            // Cek apakah produk sudah memiliki riwayat pembelian (jika ya, sarankan ubah ke Draft)
            try {
                $stmtChk = $pdo->prepare("SELECT COUNT(*) FROM digital_order_items WHERE product_id = ?");
                $stmtChk->execute([$id]);
                if ((int) $stmtChk->fetchColumn() > 0) {
                    sendResponse([
                        'status'  => 'error',
                        'message' => 'Produk sudah memiliki riwayat pembelian. Ubah status ke Draft agar pembeli lama tetap dapat mengunduh.'
                    ], 409);
                }
            } catch (PDOException $e) {
                // Abaikan jika tabel digital_order_items belum ada
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            sendResponse([
                'status'  => 'success',
                'message' => "Produk {$id} berhasil dihapus",
            ]);
            break;

        default:
            sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
    }
} catch (PDOException $e) {
    error_log('[AsetLab Products Error] ' . $e->getMessage());
    sendResponse([
        'status'  => 'error',
        'message' => APP_ENV === 'development' ? $e->getMessage() : 'Gagal memproses data produk.',
    ], 500);
}
