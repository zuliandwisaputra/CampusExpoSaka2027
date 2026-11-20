@extends('layouts.publik')

@section('judul', $pengumuman->judul)
@section('deskripsi', $pengumuman->ringkasan)

@section('isi')
    <article class="wadah max-w-3xl py-8">
        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-zaitun hover:underline"><x-ikon name="kiri" class="size-4" /> Semua pengumuman</a>
        <h1 class="mt-3 font-serif text-3xl leading-tight font-semibold sm:text-4xl">{{ $pengumuman->judul }}</h1>
        <p class="mt-2 text-sm text-redup">{{ $pengumuman->tanggal?->translatedFormat('l, j F Y') }}</p>
        <div class="isi-teks mt-6 text-[17px]">
            {{ \App\Support\Teks::paragraf($pengumuman->isi) }}
        </div>
        <a href="https://wa.me/?text={{ rawurlencode($pengumuman->judul.' — '.route('pengumuman.show', $pengumuman)) }}" target="_blank" rel="noopener noreferrer" class="tombol mt-4 bg-[#1F7A47] text-white hover:bg-[#17633A]">
            <x-ikon name="bagikan" /> Bagikan ke WhatsApp
        </a>
    </article>

    @if ($lainnya->isNotEmpty())
        <section class="wadah max-w-3xl border-t border-garis pt-6" aria-labelledby="lainnya">
            <h2 id="lainnya" class="font-serif text-xl font-semibold">Pengumuman lainnya</h2>
            <ul class="mt-3 flex flex-col gap-2">
                @foreach ($lainnya as $item)
                    <li><a href="{{ route('pengumuman.show', $item) }}" class="kartu flex items-center justify-between gap-3 p-4 font-semibold hover:border-garis-tua">{{ $item->judul }} <x-ikon name="kanan" class="size-5 shrink-0 text-redup" /></a></li>
                @endforeach
            </ul>
        </section>
    @endif
@endsection
