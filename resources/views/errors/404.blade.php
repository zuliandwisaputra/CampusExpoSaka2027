@extends('layouts.publik')

@section('judul', 'Halaman tidak ditemukan')

@section('isi')
    <div class="wadah flex max-w-xl flex-col items-start gap-4 py-16">
        <span class="font-serif text-6xl font-bold text-zaitun-brand">404</span>
        <h1 class="font-serif text-3xl font-semibold">Halaman tidak ditemukan</h1>
        <p class="text-redup">Mungkin alamatnya salah ketik, atau halamannya sudah dipindah panitia.</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('beranda') }}" class="tombol-utama">Ke beranda</a>
            <a href="{{ route('kampus.index') }}" class="tombol-garis">Lihat kampus</a>
        </div>
    </div>
@endsection
