<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cache untuk data halaman publik supaya ringan saat ±900 orang membuka web di hari H.
 * Setiap kali admin mengubah data, versi cache dinaikkan sehingga semua isi lama
 * otomatis tidak dipakai lagi (bekerja di driver file maupun database).
 */
class PublicCache
{
    private const VERSION_KEY = 'public-cache-version';

    public static function remember(string $key, Closure $callback, int $seconds = 600): mixed
    {
        $version = Cache::rememberForever(self::VERSION_KEY, fn () => 1);

        return Cache::remember("public:v{$version}:{$key}", $seconds, $callback);
    }

    public static function flush(): void
    {
        $version = (int) Cache::get(self::VERSION_KEY, 1);
        Cache::forever(self::VERSION_KEY, $version + 1);
    }
}
