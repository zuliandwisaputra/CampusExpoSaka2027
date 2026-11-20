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

    // Jumlah item per halaman di daftar publik dan admin.
    'per_page' => 12,
];
