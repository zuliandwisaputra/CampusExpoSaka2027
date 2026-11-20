<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Support\PublicCache;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $album = PublicCache::remember('galeri', fn () => Album::whereHas('foto')->with('foto')
            ->orderByDesc('tanggal')->orderByDesc('id')->get());

        return view('publik.galeri.index', compact('album'));
    }

    public function show(Album $album): View
    {
        $album = PublicCache::remember("galeri:{$album->id}", fn () => $album->load('foto'));

        return view('publik.galeri.show', compact('album'));
    }
}
