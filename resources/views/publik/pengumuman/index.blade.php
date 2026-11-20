@extends('layouts.publik')

@section('judul', 'Pengumuman')
@section('deskripsi', 'Info terbaru dari panitia Campus Expo SMAN 1 Kandanghaur 2027.')

@section('isi')
    @include('partials.judul-halaman', ['label' => 'Pengumuman', 'judul' => 'Info terbaru dari panitia'])

    <div class="wadah flex max-w-3xl flex-col gap-3 py-6">
        @forelse ($pengumuman as $item)
            <a href="{{ route('pengumuman.show', $item) }}" class="kartu flex flex-col gap-1.5 p-4 transition-colors duration-200 hover:border-garis-tua sm:p-5">
                <span class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                    @if ($item->disematkan)
                        <span class="inline-flex items-center gap-1 text-mauve-tua"><x-ikon name="pin" class="size-3.5" /> Disematkan</span>
                    @endif
                    <span class="text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}</span>
                </span>
                <span class="text-lg leading-snug font-semibold">{{ $item->judul }}</span>
                <span class="text-[15px] leading-relaxed text-redup">{{ $item->ringkasan }}</span>
            </a>
        @empty
            @include('partials.kosong', ['ikon' => 'pengumuman', 'judul' => 'Belum ada pengumuman'])
        @endforelse

        {{ $pengumuman->links() }}
    </div>
@endsection
