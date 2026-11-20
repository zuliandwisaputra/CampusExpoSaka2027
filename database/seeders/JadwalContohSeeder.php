<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

/**
 * Rundown CONTOH untuk mencoba tampilan halaman Jadwal di komputer lokal.
 * Jam acara belum diputuskan panitia, jadi seeder ini TIDAK dijalankan otomatis.
 * Jalankan manual: php artisan db:seed --class=JadwalContohSeeder
 */
class JadwalContohSeeder extends Seeder
{
    public function run(): void
    {
        $rundown = [
            ['[Contoh] Registrasi dan pembukaan', '07:30', '08:00', false],
            ['[Contoh] Sesi tanya jawab seputar kampus', '08:00', '11:00', true],
            ['[Contoh] Penutupan dan foto bersama', '11:00', '11:30', false],
        ];

        foreach ($rundown as [$judul, $mulai, $selesai, $utama]) {
            Jadwal::firstOrCreate(['judul' => $judul], [
                'waktu_mulai' => $mulai,
                'waktu_selesai' => $selesai,
                'lokasi' => 'SMA Negeri 1 Kandanghaur',
                'acara_utama' => $utama,
            ]);
        }
    }
}
