-- ========================================================
-- AsetLab Platform - Safe Migration v1 -> v2 (Digital Commerce)
-- Jalankan script ini di phpMyAdmin DomaiNesia jika database
-- Anda SUDAH memiliki tabel v1 dan tidak ingin menghapus data lama.
-- Kompatibel dengan MySQL 8.0+ dan MariaDB 10.x.
-- ========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP PROCEDURE IF EXISTS `asetlab_add_col_if_missing`;
DELIMITER $$
CREATE PROCEDURE `asetlab_add_col_if_missing`(
  IN tbl VARCHAR(64),
  IN col VARCHAR(64),
  IN def TEXT
)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = tbl
      AND COLUMN_NAME = col
  ) THEN
    SET @sql_stmt = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', def);
    PREPARE stmt FROM @sql_stmt;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END$$
DELIMITER ;

-- 1. Upgrade tabel `users`
CALL asetlab_add_col_if_missing('users', 'whatsapp', 'VARCHAR(32) NULL AFTER `email`');
CALL asetlab_add_col_if_missing('users', 'is_active', 'TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`');
CALL asetlab_add_col_if_missing('users', 'updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`');
ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'client') NOT NULL DEFAULT 'client';

-- Perbaiki password hash admin default agar cocok dengan password 'admin123' (jika masih hash placeholder lama)
UPDATE `users`
SET `password_hash` = '$2y$10$MnfRFfMinsL0GY2vzEtuwOAFD0IH5V7nP7zvWtvVwARCvJTNzovBa'
WHERE `email` = 'admin@asetlab.id'
  AND `password_hash` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- 2. Upgrade tabel `products`
CALL asetlab_add_col_if_missing('products', 'slug', 'VARCHAR(255) NULL AFTER `id`');
CALL asetlab_add_col_if_missing('products', 'original_price', 'DECIMAL(15, 2) NULL AFTER `price`');
CALL asetlab_add_col_if_missing('products', 'license_type', 'VARCHAR(120) NOT NULL DEFAULT ''Commercial Single Project'' AFTER `version`');
CALL asetlab_add_col_if_missing('products', 'highlights', 'JSON NULL AFTER `description`');
CALL asetlab_add_col_if_missing('products', 'preview_url', 'VARCHAR(1000) NULL AFTER `highlights`');
CALL asetlab_add_col_if_missing('products', 'file_path', 'VARCHAR(500) NULL AFTER `image`');
CALL asetlab_add_col_if_missing('products', 'file_name', 'VARCHAR(255) NULL AFTER `file_path`');
CALL asetlab_add_col_if_missing('products', 'file_size', 'BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `file_name`');

-- Isi slug untuk produk eksisting yang masih kosong
UPDATE `products`
SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(TRIM(`title`), ' & ', '-'), ' ', '-'), '.', ''))
WHERE `slug` IS NULL OR `slug` = '';

-- 3. Upgrade tabel `transactions`
CALL asetlab_add_col_if_missing('transactions', 'order_id', 'VARCHAR(50) NULL AFTER `id`');
CALL asetlab_add_col_if_missing('transactions', 'client_email', 'VARCHAR(255) NULL AFTER `client`');
CALL asetlab_add_col_if_missing('transactions', 'gateway', 'VARCHAR(50) NOT NULL DEFAULT ''manual'' AFTER `category`');
CALL asetlab_add_col_if_missing('transactions', 'gateway_ref', 'VARCHAR(150) NULL AFTER `gateway`');
CALL asetlab_add_col_if_missing('transactions', 'fee_amount', 'DECIMAL(15, 2) NOT NULL DEFAULT 0.00 AFTER `amount`');
CALL asetlab_add_col_if_missing('transactions', 'net_amount', 'DECIMAL(15, 2) NOT NULL DEFAULT 0.00 AFTER `fee_amount`');
CALL asetlab_add_col_if_missing('transactions', 'paid_at', 'DATETIME NULL AFTER `status`');
CALL asetlab_add_col_if_missing('transactions', 'raw_webhook', 'JSON NULL AFTER `paid_at`');

