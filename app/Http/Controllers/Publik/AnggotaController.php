<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Kampus;
use App\Support\Halaman;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        // Hanya anggota berstatus tampil; nomor HP tidak pernah diambil untuk halaman publik.
        $semua = PublicCache::remember('anggota:semua', fn () => Anggota::tampil()
            ->select(['id', 'nama', 'foto', 'kampus_id', 'divisi_id', 'jurusan', 'angkatan', 'jabatan', 'instagram', 'linkedin'])
            ->with(['kampus:id,nama,slug,singkatan', 'divisi:id,nama,slug,urutan'])
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->divisi->urutan <=> $b->divisi->urutan,
                fn ($a, $b) => (int) $b->is_ketua <=> (int) $a->is_ketua,
                fn ($a, $b) => strcmp($a->nama, $b->nama),
            ])->values());

        $q = Str::lower(trim((string) $request->input('q')));
        $kampusId = $request->integer('kampus');
        $divisiId = $request->integer('divisi');

        $hasil = $semua
            ->when($q !== '', fn ($c) => $c->filter(fn (Anggota $a) => Str::contains(Str::lower($a->nama), $q)))
            ->when($kampusId > 0, fn ($c) => $c->where('kampus_id', $kampusId))
            ->when($divisiId > 0, fn ($c) => $c->where('divisi_id', $divisiId));

        $pilihan = PublicCache::remember('anggota:pilihan', fn () => [
            'kampus' => Kampus::whereHas('anggota', fn ($q) => $q->tampil())->orderBy('nama')->get(['id', 'nama']),
            'divisi' => Divisi::orderBy('urutan')->get(['id', 'nama']),
        ]);

        return view('publik.anggota.index', [
            'anggota' => Halaman::dari($hasil->values(), $request),
            'total' => $hasil->count(),
            ...$pilihan,
        ]);
    }
}
