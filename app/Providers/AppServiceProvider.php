<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');

        /*
         * Hak akses dua role sesuai tabel "Role dan hak akses" di PRD.
         * Super admin boleh semuanya; aturan di bawah hanya berlaku untuk koordinator divisi.
         */
        Gate::before(fn (User $user) => $user->isSuperAdmin() ? true : null);

        // Akun admin, pengaturan acara, kampus (tambah/ubah/hapus), dan semua tombol hapus konten.
        Gate::define('super-admin', fn (User $user) => false);

        // Koordinator hanya mengelola anggota di divisinya sendiri.
        Gate::define('kelola-anggota', fn (User $user, ?Anggota $anggota = null) => $user->divisi_id !== null
            && ($anggota === null || $anggota->divisi_id === $user->divisi_id));

        // Koordinator hanya mengubah deskripsi divisinya sendiri.
        Gate::define('ubah-divisi', fn (User $user, Divisi $divisi) => $user->divisi_id === $divisi->id);
    }
}