UPDATE `transactions` SET `net_amount` = `amount` - `fee_amount` WHERE `net_amount` = 0 AND `amount` > 0;

-- 4. Buat tabel baru untuk transaksi produk digital
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_type` ENUM('percent', 'fixed') NOT NULL DEFAULT 'percent',
  `discount_value` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `min_purchase` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `max_uses` INT NULL,
  `used_count` INT NOT NULL DEFAULT 0,
  `expires_at` DATETIME NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `coupons` (`code`, `discount_type`, `discount_value`, `min_purchase`, `max_uses`, `is_active`) VALUES
('LAUNCH20', 'percent', 20.00, 100000.00, 500, 1),
('HEMAT50K', 'fixed', 50000.00, 250000.00, 200, 1);

CREATE TABLE IF NOT EXISTS `digital_orders` (
  `id` VARCHAR(50) NOT NULL,
  `invoice_number` VARCHAR(100) NOT NULL UNIQUE,
  `access_token` VARCHAR(64) NOT NULL UNIQUE,
  `user_id` INT NULL,
  `buyer_name` VARCHAR(255) NOT NULL,
  `buyer_email` VARCHAR(255) NOT NULL,
  `buyer_whatsapp` VARCHAR(32) NOT NULL,
  `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `admin_fee` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `coupon_code` VARCHAR(50) NULL,
  `payment_gateway` VARCHAR(50) NOT NULL DEFAULT 'simulator',
  `payment_method` VARCHAR(100) NULL,
  `gateway_reference` VARCHAR(150) NULL,
  `payment_url` VARCHAR(1000) NULL,
  `snap_token` VARCHAR(255) NULL,
  `status` ENUM('pending', 'paid', 'expired', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  `paid_at` DATETIME NULL,
  `expired_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_digital_orders_user` (`user_id`),
  INDEX `idx_digital_orders_email` (`buyer_email`),
  INDEX `idx_digital_orders_status` (`status`),
  CONSTRAINT `fk_digital_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `digital_order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` VARCHAR(50) NOT NULL,
  `product_id` VARCHAR(50) NOT NULL,
  `product_title` VARCHAR(255) NOT NULL,
  `product_sku` VARCHAR(100) NOT NULL,
  `license_type` VARCHAR(120) NOT NULL DEFAULT 'Commercial Single Project',
  `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_order_items_order` (`order_id`),
  INDEX `idx_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `digital_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_licenses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `license_key` VARCHAR(64) NOT NULL UNIQUE,
  `order_id` VARCHAR(50) NOT NULL,
  `order_item_id` INT NOT NULL,
  `user_id` INT NULL,
  `buyer_email` VARCHAR(255) NOT NULL,
  `product_id` VARCHAR(50) NOT NULL,
  `download_count` INT NOT NULL DEFAULT 0,
  `max_downloads` INT NOT NULL DEFAULT 15,
  `last_downloaded_at` DATETIME NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_licenses_user` (`user_id`),
  INDEX `idx_licenses_email` (`buyer_email`),
  INDEX `idx_licenses_order` (`order_id`),
  CONSTRAINT `fk_licenses_order` FOREIGN KEY (`order_id`) REFERENCES `digital_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_licenses_item` FOREIGN KEY (`order_item_id`) REFERENCES `digital_order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_licenses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_licenses_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `download_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `license_id` INT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(500) NULL,
  `downloaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_dl_license` (`license_id`),
  CONSTRAINT `fk_dl_license` FOREIGN KEY (`license_id`) REFERENCES `user_licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `consultations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `whatsapp` VARCHAR(32) NULL,
  `service_category` VARCHAR(150) NOT NULL,
  `notes` TEXT NULL,
  `status` ENUM('new', 'contacted', 'closed') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP PROCEDURE IF EXISTS `asetlab_add_col_if_missing`;
SET FOREIGN_KEY_CHECKS = 1;
