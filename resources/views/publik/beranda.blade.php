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
             fetchpriority="high" decoding="async" data-paralaks class="absolute inset-0 -z-20 size-full object-cover object-center">
        <div class="absolute inset-0 -z-10 bg-[rgba(46,44,20,0.6)]"></div>

        <div class="wadah flex flex-col gap-4 pt-16 pb-8 text-krem sm:pb-12">
            <span class="animasi-masuk self-start rounded-full bg-rose px-3 py-1.5 text-[13px] font-semibold text-tinta" style="--urut: 0">{{ $tanggal }}@if ($jam) · {{ $jam }}@endif</span>
            <h1 style="--urut: 1" class="animasi-masuk max-w-3xl font-serif text-[38px] leading-[1.12] font-semibold sm:text-5xl lg:text-6xl">{!! $taglineHtml !!}</h1>
            <p class="animasi-masuk max-w-2xl text-base leading-relaxed text-[#EDE7D6] sm:text-lg" style="--urut: 2">{{ $p['deskripsi'] }}</p>
            <p class="animasi-masuk flex items-center gap-2 text-sm text-[#EDE7D6]" style="--urut: 3">
                <x-ikon name="lokasi" class="size-[18px] shrink-0" /> {{ $p['lokasi'] }}, Indramayu
            </p>

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
                <div class="kartu flex flex-col gap-0.5 px-3 py-3.5 sm:px-5" data-reveal>
                    <dd class="font-serif text-3xl font-bold text-zaitun tabular-nums" data-hitung="{{ $jumlah[$kunci] }}">{{ $jumlah[$kunci] }}</dd>
                    <dt class="order-last text-[13px] text-redup">{{ $label }}</dt>
                </div>
            @endforeach
        </dl>
    </section>

    <div class="wadah grid gap-10 py-10 lg:grid-cols-2 lg:gap-12">
        {{-- Pengumuman terbaru --}}
        <section class="flex flex-col gap-3" aria-labelledby="judul-pengumuman">
            <div class="flex items-baseline justify-between gap-4" data-reveal>
                <h2 id="judul-pengumuman" class="judul-bagian">Pengumuman</h2>
                <a href="{{ route('pengumuman.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Lihat semua</a>
            </div>
            @forelse ($pengumuman as $item)
                <a href="{{ route('pengumuman.show', $item) }}" class="kartu flex flex-col gap-1.5 p-4 transition-colors duration-200 hover:border-garis-tua" data-reveal>
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
            <div class="flex items-baseline justify-between gap-4" data-reveal>
                <h2 id="judul-kampus" class="judul-bagian">Kampus yang hadir</h2>
                <a href="{{ route('kampus.index') }}" class="text-sm font-semibold text-zaitun hover:underline">Semua {{ $jumlah['kampus'] }}</a>
            </div>
            <form action="{{ route('kampus.index') }}" method="get" role="search" class="relative" data-reveal>
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
            <div class="flex items-baseline justify-between gap-4" data-reveal>
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

    {{-- Lokasi SMAN 1 Kandanghaur. Peta Google baru dimuat saat diketuk supaya beranda tetap ringan. --}}
    <section id="lokasi" class="wadah scroll-mt-24 pt-6 pb-4" aria-labelledby="judul-lokasi">
        <div class="kartu grid overflow-hidden md:grid-cols-[1fr_1.3fr]" data-reveal>
            <div class="flex flex-col gap-4 p-5 sm:p-7">
                <span class="text-xs font-semibold tracking-[0.14em] text-zaitun uppercase">Lokasi acara</span>
                <h2 id="judul-lokasi" class="judul-bagian">{{ $p['lokasi'] }}</h2>
                <ul class="flex flex-col gap-3 text-[15px] leading-relaxed text-redup">
                    <li class="flex gap-2.5"><x-ikon name="lokasi" class="mt-0.5 size-5 shrink-0 text-zaitun" /> {{ $p['alamat'] }}</li>
                    <li class="flex gap-2.5"><x-ikon name="kalender" class="mt-0.5 size-5 shrink-0 text-zaitun" /> {{ $tanggal }}@if ($jam) · {{ $jam }}@endif</li>
                </ul>
                <div class="mt-auto flex flex-wrap gap-2 pt-2">
                    <a href="{{ \App\Models\Pengaturan::petaArahUrl() }}" target="_blank" rel="noopener noreferrer" class="tombol-utama flex-1 whitespace-nowrap">
                        <x-ikon name="panah" class="size-[18px]" /> Petunjuk arah
                    </a>
                    <a href="{{ \App\Models\Pengaturan::petaUrl() }}" target="_blank" rel="noopener noreferrer" class="tombol-garis flex-1 whitespace-nowrap">
                        <x-ikon name="luar" class="size-[18px]" /> Buka Google Maps
                    </a>
                </div>
            </div>

            <div class="relative isolate min-h-72 overflow-hidden bg-krem-tua md:min-h-[360px]" data-peta>
                <svg class="absolute inset-0 -z-10 size-full" viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <rect width="400" height="300" fill="#EFE9DA"/>
                    <g fill="#E4DCC8">
                        <rect x="18" y="20" width="70" height="52" rx="6"/><rect x="104" y="20" width="96" height="52" rx="6"/>
                        <rect x="236" y="14" width="64" height="58" rx="6"/><rect x="316" y="20" width="70" height="52" rx="6"/>
                        <rect x="18" y="186" width="96" height="44" rx="6"/><rect x="130" y="186" width="58" height="96" rx="6"/>
                        <rect x="252" y="190" width="54" height="40" rx="6"/><rect x="322" y="186" width="64" height="96" rx="6"/>
                        <rect x="18" y="246" width="96" height="40" rx="6"/><rect x="252" y="246" width="54" height="40" rx="6"/>
                    </g>
                    <path d="M0 132 H400 M0 152 H400" stroke="#F6F2E9" stroke-width="16"/>
                    <path d="M0 142 H400" stroke="#D8CFB8" stroke-width="2" stroke-dasharray="10 10"/>
                    <path d="M220 0 V300 M120 300 C 150 220, 160 180, 220 152" stroke="#F6F2E9" stroke-width="12" fill="none"/>
                    <path d="M60 300 C 70 250, 40 210, 0 196" stroke="#C9DDE0" stroke-width="10" fill="none"/>
                    <rect x="222" y="78" width="78" height="46" rx="8" fill="#948A4E" opacity=".35"/>
                </svg>

                <div class="absolute top-[38%] left-[64%] flex -translate-x-1/2 -translate-y-full flex-col items-center" aria-hidden="true">
                    <span class="relative flex size-12 items-center justify-center rounded-full bg-mauve text-white shadow-lg">
                        <span class="denyut absolute inset-0 rounded-full bg-mauve"></span>
                        <x-ikon name="lokasi" class="relative size-6" />
                    </span>
                    <span class="mt-1.5 rounded-lg bg-white px-2 py-1 text-xs font-semibold whitespace-nowrap text-tinta shadow">SMAN 1 Kandanghaur</span>
                </div>

                <button type="button" data-peta-muat data-src="{{ \App\Models\Pengaturan::petaEmbedUrl() }}" data-judul="Peta lokasi {{ $p['lokasi'] }}"
                        class="tombol-zaitun absolute bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap shadow-lg">
                    <x-ikon name="lokasi" class="size-[18px]" /> Tampilkan peta interaktif
                </button>
                <noscript>
                    <iframe src="{{ \App\Models\Pengaturan::petaEmbedUrl() }}" title="Peta lokasi {{ $p['lokasi'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 size-full border-0"></iframe>
                </noscript>
            </div>
        </div>
    </section>
@endsection
