<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Menentukan URL gambar. Gambar yang belum diunggah memakai foto dummy di
 * public/images/dummy (aturan PRD), sehingga layout langsung terlihat dan
 * foto asli tinggal menggantikannya lewat panel admin.
 */
class Media
{
    public const DUMMY = [
        'sekolah' => 'images/dummy/foto-sekolah.jpg',
        'anggota' => 'images/dummy/foto-anggota.jpg',
        'logo' => 'images/dummy/logo-kampus.png',
        'galeri' => 'images/dummy/foto-galeri.jpg',
        'pratinjau' => 'images/dummy/pratinjau-link.jpg',
    ];

    public static function url(?string $path, string $dummy): string
    {
        if (blank($path)) {
            return asset(self::DUMMY[$dummy]);
        }

        // File bawaan (dummy) disimpan di folder public, hasil upload di storage.
        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }

    public static function isDummy(?string $path): bool
    {
        return blank($path) || str_starts_with($path, 'images/dummy/');
    }

    public static function delete(?string $path): void
    {
        if (! blank($path) && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
