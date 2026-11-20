<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Support\PublicCache;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        $jadwal = PublicCache::remember('jadwal', fn () => Jadwal::with('kampus:id,nama,slug')->orderBy('waktu_mulai')->get());

        return view('publik.jadwal', compact('jadwal'));
    }
}
