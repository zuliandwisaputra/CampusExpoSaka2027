<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;

/** Alamat lama dari versi multi-halaman dialihkan ke bagian yang sesuai di halaman utama. */
class AlihkanController extends Controller
{
    public function bagian(string $bagian): RedirectResponse
    {
        return redirect()->to(route('beranda').'#'.$bagian);
    }

    public function divisi(): RedirectResponse
    {
        return $this->bagian('divisi');
    }

    public function galeri(): RedirectResponse
    {
        return $this->bagian('galeri');
    }

    public function kampus(Kampus $kampus): RedirectResponse
    {
        return redirect()->to(route('beranda').'#kampus/'.$kampus->slug);
    }

    public function pengumuman(Pengumuman $pengumuman): RedirectResponse
    {
        // Draf atau yang belum waktunya terbit tidak boleh terbuka publik.
        abort_unless(
            $pengumuman->status === 'terbit' && ($pengumuman->terbit_pada === null || $pengumuman->terbit_pada->isPast()),
            404,
        );

        return redirect()->to(route('beranda').'#pengumuman/'.$pengumuman->slug);
    }
}
