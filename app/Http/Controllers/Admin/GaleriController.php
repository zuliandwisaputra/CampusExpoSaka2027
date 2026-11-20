<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Foto;
use App\Support\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class GaleriController extends Controller
{
    public function index(): View
    {
        $album = Album::withCount('foto')->with(['foto' => fn ($q) => $q->limit(1)])
            ->orderByDesc('tanggal')->orderByDesc('id')->get();

        return view('admin.galeri.index', compact('album'));
    }

    public function create(): View
    {
        return view('admin.galeri.form', ['item' => new Album]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Album::create($this->validated($request));

        return redirect()->route('admin.galeri.edit', $item)->with('status', "Album \"{$item->judul}\" dibuat. Sekarang unggah fotonya.");
    }

    public function edit(Album $item): View
    {
        $item->load('foto');

        return view('admin.galeri.form', compact('item'));
    }

    public function update(Request $request, Album $item): RedirectResponse
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.galeri.edit', $item)->with('status', 'Album disimpan.');
    }

    /** Unggah banyak foto sekaligus; tiap foto dikecilkan dan dibuatkan thumbnail otomatis. */
    public function upload(Request $request, Album $item): RedirectResponse
    {
        $request->validate([
            'foto' => ['required', 'array', 'max:20'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $berhasil = 0;
        foreach ($request->file('foto') as $file) {
            try {
                [$path, $thumb] = ImageUploader::storeWithThumb($file, "galeri/{$item->id}");
            } catch (RuntimeException) {
                continue;
            }

            $item->foto()->create([
                'file' => $path,
                'thumb' => $thumb,
                'keterangan' => $request->input('keterangan'),
            ]);
            $berhasil++;
        }

        return redirect()->route('admin.galeri.edit', $item)->with('status', "{$berhasil} foto diunggah.");
    }

    public function destroy(Album $item): RedirectResponse
    {
        // Hapus satu per satu supaya file fotonya ikut terhapus dari storage.
        $item->foto->each->delete();
        $item->delete();

        return redirect()->route('admin.galeri.index')->with('status', "Album \"{$item->judul}\" dihapus.");
    }

    public function destroyFoto(Foto $foto): RedirectResponse
    {
        $album = $foto->album;
        $foto->delete();

        return redirect()->route('admin.galeri.edit', $album)->with('status', 'Foto dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'tanggal' => ['nullable', 'date'],
        ]);
    }
}
