@extends('layouts.admin')

@section('judul', 'Divisi')

@section('isi')
    <x-admin.judul judul="Divisi" :pengantar="auth()->user()->isKoordinator() ? 'Kamu bisa mengubah deskripsi tugas divisimu sendiri.' : 'Nama, deskripsi tugas, dan urutan tampil divisi.'">
        @can('super-admin')
            <a href="{{ route('admin.divisi.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah divisi</a>
        @endcan
    </x-admin.judul>

    <ul class="kartu divide-y divide-garis">
        @foreach ($divisi as $d)
            <li class="flex items-start gap-3 px-4 py-4">
                <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-krem-tua text-sm font-semibold text-zaitun">{{ $d->urutan }}</span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">{{ $d->nama }} <span class="text-sm font-normal text-redup">· {{ $d->anggota_count }} anggota</span></p>
                    <p class="mt-0.5 text-sm text-redup">{{ $d->deskripsi ?: 'Belum ada deskripsi.' }}</p>
                </div>
                @can('ubah-divisi', $d)
                    <a href="{{ route('admin.divisi.edit', $d) }}" class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah {{ $d->nama }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                @endcan
                @can('super-admin')
                    <x-admin.hapus :action="route('admin.divisi.destroy', $d)" :pesan="'Hapus '.$d->nama.'?'" :label="'Hapus '.$d->nama" kecil />
                @endcan
            </li>
        @endforeach
    </ul>
@endsection
