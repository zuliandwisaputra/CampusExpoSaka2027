<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(): View
    {
        $pengumuman = Pengumuman::urut()->paginate(20);

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create(): View
    {
        return view('admin.pengumuman.form', ['item' => new Pengumuman(['status' => 'draf'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Pengumuman::create($this->validated($request));

        return redirect()->route('admin.pengumuman.index')->with('status', "Pengumuman \"{$item->judul}\" disimpan sebagai {$item->status}.");
    }

    public function edit(Pengumuman $item): View
    {
        return view('admin.pengumuman.form', compact('item'));
    }

    public function update(Request $request, Pengumuman $item): RedirectResponse
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.pengumuman.index')->with('status', "Pengumuman \"{$item->judul}\" disimpan.");
    }

    public function destroy(Pengumuman $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.pengumuman.index')->with('status', "Pengumuman \"{$item->judul}\" dihapus.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:180'],
            'isi' => ['required', 'string', 'max:20000'],
            'status' => ['required', Rule::in(['draf', 'terbit'])],
            'terbit_pada' => ['nullable', 'date'],
        ]);
        $data['disematkan'] = $request->boolean('disematkan');

        // Terbit tanpa tanggal berarti terbit sekarang.
        if ($data['status'] === 'terbit' && blank($data['terbit_pada'])) {
            $data['terbit_pada'] = now();
        }

        return $data;
    }
}
