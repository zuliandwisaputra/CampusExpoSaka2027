{{-- Kartu anggota: foto 1:1 (object-fit cover), nama, kampus, jurusan, angkatan, divisi, jabatan.
     Ikon Instagram/LinkedIn hanya muncul kalau datanya diisi, dan dibuka di tab baru. --}}
<article class="kartu flex flex-col overflow-hidden">
    <img src="{{ $a->foto_url }}" alt="Foto {{ $a->nama }}" width="600" height="600" loading="lazy" decoding="async"
         class="aspect-square w-full bg-foto object-cover">
    <div class="flex flex-1 flex-col gap-1 p-3 sm:p-4">
        <span @class([
            'self-start rounded-full px-2 py-0.5 text-[11px] font-semibold',
            'bg-rose-muda text-mauve-tua' => $a->is_ketua,
            'bg-krem-tua text-redup' => ! $a->is_ketua,
        ])>{{ $a->jabatan }}</span>
        <h3 class="mt-1 text-base leading-snug font-semibold">{{ $a->nama }}</h3>
        @if ($a->kampus)
            <a href="{{ route('kampus.show', $a->kampus) }}" class="text-[13px] leading-snug text-redup underline-offset-2 hover:underline">{{ $a->kampus->nama }}</a>
        @endif
        @if ($a->jurusan || $a->angkatan)
            <span class="text-xs text-redup">{{ collect([$a->jurusan, $a->angkatan ? 'Angkatan '.$a->angkatan : null])->filter()->implode(' · ') }}</span>
        @endif
        @if (($tampilDivisi ?? true) && $a->divisi)
            <span class="text-xs font-medium text-zaitun">{{ $a->divisi->nama }}</span>
        @endif

        @if ($a->instagram || $a->linkedin)
            <div class="mt-auto flex gap-2 pt-2">
                @if ($a->instagram)
                    <a href="{{ $a->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram {{ $a->nama }}"
                       class="flex size-11 items-center justify-center rounded-xl bg-krem-tua text-zaitun transition-colors duration-200 hover:bg-garis">
                        <x-ikon name="instagram" />
                    </a>
                @endif
                @if ($a->linkedin)
                    <a href="{{ $a->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn {{ $a->nama }}"
                       class="flex size-11 items-center justify-center rounded-xl bg-krem-tua text-zaitun transition-colors duration-200 hover:bg-garis">
                        <x-ikon name="linkedin" />
                    </a>
                @endif
            </div>
        @endif
    </div>
</article>
