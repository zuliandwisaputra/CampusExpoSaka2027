@extends('layouts.admin')

@section('judul', 'Akun admin')

@section('isi')
    <x-admin.judul judul="Akun admin" pengantar="Tidak ada pendaftaran terbuka. Akun koordinator divisi dibuat di sini.">
        <a href="{{ route('admin.akun.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah akun</a>
    </x-admin.judul>

    <ul class="kartu divide-y divide-garis">
        @foreach ($akun as $a)
            <li class="flex items-center gap-3 px-4 py-3">
                <div class="min-w-0 flex-1">
                    <p class="flex flex-wrap items-center gap-2 font-semibold">
                        {{ $a->name }}
                        @if (! $a->aktif)<span class="rounded-full bg-krem-tua px-2 py-0.5 text-[11px] font-semibold text-redup">Nonaktif</span>@endif
                        @if ($a->is(auth()->user()))<span class="rounded-full bg-rose-muda px-2 py-0.5 text-[11px] font-semibold text-mauve-tua">Kamu</span>@endif
                    </p>
                    <p class="truncate text-[13px] text-redup">{{ $a->email }} · {{ $a->role_label }}@if ($a->divisi) · {{ $a->divisi->nama }}@endif</p>
                </div>
                <a href="{{ route('admin.akun.edit', $a) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah akun {{ $a->name }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                @if ($a->aktif && ! $a->is(auth()->user()))
                    <form method="post" action="{{ route('admin.akun.destroy', $a) }}" data-confirm="Nonaktifkan akun {{ $a->email }}? Akun tidak bisa login sampai diaktifkan lagi.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex size-11 items-center justify-center rounded-xl border border-[#E8C4C4] bg-white text-mauve-tua hover:bg-rose-muda" aria-label="Nonaktifkan akun {{ $a->name }}"><x-ikon name="sembunyi" class="size-[18px]" /></button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
