<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kampus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        $jadwal = Jadwal::with('kampus')->orderBy('waktu_mulai')->get();

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create(): View
    {
        return view('admin.jadwal.form', ['item' => new Jadwal, 'kampus' => $this->kampus()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Jadwal::create($this->validated($request));

        return redirect()->route('admin.jadwal.index')->with('status', "\"{$item->judul}\" ditambahkan.");
    }

    public function edit(Jadwal $item): View
    {
        return view('admin.jadwal.form', ['item' => $item, 'kampus' => $this->kampus()]);
    }

    public function update(Request $request, Jadwal $item): RedirectResponse
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.jadwal.index')->with('status', "\"{$item->judul}\" disimpan.");
    }

    public function destroy(Jadwal $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.jadwal.index')->with('status', "\"{$item->judul}\" dihapus.");
    }

    private function kampus()
    {
        return Kampus::orderBy('nama')->get(['id', 'nama']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'lokasi' => ['nullable', 'string', 'max:120'],
            'kampus_id' => ['nullable', 'integer', 'exists:kampus,id'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);
        $data['acara_utama'] = $request->boolean('acara_utama');

        return $data;
    }
}
