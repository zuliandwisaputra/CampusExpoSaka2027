@extends('layouts.admin')

@section('judul', 'Anggota')

@section('isi')
    <x-admin.judul judul="Anggota" :pengantar="auth()->user()->isKoordinator() ? 'Hanya anggota divisimu yang tampil di sini.' : 'Semua anggota expo dari seluruh divisi.'">
        @can('kelola-anggota')
            <a href="{{ route('admin.anggota.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah anggota</a>
        @endcan
    </x-admin.judul>

    <form method="get" class="mb-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-[1fr_200px_200px_160px_auto]" role="search">
        <div>
            <label for="q" class="sr-only">Cari nama</label>
            <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama…" class="input">
        </div>
        <div>
            <label for="kampus" class="sr-only">Kampus</label>
            <select id="kampus" name="kampus" class="input">
                <option value="">Semua kampus</option>
                @foreach ($kampus as $k)
                    <option value="{{ $k->id }}" @selected((int) request('kampus') === $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        @if (auth()->user()->isSuperAdmin())
            <div>
                <label for="divisi" class="sr-only">Divisi</label>
                <select id="divisi" name="divisi" class="input">
                    <option value="">Semua divisi</option>
                    @foreach ($divisi as $d)
                        <option value="{{ $d->id }}" @selected((int) request('divisi') === $d->id)>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div>
            <label for="status" class="sr-only">Status tampil</label>
            <select id="status" name="status" class="input">
                <option value="">Semua status</option>
                <option value="tampil" @selected(request('status') === 'tampil')>Tampil</option>
                <option value="sembunyi" @selected(request('status') === 'sembunyi')>Disembunyikan</option>
            </select>
        </div>
        <button type="submit" class="tombol-garis"><x-ikon name="cari" /> Filter</button>
    </form>

    <p class="mb-2 text-sm text-redup">{{ $anggota->total() }} anggota</p>

    @if ($anggota->isEmpty())
        @include('partials.kosong', ['ikon' => 'orang', 'judul' => 'Belum ada anggota yang cocok'])
    @else
        <ul class="kartu divide-y divide-garis">
            @foreach ($anggota as $a)
                <li class="flex items-center gap-3 px-3 py-3 sm:px-4">
                    <img src="{{ $a->foto_url }}" alt="" width="48" height="48" loading="lazy" class="size-12 shrink-0 rounded-xl bg-foto object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="flex flex-wrap items-center gap-x-2 font-semibold">
                            {{ $a->nama }}
                            @unless ($a->ditampilkan)
                                <span class="inline-flex items-center gap-1 rounded-full bg-krem-tua px-2 py-0.5 text-[11px] font-semibold text-redup"><x-ikon name="sembunyi" class="size-3" /> Disembunyikan</span>
                            @endunless
                        </p>
                        <p class="truncate text-[13px] text-redup">{{ $a->jabatan }} · {{ $a->divisi?->nama }} · {{ $a->kampus?->nama }}</p>
                        @if ($a->data_belum_lengkap)
                            <p class="text-xs text-mauve-tua">Belum ada {{ implode(', ', $a->data_belum_lengkap) }}</p>
                        @endif
                    </div>
                    @can('kelola-anggota', $a)
                        <a href="{{ route('admin.anggota.edit', $a) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah {{ $a->nama }}">
                            <x-ikon name="ubah" class="size-[18px]" />
                        </a>
                        <x-admin.hapus :action="route('admin.anggota.destroy', $a)" :pesan="'Hapus '.$a->nama.' dari daftar anggota?'" :label="'Hapus '.$a->nama" kecil />
                    @endcan
                </li>
            @endforeach
        </ul>
        {{ $anggota->links() }}
    @endif
@endsection
