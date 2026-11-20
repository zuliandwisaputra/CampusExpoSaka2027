<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Jadwal;
use App\Models\Kampus;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $anggota = Anggota::query()->with(['kampus', 'divisi'])
            ->when($user->isKoordinator(), fn ($q) => $q->where('divisi_id', $user->divisi_id));

        // Tandai data yang belum lengkap supaya panitia tahu apa yang harus diisi (PRD: dashboard).
        $belumLengkap = (clone $anggota)->get()
            ->filter(fn (Anggota $a) => $a->data_belum_lengkap !== [])
            ->sortBy('nama')
            ->values();

        $kampusBelumLengkap = $user->isSuperAdmin()
            ? Kampus::withCount('jurusan')->orderBy('nama')->get()
                ->filter(fn (Kampus $k) => blank($k->logo) || blank($k->deskripsi) || $k->jurusan_count === 0)
                ->values()
            : collect();

        return view('admin.dashboard', [
            'jumlah' => [
                'kampus' => Kampus::count(),
                'anggota' => (clone $anggota)->count(),
                'anggotaTampil' => (clone $anggota)->where('ditampilkan', true)->count(),
                'divisi' => Divisi::count(),
                'jadwal' => Jadwal::count(),
                'pengumuman' => Pengumuman::where('status', 'terbit')->count(),
            ],
            'belumLengkap' => $belumLengkap,
            'kampusBelumLengkap' => $kampusBelumLengkap,
        ]);
    }
}
