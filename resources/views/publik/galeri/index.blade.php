@extends('layouts.publik')

@section('judul', 'Galeri')
@section('deskripsi', 'Dokumentasi Campus Expo SMAN 1 Kandanghaur 2027.')

@section('isi')
    @include('partials.judul-halaman', ['label' => 'Galeri', 'judul' => 'Dokumentasi', 'pengantar' => 'Foto-foto persiapan dan hari H Campus Expo, dikelompokkan per album.'])

    <div class="wadah flex flex-col gap-10 py-6">
        @forelse ($album as $item)
            <section class="flex flex-col gap-3" aria-labelledby="album-{{ $item->id }}">
                <div class="flex items-baseline justify-between gap-4">
                    <div>
                        <h2 id="album-{{ $item->id }}" class="judul-bagian">{{ $item->judul }}</h2>
                        <p class="text-sm text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}@if ($item->tanggal) · @endif{{ $item->foto->count() }} foto</p>
                    </div>
                    @if ($item->foto->count() > 6)
                        <a href="{{ route('galeri.show', $item) }}" class="shrink-0 text-sm font-semibold text-zaitun hover:underline">Lihat semua</a>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($item->foto->take(6) as $foto)
                        <a href="{{ $foto->url }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl">
                            <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Foto '.$item->judul }}" width="600" height="450" loading="lazy" decoding="async"
                                 class="aspect-[4/3] w-full bg-foto object-cover transition-opacity duration-200 hover:opacity-90">
                        </a>
                    @endforeach
                </div>
            </section>
        @empty
            @include('partials.kosong', ['ikon' => 'galeri', 'judul' => 'Belum ada foto', 'teks' => 'Dokumentasi akan diunggah panitia setelah acara.'])
        @endforelse
    </div>
@endsection
