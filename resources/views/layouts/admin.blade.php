@php
    $user = auth()->user();
    $menu = [
        ['admin.dashboard', 'Dashboard', 'dasbor', true],
        ['admin.anggota.index', 'Anggota', 'orang', true],
        ['admin.kampus.index', 'Kampus', 'kampus', true],
        ['admin.divisi.index', 'Divisi', 'divisi', true],
        ['admin.jadwal.index', 'Jadwal', 'jam', true],
        ['admin.pengumuman.index', 'Pengumuman', 'pengumuman', true],
        ['admin.galeri.index', 'Galeri', 'galeri', true],
        ['admin.faq.index', 'FAQ', 'tanya', true],
        ['admin.pengaturan.edit', 'Pengaturan acara', 'pengaturan', $user->isSuperAdmin()],
        ['admin.akun.index', 'Akun admin', 'kunci', $user->isSuperAdmin()],
    ];
    $aktif = fn (string $route) => request()->routeIs(str_replace(['.index', '.edit'], '.*', $route));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('judul', 'Panel Admin') · Admin Campus Expo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh">
    <header class="bg-zaitun-tua text-krem">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col leading-tight">
                <span class="font-serif text-lg font-bold">Admin Campus Expo</span>
                <span class="text-xs text-[#E4DEC9]">{{ $user->name }} · {{ $user->role_label }}@if ($user->divisi) ({{ $user->divisi->nama }})@endif</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('beranda') }}" target="_blank" rel="noopener" class="hidden min-h-11 items-center gap-1.5 rounded-xl px-3 text-sm hover:bg-white/10 sm:inline-flex">
                    <x-ikon name="luar" class="size-4" /> Lihat situs
                </a>
                <form method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex min-h-11 items-center gap-1.5 rounded-xl border border-white/25 px-3 text-sm hover:bg-white/10">
                        <x-ikon name="keluar" class="size-4" /> Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-4 sm:px-6 lg:flex-row lg:py-8">
        <nav aria-label="Menu admin" class="-mx-4 overflow-x-auto px-4 lg:mx-0 lg:w-56 lg:shrink-0 lg:overflow-visible lg:px-0">
            <ul class="flex gap-2 lg:flex-col lg:gap-1">
                @foreach ($menu as [$route, $label, $ikon, $boleh])
                    @continue(! $boleh)
                    <li>
                        <a href="{{ route($route) }}" @class([
                            'flex min-h-11 items-center gap-2.5 rounded-xl px-3 text-sm font-medium whitespace-nowrap transition-colors duration-200',
                            'bg-zaitun text-white' => $aktif($route),
                            'border border-garis bg-white text-tinta hover:bg-krem-tua lg:border-transparent lg:bg-transparent' => ! $aktif($route),
                        ]) @if ($aktif($route)) aria-current="page" @endif>
                            <x-ikon :name="$ikon" class="size-[18px]" /> {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <main class="min-w-0 flex-1">
            @if (session('status'))
                <div class="mb-4 flex items-start gap-2 rounded-2xl border border-[#BFD8B8] bg-[#EEF6EA] px-4 py-3 text-sm text-[#24451C]" role="status">
                    <x-ikon name="centang" class="mt-0.5 size-4 shrink-0" /> {{ session('status') }}
                </div>
            @endif
            @error('hapus')
                <div class="mb-4 flex items-start gap-2 rounded-2xl border border-[#E8C4C4] bg-rose-muda px-4 py-3 text-sm text-mauve-tua" role="alert">
                    <x-ikon name="peringatan" class="mt-0.5 size-4 shrink-0" /> {{ $message }}
                </div>
            @enderror

            @yield('isi')
        </main>
    </div>
</body>
</html>
