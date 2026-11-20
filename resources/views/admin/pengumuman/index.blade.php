@extends('layouts.admin')

@section('judul', 'Pengumuman')

@section('isi')
    <x-admin.judul judul="Pengumuman" pengantar="Pengumuman yang disematkan tampil paling atas. Draf tidak terlihat di halaman publik.">
        <a href="{{ route('admin.pengumuman.create') }}" class="tombol-zaitun"><x-ikon name="tambah" /> Tulis pengumuman</a>
    </x-admin.judul>

    <ul class="kartu divide-y divide-garis">
        @forelse ($pengumuman as $item)
            <li class="flex items-center gap-3 px-4 py-3">
                <div class="min-w-0 flex-1">
                    <p class="flex flex-wrap items-center gap-2 font-semibold">
                        @if ($item->disematkan)<x-ikon name="pin" class="size-4 text-mauve-tua" /><span class="sr-only">Disematkan:</span>@endif
                        {{ $item->judul }}
                    </p>
                    <p class="text-[13px] text-redup">
                        <span @class(['font-semibold', 'text-zaitun' => $item->status === 'terbit', 'text-mauve-tua' => $item->status !== 'terbit'])>{{ ucfirst($item->status) }}</span>
                        · {{ $item->terbit_pada ? ($item->terbit_pada->isFuture() ? 'terjadwal '.$item->terbit_pada->translatedFormat('j M Y H.i') : $item->terbit_pada->translatedFormat('j M Y H.i')) : 'belum terbit' }}
                    </p>
                </div>
                @if ($item->status === 'terbit')
                    <a href="{{ route('pengumuman.show', $item) }}" target="_blank" rel="noopener" class="hidden size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua sm:flex" aria-label="Lihat {{ $item->judul }}"><x-ikon name="luar" class="size-[18px]" /></a>
                @endif
                <a href="{{ route('admin.pengumuman.edit', $item) }}" class="flex size-11 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Ubah {{ $item->judul }}"><x-ikon name="ubah" class="size-[18px]" /></a>
                @can('super-admin')
                    <x-admin.hapus :action="route('admin.pengumuman.destroy', $item)" :pesan="'Hapus pengumuman '.$item->judul.'?'" :label="'Hapus '.$item->judul" kecil />
                @endcan
            </li>
        @empty
            <li class="p-6 text-center text-sm text-redup">Belum ada pengumuman.</li>
        @endforelse
    </ul>
    {{ $pengumuman->links() }}
@endsection
