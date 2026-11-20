<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use App\Support\ImageUploader;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KampusController extends Controller
{
    public function index(Request $request): View
    {
        $kampus = Kampus::query()->withCount(['anggota', 'jurusan'])
            ->when($request->filled('q'), fn ($q) => $q->where('nama', 'like', '%'.$request->string('q')->trim().'%'))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->input('jenis')))
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('admin.kampus.index', compact('kampus'));
    }

    public function show(Kampus $kampus): View
    {
        $kampus->load(['jurusan', 'anggota.divisi']);

        return view('admin.kampus.show', compact('kampus'));
    }

    public function create(): View
    {
        return view('admin.kampus.form', ['kampus' => new Kampus(['jenis' => 'ptn']), 'jurusanTeks' => '']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = ImageUploader::store($request->file('logo'), 'logo', 'logo');
        }

        $kampus = DB::transaction(function () use ($data, $request) {
            $kampus = Kampus::create($data);
            $this->simpanJurusan($kampus, (string) $request->input('jurusan'));

            return $kampus;
        });

        return redirect()->route('admin.kampus.index')->with('status', "Kampus {$kampus->nama} ditambahkan.");
    }

    public function edit(Kampus $kampus): View
    {
        $jurusanTeks = $kampus->jurusan
            ->map(fn ($j) => $j->jenjang ? "{$j->jenjang} | {$j->nama}" : $j->nama)
            ->implode("\n");

        return view('admin.kampus.form', compact('kampus', 'jurusanTeks'));
    }

    public function update(Request $request, Kampus $kampus): RedirectResponse
    {
        $data = $this->validated($request, $kampus);

        if ($request->hasFile('logo')) {
            Media::delete($kampus->logo);
            $data['logo'] = ImageUploader::store($request->file('logo'), 'logo', 'logo');
        } elseif ($request->boolean('hapus_logo')) {
            Media::delete($kampus->logo);
            $data['logo'] = null;
        }

        DB::transaction(function () use ($kampus, $data, $request) {
            $kampus->update($data);
            $this->simpanJurusan($kampus, (string) $request->input('jurusan'));
        });

        return redirect()->route('admin.kampus.index')->with('status', "Data {$kampus->nama} disimpan.");
    }

    public function destroy(Kampus $kampus): RedirectResponse
    {
        if ($kampus->anggota()->exists()) {
            return back()->withErrors(['hapus' => "{$kampus->nama} masih punya anggota. Pindahkan atau hapus anggotanya lebih dulu."]);
        }

        Media::delete($kampus->logo);
        $kampus->delete();

        return redirect()->route('admin.kampus.index')->with('status', "Kampus {$kampus->nama} dihapus.");
    }

    private function validated(Request $request, ?Kampus $kampus = null): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150', Rule::unique('kampus', 'nama')->ignore($kampus)],
            'singkatan' => ['nullable', 'string', 'max:30'],
            'jenis' => ['required', Rule::in(array_keys(Kampus::JENIS))],
            'kota' => ['nullable', 'string', 'max:80'],
            'provinsi' => ['nullable', 'string', 'max:80'],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
            'jalur_masuk' => ['nullable', 'string', 'max:3000'],
            'kisaran_biaya' => ['nullable', 'string', 'max:2000'],
            'beasiswa' => ['nullable', 'string', 'max:3000'],
            'website' => ['nullable', 'url:https,http', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:30', 'regex:/^[A-Za-z0-9._]+$/'],
            'jurusan' => ['nullable', 'string', 'max:10000'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        unset($data['jurusan'], $data['logo']);

        return $data;
    }

    /**
     * Jurusan diisi satu per baris. Format "S1 | Pendidikan Matematika" atau cukup nama jurusannya.
     */
    private function simpanJurusan(Kampus $kampus, string $teks): void
    {
        $baris = collect(preg_split('/\r\n|\r|\n/', $teks))
            ->map(fn ($b) => trim($b))
            ->filter()
            ->unique()
            ->take(300);

        $kampus->jurusan()->delete();

        foreach ($baris as $b) {
            [$jenjang, $nama] = str_contains($b, '|')
                ? array_map('trim', explode('|', $b, 2))
                : [null, $b];

            if ($nama !== '') {
                $kampus->jurusan()->create([
                    'jenjang' => $jenjang ? mb_substr($jenjang, 0, 20) : null,
                    'nama' => mb_substr($nama, 0, 255),
                ]);
            }
        }
    }
}
