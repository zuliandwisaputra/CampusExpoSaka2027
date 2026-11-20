<?php

use App\Support\PublicCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * Kategori "Kedinasan" digabung ke "Politeknik" karena semua kampus kedinasan
 * yang hadir (Poltek PU, Poltekkes Semarang, Poltekesos) adalah politeknik.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('kampus')->where('jenis', 'kedinasan')->update(['jenis' => 'politeknik', 'updated_at' => now()]);

        // Update lewat query builder tidak memicu event model, jadi cache halaman publik dibersihkan manual.
        PublicCache::flush();
    }

    public function down(): void
    {
        // Sengaja kosong: setelah digabung, kampus kedinasan tidak bisa dibedakan lagi dari politeknik lain.
    }
};
