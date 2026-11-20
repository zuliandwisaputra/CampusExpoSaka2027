@extends('layouts.admin')

@section('judul', 'Jadwal')

@section('isi')
    <x-admin.judul judul="Jadwal" pengantar="Rundown hari H, otomatis diurutkan berdasarkan jam mulai.">
        <a href="{{ route('admin.jadwal.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah acara</a>
    </x-admin.judul>

    @if ($jadwal->isEmpty())
        @include('partials.kosong', ['ikon' => 'jam', 'judul' => 'Rundown masih kosong', 'teks' => 'Tambahkan acara setelah jam mulai dan selesai acara diputuskan.'])
    @else
        <ul class="kartu divide-y divide-garis">
            @foreach ($jadwal as $item)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="w-24 shrink-0 font-serif font-bold text-zaitun tabular-nums">{{ str_replace(':', '.', $item->jam) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold">{{ $item->judul }}
                            @if ($item->acara_utama)<span class="ml-1 rounded-full bg-mauve px-2 py-0.5 text-[11px] font-semibold text-white">Acara utama</span>@endif
                        </p>
                        <p class="text-[13px] text-redup">{{ collect([$item->lokasi, $item->kampus?->nama])->filter()->implode(' · ') ?: '—' }}</p>
                    </div>
                    <a href="{{ route('admin.jadwal.edit', $item) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah {{ $item->judul }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                    @can('super-admin')
                        <x-admin.hapus :action="route('admin.jadwal.destroy', $item)" :pesan="'Hapus acara '.$item->judul.' dari rundown?'" :label="'Hapus '.$item->judul" kecil />
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif
@endsection
