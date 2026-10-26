-- ========================================================
-- AsetLab Platform - Database Schema for DomaiNesia (MySQL / MariaDB)
-- Encoding: UTF8MB4
-- ========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table structure for `products` (Katalog Produk Digital)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` VARCHAR(50) NOT NULL,
  `sku` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `category` ENUM('UI Kit', 'Source Code', 'Template & OS', '3D & Grafis') NOT NULL DEFAULT 'UI Kit',
  `price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `downloads` INT NOT NULL DEFAULT 0,
  `rating` DECIMAL(3, 2) NOT NULL DEFAULT 5.00,
  `status` ENUM('Live', 'Draft') NOT NULL DEFAULT 'Live',
  `version` VARCHAR(50) NOT NULL DEFAULT 'v1.0.0',
  `description` TEXT NULL,
  `image` VARCHAR(1000) NULL,
  `tags` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping initial data for `products`
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `sku`, `title`, `category`, `price`, `downloads`, `rating`, `status`, `version`, `description`, `image`, `tags`) VALUES
('PROD-001', 'DS-CYBERPULSE-25', 'Fintech & Crypto SaaS Dashboard UI Kit', 'UI Kit', 349000.00, 412, 4.90, 'Live', 'v2.4.0', 'Design system Figma lengkap dengan 240+ komponen terautentikasi token.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg', '["Figma", "Tailwind", "SaaS", "Dark Mode"]'),
('PROD-002', 'TMPL-NEXTJS-LANDING', 'SaaS Landing Page Next.js & Tailwind', 'Source Code', 299000.00, 328, 4.80, 'Live', 'v1.8.2', 'Template modern Next.js 14 App Router dengan optimasi SEO 100/100.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEjS290740Qc3P7-bU9o1pLp02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4', '["Next.js", "React", "Tailwind"]'),
('PROD-003', 'DS-TOKEN-LIB-ASET', 'Asetlab Design System & Token Library', 'UI Kit', 499000.00, 189, 5.00, 'Live', 'v3.0.0', 'Master token library untuk Flutter, React, dan Tailwind CSS.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuD28K8p02f0R_iXh0pB2K9aF4qA-0N8jK92jZ21Q0_5p4e7R8s9', '["Design System", "Tokens", "Figma"]'),
('PROD-004', 'APP-ECOMMERCE-STARTER', 'E-Commerce Mobile App UI Starter', 'UI Kit', 279000.00, 541, 4.70, 'Live', 'v1.2.0', 'Desain mobile app e-commerce komprehensif dengan 60+ layar.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ', '["Mobile", "iOS", "Android"]'),
('PROD-005', 'NOTION-STARTUP-OS', 'Notion Startup Operating System 2025', 'Template & OS', 189000.00, 875, 4.90, 'Live', 'v2.1.0', 'Sistem operasional Notion untuk startup, product roadmap, dan sprint.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A', '["Notion", "Productivity", "Startup"]'),
('PROD-006', '3D-CYBER-ICON-PACK', '3D Cybernetic Luminescent Icon Pack', '3D & Grafis', 249000.00, 160, 4.80, 'Draft', 'v1.0.0', 'Koleksi 80 icon 3D format PNG transparan & Blender source file.', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q', '["3D", "Icons", "Blender"]');

-- --------------------------------------------------------
-- Table structure for `orders` (Pesanan & Manajemen Proyek)
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

-- --------------------------------------------------------
-- Dumping initial data for `orders`
-- --------------------------------------------------------
INSERT INTO `orders` (`id`, `client`, `client_email`, `client_avatar`, `project`, `scope`, `assignee`, `budget`, `deadline`, `progress`, `status`) VALUES
('ORD-2025-089', 'PT FinPay Nusantara', 'corporate@finpay.id', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A', 'SaaS Crypto Tracking Platform', 'Full-Stack Web App', 'Bramantyo & Rizky', 48500000.00, '2026-10-28', 75, 'Sedang Dikerjakan'),
('ORD-2025-090', 'KopiKultur Indonesia', 'tech@kopikultur.co.id', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q', 'Omnichannel E-Commerce & Loyalty', 'Web & API Integration', 'Siti Andini', 32000000.00, '2026-11-05', 40, 'Sedang Dikerjakan'),
('ORD-2025-091', 'LogistiKu Global', 'operation@logistiku.id', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCLl94JZ4kGRP67QKvRvoSNxeX1RqtMC9aajgU-lA4ehu8jbKWEBnSmai24xVujV-aJ_voffRYeoXRH8TaUJ0DgUFn7nXCh7rw_xD5RsVa8AWHal2ElGVpZCztupCXtJ9ilugvJmcX7_c6AVizYNYCG4z17mcAYN2zTqZaUXLALBSbV4mPlJGS_bwt4KsAamomqzPRc5cEGBEUoR0Ss2In8OKcn8TtQQ5Xsb5I5X_Tvp-49qDI4WZVa6A', 'B2B Fleet Management Dashboard', 'UI/UX & Frontend Architecture', 'Bramantyo W.', 65000000.00, '2026-11-12', 15, 'Menunggu Konfirmasi'),
('ORD-2025-092', 'TechNusa Summit 2026', 'event@technusa.id', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDtOZwVf0Ni8WXP6p6oad4fdo2TcWklxZViT4_eggWtrCCo_cfWe-3Wv2wdpU1PsEeMlINBwnesZijA6ONbLPXhdA9ic-3T4RXiuXoFapaeOarKBwTV4W7tjrlwWBGvpM8VG2ruHOwLe3kKULjNCPCEq35-034XQRk0wq3RhYQSJHfEe6ierA3QqPZqxvuOvWQba1qcSz1SgjtHu-NqgfqvREGNu6OFSL_t5fL8QwyZhCzInyhmslMljw', 'Event Landing Page & Ticketing Gateway', 'Landing Page & Payment Hook', 'Tim Frontend', 25000000.00, '2026-10-20', 100, 'Selesai'),
('ORD-2025-093', 'Nexa Brand Studio', 'hello@nexabrand.com', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHCeZo0BiPaq-zAbEFfp48AYriqBbCnJu2PTpOd4Kxnk9PtH3tvMmfdPqpsJVs2HDnsX0uFUiCY7YPnAuBO6wamOZfu_hQkCAZtadSIgVM-JhjdI3DiSkJpNvTI25BdCVWDfPWBWVdUumuQypcEbzeZDUFoUZh_D61XjJJKUtFMWreDUuxX3PKpmHpaNAlnl_ofhEjLxP0RpfN4b-u29NuK0DwYyPHNtPnatNlV5MmVJlceiYzwjmfQ', 'Social Media Growth & Creative Automation', 'Creative Direction & Ads Strategy', 'Siti Andini', 18500000.00, '2026-10-30', 60, 'Review');

-- --------------------------------------------------------
-- Table structure for `transactions` (Transaksi Keuangan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` VARCHAR(50) NOT NULL,
  `ref_invoice` VARCHAR(100) NOT NULL,
  `date` VARCHAR(100) NOT NULL,
  `client` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NOT NULL,
  `method` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('Berhasil', 'Menunggu', 'Gagal') NOT NULL DEFAULT 'Berhasil',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping initial data for `transactions`
-- --------------------------------------------------------
INSERT INTO `transactions` (`id`, `ref_invoice`, `date`, `client`, `category`, `method`, `amount`, `status`) VALUES
('TRX-94821', 'INV/20261022/ASET/9041', '22 Okt 2026, 14:32 WIB', 'PT FinPay Nusantara', 'Termin II (Milestone Proyek)', 'BCA Virtual Account', 24250000.00, 'Berhasil'),
('TRX-94820', 'INV/20261022/DIGI/1049', '22 Okt 2026, 12:15 WIB', 'Budi Santoso (Studio Dev)', 'Pembelian Lisensi UI Kit', 'QRIS Dinamis', 349000.00, 'Berhasil'),
('TRX-94819', 'INV/20261021/DIGI/1048', '21 Okt 2026, 19:40 WIB', 'Dian Permana', 'Next.js Landing Template', 'Midtrans Mandiri', 299000.00, 'Berhasil'),
('TRX-94818', 'INV/20261021/ASET/9040', '21 Okt 2026, 16:05 WIB', 'LogistiKu Global', 'DP 30% Perancangan Sistem', 'Corporate Escrow Stripe', 19500000.00, 'Menunggu'),
('TRX-94817', 'INV/20261020/DIGI/1047', '20 Okt 2026, 11:22 WIB', 'Ahmad Fauzi', 'Notion Startup OS 2025', 'GoPay / QRIS', 189000.00, 'Berhasil');

-- --------------------------------------------------------
-- Table structure for `users` (Admin & Klien)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'client') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Default (Password: admin123)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`) VALUES
('Bramantyo Wardhana', 'admin@asetlab.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

SET FOREIGN_KEY_CHECKS = 1;
