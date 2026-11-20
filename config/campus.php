<?php

return [
    // Akun super admin pertama yang dibuat oleh AdminSeeder.
    'admin' => [
        'name' => env('ADMIN_NAME', 'Super Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    // Batas percobaan login per email + IP dalam satu menit.
    'login_max_attempts' => 5,

    // Batas ukuran satu foto yang diunggah (KB). Foto tetap dikecilkan dan dikompres ke WebP
    // setelah diunggah, jadi file yang tersimpan jauh lebih kecil. Kalau angka ini diubah,
    // sesuaikan juga public/.user.ini, public/.htaccess, dan pesan di lang/id/validation.php.
    'upload_max_kb' => 7168,

    // Jumlah foto galeri per sekali unggah (10 × 7 MB = 70 MB, masih di bawah post_max_size 80M).
    'galeri_maks_per_unggah' => 10,

    // Jumlah item per halaman di daftar publik dan admin.
    'per_page' => 12,

    // Pembuat website, ditampilkan di footer dan di <meta name="author">.
    'pembuat' => 'Zulian Dwi Saputra',
];
