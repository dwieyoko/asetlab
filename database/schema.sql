-- ========================================================
-- AsetLab Platform v2.0 - Full Database Schema (MySQL 8 / MariaDB 10+)
-- Encoding: UTF8MB4
-- Cocok untuk fresh install di phpMyAdmin cPanel DomaiNesia.
-- Jika database sudah berisi data lama, gunakan:
--   database/migration_v2_digital_commerce.sql
-- ========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. Table `users` (Administrator & Pelanggan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `whatsapp` VARCHAR(32) NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'client') NOT NULL DEFAULT 'client',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Default (Email: admin@asetlab.id | Password awal: admin123 — SEGERA GANTI setelah login pertama!)
INSERT INTO `users` (`name`, `email`, `whatsapp`, `password_hash`, `role`, `is_active`) VALUES
('Admin AsetLab', 'admin@asetlab.id', '6281200000000', '$2y$10$MnfRFfMinsL0GY2vzEtuwOAFD0IH5V7nP7zvWtvVwARCvJTNzovBa', 'admin', 1);

-- --------------------------------------------------------
-- 2. Table `products` (Katalog Produk Digital)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `sku` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `category` ENUM('UI Kit', 'Source Code', 'Template & OS', '3D & Grafis') NOT NULL DEFAULT 'UI Kit',
  `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `original_price` DECIMAL(15, 2) NULL,
  `downloads` INT NOT NULL DEFAULT 0,
  `rating` DECIMAL(3, 2) NOT NULL DEFAULT 5.00,
  `status` ENUM('Live', 'Draft') NOT NULL DEFAULT 'Live',
  `version` VARCHAR(50) NOT NULL DEFAULT 'v1.0.0',
  `license_type` VARCHAR(120) NOT NULL DEFAULT 'Commercial Single Project',
  `description` TEXT NULL,
  `highlights` JSON NULL,
  `preview_url` VARCHAR(1000) NULL,
  `image` VARCHAR(1000) NULL,
  `file_path` VARCHAR(500) NULL COMMENT 'Nama file tersimpan di storage/private/products/',
  `file_name` VARCHAR(255) NULL COMMENT 'Nama file asli untuk header Content-Disposition saat diunduh',
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Ukuran file dalam bytes',
  `tags` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_products_status_cat` (`status`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `slug`, `sku`, `title`, `category`, `price`, `original_price`, `downloads`, `rating`, `status`, `version`, `license_type`, `description`, `highlights`, `preview_url`, `image`, `tags`) VALUES
