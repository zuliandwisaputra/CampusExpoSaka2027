{{-- Kartu kampus: logo (object-fit contain), nama, jenis, dan kota. Ketuk untuk membuka detail di pop-up. --}}
<a href="#kampus/{{ $k->slug }}" data-buka-dialog="dialog-kampus-{{ $k->id }}"
   data-item data-cari="{{ \Illuminate\Support\Str::lower($k->nama.' '.$k->singkatan.' '.$k->kota.' '.$k->provinsi) }}"
   data-jenis="{{ $k->jenis }}" data-provinsi="{{ $k->provinsi }}"
   @class(['kartu flex items-center gap-3.5 p-3 transition-colors duration-200 hover:border-garis-tua hover:bg-[#FFFDF8]', 'lebih' => $lebih ?? false]) data-reveal>
    <img src="{{ $k->logo_url }}" alt="Logo {{ $k->nama }}" width="64" height="64" loading="lazy" decoding="async"
         class="size-16 shrink-0 rounded-xl bg-krem-tua object-contain p-1">
    <span class="flex min-w-0 flex-1 flex-col gap-1">
        <span class="text-base leading-snug font-semibold">{{ $k->nama }}</span>
        <span class="text-[13px] text-redup">
            {{ $k->jenis_label }}@if ($k->lokasi) · {{ $k->lokasi }}@endif
            @if ($k->relationLoaded('anggota') && $k->anggota->isNotEmpty()) · {{ $k->anggota->count() }} alumni @endif
        </span>
    </span>
    <x-ikon name="kanan" class="size-5 shrink-0 text-redup" />
</a>
