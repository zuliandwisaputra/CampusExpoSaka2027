@extends('layouts.admin')

@section('judul', 'Galeri')

@section('isi')
    <x-admin.judul judul="Galeri" pengantar="Buat album, lalu unggah banyak foto sekaligus. Foto dikecilkan otomatis saat diunggah.">
        <a href="{{ route('admin.galeri.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Buat album</a>
    </x-admin.judul>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($album as $item)
            <article class="kartu flex flex-col overflow-hidden">
                <img src="{{ $item->foto->first()?->thumb_url ?? asset(\App\Support\Media::DUMMY['galeri']) }}" alt="" loading="lazy" class="aspect-[4/3] w-full bg-foto object-cover">
                <div class="flex items-center gap-2 p-3">
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold">{{ $item->judul }}</p>
                        <p class="text-[13px] text-redup">{{ $item->foto_count }} foto @if ($item->tanggal) · {{ $item->tanggal->translatedFormat('j M Y') }} @endif</p>
                    </div>
                    <a href="{{ route('admin.galeri.edit', $item) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Kelola {{ $item->judul }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                    @can('super-admin')
                        <x-admin.hapus :action="route('admin.galeri.destroy', $item)" :pesan="'Hapus album '.$item->judul.' beserta '.$item->foto_count.' fotonya?'" :label="'Hapus '.$item->judul" kecil />
                    @endcan
                </div>
            </article>
        @empty
            <div class="sm:col-span-2 xl:col-span-3">@include('partials.kosong', ['ikon' => 'galeri', 'judul' => 'Belum ada album'])</div>
        @endforelse
    </div>
@endsection
