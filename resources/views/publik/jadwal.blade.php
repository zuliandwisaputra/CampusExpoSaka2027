@extends('layouts.publik')

@php
    $p = \App\Models\Pengaturan::semua();
    $tanggal = \Illuminate\Support\Carbon::parse($p['tanggal_acara'])->translatedFormat('l, j F Y');
@endphp

@section('judul', 'Jadwal')
@section('deskripsi', "Rundown Campus Expo SMAN 1 Kandanghaur 2027, {$tanggal}.")

@section('isi')
    @include('partials.judul-halaman', [
        'label' => 'Jadwal',
        'judul' => 'Rundown hari H',
        'pengantar' => "{$tanggal} · {$p['lokasi']}",
    ])

    <div class="wadah max-w-3xl py-6">
        @if ($jadwal->isEmpty())
            @include('partials.kosong', ['ikon' => 'jam', 'judul' => 'Rundown segera diumumkan', 'teks' => 'Panitia sedang menyusun jam acara. Cek lagi halaman ini atau Instagram panitia.'])
        @else
            <ol class="relative flex flex-col gap-3 border-l-2 border-garis-tua pl-5">
                @foreach ($jadwal as $item)
                    <li class="relative" data-reveal>
                        <span aria-hidden="true" @class([
                            'absolute top-5 -left-[29px] size-4 rounded-full border-2 border-krem',
                            'bg-mauve' => $item->acara_utama,
                            'bg-zaitun-brand' => ! $item->acara_utama,
                        ])></span>
                        <article @class([
                            'flex flex-col gap-1.5 rounded-2xl border p-4',
                            'border-rose bg-rose-muda' => $item->acara_utama,
                            'kartu' => ! $item->acara_utama,
                        ])>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 font-serif text-lg font-bold text-zaitun tabular-nums"><x-ikon name="jam" class="size-4" /> {{ str_replace(':', '.', $item->jam) }}</span>
                                @if ($item->acara_utama)
                                    <span class="rounded-full bg-mauve px-2.5 py-0.5 text-xs font-semibold text-white">Acara utama</span>
                                @endif
                            </div>
                            <h2 class="text-lg leading-snug font-semibold">{{ $item->judul }}</h2>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-redup">
                                @if ($item->lokasi)
                                    <span class="inline-flex items-center gap-1"><x-ikon name="lokasi" class="size-4" /> {{ $item->lokasi }}</span>
                                @endif
                                @if ($item->kampus)
                                    <a href="{{ route('kampus.show', $item->kampus) }}" class="inline-flex items-center gap-1 hover:underline"><x-ikon name="kampus" class="size-4" /> {{ $item->kampus->nama }}</a>
                                @endif
                            </div>
                            @if ($item->keterangan)
                                <p class="text-[15px] leading-relaxed">{{ $item->keterangan }}</p>
                            @endif
                        </article>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
@endsection
