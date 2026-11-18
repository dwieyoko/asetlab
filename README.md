# ⚡ AsetLab Platform v2.0 (asetlab.web.id / asetlab.id)

> **Platform Penjualan Produk Digital & Studio Solusi Web**  
> E-Commerce produk digital siap produksi (UI Kit Figma, Source Code, Notion OS, 3D Icon Pack) dengan **Guest Checkout**, **Payment Gateway (Midtrans Snap / Tripay / Simulator)**, **Private File Storage DomaiNesia**, **Lisensi Unik Otomatis**, **Customer Library (`/akun`)**, dan **Admin Console** berestetika *Obsidian Cyberpulse*.

---

## 🛠️ Arsitektur & Fitur Utama

* **Frontend**: Vue 3 (`<script setup>`), Vite, Tailwind CSS 3, Vue Router 4 + RBAC Navigation Guards.
* **Backend**: Native PHP 8+ PDO REST API (`api/`) — tanpa dependensi Composer, langsung siap jalan di cPanel DomaiNesia.
* **Autentikasi & Keamanan**: Pure PHP HS256 JWT (`api/helpers/auth_middleware.php`), pemisahan role `admin` vs `client`, kredensial dipisah di `api/secrets.php` (di-ignore Git & diblokir `.htaccess`).
* **Private Digital Storage**: File produk diunggah oleh Admin ke `storage/private/products/` (diblokir total oleh `.htaccess`) dan dilayani secara *chunked streaming* lewat `api/download.php` setelah verifikasi `license_key` & status `paid`.
* **Alur Transaksi Digital**:
  * Katalog Produk (`/produk`) -> Detail Produk (`/produk/:slug`) -> Keranjang Drawer -> Checkout Guest / Member (`/checkout`) + Kode Kupon (`LAUNCH20`, `HEMAT50K`).
  * Pembayaran via **Midtrans Snap**, **Tripay**, atau **Mode Simulator** -> Webhook verifikasi signature (`api/webhook_payment.php`) -> Auto-generate License Key -> Email otomatis (`MailService`) -> Halaman Invoice (`/transaksi/:invoice`) & Library Pembeli (`/akun`).

---

## 🚀 Menjalankan Secara Lokal (Development)

```bash
npm install
npm run dev
```

---

## 🌐 Panduan Setup & Go-Live di DomaiNesia cPanel

### 1. Database MySQL
* **Database Baru**: Import `database/schema.sql` melalui **phpMyAdmin**.
* **Database Lama (Upgrade v1 → v2 tanpa hapus data)**: Import `database/migration_v2_digital_commerce.sql` melalui **phpMyAdmin**.

### 2. Konfigurasi Privat (`api/secrets.php`)
1. Di File Manager cPanel (`public_html/api/`), salin `api/secrets.example.php` menjadi **`api/secrets.php`**.
2. Isi:
   * `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` (gunakan password MySQL baru yang kuat).
   * `JWT_SECRET` (string acak minimal 64 karakter).
   * `PAYMENT_GATEWAY`: pilih `'simulator'` (untuk tes awal), `'midtrans'`, atau `'tripay'`, lalu isi API key sesuai gateway pilihan Anda.
   * `SMTP_*`: aktifkan `SMTP_ENABLED => true` dengan akun email cPanel (`mail.asetlab.web.id:465`) agar pembeli otomatis menerima email invoice & link download.

### 3. Daftarkan URL Webhook di Payment Gateway
* **Midtrans Dashboard → Settings → Configuration → Payment Notification URL**:
  ```
  https://asetlab.web.id/api/webhook_payment.php?gateway=midtrans
  ```
* **Tripay Dashboard → Merchant → Callback URL**:
  ```
  https://asetlab.web.id/api/webhook_payment.php?gateway=tripay
  ```

### 4. Akun Default Admin & Keamanan Awal
* **URL Login**: `/login` → otomatis diarahkan ke `/admin`
* **Email**: `admin@asetlab.id`
* **Password awal**: `admin123` *(segera ganti di menu `/akun` → tab Keamanan Akun setelah login pertama!)*

### 5. Checklist Upload Produk Pertama
1. Masuk ke `/admin/produk` sebagai Admin.
2. Klik **Edit** pada setiap produk → unggah **Cover Gambar** dan **File Produk Digital (`.zip`/`.fig`/`.pdf`)** ke folder privat.
3. Uji 1 transaksi penuh menggunakan mode `simulator` (atau Sandbox Midtrans/Tripay), lalu pastikan file berhasil diunduh di halaman Invoice & Library `/akun`.
