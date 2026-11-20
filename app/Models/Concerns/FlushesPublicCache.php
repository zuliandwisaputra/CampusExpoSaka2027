<?php

namespace App\Models\Concerns;

use App\Support\PublicCache;

/** Setiap perubahan data oleh admin langsung menyegarkan cache halaman publik. */
trait FlushesPublicCache
{
    public static function bootFlushesPublicCache(): void
    {
        static::saved(fn () => PublicCache::flush());
        static::deleted(fn () => PublicCache::flush());
    }
}
