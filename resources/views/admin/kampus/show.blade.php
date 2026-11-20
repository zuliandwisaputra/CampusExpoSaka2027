@extends('layouts.admin')

@section('judul', $kampus->nama)

@section('isi')
    <x-admin.judul :judul="$kampus->nama" :pengantar="$kampus->jenis_label.($kampus->lokasi ? ' · '.$kampus->lokasi : '')">
        <a href="{{ route('admin.kampus.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
        <a href="{{ route('kampus.show', $kampus) }}" target="_blank" rel="noopener" class="tombol-garis"><x-ikon name="luar" /> Halaman publik</a>
        @can('super-admin')
            <a href="{{ route('admin.kampus.edit', $kampus) }}" class="tombol-zaitun"><x-ikon name="ubah" /> Ubah</a>
        @endcan
    </x-admin.judul>

    <div class="grid gap-4 lg:grid-cols-[1fr_280px]">
        <div class="kartu flex flex-col gap-4 p-5 text-[15px]">
            @foreach (['deskripsi' => 'Profil singkat', 'jalur_masuk' => 'Jalur masuk', 'kisaran_biaya' => 'Kisaran biaya', 'beasiswa' => 'Beasiswa'] as $kolom => $judul)
                <section>
                    <h2 class="font-semibold text-zaitun">{{ $judul }}</h2>
                    <div class="isi-teks mt-1">@if ($kampus->$kolom) {{ \App\Support\Teks::paragraf($kampus->$kolom) }} @else <p class="text-redup">Belum diisi.</p> @endif</div>
                </section>
            @endforeach
            <section>
                <h2 class="font-semibold text-zaitun">Jurusan ({{ $kampus->jurusan->count() }})</h2>
                <ul class="mt-2 flex flex-wrap gap-2">
                    @forelse ($kampus->jurusan as $j)
                        <li class="rounded-full border border-garis bg-krem px-3 py-1 text-sm">{{ $j->jenjang ? $j->jenjang.' ' : '' }}{{ $j->nama }}</li>
                    @empty
                        <li class="text-redup">Belum diisi.</li>
                    @endforelse
                </ul>
            </section>
        </div>
        <aside class="kartu flex flex-col gap-3 p-5">
            <img src="{{ $kampus->logo_url }}" alt="Logo {{ $kampus->nama }}" class="aspect-square w-full rounded-xl bg-krem-tua object-contain p-3">
            <p class="text-sm"><span class="font-semibold">Website:</span> {{ $kampus->website ?: '-' }}</p>
            <p class="text-sm"><span class="font-semibold">Instagram:</span> {{ $kampus->instagram ? '@'.$kampus->instagram : '-' }}</p>
            <div>
                <p class="text-sm font-semibold">Anggota expo ({{ $kampus->anggota->count() }})</p>
                <ul class="mt-1 text-sm text-redup">
                    @foreach ($kampus->anggota as $a)
                        <li>{{ $a->nama }} · {{ $a->divisi?->nama }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
@endsection
