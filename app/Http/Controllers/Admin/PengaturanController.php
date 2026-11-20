<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Support\ImageUploader;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function edit(): View
    {
        return view('admin.pengaturan', ['p' => Pengaturan::semua()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_acara' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:600'],
            'tanggal_acara' => ['required', 'date_format:Y-m-d'],
            'jam_mulai' => ['nullable', 'date_format:H:i'],
            'jam_selesai' => ['nullable', 'date_format:H:i', 'after:jam_mulai'],
            'lokasi' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:150'],
            'instagram' => ['nullable', 'string', 'max:30', 'regex:/^[A-Za-z0-9._]+$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'foto_beranda' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoLama = Pengaturan::ambil('foto_beranda');
        if ($request->hasFile('foto_beranda')) {
            Media::delete($fotoLama);
            $data['foto_beranda'] = ImageUploader::store($request->file('foto_beranda'), 'beranda', 'sekolah', 78);
        } elseif ($request->boolean('hapus_foto_beranda')) {
            Media::delete($fotoLama);
            $data['foto_beranda'] = '';
        } else {
            unset($data['foto_beranda']);
        }

        foreach ($data as $kunci => $nilai) {
            Pengaturan::simpan($kunci, $nilai ?? '');
        }

        return redirect()->route('admin.pengaturan.edit')->with('status', 'Pengaturan acara disimpan.');
    }
}
