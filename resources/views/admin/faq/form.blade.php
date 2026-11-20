@extends('layouts.admin')

@php $baru = ! $item->exists; @endphp
@section('judul', $baru ? 'Tambah pertanyaan' : 'Ubah pertanyaan')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah pertanyaan' : 'Ubah pertanyaan'">
        <a href="{{ route('admin.faq.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.faq.store') : route('admin.faq.update', $item) }}" class="kartu flex max-w-2xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-admin.input name="pertanyaan" label="Pertanyaan" :value="$item->pertanyaan" required maxlength="255" />
        <x-admin.textarea name="jawaban" label="Jawaban" :value="$item->jawaban" rows="5" required maxlength="3000" />
        <div class="max-w-40">
            <x-admin.input name="urutan" label="Urutan tampil" type="number" min="0" max="999" :value="$item->urutan" required />
        </div>

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.faq.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
