@extends('layouts.publik')

@section('judul', 'Anggota')
@section('deskripsi', 'Kenalan dengan alumni SMAN 1 Kandanghaur yang menjadi panitia Campus Expo 2027, lengkap dengan kampus, jurusan, dan media sosialnya.')

@section('isi')
    @include('partials.judul-halaman', [
        'label' => 'Anggota',
        'judul' => 'Kenalan dengan alumni',
        'pengantar' => 'Alumni SMAN 1 Kandanghaur yang mewakili kampusnya. Sapa mereka lewat Instagram atau LinkedIn kalau ingin bertanya lebih jauh.',
    ])

    <div class="wadah flex flex-col gap-4 py-6">
        <form method="get" action="{{ route('anggota.index') }}" class="grid gap-3 sm:grid-cols-[1fr_220px_220px_auto]" role="search">
            <div class="relative">
                <label for="q" class="sr-only">Cari nama anggota</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama…" class="input pl-11">
                <x-ikon name="cari" class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-redup" />
            </div>
            <div>
                <label for="kampus" class="sr-only">Kampus</label>
                <select id="kampus" name="kampus" class="input" onchange="this.form.submit()">
                    <option value="">Semua kampus</option>
                    @foreach ($kampus as $k)
                        <option value="{{ $k->id }}" @selected((int) request('kampus') === $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="divisi" class="sr-only">Divisi</label>
                <select id="divisi" name="divisi" class="input" onchange="this.form.submit()">
                    <option value="">Semua divisi</option>
                    @foreach ($divisi as $d)
                        <option value="{{ $d->id }}" @selected((int) request('divisi') === $d->id)>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="tombol-zaitun">Cari</button>
        </form>

        <div class="flex items-center justify-between gap-3">
            <p class="text-sm text-redup" aria-live="polite">{{ $total }} anggota</p>
            @if (request()->hasAny(['q', 'kampus', 'divisi']))
                <a href="{{ route('anggota.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Hapus filter</a>
            @endif
        </div>

        @if ($anggota->isEmpty())
            @include('partials.kosong', ['ikon' => 'orang', 'judul' => 'Anggota tidak ditemukan', 'teks' => 'Coba nama lain atau ganti filter kampus dan divisi.'])
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($anggota as $a)
                    @include('partials.kartu-anggota', ['a' => $a])
                @endforeach
            </div>
            {{ $anggota->links() }}
        @endif
    </div>
@endsection
