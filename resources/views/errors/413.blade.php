<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>File terlalu besar · Campus Expo</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-dvh items-center justify-center px-4">
    <main class="kartu flex max-w-md flex-col gap-3 p-6">
        <h1 class="font-serif text-2xl font-semibold">Total file terlalu besar</h1>
        <p class="text-redup">Satu foto maksimal {{ \App\Support\ImageUploader::batasMb() }} MB, dan galeri maksimal {{ config('campus.galeri_maks_per_unggah') }} foto sekali unggah. Kembali, lalu kirim foto yang lebih kecil atau lebih sedikit.</p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/admin') }}" class="tombol-zaitun self-start">Kembali</a>
    </main>
</body>
</html>
