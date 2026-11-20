{{-- Pengumuman: 3 teratas langsung terlihat, sisanya lewat tombol. Isi lengkap dibuka di pop-up. --}}
<section id="pengumuman" class="scroll-mt-20 py-12 sm:py-16" aria-labelledby="judul-pengumuman">
    <div class="wadah flex flex-col gap-5" data-daftar="pengumuman" data-awal="3" data-tambah="0">
        @include('partials.kepala-bagian', ['id' => 'judul-pengumuman', 'label' => 'Pengumuman', 'judul' => 'Info terbaru dari panitia'])

        @if ($pengumuman->isEmpty())
            @include('partials.kosong', ['ikon' => 'pengumuman', 'judul' => 'Belum ada pengumuman', 'teks' => 'Info terbaru dari panitia akan muncul di sini.'])
        @else
            <div class="grid gap-3 md:grid-cols-3">
                @foreach ($pengumuman as $item)
                    <a href="#pengumuman/{{ $item->slug }}" data-buka-dialog="dialog-pengumuman-{{ $item->id }}" data-item
                       @class(['kartu flex flex-col gap-1.5 p-4 transition-colors duration-200 hover:border-garis-tua sm:p-5', 'lebih' => $loop->index >= 3]) data-reveal>
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
            </div>
            @if ($pengumuman->count() > 3)
                <button type="button" class="tombol-garis self-start" data-tampil-lebih>Lihat pengumuman lainnya</button>
            @endif
        @endif
    </div>
</section>
