@extends('layouts.admin')

@section('judul', 'Kampus')

@section('isi')
    <x-admin.judul judul="Kampus" :pengantar="auth()->user()->isSuperAdmin() ? 'Logo, jenis, kota, jurusan, jalur masuk, biaya, beasiswa, dan link resmi.' : 'Koordinator hanya dapat melihat data kampus.'">
        @can('super-admin')
            <a href="{{ route('admin.kampus.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah kampus</a>
        @endcan
    </x-admin.judul>

    <form method="get" class="mb-4 grid gap-2 sm:grid-cols-[1fr_200px_auto]" role="search">
        <div>
            <label for="q" class="sr-only">Cari kampus</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kampus…" class="input">
        </div>
        <div>
            <label for="jenis" class="sr-only">Jenis</label>
            <select id="jenis" name="jenis" class="input">
                <option value="">Semua jenis</option>
                @foreach (\App\Models\Kampus::JENIS as $kode => $teks)
                    <option value="{{ $kode }}" @selected(request('jenis') === $kode)>{{ $teks }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="tombol-garis"><x-ikon name="cari" /> Filter</button>
    </form>

    <p class="mb-2 text-sm text-redup">{{ $kampus->total() }} kampus</p>

    <ul class="kartu divide-y divide-garis">
        @forelse ($kampus as $k)
            <li class="flex items-center gap-3 px-3 py-3 sm:px-4">
                <img src="{{ $k->logo_url }}" alt="" width="48" height="48" loading="lazy" class="size-12 shrink-0 rounded-xl bg-krem-tua object-contain p-1">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.kampus.show', $k) }}" class="font-semibold hover:underline">{{ $k->nama }}</a>
                    <p class="text-[13px] text-redup">{{ $k->jenis_label }}@if ($k->lokasi) · {{ $k->lokasi }}@endif · {{ $k->anggota_count }} anggota · {{ $k->jurusan_count }} jurusan</p>
                </div>
                @can('super-admin')
                    <a href="{{ route('admin.kampus.edit', $k) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah {{ $k->nama }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                    <x-admin.hapus :action="route('admin.kampus.destroy', $k)" :pesan="'Hapus kampus '.$k->nama.' beserta daftar jurusannya?'" :label="'Hapus '.$k->nama" kecil />
                @else
                    <a href="{{ route('admin.kampus.show', $k) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Lihat {{ $k->nama }}"><x-ikon name="lihat" class="size-[18px]" /></a>
                @endcan
            </li>
        @empty
            <li class="p-6 text-center text-sm text-redup">Kampus tidak ditemukan.</li>
        @endforelse
    </ul>
    {{ $kampus->links() }}
@endsection
