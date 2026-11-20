{{-- Kepala halaman publik: label kecil, judul serif, dan kalimat pengantar. --}}
<section class="border-b border-garis bg-krem-tua/60">
    <div class="wadah py-8 sm:py-10">
        @isset($label)
            <span class="text-xs font-semibold tracking-[0.14em] text-zaitun uppercase">{{ $label }}</span>
        @endisset
        <h1 class="mt-1 font-serif text-3xl leading-tight font-semibold sm:text-4xl">{{ $judul }}</h1>
        @isset($pengantar)
            <p class="mt-2 max-w-2xl text-base leading-relaxed text-redup">{{ $pengantar }}</p>
        @endisset
    </div>
</section>
