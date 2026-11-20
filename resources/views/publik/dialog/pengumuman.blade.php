{{-- Isi lengkap pengumuman di pop-up. Alamat #pengumuman/{slug} langsung membukanya. --}}
<dialog id="dialog-pengumuman-{{ $item->id }}" data-hash="pengumuman/{{ $item->slug }}" class="dialog" aria-labelledby="dialog-pengumuman-{{ $item->id }}-judul">
    <div class="flex max-h-[inherit] flex-col">
        <div class="flex items-start gap-3 border-b border-garis p-4 sm:p-5">
            <div class="min-w-0 flex-1">
                <p class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                    @if ($item->disematkan)
                        <span class="inline-flex items-center gap-1 text-mauve-tua"><x-ikon name="pin" class="size-3.5" /> Disematkan</span>
                    @endif
                    <span class="text-redup">{{ $item->tanggal?->translatedFormat('l, j F Y') }}</span>
                </p>
                <h3 id="dialog-pengumuman-{{ $item->id }}-judul" class="mt-1 font-serif text-xl leading-tight font-semibold sm:text-2xl">{{ $item->judul }}</h3>
            </div>
            <button type="button" data-tutup-dialog class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Tutup">
                <x-ikon name="x" />
            </button>
        </div>
        <div class="flex flex-col gap-4 overflow-y-auto p-4 sm:p-5">
            <div class="isi-teks text-[16px]">{{ \App\Support\Teks::paragraf($item->isi) }}</div>
            <a href="https://wa.me/?text={{ rawurlencode($item->judul.' — '.route('beranda').'#pengumuman/'.$item->slug) }}" target="_blank" rel="noopener noreferrer" class="tombol self-start bg-[#1F7A47] text-white hover:bg-[#17633A]">
                <x-ikon name="bagikan" /> Bagikan ke WhatsApp
            </a>
        </div>
    </div>
</dialog>
