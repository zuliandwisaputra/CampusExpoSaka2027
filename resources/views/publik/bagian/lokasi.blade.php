{{-- Lokasi SMAN 1 Kandanghaur. Peta Google baru dimuat saat diketuk supaya halaman tetap ringan. --}}
<section id="lokasi" class="scroll-mt-20 bg-krem-tua/60 py-12 sm:py-16" aria-labelledby="judul-lokasi">
    <div class="wadah">
        <div class="kartu grid overflow-hidden md:grid-cols-[1fr_1.3fr]" data-reveal>
            <div class="flex flex-col gap-4 p-5 sm:p-7">
                <span class="text-xs font-semibold tracking-[0.14em] text-zaitun uppercase">Lokasi acara</span>
                <h2 id="judul-lokasi" class="judul-bagian">{{ $p['lokasi'] }}</h2>
                <ul class="flex flex-col gap-3 text-[15px] leading-relaxed text-redup">
                    <li class="flex gap-2.5"><x-ikon name="lokasi" class="mt-0.5 size-5 shrink-0 text-zaitun" /> {{ $p['alamat'] }}</li>
                    <li class="flex gap-2.5"><x-ikon name="kalender" class="mt-0.5 size-5 shrink-0 text-zaitun" /> {{ $tanggal }}@if ($jam) · {{ $jam }}@endif</li>
                </ul>
                <div class="mt-auto flex flex-wrap gap-2 pt-2">
                    <a href="{{ \App\Models\Pengaturan::petaArahUrl() }}" target="_blank" rel="noopener noreferrer" class="tombol-utama flex-1 whitespace-nowrap">
                        <x-ikon name="panah" class="size-[18px]" /> Petunjuk arah
                    </a>
                    <a href="{{ \App\Models\Pengaturan::petaUrl() }}" target="_blank" rel="noopener noreferrer" class="tombol-garis flex-1 whitespace-nowrap">
                        <x-ikon name="luar" class="size-[18px]" /> Buka Google Maps
                    </a>
                </div>
            </div>

            <div class="relative isolate min-h-72 overflow-hidden bg-krem-tua md:min-h-[360px]" data-peta>
                <svg class="absolute inset-0 -z-10 size-full" viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <rect width="400" height="300" fill="#EFE9DA"/>
                    <g fill="#E4DCC8">
                        <rect x="18" y="20" width="70" height="52" rx="6"/><rect x="104" y="20" width="96" height="52" rx="6"/>
                        <rect x="236" y="14" width="64" height="58" rx="6"/><rect x="316" y="20" width="70" height="52" rx="6"/>
                        <rect x="18" y="186" width="96" height="44" rx="6"/><rect x="130" y="186" width="58" height="96" rx="6"/>
                        <rect x="252" y="190" width="54" height="40" rx="6"/><rect x="322" y="186" width="64" height="96" rx="6"/>
                        <rect x="18" y="246" width="96" height="40" rx="6"/><rect x="252" y="246" width="54" height="40" rx="6"/>
                    </g>
                    <path d="M0 132 H400 M0 152 H400" stroke="#F6F2E9" stroke-width="16"/>
                    <path d="M0 142 H400" stroke="#D8CFB8" stroke-width="2" stroke-dasharray="10 10"/>
                    <path d="M220 0 V300 M120 300 C 150 220, 160 180, 220 152" stroke="#F6F2E9" stroke-width="12" fill="none"/>
                    <path d="M60 300 C 70 250, 40 210, 0 196" stroke="#C9DDE0" stroke-width="10" fill="none"/>
                    <rect x="222" y="78" width="78" height="46" rx="8" fill="#948A4E" opacity=".35"/>
                </svg>

                <div class="absolute top-[38%] left-[64%] flex -translate-x-1/2 -translate-y-full flex-col items-center" aria-hidden="true">
                    <span class="relative flex size-12 items-center justify-center rounded-full bg-mauve text-white shadow-lg">
                        <span class="denyut absolute inset-0 rounded-full bg-mauve"></span>
                        <x-ikon name="lokasi" class="relative size-6" />
                    </span>
                    <span class="mt-1.5 rounded-lg bg-white px-2 py-1 text-xs font-semibold whitespace-nowrap text-tinta shadow">SMAN 1 Kandanghaur</span>
                </div>

                <button type="button" data-peta-muat data-src="{{ \App\Models\Pengaturan::petaEmbedUrl() }}" data-judul="Peta lokasi {{ $p['lokasi'] }}"
                        class="tombol-zaitun absolute bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap shadow-lg">
                    <x-ikon name="lokasi" class="size-[18px]" /> Tampilkan peta interaktif
                </button>
                <noscript>
                    <iframe src="{{ \App\Models\Pengaturan::petaEmbedUrl() }}" title="Peta lokasi {{ $p['lokasi'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 size-full border-0"></iframe>
                </noscript>
            </div>
        </div>
    </div>
</section>
