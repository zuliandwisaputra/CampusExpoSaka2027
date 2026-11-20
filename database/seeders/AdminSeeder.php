<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Membuat akun super admin pertama dari .env (ADMIN_EMAIL, ADMIN_PASSWORD).
 * Tidak ada password bawaan: kalau ADMIN_PASSWORD kosong, dibuatkan password acak
 * yang ditampilkan sekali di terminal. Akun koordinator dibuat super admin lewat panel.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('campus.admin.email');

        if (blank($email)) {
            $this->command?->warn('ADMIN_EMAIL belum diisi di .env, akun super admin tidak dibuat.');

            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Akun super admin {$email} sudah ada, tidak diubah.");

            return;
        }

        $password = config('campus.admin.password') ?: Str::password(16, symbols: false);

        User::create([
            'name' => config('campus.admin.name', 'Super Admin'),
            'email' => $email,
            'password' => $password,
            'role' => 'super_admin',
            'aktif' => true,
        ]);

        $this->command?->info("Akun super admin dibuat: {$email}");

        if (blank(config('campus.admin.password'))) {
            $this->command?->warn("Password sementara: {$password}  (segera ganti lewat menu Akun admin)");
        }
    }
}
