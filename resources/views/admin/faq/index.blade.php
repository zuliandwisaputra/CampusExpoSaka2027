@extends('layouts.admin')

@section('judul', 'FAQ')

@section('isi')
    <x-admin.judul judul="FAQ" pengantar="Tampil di halaman publik sesuai nomor urutan, dari kecil ke besar.">
        <a href="{{ route('admin.faq.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tambah pertanyaan</a>
    </x-admin.judul>

    <ul class="kartu divide-y divide-garis">
        @forelse ($faq as $item)
            <li class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-krem-tua text-sm font-semibold text-zaitun">{{ $item->urutan }}</span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">{{ $item->pertanyaan }}</p>
                    <p class="line-clamp-2 text-[13px] text-redup">{{ $item->jawaban }}</p>
                </div>
                <a href="{{ route('admin.faq.edit', $item) }}" class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah pertanyaan"><x-ikon name="ubah" class="size-[18px]" /></a>
                @can('super-admin')
                    <x-admin.hapus :action="route('admin.faq.destroy', $item)" pesan="Hapus pertanyaan ini?" label="Hapus pertanyaan" kecil />
                @endcan
            </li>
        @empty
            <li class="p-6 text-center text-sm text-redup">Belum ada pertanyaan.</li>
        @endforelse
    </ul>
@endsection
