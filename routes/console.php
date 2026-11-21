<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Lupa email atau password admin:
 *   php artisan admin:reset-password            → tampilkan akun super admin, lalu reset password-nya
 *   php artisan admin:reset-password email@...  → reset password akun dengan email itu (termasuk koordinator)
 * Password baru dibuat acak dan hanya ditampilkan sekali di terminal. Akun juga diaktifkan kembali.
 */
Artisan::command('admin:reset-password {email? : Email akun yang password-nya direset}', function (?string $email = null) {
    $superAdmin = User::where('role', 'super_admin')->orderBy('id')->get();

    if (! $email) {
        if ($superAdmin->isEmpty()) {
            $this->error('Belum ada akun super admin.');
            $this->line('Isi ADMIN_EMAIL di .env, lalu jalankan: php artisan db:seed --class=AdminSeeder');

            return 1;
        }

        $this->info('Akun super admin yang terdaftar:');
        $this->table(['Nama', 'Email', 'Aktif'], $superAdmin->map(fn (User $u) => [$u->name, $u->email, $u->aktif ? 'ya' : 'tidak'])->all());

        $email = $superAdmin->count() === 1
            ? $superAdmin->first()->email
            : $this->choice('Password akun mana yang mau direset?', $superAdmin->pluck('email')->all(), 0);
    }

    $user = User::where('email', $email)->first();

    if (! $user) {
        $this->error("Akun dengan email {$email} tidak ditemukan.");

        return 1;
    }

    if (! $this->confirm("Buat password baru untuk {$user->email}? Password lama tidak bisa dipakai lagi.", true)) {
        $this->line('Dibatalkan, tidak ada yang diubah.');

        return 0;
    }

    $password = Str::password(16, symbols: false);
    $user->forceFill([
        'password' => $password,
        'aktif' => true,
        'remember_token' => Str::random(60),
    ])->save();

    $this->newLine();
    $this->info('Password berhasil direset.');
    $this->line("  Email    : {$user->email}");
    $this->line("  Password : {$password}");
    $this->newLine();
    $this->warn('Catat password ini sekarang (hanya ditampilkan sekali). Setelah login, ganti lewat menu Akun admin.');

    return 0;
})->purpose('Tampilkan akun super admin dan buat password baru kalau lupa');
