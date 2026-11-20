<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Support\PublicCache;
use Illuminate\View\View;

class DivisiController extends Controller
{
    public function index(): View
    {
        $divisi = PublicCache::remember('divisi:semua', fn () => Divisi::orderBy('urutan')
            ->with(['anggota' => fn ($q) => $q->tampil()->with('kampus:id,nama,slug,singkatan')->urutJabatan()])
            ->get());

        return view('publik.divisi.index', compact('divisi'));
    }

    public function show(Divisi $divisi): View
    {
        $divisi = PublicCache::remember("divisi:{$divisi->id}", fn () => $divisi->load([
            'anggota' => fn ($q) => $q->tampil()->with(['kampus:id,nama,slug,singkatan', 'divisi:id,nama,slug'])->urutJabatan(),
        ]));

        return view('publik.divisi.show', compact('divisi'));
    }
}
