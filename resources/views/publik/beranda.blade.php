@extends('layouts.publik')

@php
    $p = \App\Models\Pengaturan::semua();
    $tagline = e($p['tagline']);
    $taglineHtml = str_contains($tagline, 'Landscape')
        ? str_replace('Landscape', '<em class="font-medium text-rose-terang italic">Landscape</em>', $tagline)
        : $tagline;
    $tanggal = \Illuminate\Support\Carbon::parse($p['tanggal_acara'])->translatedFormat('l, j F Y');
    $jam = $p['jam_mulai'] ? str_replace(':', '.', $p['jam_mulai']).($p['jam_selesai'] ? '–'.str_replace(':', '.', $p['jam_selesai']) : '').' WIB' : null;
@endphp

@section('isi')
    {{-- Foto sekolah paling atas. Di HP foto terpotong kiri-kanan, jadi objek utama ada di tengah. --}}
    <section class="relative isolate flex min-h-[600px] items-end overflow-hidden bg-[#BDB596] sm:min-h-[640px]">
        <img src="{{ \App\Models\Pengaturan::fotoBerandaUrl() }}" alt="SMA Negeri 1 Kandanghaur" width="1920" height="1080"
             fetchpriority="high" decoding="async" class="absolute inset-0 -z-20 size-full object-cover object-center">
        <div class="absolute inset-0 -z-10 bg-[rgba(46,44,20,0.6)]"></div>

        <div class="wadah flex flex-col gap-4 pt-16 pb-8 text-krem sm:pb-12">
            <span class="self-start rounded-full bg-rose px-3 py-1.5 text-[13px] font-semibold text-tinta">{{ $tanggal }}@if ($jam) · {{ $jam }}@endif</span>
            <h1 class="max-w-3xl font-serif text-[38px] leading-[1.12] font-semibold sm:text-5xl lg:text-6xl">{!! $taglineHtml !!}</h1>
            <p class="max-w-2xl text-base leading-relaxed text-[#EDE7D6] sm:text-lg">{{ $p['deskripsi'] }}</p>
            <p class="flex items-center gap-2 text-sm text-[#EDE7D6]">
                <x-ikon name="lokasi" class="size-[18px] shrink-0" /> {{ $p['lokasi'] }}, Indramayu
            </p>

            @if (! $acaraLewat)
                <div class="grid max-w-md grid-cols-4 gap-2" data-countdown="{{ $waktuAcara->toIso8601String() }}" data-reload-when-done
                     role="timer" aria-label="Hitung mundur menuju hari H">
                    @php $s = max(0, $waktuAcara->getTimestamp() - now()->getTimestamp()); @endphp
                    @foreach (['hari' => intdiv($s, 86400), 'jam' => intdiv($s % 86400, 3600), 'menit' => intdiv($s % 3600, 60), 'detik' => $s % 60] as $unit => $nilai)
                        <div class="flex flex-col items-center gap-0.5 rounded-2xl bg-krem py-2.5 text-tinta">
                            <span class="font-serif text-[28px] leading-tight font-bold text-zaitun tabular-nums" data-unit="{{ $unit }}">{{ $unit === 'hari' ? $nilai : str_pad((string) $nilai, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-xs font-medium text-redup">{{ ucfirst($unit) }}</span>
                        </div>
                    @endforeach
                </div>
            @elseif ($dokumentasi->isNotEmpty())
                {{-- Setelah acara lewat, hitung mundur diganti foto dokumentasi dari Galeri. --}}
                <div class="flex max-w-2xl flex-col gap-2">
                    <span class="text-sm font-semibold">Dokumentasi Campus Expo 2027</span>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($dokumentasi->take(3) as $foto)
                            <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Dokumentasi acara' }}" loading="lazy" class="aspect-[4/3] w-full rounded-xl object-cover">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex max-w-md gap-2">
                <a href="{{ route('kampus.index') }}" class="tombol-utama flex-1">Lihat Kampus <x-ikon name="panah" class="size-[18px]" /></a>
                @if ($acaraLewat)
                    <a href="{{ route('galeri.index') }}" class="tombol flex-1 border border-krem text-krem hover:bg-white/10">Galeri</a>
                @else
                    <a href="{{ route('jadwal.index') }}" class="tombol flex-1 border border-krem text-krem hover:bg-white/10">Jadwal Acara</a>
                @endif
            </div>
        </div>
    </section>

    <div aria-hidden="true" class="flex h-2.5">
        <div class="flex-[5] bg-zaitun-brand"></div>
        <div class="flex-[3] bg-rose"></div>
        <div class="flex-1 bg-mauve"></div>
    </div>

    {{-- Ringkasan angka --}}
    <section class="wadah pt-6" aria-label="Ringkasan">
        <dl class="grid grid-cols-3 gap-2 sm:max-w-2xl sm:gap-4">
            @foreach (['kampus' => 'Kampus', 'anggota' => 'Anggota', 'divisi' => 'Divisi'] as $kunci => $label)
                <div class="kartu flex flex-col gap-0.5 px-3 py-3.5 sm:px-5">
                    <dd class="font-serif text-3xl font-bold text-zaitun">{{ $jumlah[$kunci] }}</dd>
                    <dt class="order-last text-[13px] text-redup">{{ $label }}</dt>
                </div>
            @endforeach
        </dl>
    </section>

    <div class="wadah grid gap-10 py-10 lg:grid-cols-2 lg:gap-12">
        {{-- Pengumuman terbaru --}}
        <section class="flex flex-col gap-3" aria-labelledby="judul-pengumuman">
            <div class="flex items-baseline justify-between gap-4">
                <h2 id="judul-pengumuman" class="judul-bagian">Pengumuman</h2>
                <a href="{{ route('pengumuman.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Lihat semua</a>
            </div>
            @forelse ($pengumuman as $item)
                <a href="{{ route('pengumuman.show', $item) }}" class="kartu flex flex-col gap-1.5 p-4 transition-colors duration-200 hover:border-garis-tua">
                    @if ($item->disematkan)
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-mauve-tua"><x-ikon name="pin" class="size-3.5" /> Disematkan</span>
                    @endif
                    <span class="text-base leading-snug font-semibold">{{ $item->judul }}</span>
                    <span class="text-[13px] text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}</span>
                </a>
            @empty
                @include('partials.kosong', ['ikon' => 'pengumuman', 'judul' => 'Belum ada pengumuman', 'teks' => 'Info terbaru dari panitia akan muncul di sini.'])
            @endforelse
        </section>

        {{-- Kampus --}}
        <section class="flex flex-col gap-3" aria-labelledby="judul-kampus">
            <div class="flex items-baseline justify-between gap-4">
                <h2 id="judul-kampus" class="judul-bagian">Kampus yang hadir</h2>
                <a href="{{ route('kampus.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Semua {{ $jumlah['kampus'] }}</a>
            </div>
            <form action="{{ route('kampus.index') }}" method="get" role="search" class="relative">
                <label for="cari-beranda" class="sr-only">Cari kampus</label>
                <input id="cari-beranda" type="search" name="q" placeholder="Cari nama kampus…" class="input pl-11">
                <x-ikon name="cari" class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-redup" />
            </form>
            @foreach ($kampus as $k)
                @include('partials.kartu-kampus', ['k' => $k])
            @endforeach
        </section>
    </div>

    {{-- Kenalan dengan alumni (ketua divisi) --}}
    @if ($anggota->isNotEmpty())
        <section class="wadah flex flex-col gap-3 pb-6" aria-labelledby="judul-alumni">
            <div class="flex items-baseline justify-between gap-4">
                <h2 id="judul-alumni" class="judul-bagian">Kenalan dengan alumni</h2>
                <a href="{{ route('anggota.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Semua {{ $jumlah['anggota'] }}</a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($anggota as $a)
                    @include('partials.kartu-anggota', ['a' => $a])
                @endforeach
            </div>
        </section>
    @endif
@endsection
