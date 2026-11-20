@extends('layouts.admin')

@php $baru = ! $item->exists; @endphp
@section('judul', $baru ? 'Tulis pengumuman' : 'Ubah pengumuman')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tulis pengumuman' : 'Ubah pengumuman'">
        <a href="{{ route('admin.pengumuman.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.pengumuman.store') : route('admin.pengumuman.update', $item) }}" class="kartu flex max-w-3xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-admin.input name="judul" label="Judul" :value="$item->judul" required maxlength="180" />
        <x-admin.textarea name="isi" label="Isi" :value="$item->isi" rows="10" required
                          help="Pisahkan paragraf dengan baris kosong. Baris yang diawali tanda minus dan spasi ditampilkan sebagai daftar." />

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.select name="status" label="Status" :options="['draf' => 'Draf (belum terlihat publik)', 'terbit' => 'Terbit']" :value="$item->status" required />
            <x-admin.input name="terbit_pada" label="Waktu terbit" type="datetime-local" :value="$item->terbit_pada?->format('Y-m-d\TH:i')"
                           help="Kosongkan agar langsung terbit saat disimpan. Isi waktu nanti untuk menjadwalkan." />
        </div>

        <x-admin.checkbox name="disematkan" label="Sematkan di paling atas" :checked="$item->disematkan" />

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.pengumuman.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
