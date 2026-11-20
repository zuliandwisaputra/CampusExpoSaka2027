@extends('layouts.admin')

@php
    $baru = ! $divisi->exists;
    $super = auth()->user()->isSuperAdmin();
@endphp
@section('judul', $baru ? 'Tambah divisi' : 'Ubah divisi')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah divisi' : 'Ubah '.$divisi->nama">
        <a href="{{ route('admin.divisi.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.divisi.store') : route('admin.divisi.update', $divisi) }}" class="kartu flex max-w-2xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        @if ($super)
            <div class="grid gap-4 sm:grid-cols-[1fr_140px]">
                <x-admin.input name="nama" label="Nama divisi" :value="$divisi->nama" required maxlength="120" />
                <x-admin.input name="urutan" label="Urutan tampil" type="number" min="0" max="999" :value="$divisi->urutan" required />
            </div>
        @else
            <p class="font-semibold">{{ $divisi->nama }}</p>
        @endif

        <x-admin.textarea name="deskripsi" label="Deskripsi tugas" :value="$divisi->deskripsi" rows="4" maxlength="2000" />

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.divisi.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
