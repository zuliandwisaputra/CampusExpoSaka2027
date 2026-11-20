<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk · Admin Campus Expo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-dvh items-center justify-center px-4 py-10">
    <main class="w-full max-w-sm">
        <a href="{{ route('beranda') }}" class="mb-6 flex flex-col items-center text-center leading-tight">
            <span class="font-serif text-3xl font-bold">Campus Expo</span>
            <span class="text-xs font-semibold tracking-[0.12em] text-zaitun">SMAN 1 KANDANGHAUR · 2027</span>
        </a>

        <div class="kartu p-6">
            <h1 class="font-serif text-2xl font-semibold">Masuk panel admin</h1>
            <p class="mt-1 text-sm text-redup">Khusus panitia. Akun dibuat oleh super admin.</p>

            @if (session('status'))
                <p class="mt-4 rounded-xl bg-[#EEF6EA] px-3 py-2 text-sm text-[#24451C]" role="status">{{ session('status') }}</p>
            @endif

            <form method="post" action="{{ route('admin.login.store') }}" class="mt-5 flex flex-col gap-4" data-lock>
                @csrf
                <x-admin.input name="email" label="Email" type="email" autocomplete="username" required autofocus />
                <x-admin.input name="password" label="Password" type="password" autocomplete="current-password" required />
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="ingat" value="1" class="size-4 accent-[#5C5A2C]"> Ingat saya di perangkat ini
                </label>
                <button type="submit" class="tombol-zaitun w-full">Masuk</button>
            </form>
        </div>
    </main>
</body>
</html>
