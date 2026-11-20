<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use App\Support\Halaman;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KampusController extends Controller
{
    public function index(Request $request): View
    {
        $semua = PublicCache::remember('kampus:semua', fn () => Kampus::query()
            ->withCount(['anggota' => fn ($q) => $q->tampil()])
            ->orderBy('nama')->get());

        $q = Str::lower(trim((string) $request->input('q')));
        $jenis = (string) $request->input('jenis');
        $provinsi = (string) $request->input('provinsi');

        $hasil = $semua
            ->when($q !== '', fn ($c) => $c->filter(fn (Kampus $k) => Str::contains(Str::lower($k->nama.' '.$k->singkatan.' '.$k->kota), $q)))
            ->when(array_key_exists($jenis, Kampus::JENIS), fn ($c) => $c->where('jenis', $jenis))
            ->when($provinsi !== '', fn ($c) => $c->where('provinsi', $provinsi));

        return view('publik.kampus.index', [
            'kampus' => Halaman::dari($hasil->values(), $request),
            'total' => $hasil->count(),
            'provinsi' => $semua->pluck('provinsi')->filter()->unique()->sort()->values(),
        ]);
    }

    public function show(Kampus $kampus): View
    {
        $kampus = PublicCache::remember("kampus:{$kampus->id}", fn () => $kampus->load([
            'jurusan',
            'anggota' => fn ($q) => $q->tampil()->with('divisi')->urutJabatan(),
        ]));

        return view('publik.kampus.show', compact('kampus'));
    }
}
