@extends('layouts.publik')

@section('judul', 'Kampus')
@section('deskripsi', 'Daftar kampus yang hadir di Campus Expo SMAN 1 Kandanghaur 2027: profil, jurusan, jalur masuk, biaya, dan beasiswa.')

@section('isi')
    @include('partials.judul-halaman', [
        'label' => 'Kampus',
        'judul' => 'Kampus yang hadir',
        'pengantar' => 'Cari kampus incaranmu, lalu buka detailnya untuk melihat jurusan, jalur masuk, kisaran biaya, beasiswa, dan alumni yang bisa kamu tanyai.',
    ])

    <div class="wadah flex flex-col gap-4 py-6">
        <form method="get" action="{{ route('kampus.index') }}" class="flex flex-col gap-3" role="search">
            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <label for="q" class="sr-only">Cari nama kampus</label>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kampus…" class="input pl-11">
                    <x-ikon name="cari" class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-redup" />
                </div>
                <div class="sm:w-56">
                    <label for="provinsi" class="sr-only">Provinsi</label>
                    <select id="provinsi" name="provinsi" class="input" onchange="this.form.submit()">
                        <option value="">Semua provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov }}" @selected(request('provinsi') === $prov)>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="tombol-zaitun">Cari</button>
            </div>
            @if (request('jenis'))
                <input type="hidden" name="jenis" value="{{ request('jenis') }}">
            @endif
        </form>

        <nav aria-label="Filter jenis kampus" class="-mx-4 overflow-x-auto px-4">
            <ul class="flex gap-2 pb-1">
                <li><a href="{{ route('kampus.index', request()->except(['jenis', 'page'])) }}" @class(['chip', 'chip-aktif' => ! request('jenis')])>Semua</a></li>
                @foreach (\App\Models\Kampus::JENIS as $kode => $label)
                    <li><a href="{{ route('kampus.index', [...request()->except(['jenis', 'page']), 'jenis' => $kode]) }}" @class(['chip', 'chip-aktif' => request('jenis') === $kode])>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <p class="text-sm text-redup" aria-live="polite">{{ $total }} kampus ditemukan</p>

        @if ($kampus->isEmpty())
            @include('partials.kosong', ['judul' => 'Kampus tidak ditemukan', 'teks' => 'Coba kata kunci lain atau hapus filter.'])
        @else
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($kampus as $k)
                    @include('partials.kartu-kampus', ['k' => $k])
                @endforeach
            </div>
            {{ $kampus->links() }}
        @endif
    </div>
@endsection
