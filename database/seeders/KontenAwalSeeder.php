<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Faq;
use App\Models\Foto;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use App\Support\Media;
use Illuminate\Database\Seeder;

/**
 * Isi awal: pengaturan acara, pengumuman panitia, FAQ, dan album galeri berisi foto dummy.
 * Jawaban FAQ hanya berisi hal yang sudah pasti di PRD; sisanya diisi panitia.
 */
class KontenAwalSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Pengaturan::DEFAULTS as $kunci => $nilai) {
            Pengaturan::firstOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        }

        Pengumuman::firstOrCreate(
            ['judul' => 'Pengumuman Panitia Pelaksana Campus Expo 2027'],
            [
                'isi' => "Susunan panitia pelaksana Campus Expo SMA Negeri 1 Kandanghaur 2027 sudah resmi diumumkan. Ada 83 alumni dari 31 kampus yang tergabung dalam 6 divisi: Acara, Logistik, Sponsorship, Public Relation, Dana Usaha dan Konsumsi, serta Media Komunikasi, Dokumentasi & Publikasi.\n\nKenalan dengan mereka di halaman Anggota dan Divisi.",
                'status' => 'terbit',
                'disematkan' => true,
                'terbit_pada' => now(),
            ],
        );

        $faq = [
            ['Kapan Campus Expo diadakan?', 'Senin, 18 Januari 2027. Jam mulai dan selesai akan diumumkan panitia di halaman Jadwal.'],
            ['Di mana lokasinya?', 'Di SMA Negeri 1 Kandanghaur, Jl. Pantura Karanganyar, Kec. Kandanghaur, Kab. Indramayu.'],
            ['Acaranya seperti apa?', 'Kumpul alumni SMAN 1 Kandanghaur dari berbagai kampus dengan sesi tanya jawab seputar kampus. Tidak ada stand, jadi kamu bisa langsung bertanya ke kakak alumni.'],
            ['Apakah perlu login atau daftar akun di website ini?', 'Tidak. Semua halaman bisa dibuka tanpa login, langsung dari HP.'],
            ['Bagaimana cara menghubungi kakak alumni?', 'Buka halaman Anggota atau detail kampus. Kalau alumni sudah mengisi Instagram atau LinkedIn, ikonnya muncul di kartu profil mereka.'],
        ];
        foreach ($faq as $i => [$pertanyaan, $jawaban]) {
            Faq::firstOrCreate(['pertanyaan' => $pertanyaan], ['jawaban' => $jawaban, 'urutan' => $i + 1]);
        }

        // Album contoh berisi foto dummy supaya letak dan ukuran galeri langsung terlihat.
        foreach (['Persiapan Panitia', 'Hari H Campus Expo 2027'] as $judul) {
            $album = Album::firstOrCreate(['judul' => $judul]);
            if ($album->foto()->doesntExist()) {
                for ($i = 1; $i <= 6; $i++) {
                    Foto::create([
                        'album_id' => $album->id,
                        'file' => Media::DUMMY['galeri'],
                        'keterangan' => "Foto dummy {$i}",
                    ]);
                }
            }
        }
    }
}
