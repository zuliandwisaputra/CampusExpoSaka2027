@extends('layouts.publik')

@section('judul', $kampus->nama)
@section('deskripsi', $kampus->deskripsi ? \Illuminate\Support\Str::limit($kampus->deskripsi, 160) : "Profil {$kampus->nama} di Campus Expo SMAN 1 Kandanghaur 2027: jurusan, jalur masuk, biaya, beasiswa, dan alumni.")
@if (! \App\Support\Media::isDummy($kampus->logo))
    @section('gambar', $kampus->logo_url)
@endif

@php
    $teksBagikan = "Cek {$kampus->nama} di Campus Expo SMAN 1 Kandanghaur 2027: ".route('kampus.show', $kampus);
    $jurusanPerJenjang = $kampus->jurusan->groupBy(fn ($j) => $j->jenjang ?: 'Lainnya');
    $belumDiisi = 'Belum diisi panitia. Pantau lagi menjelang hari H.';
@endphp

@section('isi')
    <section class="border-b border-garis bg-krem-tua/60">
        <div class="wadah flex flex-col gap-4 py-8 sm:flex-row sm:items-center sm:gap-6">
            <img src="{{ $kampus->logo_url }}" alt="Logo {{ $kampus->nama }}" width="400" height="400" class="size-24 rounded-2xl border border-garis bg-white object-contain p-2 sm:size-28">
            <div class="flex flex-col gap-2">
                <a href="{{ route('kampus.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-zaitun hover:underline"><x-ikon name="kiri" class="size-4" /> Semua kampus</a>
                <h1 class="font-serif text-3xl leading-tight font-semibold sm:text-4xl">{{ $kampus->nama }}</h1>
                <p class="text-sm text-redup">
                    <span class="rounded-full bg-zaitun px-2.5 py-0.5 text-xs font-semibold text-white">{{ $kampus->jenis_label }}</span>
                    @if ($kampus->lokasi) <span class="ml-1">{{ $kampus->lokasi }}</span> @endif
                </p>
            </div>
        </div>
    </section>

    <div class="wadah grid gap-8 py-8 lg:grid-cols-[1fr_320px]">
        <div class="flex flex-col gap-6">
            <section class="kartu p-5" aria-labelledby="profil">
                <h2 id="profil" class="font-serif text-xl font-semibold">Profil singkat</h2>
                <div class="isi-teks mt-3 text-[15px] text-tinta">
                    @if ($kampus->deskripsi) {{ \App\Support\Teks::paragraf($kampus->deskripsi) }} @else <p class="text-redup">{{ $belumDiisi }}</p> @endif
                </div>
            </section>

            <section class="kartu p-5" aria-labelledby="jurusan">
                <h2 id="jurusan" class="font-serif text-xl font-semibold">Jurusan</h2>
                @if ($kampus->jurusan->isEmpty())
                    <p class="mt-3 text-[15px] text-redup">{{ $belumDiisi }}</p>
                @else
                    <div class="mt-3 flex flex-col gap-4">
                        @foreach ($jurusanPerJenjang as $jenjang => $daftar)
                            <div>
                                <h3 class="text-sm font-semibold text-zaitun">{{ $jenjang }}</h3>
                                <ul class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($daftar as $j)
                                        <li class="rounded-full border border-garis bg-krem px-3 py-1.5 text-sm">{{ $j->nama }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @foreach (['jalur_masuk' => 'Jalur masuk', 'kisaran_biaya' => 'Kisaran biaya kuliah', 'beasiswa' => 'Info beasiswa'] as $kolom => $judul)
                <section class="kartu p-5" aria-labelledby="{{ $kolom }}">
                    <h2 id="{{ $kolom }}" class="font-serif text-xl font-semibold">{{ $judul }}</h2>
                    <div class="isi-teks mt-3 text-[15px]">
                        @if ($kampus->$kolom) {{ \App\Support\Teks::paragraf($kampus->$kolom) }} @else <p class="text-redup">{{ $belumDiisi }}</p> @endif
                    </div>
                </section>
            @endforeach
        </div>

        <aside class="flex flex-col gap-4">
            <div class="kartu flex flex-col gap-2 p-5">
                <h2 class="font-serif text-xl font-semibold">Link resmi</h2>
                @if ($kampus->website)
                    <a href="{{ $kampus->website }}" target="_blank" rel="noopener noreferrer" class="tombol-garis justify-start"><x-ikon name="web" /> Website resmi</a>
                @endif
                @if ($kampus->instagram)
                    <a href="{{ $kampus->instagram_url }}" target="_blank" rel="noopener noreferrer" class="tombol-garis justify-start"><x-ikon name="instagram" /> {{ '@'.$kampus->instagram }}</a>
                @endif
                @if (! $kampus->website && ! $kampus->instagram)
                    <p class="text-sm text-redup">Belum diisi panitia.</p>
                @endif
            </div>
            <a href="https://wa.me/?text={{ rawurlencode($teksBagikan) }}" target="_blank" rel="noopener noreferrer" class="tombol bg-[#1F7A47] text-white hover:bg-[#17633A]">
                <x-ikon name="bagikan" /> Bagikan ke WhatsApp
            </a>
        </aside>
    </div>

    <section class="wadah flex flex-col gap-3 pb-6" aria-labelledby="alumni">
        <h2 id="alumni" class="judul-bagian">Alumni dari {{ $kampus->singkatan ?: $kampus->nama }}</h2>
        @if ($kampus->anggota->isEmpty())
            @include('partials.kosong', ['ikon' => 'orang', 'judul' => 'Belum ada alumni yang ditampilkan dari kampus ini'])
        @else
            <p class="text-sm text-redup">Tanyakan langsung soal kampus ini ke {{ $kampus->anggota->count() }} kakak alumni berikut.</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($kampus->anggota as $a)
                    @include('partials.kartu-anggota', ['a' => $a])
                @endforeach
            </div>
        @endif
    </section>
@endsection
