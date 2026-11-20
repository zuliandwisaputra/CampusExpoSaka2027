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
    {{-- Satu halaman (one page). Urutan bagian sama dengan urutan menu di header. --}}

    {{-- Foto sekolah paling atas. Di HP foto terpotong kiri-kanan, jadi objek utama ada di tengah. --}}
    <section id="beranda" class="relative isolate flex min-h-[600px] scroll-mt-20 items-end overflow-hidden bg-[#BDB596] sm:min-h-[640px]">
        <img src="{{ \App\Models\Pengaturan::fotoBerandaUrl() }}" alt="SMA Negeri 1 Kandanghaur" width="1920" height="1080"
             fetchpriority="high" decoding="async" data-paralaks class="absolute inset-0 -z-20 size-full object-cover object-center">
        <div class="absolute inset-0 -z-10 bg-[rgba(46,44,20,0.6)]"></div>

        <div class="wadah flex flex-col gap-4 pt-16 pb-8 text-krem sm:pb-12">
            <span class="animasi-masuk self-start rounded-full bg-rose px-3 py-1.5 text-[13px] font-semibold text-tinta" style="--urut: 0">{{ $tanggal }}@if ($jam) · {{ $jam }}@endif</span>
            <h1 style="--urut: 1" class="animasi-masuk max-w-3xl font-serif text-[38px] leading-[1.12] font-semibold sm:text-5xl lg:text-6xl">{!! $taglineHtml !!}</h1>
            <p class="animasi-masuk max-w-2xl text-base leading-relaxed text-[#EDE7D6] sm:text-lg" style="--urut: 2">{{ $p['deskripsi'] }}</p>
            <a href="#lokasi" class="animasi-masuk flex items-center gap-2 self-start text-sm text-[#EDE7D6] underline-offset-4 hover:underline" style="--urut: 3">
                <x-ikon name="lokasi" class="size-[18px] shrink-0" /> {{ $p['lokasi'] }}, Indramayu
            </a>

            @if (! $acaraLewat)
                <div class="animasi-masuk grid max-w-md grid-cols-4 gap-2" style="--urut: 4" data-countdown="{{ $waktuAcara->toIso8601String() }}" data-reload-when-done
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
                <div class="animasi-masuk flex max-w-2xl flex-col gap-2" style="--urut: 4">
                    <span class="text-sm font-semibold">Dokumentasi Campus Expo 2027</span>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($dokumentasi->take(3) as $foto)
                            <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Dokumentasi acara' }}" loading="lazy" class="aspect-[4/3] w-full rounded-xl object-cover">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="animasi-masuk flex max-w-md gap-2" style="--urut: 5">
                <a href="#kampus" class="tombol-utama flex-1">Lihat Kampus <x-ikon name="panah" class="size-[18px]" /></a>
                @if ($acaraLewat)
                    <a href="#galeri" class="tombol flex-1 border border-krem text-krem hover:bg-white/10">Galeri</a>
                @else
                    <a href="#jadwal" class="tombol flex-1 border border-krem text-krem hover:bg-white/10">Jadwal Acara</a>
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
                <a href="#{{ $kunci }}" class="kartu flex flex-col gap-0.5 px-3 py-3.5 transition-colors duration-200 hover:border-garis-tua sm:px-5" data-reveal>
                    <dd class="font-serif text-3xl font-bold text-zaitun tabular-nums" data-hitung="{{ $jumlah[$kunci] }}">{{ $jumlah[$kunci] }}</dd>
                    <dt class="order-last text-[13px] text-redup">{{ $label }}</dt>
                </a>
            @endforeach
        </dl>
    </section>

    @include('publik.bagian.pengumuman')
    @include('publik.bagian.kampus')
    @include('publik.bagian.anggota')
    @include('publik.bagian.divisi')
    @include('publik.bagian.jadwal')
    @include('publik.bagian.galeri')
    @include('publik.bagian.faq')
    @include('publik.bagian.lokasi')
@endsection
