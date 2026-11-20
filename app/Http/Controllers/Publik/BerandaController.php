<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Foto;
use App\Models\Kampus;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use App\Support\PublicCache;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $data = PublicCache::remember('beranda', fn () => [
            'jumlah' => [
                'kampus' => Kampus::count(),
                'anggota' => Anggota::tampil()->count(),
                'divisi' => Divisi::count(),
            ],
            'pengumuman' => Pengumuman::terbit()->urut()->take(3)->get(),
            'kampus' => Kampus::withCount(['anggota' => fn ($q) => $q->tampil()])
                ->orderByDesc('anggota_count')->orderBy('nama')->take(4)->get(),
            'anggota' => Anggota::tampil()->with(['kampus', 'divisi'])
                ->where('jabatan', 'like', 'Ketua%')->orderBy('divisi_id')->take(4)->get(),
            'dokumentasi' => Foto::latest('id')->take(6)->get(),
        ], 300);

        return view('publik.beranda', [
            ...$data,
            'acaraLewat' => Pengaturan::acaraSudahLewat(),
            'waktuAcara' => Pengaturan::waktuAcara(),
        ]);
    }
}
