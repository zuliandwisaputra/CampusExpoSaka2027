{{-- Galeri: per album, 6 foto pertama terlihat, sisanya lewat tombol. --}}
<section id="galeri" class="scroll-mt-20 bg-krem-tua/60 py-12 sm:py-16" aria-labelledby="judul-galeri">
    <div class="wadah flex flex-col gap-8">
        @include('partials.kepala-bagian', [
            'id' => 'judul-galeri', 'label' => 'Galeri', 'judul' => 'Dokumentasi',
            'pengantar' => 'Foto persiapan dan hari H Campus Expo, dikelompokkan per album.',
        ])

        @forelse ($album as $item)
            <div class="flex flex-col gap-3" data-daftar="album-{{ $item->id }}" data-awal="6" data-tambah="0">
                <div data-reveal>
                    <h3 class="font-serif text-xl font-semibold">{{ $item->judul }}</h3>
                    <p class="text-sm text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}@if ($item->tanggal) · @endif{{ $item->foto->count() }} foto</p>
                </div>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($item->foto as $foto)
                        <a href="{{ $foto->url }}" target="_blank" rel="noopener" data-item @class(['block overflow-hidden rounded-xl', 'lebih' => $loop->index >= 6]) data-reveal>
                            <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Foto '.$item->judul }}" width="600" height="450" loading="lazy" decoding="async"
                                 class="aspect-[4/3] w-full bg-foto object-cover transition-opacity duration-200 hover:opacity-90">
                        </a>
                    @endforeach
                </div>
                @if ($item->foto->count() > 6)
                    <button type="button" class="tombol-garis self-start" data-tampil-lebih>Lihat semua {{ $item->foto->count() }} foto</button>
                @endif
            </div>
        @empty
            @include('partials.kosong', ['ikon' => 'galeri', 'judul' => 'Belum ada foto', 'teks' => 'Dokumentasi akan diunggah panitia setelah acara.'])
        @endforelse
    </div>
</section>
