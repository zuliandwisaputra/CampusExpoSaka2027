@extends('layouts.admin')

@php $baru = ! $item->exists; @endphp
@section('judul', $baru ? 'Tambah acara' : 'Ubah acara')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah acara ke rundown' : 'Ubah acara'">
        <a href="{{ route('admin.jadwal.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.jadwal.store') : route('admin.jadwal.update', $item) }}" class="kartu flex max-w-2xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-admin.input name="judul" label="Kegiatan" :value="$item->judul" required maxlength="150" placeholder="Sesi tanya jawab seputar kampus" />

        <div class="grid grid-cols-2 gap-4">
            <x-admin.input name="waktu_mulai" label="Jam mulai" type="time" :value="$item->waktu_mulai ? substr($item->waktu_mulai, 0, 5) : null" required />
            <x-admin.input name="waktu_selesai" label="Jam selesai" type="time" :value="$item->waktu_selesai ? substr($item->waktu_selesai, 0, 5) : null" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="lokasi" label="Lokasi" :value="$item->lokasi" maxlength="120" placeholder="Aula SMAN 1 Kandanghaur" />
            <x-admin.select name="kampus_id" label="Kampus pengisi" :options="$kampus->pluck('nama', 'id')->all()" :value="$item->kampus_id" placeholder="Tidak ada / semua kampus" />
        </div>

        <x-admin.textarea name="keterangan" label="Keterangan" :value="$item->keterangan" rows="3" maxlength="1000" />

        <x-admin.checkbox name="acara_utama" label="Tandai sebagai acara utama" :checked="$item->acara_utama"
                          help="Acara utama (misalnya sesi tanya jawab seputar kampus) tampil menonjol di rundown." />

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.jadwal.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
