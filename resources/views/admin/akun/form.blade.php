@extends('layouts.admin')

@php $baru = ! $akun->exists; @endphp
@section('judul', $baru ? 'Tambah akun' : 'Ubah akun')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah akun admin' : 'Ubah akun '.$akun->name">
        <a href="{{ route('admin.akun.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.akun.store') : route('admin.akun.update', $akun) }}" class="kartu flex max-w-2xl flex-col gap-5 p-5" data-lock autocomplete="off">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="name" label="Nama" :value="$akun->name" required maxlength="120" />
            <x-admin.input name="email" label="Email" type="email" :value="$akun->email" required maxlength="150" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.select name="role" label="Role" :options="\App\Models\User::ROLES" :value="$akun->role" required />
            <x-admin.select name="divisi_id" label="Divisi (khusus koordinator)" :options="$divisi->pluck('nama', 'id')->all()" :value="$akun->divisi_id" placeholder="Pilih divisi"
                            help="Koordinator hanya bisa mengelola anggota di divisi ini." />
        </div>

        <fieldset class="flex flex-col gap-4 rounded-2xl border border-garis p-4">
            <legend class="px-1 text-sm font-semibold">{{ $baru ? 'Password' : 'Reset password' }}</legend>
            @unless ($baru)
                <p class="bantuan -mt-1">Kosongkan kalau tidak ingin mengganti password.</p>
            @endunless
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="password" label="Password baru" type="password" autocomplete="new-password" :required="$baru" help="Minimal 8 karakter, berisi huruf dan angka." />
                <x-admin.input name="password_confirmation" label="Ulangi password" type="password" autocomplete="new-password" :required="$baru" />
            </div>
        </fieldset>

        <x-admin.checkbox name="aktif" label="Akun aktif (boleh login)" :checked="$akun->aktif" />

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.akun.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
