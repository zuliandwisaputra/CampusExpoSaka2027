<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Akun admin: hanya super admin. Akun tidak dihapus, cukup dinonaktifkan. */
class AkunController extends Controller
{
    public function index(): View
    {
        $akun = User::with('divisi')->orderBy('role')->orderBy('name')->get();

        return view('admin.akun.index', compact('akun'));
    }

    public function create(): View
    {
        return view('admin.akun.form', ['akun' => new User(['role' => 'koordinator', 'aktif' => true]), 'divisi' => $this->divisi()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $akun = User::create($data);

        return redirect()->route('admin.akun.index')->with('status', "Akun {$akun->email} dibuat.");
    }

    public function edit(User $akun): View
    {
        return view('admin.akun.form', ['akun' => $akun, 'divisi' => $this->divisi()]);
    }

    public function update(Request $request, User $akun): RedirectResponse
    {
        $data = $this->validated($request, $akun);

        // Cegah super admin mengunci dirinya sendiri atau menghilangkan super admin terakhir.
        if ($akun->is($request->user()) && ($data['role'] !== 'super_admin' || ! $data['aktif'])) {
            return back()->withInput()->withErrors(['role' => 'Kamu tidak bisa menurunkan role atau menonaktifkan akunmu sendiri.']);
        }

        if ($akun->isSuperAdmin() && ($data['role'] !== 'super_admin' || ! $data['aktif'])
            && User::where('role', 'super_admin')->where('aktif', true)->whereKeyNot($akun->id)->doesntExist()) {
            return back()->withInput()->withErrors(['role' => 'Harus ada minimal satu super admin yang aktif.']);
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $akun->update($data);

        return redirect()->route('admin.akun.index')->with('status', "Akun {$akun->email} disimpan.");
    }

    public function destroy(Request $request, User $akun): RedirectResponse
    {
        if ($akun->is($request->user())) {
            return back()->withErrors(['hapus' => 'Kamu tidak bisa menonaktifkan akunmu sendiri.']);
        }

        if ($akun->isSuperAdmin() && User::where('role', 'super_admin')->where('aktif', true)->whereKeyNot($akun->id)->doesntExist()) {
            return back()->withErrors(['hapus' => 'Harus ada minimal satu super admin yang aktif.']);
        }

        $akun->update(['aktif' => false]);

        return redirect()->route('admin.akun.index')->with('status', "Akun {$akun->email} dinonaktifkan.");
    }

    private function divisi()
    {
        return Divisi::orderBy('urutan')->get(['id', 'nama']);
    }

    private function validated(Request $request, ?User $akun = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($akun)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'divisi_id' => ['nullable', 'required_if:role,koordinator', 'integer', 'exists:divisi,id'],
            'password' => [$akun ? 'nullable' : 'required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $data['aktif'] = $request->boolean('aktif');
        if ($data['role'] === 'super_admin') {
            $data['divisi_id'] = null;
        }

        return $data;
    }
}
