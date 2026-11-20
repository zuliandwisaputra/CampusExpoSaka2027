@extends('layouts.publik')

@section('judul', $divisi->nama)
@section('deskripsi', $divisi->deskripsi ?: "Anggota {$divisi->nama} Campus Expo SMAN 1 Kandanghaur 2027.")

@section('isi')
    <section class="border-b border-garis bg-krem-tua/60">
        <div class="wadah py-8">
            <a href="{{ route('divisi.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-zaitun hover:underline"><x-ikon name="kiri" class="size-4" /> Semua divisi</a>
            <h1 class="mt-2 font-serif text-3xl leading-tight font-semibold sm:text-4xl">{{ $divisi->nama }}</h1>
            @if ($divisi->deskripsi)
                <p class="mt-2 max-w-2xl leading-relaxed text-redup">{{ $divisi->deskripsi }}</p>
            @endif
            <p class="mt-3 text-sm font-semibold text-zaitun">{{ $divisi->anggota->count() }} anggota</p>
        </div>
    </section>

    <div class="wadah py-6">
        @if ($divisi->anggota->isEmpty())
            @include('partials.kosong', ['ikon' => 'orang', 'judul' => 'Belum ada anggota yang ditampilkan'])
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($divisi->anggota as $a)
                    @include('partials.kartu-anggota', ['a' => $a, 'tampilDivisi' => false])
                @endforeach
            </div>
        @endif
    </div>
@endsection
