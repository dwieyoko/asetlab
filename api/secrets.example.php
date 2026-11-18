<?php
/**
 * AsetLab Platform - Template Konfigurasi Privat (Backend PHP)
 *
 * CARA PAKAI:
 * 1. Copy file ini menjadi `api/secrets.php` (di lokal maupun di cPanel DomaiNesia).
 * 2. Isi kredensial asli Anda. File `api/secrets.php` sudah masuk .gitignore & diblokir .htaccess.
 * 3. Generate JWT_SECRET acak minimal 64 karakter (misal: bin2hex(random_bytes(32))).
 */

return [
    // Environment: 'production' | 'development'
    'APP_ENV' => 'production',
    'APP_URL' => 'https://asetlab.web.id',

    // Daftar origin yang diizinkan mengakses API (pisahkan dengan koma)
    'ALLOWED_ORIGINS' => 'https://asetlab.web.id,https://www.asetlab.web.id,https://asetlab.id,https://www.asetlab.id,http://localhost:5173,http://127.0.0.1:5173',

    // Kredensial Database MySQL cPanel DomaiNesia
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'u1234567_asetlab',
    'DB_USER' => 'u1234567_dbuser',
    'DB_PASS' => 'GANTI_DENGAN_PASSWORD_DATABASE_BARU_YANG_KUAT',

    // Secret Key untuk JWT Auth & Download Signature (WAJIB DIGANTI!)
    'JWT_SECRET' => 'GANTI_DENGAN_64_KARAKTER_RANDOM_HEX_STRING_YANG_AMAN_DAN_UNIK',
    'JWT_TTL_SECONDS' => 60 * 60 * 24 * 7, // 7 hari

    // Payment Gateway: 'midtrans' | 'tripay' | 'simulator'
    // Gunakan 'simulator' sementara untuk tes alur checkout sebelum mengisi API key gateway
    'PAYMENT_GATEWAY' => 'simulator',

    // Midtrans (Rekomendasi: QRIS 0.7%, VA Rp4.000, tanpa biaya bulanan)
    'MIDTRANS_IS_PRODUCTION' => false,
    'MIDTRANS_MERCHANT_ID'   => '',
    'MIDTRANS_CLIENT_KEY'    => '',
    'MIDTRANS_SERVER_KEY'    => '',

    // Tripay (Alternatif populer untuk perorangan / UMKM tanpa badan usaha)
    'TRIPAY_IS_PRODUCTION' => false,
    'TRIPAY_MERCHANT_CODE' => '',
    'TRIPAY_API_KEY'       => '',
    'TRIPAY_PRIVATE_KEY'   => '',

    // SMTP Email untuk pengiriman Invoice & Link Download Otomatis (cPanel Email / Resend)
    'SMTP_ENABLED'    => false,
    'SMTP_HOST'       => 'mail.asetlab.web.id',
    'SMTP_PORT'       => 465,
    'SMTP_ENCRYPTION' => 'ssl', // 'ssl' | 'tls'
    'SMTP_USER'       => 'noreply@asetlab.web.id',
    'SMTP_PASS'       => '',
    'SMTP_FROM_EMAIL' => 'noreply@asetlab.web.id',
    'SMTP_FROM_NAME'  => 'AsetLab Digital Store',
];
