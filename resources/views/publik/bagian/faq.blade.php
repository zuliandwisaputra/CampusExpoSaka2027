{{-- FAQ: pertanyaan umum, tampil buka-tutup. --}}
<section id="faq" class="scroll-mt-20 py-12 sm:py-16" aria-labelledby="judul-faq">
    <div class="wadah flex flex-col gap-5">
        @include('partials.kepala-bagian', ['id' => 'judul-faq', 'label' => 'FAQ', 'judul' => 'Pertanyaan umum'])

        <div class="flex max-w-3xl flex-col gap-2">
            @forelse ($faq as $item)
                <details class="kartu group" data-reveal @if ($loop->first) open @endif>
                    <summary class="flex min-h-14 list-none items-center justify-between gap-3 px-4 py-3 font-semibold [&::-webkit-details-marker]:hidden">
                        <span>{{ $item->pertanyaan }}</span>
                        <x-ikon name="kanan" class="size-5 shrink-0 text-redup transition-transform duration-200 group-open:rotate-90" />
                    </summary>
                    <div class="isi-teks border-t border-garis px-4 pt-3 pb-1 text-[15px] text-redup">
                        {{ \App\Support\Teks::paragraf($item->jawaban) }}
                    </div>
                </details>
            @empty
                @include('partials.kosong', ['ikon' => 'tanya', 'judul' => 'Belum ada pertanyaan'])
            @endforelse

            <p class="mt-4 text-sm text-redup">Masih ada pertanyaan? Hubungi panitia lewat Instagram
                <a href="https://www.instagram.com/{{ $p['instagram'] }}/" target="_blank" rel="noopener noreferrer" class="font-semibold text-zaitun hover:underline">{{ '@'.$p['instagram'] }}</a>.
            </p>
        </div>
    </div>
</section>
