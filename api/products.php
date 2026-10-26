<?php
/**
 * AsetLab Platform - Products API (CRUD)
 */
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Read Single or All
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $product = $stmt->fetch();
                if ($product) {
                    $product['tags'] = json_decode($product['tags'] ?: '[]');
                    sendResponse(['status' => 'success', 'data' => $product]);
                } else {
                    sendResponse(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                $products = $stmt->fetchAll();
                foreach ($products as &$p) {
                    $p['tags'] = json_decode($p['tags'] ?: '[]');
                    $p['price'] = (float)$p['price'];
                    $p['downloads'] = (int)$p['downloads'];
                    $p['rating'] = (float)$p['rating'];
                }
                sendResponse(['status' => 'success', 'data' => $products]);
            }
            break;

        case 'POST':
            // Create Product
            $input = getJsonInput();
            if (empty($input['title'])) {
                sendResponse(['status' => 'error', 'message' => 'Judul produk wajib diisi'], 400);
            }

            $id = $input['id'] ?? ('PROD-' . strtoupper(substr(uniqid(), -6)));
            $sku = $input['sku'] ?? ('SKU-' . time());
            $title = $input['title'];
            $category = $input['category'] ?? 'UI Kit';
            $price = (float)($input['price'] ?? 0);
            $status = $input['status'] ?? 'Live';
            $version = $input['version'] ?? 'v1.0.0';
            $description = $input['description'] ?? '';
            $image = $input['image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg';
            $tags = json_encode($input['tags'] ?? [$category]);

            $sql = "INSERT INTO products (id, sku, title, category, price, downloads, rating, status, version, description, image, tags) 
                    VALUES (?, ?, ?, ?, ?, 0, 5.0, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $sku, $title, $category, $price, $status, $version, $description, $image, $tags]);

            sendResponse([
                'status'  => 'success',
                'message' => 'Produk berhasil ditambahkan ke database DomaiNesia',
                'data'    => array_merge($input, ['id' => $id, 'sku' => $sku])
            ], 201);
            break;

        case 'PUT':
            // Update Product
            $input = getJsonInput();
            $id = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Produk diperlukan untuk update'], 400);
            }

            $fields = [];
            $values = [];

            $allowed = ['title', 'category', 'price', 'status', 'version', 'description', 'image', 'downloads', 'rating'];
            foreach ($allowed as $f) {
                if (isset($input[$f])) {
                    $fields[] = "`$f` = ?";
                    $values[] = $input[$f];
                }
            }

            if (isset($input['tags'])) {
                $fields[] = "`tags` = ?";
                $values[] = json_encode($input['tags']);
            }

            if (empty($fields)) {
                sendResponse(['status' => 'error', 'message' => 'Tidak ada data untuk diperbarui'], 400);
            }

            $values[] = $id;
            $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            sendResponse([
                'status'  => 'success',
                'message' => "Produk $id berhasil diperbarui"
            ]);
            break;

        case 'DELETE':
            // Delete Product
            $input = getJsonInput();
            $id = $_GET['id'] ?? ($input['id'] ?? null);

            if (!$id) {
                sendResponse(['status' => 'error', 'message' => 'ID Produk diperlukan untuk menghapus'], 400);
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            sendResponse([
                'status'  => 'success',
                'message' => "Produk $id berhasil dihapus dari database DomaiNesia"
            ]);
            break;

        default:
            sendResponse(['status' => 'error', 'message' => 'Metode HTTP tidak didukung'], 405);
    }
} catch (PDOException $e) {
    sendResponse([
        'status'  => 'error',
        'message' => 'Query database gagal: ' . $e->getMessage()
    ], 500);
}
