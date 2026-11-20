{{-- Pengumuman: kartu digeser ke samping, terbaru dan yang disematkan lebih dulu. Isi lengkap dibuka di pop-up. --}}
<section id="pengumuman" class="scroll-mt-20 py-12 sm:py-16" aria-labelledby="judul-pengumuman">
    <div class="wadah flex flex-col gap-5">
        @include('partials.kepala-bagian', ['id' => 'judul-pengumuman', 'label' => 'Pengumuman', 'judul' => 'Info terbaru dari panitia'])

        @if ($pengumuman->isEmpty())
            @include('partials.kosong', ['ikon' => 'pengumuman', 'judul' => 'Belum ada pengumuman', 'teks' => 'Info terbaru dari panitia akan muncul di sini.'])
        @else
            <x-geser id="geser-pengumuman" label="Daftar pengumuman">
                <x-slot:kepala><p class="text-sm text-redup">{{ $pengumuman->count() }} pengumuman</p></x-slot:kepala>

                @foreach ($pengumuman as $item)
                    <a href="#pengumuman/{{ $item->slug }}" data-buka-dialog="dialog-pengumuman-{{ $item->id }}"
                       class="kartu flex flex-col gap-1.5 p-4 transition-colors duration-200 hover:border-garis-tua sm:p-5">
                        <span class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                            @if ($item->disematkan)
                                <span class="inline-flex items-center gap-1 text-mauve-tua"><x-ikon name="pin" class="size-3.5" /> Disematkan</span>
                            @endif
                            <span class="text-redup">{{ $item->tanggal?->translatedFormat('j F Y') }}</span>
                        </span>
                        <span class="text-base leading-snug font-semibold sm:text-lg">{{ $item->judul }}</span>
                        <span class="line-clamp-3 text-[15px] leading-relaxed text-redup">{{ $item->ringkasan }}</span>
                        <span class="mt-auto inline-flex items-center gap-1 pt-1 text-sm font-semibold text-zaitun">Baca selengkapnya <x-ikon name="kanan" class="size-4" /></span>
                    </a>

                    @push('dialog')
                        @include('publik.dialog.pengumuman', ['item' => $item])
                    @endpush
                @endforeach
            </x-geser>
        @endif
    </div>
</section>
