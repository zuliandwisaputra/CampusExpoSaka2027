<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Support\Halaman;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $semua = PublicCache::remember('pengumuman:semua', fn () => Pengumuman::terbit()->urut()->get(), 300);

        return view('publik.pengumuman.index', ['pengumuman' => Halaman::dari($semua, $request, 10)]);
    }

    public function show(Pengumuman $pengumuman): View
    {
        // Draf atau yang belum waktunya terbit tidak bisa dibuka publik.
        abort_unless(
            $pengumuman->status === 'terbit' && ($pengumuman->terbit_pada === null || $pengumuman->terbit_pada->isPast()),
            404,
        );

        $lainnya = PublicCache::remember('pengumuman:lainnya', fn () => Pengumuman::terbit()->urut()->take(4)->get(), 300)
            ->reject(fn ($p) => $p->id === $pengumuman->id)->take(3);

        return view('publik.pengumuman.show', compact('pengumuman', 'lainnya'));
    }
}
