<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\Kampus;
use App\Support\ImageUploader;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $anggota = Anggota::query()->with(['kampus', 'divisi'])
            ->when($user->isKoordinator(), fn ($q) => $q->where('divisi_id', $user->divisi_id))
            ->when($request->filled('q'), fn ($q) => $q->where('nama', 'like', '%'.$request->string('q')->trim().'%'))
            ->when($request->filled('kampus'), fn ($q) => $q->where('kampus_id', $request->integer('kampus')))
            ->when($user->isSuperAdmin() && $request->filled('divisi'), fn ($q) => $q->where('divisi_id', $request->integer('divisi')))
            ->when($request->input('status') === 'tampil', fn ($q) => $q->where('ditampilkan', true))
            ->when($request->input('status') === 'sembunyi', fn ($q) => $q->where('ditampilkan', false))
            ->urutJabatan()
            ->paginate(20)
            ->withQueryString();

        return view('admin.anggota.index', [
            'anggota' => $anggota,
            'kampus' => Kampus::orderBy('nama')->get(['id', 'nama']),
            'divisi' => Divisi::orderBy('urutan')->get(['id', 'nama']),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('kelola-anggota');

        return view('admin.anggota.form', $this->formData($request, new Anggota(['ditampilkan' => true, 'jabatan' => 'Anggota'])));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('kelola-anggota');
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = ImageUploader::store($request->file('foto'), 'anggota', 'anggota');
        }

        $anggota = Anggota::create($data);

        return redirect()->route('admin.anggota.index')->with('status', "Anggota {$anggota->nama} ditambahkan.");
    }

    public function edit(Request $request, Anggota $anggota): View
    {
        Gate::authorize('kelola-anggota', $anggota);

        return view('admin.anggota.form', $this->formData($request, $anggota));
    }

    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        Gate::authorize('kelola-anggota', $anggota);
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            Media::delete($anggota->foto);
            $data['foto'] = ImageUploader::store($request->file('foto'), 'anggota', 'anggota');
        } elseif ($request->boolean('hapus_foto')) {
            Media::delete($anggota->foto);
            $data['foto'] = null;
        }

        $anggota->update($data);

        return redirect()->route('admin.anggota.index')->with('status', "Data {$anggota->nama} disimpan.");
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        Gate::authorize('kelola-anggota', $anggota);

        Media::delete($anggota->foto);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('status', "Anggota {$anggota->nama} dihapus.");
    }

    private function formData(Request $request, Anggota $anggota): array
    {
        $user = $request->user();

        return [
            'anggota' => $anggota,
            'kampus' => Kampus::orderBy('nama')->get(['id', 'nama']),
            'divisi' => Divisi::orderBy('urutan')
                ->when($user->isKoordinator(), fn ($q) => $q->whereKey($user->divisi_id))
                ->get(['id', 'nama']),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'kampus_id' => ['required', 'integer', 'exists:kampus,id'],
            'divisi_id' => ['required', 'integer', 'exists:divisi,id'],
            'jurusan' => ['nullable', 'string', 'max:120'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'jabatan' => ['required', 'string', 'max:80'],
            'instagram' => ['nullable', 'string', 'max:30', 'regex:/^[A-Za-z0-9._]+$/'],
            'linkedin' => ['nullable', 'string', 'max:255', 'regex:#^https://www\.linkedin\.com/.+#'],
            'no_hp' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s]+$/'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.config('campus.upload_max_kb')],
        ]);

        // Koordinator selalu menyimpan anggota ke divisinya sendiri.
        if ($request->user()->isKoordinator()) {
            $data['divisi_id'] = $request->user()->divisi_id;
        }

        $data['ditampilkan'] = $request->boolean('ditampilkan');
        unset($data['foto']);

        return $data;
    }
}
