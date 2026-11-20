<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Kampus;
use Illuminate\Database\Seeder;

/** Mengisi divisi, kampus, dan anggota dari data resmi panitia. Aman dijalankan ulang. */
class PanitiaSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('data/panitia.php');

        $divisi = [];
        $urutan = 1;
        foreach ($data['divisi'] as $slug => [$nama, $deskripsi]) {
            $divisi[$slug] = Divisi::updateOrCreate(
                ['slug' => $slug],
                ['nama' => $nama, 'deskripsi' => $deskripsi, 'urutan' => $urutan++],
            );
        }

        $kampus = [];
        foreach ($data['kampus'] as $key => [$nama, $singkatan, $jenis, $kota, $provinsi]) {
            $kampus[$key] = Kampus::firstOrCreate(
                ['nama' => $nama],
                compact('singkatan', 'jenis', 'kota', 'provinsi'),
            );
        }

        foreach ($data['anggota'] as [$nama, $kampusKey, $divisiKey, $jabatan]) {
            Anggota::firstOrCreate(
                ['nama' => $nama, 'divisi_id' => $divisi[$divisiKey]->id],
                [
                    'kampus_id' => $kampus[$kampusKey]->id,
                    'jabatan' => $jabatan,
                    'ditampilkan' => true,
                ],
            );
        }
    }
}
