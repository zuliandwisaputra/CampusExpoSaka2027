<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Faq;
use App\Models\Foto;
use App\Models\Jadwal;
use App\Models\Kampus;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use App\Support\PublicCache;
use Illuminate\View\View;

/** Situs publik one page: semua bagian diambil sekali dan di-cache. */
class BerandaController extends Controller
{
    public function index(): View
    {
        $data = PublicCache::remember('one-page', function () {
            return [
                'jumlah' => [
                    'kampus' => Kampus::count(),
                    'anggota' => Anggota::tampil()->count(),
                    'divisi' => Divisi::count(),
                ],
                'pengumuman' => Pengumuman::terbit()->urut()->take(10)->get(),

                // Kampus dengan alumni terbanyak tampil lebih dulu.
                'kampus' => Kampus::with([
                    'jurusan',
                    'anggota' => fn ($q) => $q->tampil()->with('divisi:id,nama')->urutJabatan(),
                ])->get()
                    ->sortBy([fn ($a, $b) => $b->anggota->count() <=> $a->anggota->count(), fn ($a, $b) => strcmp($a->nama, $b->nama)])
                    ->values(),

                // Nomor HP tidak ikut diambil untuk halaman publik.
                'anggota' => Anggota::tampil()
                    ->select(['id', 'nama', 'foto', 'kampus_id', 'divisi_id', 'jurusan', 'angkatan', 'jabatan', 'instagram', 'linkedin'])
                    ->with(['kampus:id,nama,slug,singkatan', 'divisi:id,nama,slug,urutan'])
                    ->get()
                    ->sortBy([
                        fn ($a, $b) => (int) $b->is_ketua <=> (int) $a->is_ketua,
                        fn ($a, $b) => $a->divisi->urutan <=> $b->divisi->urutan,
                        fn ($a, $b) => strcmp($a->nama, $b->nama),
                    ])->values(),

                'divisi' => Divisi::orderBy('urutan')->with(['anggota' => fn ($q) => $q->tampil()->with('kampus:id,nama')->urutJabatan()])->get(),
                'jadwal' => Jadwal::with('kampus:id,nama,slug')->orderBy('waktu_mulai')->get(),
                'album' => Album::whereHas('foto')->with('foto')->orderByDesc('tanggal')->orderByDesc('id')->get(),
                'faq' => Faq::orderBy('urutan')->orderBy('id')->get(),
                'dokumentasi' => Foto::latest('id')->take(6)->get(),
            ];
        }, 300);

        return view('publik.beranda', [
            ...$data,
            'acaraLewat' => Pengaturan::acaraSudahLewat(),
            'waktuAcara' => Pengaturan::waktuAcara(),
        ]);
    }
}
