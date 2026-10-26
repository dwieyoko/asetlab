# ⚡ AsetLab Platform (asetlab.id)

> **Laboratorium Solusi Digital & Platform Akselerasi Bisnis**  
> Sistem inventori produk digital siap pakai (UI Kit Figma, Source Code, Notion OS, 3D Icon Pack) dan dashboard monitoring manajemen pesanan / proyek kreatif kustom dengan estetika *Obsidian Cyberpulse*.

---

## 🛠️ Arsitektur Teknologi

* **Frontend**: Vue 3 (Composition API `<script setup>`), Vite 5, Tailwind CSS, Vue Router (HTML5 History Mode)
* **Design System**: Obsidian Cyberpulse (Dark Cybernetic, Luminescent Cyan `#00F2FE`, Emerald `#10B981`, Deep Obsidian `#07090E`)
* **Typography**: Plus Jakarta Sans (Headings & Display), Inter (Body & Tabular Data)
* **Backend API**: PHP 8+ PDO REST API (Natif & optimal untuk cPanel DomaiNesia)
* **Database**: MySQL / MariaDB (Schema di `database/schema.sql`)
* **Hosting**: DomaiNesia (cPanel Shared / Cloud Hosting / VPS)
* **Version Control**: GitHub (`https://github.com/dwieyoko/asetlab.git`)

---

## 🚀 Menjalankan Secara Lokal (Development)

```bash
# 1. Clone repository
git clone https://github.com/dwieyoko/asetlab.git
cd asetlab

# 2. Install dependencies
npm install

# 3. Jalankan Vite Development Server
npm run dev
```

Akses aplikasi di browser: `http://localhost:5173/`

---

## 🌐 Panduan Deployment & Database di DomaiNesia

### Langkah 1: Buat Database MySQL di cPanel DomaiNesia
1. Login ke **cPanel DomaiNesia**.
2. Masuk ke menu **MySQL® Databases**.
3. Buat database baru, contoh: `u1234567_asetlab`.
4. Buat user database baru, contoh: `u1234567_dbuser` dan set kata sandi yang kuat.
5. Pada bagian **Add User to Database**, hubungkan user ke database dan centang **ALL PRIVILEGES**.

### Langkah 2: Import Tabel & Data Awal via phpMyAdmin
1. Di cPanel DomaiNesia, buka **phpMyAdmin**.
2. Pilih database `u1234567_asetlab` yang baru dibuat.
3. Klik tab **Import**.
4. Upload file `database/schema.sql` yang ada di repository ini, lalu klik **Go / Kirim**.
5. Semua tabel (`products`, `orders`, `transactions`, `users`) beserta data awal langsung siap pakai!

### Langkah 3: Konfigurasi Kredensial Database
Buka file `api/config.php` dan sesuaikan kredensial database Anda:
```php
$DB_HOST = 'localhost';
$DB_NAME = 'u1234567_asetlab'; // Nama database DomaiNesia Anda
$DB_USER = 'u1234567_dbuser';  // User database DomaiNesia Anda
$DB_PASS = 'PasswordDatabaseAnda';
```

### Langkah 4: Build & Upload ke DomaiNesia
1. Jalankan build frontend:
   ```bash
   npm run build
   ```
2. Salin isi folder `dist/` ke direktori `public_html/` di File Manager cPanel DomaiNesia.
3. Salin juga folder `api/` ke dalam `public_html/api/`.
4. File `public/.htaccess` yang sudah kami sediakan akan otomatis mengatur routing SPA (*Single Page Application*) sehingga tidak akan terjadi error 404 saat pengguna me-refresh halaman seperti `/admin/produk` atau `/layanan`.

---

## 🤖 Otomasi Deploy via GitHub Actions (Opsional)
Repository ini telah dilengkapi workflow CI/CD di `.github/workflows/deploy.yml`. Untuk mengaktifkan deploy otomatis saat push ke branch `main`:
1. Buka repository Anda di GitHub: `https://github.com/dwieyoko/asetlab`
2. Masuk ke menu **Settings > Secrets and variables > Actions > New repository secret**.
3. Tambahkan 3 secret berikut:
   * `DOMAINESIA_FTP_SERVER`: Host FTP DomaiNesia (misal `ftp.namadomain.com` atau IP server)
   * `DOMAINESIA_FTP_USERNAME`: Akun FTP cPanel Anda
   * `DOMAINESIA_FTP_PASSWORD`: Password FTP cPanel Anda
4. Setiap Anda melakukan `git push origin main`, GitHub Actions akan otomatis meng-compile dan meng-upload file ke DomaiNesia.

---

## 🔐 Akun Default Admin
* **URL Admin**: `/admin` atau `/login`
* **Email**: `admin@asetlab.id`
* **Password**: `admin123`
