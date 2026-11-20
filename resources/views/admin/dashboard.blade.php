@extends('layouts.admin')

@section('judul', 'Dashboard')

@php
    $label = ['foto' => 'foto', 'jurusan' => 'jurusan', 'angkatan' => 'angkatan'];
    $user = auth()->user();
@endphp

@section('isi')
    <x-admin.judul judul="Halo, {{ $user->name }}" :pengantar="$user->isKoordinator() ? 'Kamu mengelola anggota '.($user->divisi?->nama ?? '-').' serta jadwal, pengumuman, galeri, dan FAQ.' : 'Ringkasan data Campus Expo 2027.'" />

    <dl class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
        @foreach ([
            ['Kampus', $jumlah['kampus'], 'admin.kampus.index'],
            [$user->isKoordinator() ? 'Anggota divisimu' : 'Anggota', $jumlah['anggota'], 'admin.anggota.index'],
            ['Tampil di web', $jumlah['anggotaTampil'], 'admin.anggota.index'],
            ['Divisi', $jumlah['divisi'], 'admin.divisi.index'],
            ['Acara di rundown', $jumlah['jadwal'], 'admin.jadwal.index'],
            ['Pengumuman terbit', $jumlah['pengumuman'], 'admin.pengumuman.index'],
        ] as [$teks, $angka, $route])
            <a href="{{ route($route) }}" class="kartu flex flex-col p-4 hover:border-garis-tua">
                <dd class="font-serif text-3xl font-bold text-zaitun">{{ $angka }}</dd>
                <dt class="order-last text-sm text-redup">{{ $teks }}</dt>
            </a>
        @endforeach
    </dl>

    <section class="mt-8" aria-labelledby="belum-lengkap">
        <h2 id="belum-lengkap" class="font-serif text-xl font-semibold">Anggota yang datanya belum lengkap</h2>
        <p class="mt-1 text-sm text-redup">Target PRD: semua anggota lengkap paling lambat H-7 (11 Januari 2027).</p>

        @if ($belumLengkap->isEmpty())
            <p class="kartu mt-3 flex items-center gap-2 p-4 text-sm"><x-ikon name="centang" class="size-5 text-zaitun" /> Semua data anggota sudah lengkap.</p>
        @else
            <p class="mt-2 text-sm font-semibold text-mauve-tua">{{ $belumLengkap->count() }} anggota belum lengkap</p>
            <ul class="kartu mt-3 divide-y divide-garis">
                @foreach ($belumLengkap->take(15) as $a)
                    <li class="flex flex-wrap items-center justify-between gap-2 px-4 py-3">
                        <span class="min-w-0">
                            <span class="block font-semibold">{{ $a->nama }}</span>
                            <span class="block text-xs text-redup">{{ $a->divisi?->nama }} · belum ada {{ collect($a->data_belum_lengkap)->map(fn ($k) => $label[$k])->implode(', ') }}</span>
                        </span>
                        @can('kelola-anggota', $a)
                            <a href="{{ route('admin.anggota.edit', $a) }}" class="inline-flex min-h-10 items-center gap-1 rounded-xl px-3 text-sm font-semibold text-zaitun hover:bg-krem-tua"><x-ikon name="ubah" class="size-4" /> Lengkapi</a>
                        @endcan
                    </li>
                @endforeach
            </ul>
            @if ($belumLengkap->count() > 15)
                <p class="mt-2 text-sm text-redup">dan {{ $belumLengkap->count() - 15 }} lainnya. Lihat semua di menu Anggota.</p>
            @endif
        @endif
    </section>

    @if ($kampusBelumLengkap->isNotEmpty())
        <section class="mt-8" aria-labelledby="kampus-belum">
            <h2 id="kampus-belum" class="font-serif text-xl font-semibold">Kampus yang datanya belum lengkap</h2>
            <p class="mt-1 text-sm text-redup">{{ $kampusBelumLengkap->count() }} kampus belum punya logo, deskripsi, atau daftar jurusan.</p>
            <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                @foreach ($kampusBelumLengkap->take(10) as $k)
                    <li>
                        <a href="{{ route('admin.kampus.edit', $k) }}" class="kartu flex items-center justify-between gap-2 px-4 py-3 hover:border-garis-tua">
                            <span class="min-w-0">
                                <span class="block font-semibold">{{ $k->nama }}</span>
                                <span class="block text-xs text-redup">
                                    {{ collect(['logo' => blank($k->logo), 'deskripsi' => blank($k->deskripsi), 'jurusan' => $k->jurusan_count === 0])->filter()->keys()->map(fn ($x) => 'tanpa '.$x)->implode(' · ') }}
                                </span>
                            </span>
                            <x-ikon name="kanan" class="size-5 shrink-0 text-redup" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
@endsection
