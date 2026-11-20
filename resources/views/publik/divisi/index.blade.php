@extends('layouts.publik')

@section('judul', 'Divisi')
@section('deskripsi', 'Struktur kepanitiaan Campus Expo SMAN 1 Kandanghaur 2027: divisi, tugas, koordinator, dan anggotanya.')

@section('isi')
    @include('partials.judul-halaman', [
        'label' => 'Divisi',
        'judul' => 'Struktur kepanitiaan',
        'pengantar' => $divisi->count().' divisi bekerja sama menyiapkan Campus Expo 2027. Buka tiap divisi untuk melihat seluruh anggotanya.',
    ])

    <div class="wadah grid gap-4 py-6 md:grid-cols-2">
        @foreach ($divisi as $d)
            @php $koordinator = $d->koordinator(); @endphp
            <article class="kartu flex flex-col gap-3 p-5">
                <div class="flex items-start justify-between gap-3">
                    <h2 class="font-serif text-xl leading-snug font-semibold">{{ $d->nama }}</h2>
                    <span class="shrink-0 rounded-full bg-krem-tua px-2.5 py-1 text-xs font-semibold text-redup">{{ $d->anggota->count() }} orang</span>
                </div>
                @if ($d->deskripsi)
                    <p class="text-[15px] leading-relaxed text-redup">{{ $d->deskripsi }}</p>
                @endif
                @if ($koordinator)
                    <div class="flex items-center gap-3 rounded-xl bg-krem p-3">
                        <img src="{{ $koordinator->foto_url }}" alt="Foto {{ $koordinator->nama }}" width="48" height="48" loading="lazy" class="size-12 rounded-full bg-foto object-cover">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-mauve-tua">Koordinator</span>
                            <span class="font-semibold">{{ $koordinator->nama }}</span>
                            <span class="text-xs text-redup">{{ $koordinator->kampus?->nama }}</span>
                        </div>
                    </div>
                @endif
                <a href="{{ route('divisi.show', $d) }}" class="tombol-garis mt-auto">Lihat anggota divisi <x-ikon name="panah" class="size-[18px]" /></a>
            </article>
        @endforeach
    </div>
@endsection
