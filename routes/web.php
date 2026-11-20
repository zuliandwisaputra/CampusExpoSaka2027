<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Publik;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [Publik\BerandaController::class, 'index'])->name('beranda');
Route::get('/kampus', [Publik\KampusController::class, 'index'])->name('kampus.index');
Route::get('/kampus/{kampus}', [Publik\KampusController::class, 'show'])->name('kampus.show');
Route::get('/anggota', [Publik\AnggotaController::class, 'index'])->name('anggota.index');
Route::get('/divisi', [Publik\DivisiController::class, 'index'])->name('divisi.index');
Route::get('/divisi/{divisi}', [Publik\DivisiController::class, 'show'])->name('divisi.show');
Route::get('/jadwal', [Publik\JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/pengumuman', [Publik\PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{pengumuman}', [Publik\PengumumanController::class, 'show'])->name('pengumuman.show');
Route::get('/galeri', [Publik\GaleriController::class, 'index'])->name('galeri.index');
Route::get('/galeri/{album}', [Publik\GaleriController::class, 'show'])->name('galeri.show');
Route::get('/faq', [Publik\FaqController::class, 'index'])->name('faq.index');

/*
|--------------------------------------------------------------------------
| Panel admin (/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'aktif'])->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Anggota: super admin semua anggota, koordinator hanya divisinya (dicek di controller).
        Route::resource('anggota', Admin\AnggotaController::class)->except('show')
            ->parameters(['anggota' => 'anggota']);

        // Kampus: koordinator hanya melihat.
        Route::get('kampus', [Admin\KampusController::class, 'index'])->name('kampus.index');
        Route::middleware('can:super-admin')->group(function () {
            Route::get('kampus/create', [Admin\KampusController::class, 'create'])->name('kampus.create');
            Route::post('kampus', [Admin\KampusController::class, 'store'])->name('kampus.store');
            Route::get('kampus/{kampus}/edit', [Admin\KampusController::class, 'edit'])->name('kampus.edit');
            Route::put('kampus/{kampus}', [Admin\KampusController::class, 'update'])->name('kampus.update');
            Route::delete('kampus/{kampus}', [Admin\KampusController::class, 'destroy'])->name('kampus.destroy');
        });
        Route::get('kampus/{kampus}', [Admin\KampusController::class, 'show'])->name('kampus.show');

        // Divisi: koordinator hanya mengubah deskripsi divisinya sendiri.
        Route::get('divisi', [Admin\DivisiController::class, 'index'])->name('divisi.index');
        Route::get('divisi/{divisi}/edit', [Admin\DivisiController::class, 'edit'])->name('divisi.edit');
        Route::put('divisi/{divisi}', [Admin\DivisiController::class, 'update'])->name('divisi.update');
        Route::middleware('can:super-admin')->group(function () {
            Route::get('divisi/create', [Admin\DivisiController::class, 'create'])->name('divisi.create');
            Route::post('divisi', [Admin\DivisiController::class, 'store'])->name('divisi.store');
            Route::delete('divisi/{divisi}', [Admin\DivisiController::class, 'destroy'])->name('divisi.destroy');
        });

        // Jadwal, pengumuman, galeri, FAQ: koordinator boleh tambah dan ubah, hapus hanya super admin.
        foreach ([
            'jadwal' => Admin\JadwalController::class,
            'pengumuman' => Admin\PengumumanController::class,
            'faq' => Admin\FaqController::class,
            'galeri' => Admin\GaleriController::class,
        ] as $nama => $controller) {
            Route::resource($nama, $controller)->except(['show', 'destroy'])->parameters([$nama => 'item']);
            Route::delete("{$nama}/{item}", [$controller, 'destroy'])->name("{$nama}.destroy")->middleware('can:super-admin');
        }
        Route::post('galeri/{item}/foto', [Admin\GaleriController::class, 'upload'])->name('galeri.upload');
        Route::delete('galeri/foto/{foto}', [Admin\GaleriController::class, 'destroyFoto'])->name('galeri.foto.destroy')->middleware('can:super-admin');

        // Khusus super admin.
        Route::middleware('can:super-admin')->group(function () {
            Route::get('pengaturan', [Admin\PengaturanController::class, 'edit'])->name('pengaturan.edit');
            Route::put('pengaturan', [Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
            Route::resource('akun', Admin\AkunController::class)->except('show')->parameters(['akun' => 'akun']);
        });
    });
});
