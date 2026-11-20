@extends('layouts.publik')

@section('judul', $album->judul)
@section('deskripsi', "Album {$album->judul} · Galeri Campus Expo SMAN 1 Kandanghaur 2027.")

@section('isi')
    <section class="border-b border-garis bg-krem-tua/60">
        <div class="animasi-masuk wadah py-8">
            <a href="{{ route('galeri.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-zaitun hover:underline"><x-ikon name="kiri" class="size-4" /> Semua album</a>
            <h1 class="mt-2 font-serif text-3xl font-semibold sm:text-4xl">{{ $album->judul }}</h1>
            <p class="mt-1 text-sm text-redup">{{ $album->tanggal?->translatedFormat('j F Y') }}@if ($album->tanggal) · @endif{{ $album->foto->count() }} foto</p>
        </div>
    </section>

    <div class="wadah grid grid-cols-2 gap-2 py-6 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ($album->foto as $foto)
            <figure class="flex flex-col gap-1" data-reveal>
                <a href="{{ $foto->url }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl">
                    <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Foto '.$album->judul }}" width="600" height="450" loading="lazy" decoding="async" class="aspect-[4/3] w-full bg-foto object-cover">
                </a>
                @if ($foto->keterangan)
                    <figcaption class="text-xs text-redup">{{ $foto->keterangan }}</figcaption>
                @endif
            </figure>
        @endforeach
    </div>
@endsection
