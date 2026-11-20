@php
    $p = \App\Models\Pengaturan::semua();
    $judulHalaman = trim($__env->yieldContent('judul'));
    $judulLengkap = $judulHalaman !== '' ? $judulHalaman.' · '.$p['nama_acara'] : $p['nama_acara'];
    $deskripsi = trim($__env->yieldContent('deskripsi')) ?: ($p['deskripsi'] ?? '');
    $gambar = trim($__env->yieldContent('gambar')) ?: asset(\App\Support\Media::DUMMY['pratinjau']);
    $diBeranda = request()->routeIs('beranda');
    // Situs one page: menu menggulir ke bagian di halaman utama.
    $menu = [
        ['beranda', 'Beranda'],
        ['pengumuman', 'Pengumuman'],
        ['kampus', 'Kampus'],
        ['anggota', 'Anggota'],
        ['divisi', 'Divisi'],
        ['jadwal', 'Jadwal'],
        ['galeri', 'Galeri'],
        ['faq', 'FAQ'],
        ['lokasi', 'Lokasi'],
    ];
    $tautan = fn (string $id) => ($diBeranda ? '' : route('beranda')).'#'.$id;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3E4720">
    <title>{{ $judulLengkap }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($deskripsi, 160) }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Judul, deskripsi, dan gambar pratinjau saat link dibagikan ke WhatsApp. --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $p['nama_acara'] }}">
    <meta property="og:title" content="{{ $judulLengkap }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($deskripsi, 200) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $gambar }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">

    {{-- Tandai bahwa JavaScript aktif untuk animasi scroll. Kalau skrip utama gagal dimuat,
         semua elemen tetap ditampilkan setelah 2,5 detik. --}}
    <script>document.documentElement.classList.add('js');setTimeout(function(){if(!window.ceAnimasi)document.documentElement.classList.add('tanpa-animasi')},2500);</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-dvh flex-col">
    <a href="#isi" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-white focus:px-4 focus:py-3 focus:font-semibold">Langsung ke isi</a>

    <header class="sticky top-0 z-30 border-b border-garis bg-krem/95 backdrop-blur" data-header>
        <div class="wadah flex items-center justify-between gap-4 py-3">
            <a href="{{ $tautan('beranda') }}" class="flex flex-col leading-tight">
                <span class="font-serif text-[21px] font-bold">Campus Expo</span>
                <span class="text-xs font-semibold tracking-[0.12em] text-zaitun">SMAN 1 KANDANGHAUR · 2027</span>
            </a>

            <nav aria-label="Navigasi utama" class="hidden xl:block">
                <ul class="flex items-center gap-0.5">
                    @foreach ($menu as [$id, $label])
                        <li>
                            <a href="{{ $tautan($id) }}" data-nav="{{ $id }}"
                               class="inline-flex min-h-11 items-center rounded-xl px-3 text-[15px] font-medium text-tinta transition-colors duration-200 hover:bg-krem-tua">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <button type="button" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white xl:hidden" data-menu-toggle aria-controls="menu-hp" aria-expanded="false" aria-label="Buka menu">
                <x-ikon name="menu" class="size-[22px]" />
            </button>
        </div>

        <nav id="menu-hp" aria-label="Navigasi utama" class="max-h-[calc(100dvh-72px)] overflow-y-auto border-t border-garis xl:hidden" hidden>
            <ul class="wadah grid grid-cols-2 gap-2 py-3 sm:grid-cols-3">
                @foreach ($menu as [$id, $label])
                    <li>
                        <a href="{{ $tautan($id) }}" data-nav="{{ $id }}" data-tutup-menu
                           class="flex min-h-12 items-center rounded-xl border border-garis bg-white px-4 font-medium">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </header>

    <main id="isi" class="flex-1">
        @yield('isi')
    </main>

    <footer class="bg-zaitun-tua text-krem">
        <div aria-hidden="true" class="flex h-2.5">
            <div class="flex-[5] bg-zaitun-brand"></div>
            <div class="flex-[3] bg-rose"></div>
            <div class="flex-1 bg-mauve"></div>
        </div>
        <div class="wadah grid gap-8 py-10 md:grid-cols-[1.4fr_1fr]">
            <div class="flex flex-col gap-3">
                <span class="font-serif text-2xl font-semibold">Campus Expo 2027</span>
                <p class="max-w-md text-sm leading-relaxed text-[#E4DEC9]">
                    {{ $p['penyelenggara'] }}<br>{{ $p['alamat'] }}
                </p>
                <a href="{{ $tautan('lokasi') }}" class="inline-flex min-h-10 items-center gap-1.5 self-start text-sm font-semibold text-rose-terang hover:text-white"><x-ikon name="lokasi" class="size-4" /> Lihat peta lokasi</a>
                <div class="flex flex-wrap gap-2">
                    @if ($p['instagram'])
                        <a href="https://www.instagram.com/{{ $p['instagram'] }}/" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/25 px-3 text-sm hover:bg-white/10">
                            <x-ikon name="instagram" /> {{ '@'.$p['instagram'] }}
                        </a>
                    @endif
                    @if ($p['email'])
                        <a href="mailto:{{ $p['email'] }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/25 px-3 text-sm break-all hover:bg-white/10">
                            <x-ikon name="email" /> Email panitia
                        </a>
                    @endif
                </div>
            </div>
            <nav aria-label="Navigasi bawah">
                <ul class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm sm:grid-cols-3">
                    @foreach ($menu as [$id, $label])
                        <li><a href="{{ $tautan($id) }}" class="inline-flex min-h-10 items-center text-[#E4DEC9] hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="border-t border-white/15">
            <p class="wadah py-4 text-xs text-[#E4DEC9]">&copy; {{ date('Y') }} Panitia Campus Expo SMAN 1 Kandanghaur. “{{ $p['tagline'] }}”</p>
        </div>
    </footer>

    @stack('dialog')
</body>
</html>
