{{-- Galeri: tiap album jadi satu baris foto yang digeser ke samping. --}}
<section id="galeri" class="scroll-mt-20 bg-krem-tua/60 py-12 sm:py-16" aria-labelledby="judul-galeri">
    <div class="wadah flex flex-col gap-8">
        @include('partials.kepala-bagian', [
            'id' => 'judul-galeri', 'label' => 'Galeri', 'judul' => 'Dokumentasi',
            'pengantar' => 'Foto persiapan dan hari H Campus Expo, dikelompokkan per album.',
        ])

        @forelse ($album as $item)
            <x-geser id="geser-album-{{ $item->id }}" label="Foto album {{ $item->judul }}" kolom="geser-foto">
                <x-slot:kepala>
                    <h3 class="font-serif text-xl font-semibold">{{ $item->judul }}</h3>
                    <p class="text-sm text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}@if ($item->tanggal) · @endif{{ $item->foto->count() }} foto</p>
                </x-slot:kepala>

                @foreach ($item->foto as $foto)
                    <a href="{{ $foto->url }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl">
                        <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan ?: 'Foto '.$item->judul }}" width="600" height="450" loading="lazy" decoding="async"
                             class="aspect-[4/3] w-full bg-foto object-cover transition-opacity duration-200 hover:opacity-90">
                    </a>
                @endforeach
            </x-geser>
        @empty
            @include('partials.kosong', ['ikon' => 'galeri', 'judul' => 'Belum ada foto', 'teks' => 'Dokumentasi akan diunggah panitia setelah acara.'])
        @endforelse
    </div>
</section>
