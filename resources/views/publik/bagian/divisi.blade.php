{{-- Divisi: kartu digeser ke samping berisi tugas dan koordinator. Tombolnya menyaring daftar anggota ke divisi itu. --}}
<section id="divisi" class="scroll-mt-20 bg-krem-tua/60 py-12 sm:py-16" aria-labelledby="judul-divisi">
    <div class="wadah flex flex-col gap-5">
        @include('partials.kepala-bagian', [
            'id' => 'judul-divisi', 'label' => 'Divisi', 'judul' => 'Struktur kepanitiaan',
            'pengantar' => $divisi->count().' divisi bekerja sama menyiapkan Campus Expo 2027.',
        ])

        <x-geser id="geser-divisi" label="Daftar divisi" kecepatan="50">
            @foreach ($divisi as $d)
                @php $koordinator = $d->koordinator(); @endphp
                <article class="kartu flex flex-col gap-3 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="font-serif text-xl leading-snug font-semibold">{{ $d->nama }}</h3>
                        <span class="shrink-0 rounded-full bg-krem-tua px-2.5 py-1 text-xs font-semibold text-redup">{{ $d->anggota->count() }} orang</span>
                    </div>
                    @if ($d->deskripsi)
                        <p class="text-[15px] leading-relaxed text-redup">{{ $d->deskripsi }}</p>
                    @endif
                    @if ($koordinator)
                        <div class="flex items-center gap-3 rounded-xl bg-krem p-3">
                            <img src="{{ $koordinator->foto_url }}" alt="Foto {{ $koordinator->nama }}" width="48" height="48" loading="lazy" class="size-12 rounded-full bg-foto object-cover">
                            <div class="flex min-w-0 flex-col">
                                <span class="text-xs font-semibold text-mauve-tua">Koordinator</span>
                                <span class="font-semibold">{{ $koordinator->nama }}</span>
                                <span class="truncate text-xs text-redup">{{ $koordinator->kampus?->nama }}</span>
                            </div>
                        </div>
                    @endif
                    <a href="#anggota" class="tombol-garis mt-auto" data-pilih-divisi="{{ $d->id }}">Lihat anggota divisi <x-ikon name="panah" class="size-[18px]" /></a>
                </article>
            @endforeach
        </x-geser>
    </div>
</section>