('PROD-001', 'fintech-crypto-saas-dashboard-ui-kit', 'DS-CYBERPULSE-25', 'Fintech & Crypto SaaS Dashboard UI Kit', 'UI Kit', 349000.00, 520000.00, 412, 4.90, 'Live', 'v2.4.0', 'Commercial Agency License', 'Design system Figma lengkap dengan 240+ komponen terautentikasi token, dark mode Obsidian Cyberpulse, dan dokumentasi auto-layout.', '["240+ Komponen Figma Auto-Layout", "Design Token JSON Siap Tailwind CSS", "Dark & Light Mode Variable Ready", "Free Update Versi Minor Selamanya"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg', '["Figma", "Tailwind", "SaaS", "Dark Mode"]'),
('PROD-002', 'saas-landing-page-nextjs-tailwind', 'TMPL-NEXTJS-LANDING', 'SaaS Landing Page Next.js & Tailwind', 'Source Code', 299000.00, 450000.00, 328, 4.80, 'Live', 'v1.8.2', 'Commercial Developer License', 'Template modern Next.js 14 App Router dengan optimasi SEO 100/100, komponen modular, dan integrasi analitik siap pakai.', '["Next.js App Router + TypeScript", "Skor Lighthouse SEO & Performa 99+", "15+ Section Siap Konversi", "Dokumentasi Deploy Vercel & VPS"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEjS290740Qc3P7-bU9o1pLp02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4', '["Next.js", "React", "Tailwind"]'),
('PROD-003', 'asetlab-design-system-token-library', 'DS-TOKEN-LIB-ASET', 'Asetlab Design System & Token Library', 'UI Kit', 499000.00, 750000.00, 189, 5.00, 'Live', 'v3.0.0', 'Studio Unlimited License', 'Master token library untuk Flutter, React, Vue, dan Tailwind CSS lengkap dengan panduan tipografi dan skala warna.', '["Multi-platform Tokens (CSS, Tailwind, Flutter)", "Aksesibilitas Kontras WCAG AA", "Komponen Form, Tabel & Modal Lengkap"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuD28K8p02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4e7R8s9', '["Design System", "Tokens", "Figma"]'),
('PROD-004', 'ecommerce-mobile-app-ui-starter', 'APP-ECOMMERCE-STARTER', 'E-Commerce Mobile App UI Starter', 'UI Kit', 279000.00, 420000.00, 541, 4.70, 'Live', 'v1.2.0', 'Commercial Single Project', 'Desain mobile app e-commerce komprehensif dengan 60+ layar mulai dari onboarding, katalog, checkout, hingga tracking paket.', '["60+ Screen iOS & Android", "Alur Checkout & Payment Siap Pakai", "Prototype Interaktif Figma"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ', '["Mobile", "iOS", "Android"]'),
('PROD-005', 'notion-startup-operating-system-2025', 'NOTION-STARTUP-OS', 'Notion Startup Operating System 2025', 'Template & OS', 189000.00, 299000.00, 875, 4.90, 'Live', 'v2.1.0', 'Personal & Team License', 'Sistem operasional Notion untuk startup, product roadmap, sprint tracking, CRM klien, dan manajemen cashflow.', '["Workspace All-in-One Startup", "Template OKR, Sprint & CRM", "Video Panduan Duplikasi & Setup"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A', '["Notion", "Productivity", "Startup"]'),
('PROD-006', '3d-cybernetic-luminescent-icon-pack', '3D-CYBER-ICON-PACK', '3D Cybernetic Luminescent Icon Pack', '3D & Grafis', 249000.00, 390000.00, 160, 4.80, 'Draft', 'v1.0.0', 'Commercial Agency License', 'Koleksi 80 icon 3D format PNG resolusi tinggi transparan & Blender source file yang mudah diubah warnanya.', '["80 Icon 3D PNG 3000x3000px", "File Source .blend Tersedia", "3 Sudut Kamera Berbeda"]', '', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q', '["3D", "Icons", "Blender"]');

-- --------------------------------------------------------
-- 3. Table `coupons` (Kode Promo / Diskon)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
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

INSERT INTO `coupons` (`code`, `discount_type`, `discount_value`, `min_purchase`, `max_uses`, `is_active`) VALUES
('LAUNCH20', 'percent', 20.00, 100000.00, 500, 1),
('HEMAT50K', 'fixed', 50000.00, 250000.00, 200, 1);

-- --------------------------------------------------------
-- 4. Table `digital_orders` (Checkout Produk Digital — Support Guest & Member)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `digital_orders`;
CREATE TABLE `digital_orders` (
  `id` VARCHAR(50) NOT NULL,
  `invoice_number` VARCHAR(100) NOT NULL UNIQUE,
  `access_token` VARCHAR(64) NOT NULL UNIQUE COMMENT 'Token aman agar guest bisa membuka invoice & mengunduh file',
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

-- --------------------------------------------------------
-- 5. Table `digital_order_items` (Item per Checkout Produk Digital)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `digital_order_items`;
CREATE TABLE `digital_order_items` (
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

-- --------------------------------------------------------
-- 6. Table `user_licenses` (Hak Unduh & Lisensi Produk Digital)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `user_licenses`;
CREATE TABLE `user_licenses` (
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

-- --------------------------------------------------------
-- 7. Table `download_logs` (Audit Log Pengunduhan Aset Privat)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `download_logs`;
CREATE TABLE `download_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `license_id` INT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(500) NULL,
  `downloaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_dl_license` (`license_id`),
  CONSTRAINT `fk_dl_license` FOREIGN KEY (`license_id`) REFERENCES `user_licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Table `orders` (Manajemen Proyek Jasa Kustom)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` VARCHAR(50) NOT NULL,
  `client` VARCHAR(255) NOT NULL,
  `client_email` VARCHAR(255) NOT NULL,
  `client_avatar` VARCHAR(1000) NULL,
  `project` VARCHAR(255) NOT NULL,
  `scope` VARCHAR(255) NOT NULL,
  `assignee` VARCHAR(255) NOT NULL,
  `budget` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `deadline` DATE NOT NULL,
  `progress` INT NOT NULL DEFAULT 0,
  `status` ENUM('Menunggu Konfirmasi', 'Sedang Dikerjakan', 'Review', 'Selesai') NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `client`, `client_email`, `client_avatar`, `project`, `scope`, `assignee`, `budget`, `deadline`, `progress`, `status`) VALUES
('ORD-2025-089', 'PT FinPay Nusantara', 'corporate@finpay.id', '', 'SaaS Crypto Tracking Platform', 'Full-Stack Web App', 'Bramantyo & Rizky', 48500000.00, '2026-10-28', 75, 'Sedang Dikerjakan'),
('ORD-2025-090', 'KopiKultur Indonesia', 'tech@kopikultur.co.id', '', 'Omnichannel E-Commerce & Loyalty', 'Web & API Integration', 'Siti Andini', 32000000.00, '2026-11-05', 40, 'Sedang Dikerjakan'),
('ORD-2025-091', 'LogistiKu Global', 'operation@logistiku.id', '', 'B2B Fleet Management Dashboard', 'UI/UX & Frontend Architecture', 'Bramantyo W.', 65000000.00, '2026-11-12', 15, 'Menunggu Konfirmasi'),
('ORD-2025-092', 'TechNusa Summit 2026', 'event@technusa.id', '', 'Event Landing Page & Ticketing Gateway', 'Landing Page & Payment Hook', 'Tim Frontend', 25000000.00, '2026-10-20', 100, 'Selesai'),
('ORD-2025-093', 'Nexa Brand Studio', 'hello@nexabrand.com', '', 'Social Media Growth & Creative Automation', 'Creative Direction & Ads Strategy', 'Siti Andini', 18500000.00, '2026-10-30', 60, 'Review');

-- --------------------------------------------------------
-- 9. Table `transactions` (Buku Besar / Ledger Keuangan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` VARCHAR(50) NOT NULL,
  `order_id` VARCHAR(50) NULL COMMENT 'Relasi opsional ke digital_orders.id',
  `ref_invoice` VARCHAR(100) NOT NULL,
  `date` VARCHAR(100) NOT NULL,
  `client` VARCHAR(255) NOT NULL,
  `client_email` VARCHAR(255) NULL,
  `category` VARCHAR(255) NOT NULL,
  `gateway` VARCHAR(50) NOT NULL DEFAULT 'manual',
  `gateway_ref` VARCHAR(150) NULL,
  `method` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `fee_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('Berhasil', 'Menunggu', 'Gagal') NOT NULL DEFAULT 'Menunggu',
  `paid_at` DATETIME NULL,
  `raw_webhook` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_trx_order` (`order_id`),
  INDEX `idx_trx_status` (`status`),
  INDEX `idx_trx_invoice` (`ref_invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transactions` (`id`, `ref_invoice`, `date`, `client`, `client_email`, `category`, `gateway`, `method`, `amount`, `fee_amount`, `net_amount`, `status`, `paid_at`) VALUES
('TRX-94821', 'INV/20261022/ASET/9041', '22 Okt 2026, 14:32 WIB', 'PT FinPay Nusantara', 'corporate@finpay.id', 'Termin II (Milestone Proyek)', 'midtrans', 'BCA Virtual Account', 24250000.00, 4000.00, 24246000.00, 'Berhasil', '2026-10-22 14:32:00'),
('TRX-94820', 'INV/20261022/DIGI/1049', '22 Okt 2026, 12:15 WIB', 'Budi Santoso (Studio Dev)', 'budi@studiodev.id', 'Pembelian Lisensi UI Kit', 'midtrans', 'QRIS Dinamis', 349000.00, 2443.00, 346557.00, 'Berhasil', '2026-10-22 12:15:00'),
('TRX-94819', 'INV/20261021/DIGI/1048', '21 Okt 2026, 19:40 WIB', 'Dian Permana', 'dian@permana.id', 'Next.js Landing Template', 'midtrans', 'Mandiri Virtual Account', 299000.00, 4000.00, 295000.00, 'Berhasil', '2026-10-21 19:40:00'),
('TRX-94818', 'INV/20261021/ASET/9040', '21 Okt 2026, 16:05 WIB', 'LogistiKu Global', 'operation@logistiku.id', 'DP 30% Perancangan Sistem', 'manual', 'Transfer Bank Corporate', 19500000.00, 0.00, 19500000.00, 'Menunggu', NULL),
('TRX-94817', 'INV/20261020/DIGI/1047', '20 Okt 2026, 11:22 WIB', 'Ahmad Fauzi', 'ahmad.fauzi@gmail.com', 'Notion Startup OS 2025', 'midtrans', 'GoPay / QRIS', 189000.00, 1323.00, 187677.00, 'Berhasil', '2026-10-20 11:22:00');

-- --------------------------------------------------------
-- 10. Table `consultations` (Lead Form Konsultasi Publik)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `consultations`;
CREATE TABLE `consultations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `whatsapp` VARCHAR(32) NULL,
  `service_category` VARCHAR(150) NOT NULL,
  `notes` TEXT NULL,
  `status` ENUM('new', 'contacted', 'closed') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
