<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DivisiController extends Controller
{
    public function index(): View
    {
        $divisi = Divisi::withCount('anggota')->orderBy('urutan')->get();

        return view('admin.divisi.index', compact('divisi'));
    }

    public function create(): View
    {
        return view('admin.divisi.form', ['divisi' => new Divisi(['urutan' => Divisi::max('urutan') + 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $divisi = Divisi::create($this->validatedSuperAdmin($request));

        return redirect()->route('admin.divisi.index')->with('status', "{$divisi->nama} ditambahkan.");
    }

    public function edit(Divisi $divisi): View
    {
        Gate::authorize('ubah-divisi', $divisi);

        return view('admin.divisi.form', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi): RedirectResponse
    {
        Gate::authorize('ubah-divisi', $divisi);

        // Koordinator hanya boleh mengubah deskripsi divisinya.
        $data = $request->user()->isSuperAdmin()
            ? $this->validatedSuperAdmin($request, $divisi)
            : $request->validate(['deskripsi' => ['nullable', 'string', 'max:2000']]);

        $divisi->update($data);

        return redirect()->route('admin.divisi.index')->with('status', "{$divisi->nama} disimpan.");
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        if ($divisi->anggota()->exists()) {
            return back()->withErrors(['hapus' => "{$divisi->nama} masih punya anggota. Pindahkan anggotanya lebih dulu."]);
        }

        $divisi->delete();

        return redirect()->route('admin.divisi.index')->with('status', "{$divisi->nama} dihapus.");
    }

    private function validatedSuperAdmin(Request $request, ?Divisi $divisi = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:120', Rule::unique('divisi', 'nama')->ignore($divisi)],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'urutan' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
    }
}
