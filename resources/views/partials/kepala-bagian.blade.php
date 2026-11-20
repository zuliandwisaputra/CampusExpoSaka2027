{{-- Kepala setiap bagian di halaman one page: label kecil, judul serif, dan pengantar. --}}
<div class="flex flex-col gap-1" data-reveal>
    <span class="text-xs font-semibold tracking-[0.14em] text-zaitun uppercase">{{ $label }}</span>
    <h2 id="{{ $id }}" class="judul-bagian">{{ $judul }}</h2>
    @isset($pengantar)
        <p class="mt-1 max-w-2xl text-[15px] leading-relaxed text-redup sm:text-base">{{ $pengantar }}</p>
    @endisset
</div>
