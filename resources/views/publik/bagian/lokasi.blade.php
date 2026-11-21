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

            {{-- Ilustrasi peta (SVG ringan, tanpa gambar). Sekolah selalu di tengah, jadi pin HTML di 50%/50% tepat di atasnya. --}}
            <div class="relative isolate min-h-72 overflow-hidden bg-[#EEE9D8] md:min-h-[360px]" data-peta>
                <svg class="absolute inset-0 -z-10 size-full" viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <defs>
                        <pattern id="peta-sawah" width="7" height="7" patternUnits="userSpaceOnUse" patternTransform="rotate(-24)">
                            <rect width="7" height="7" fill="#DDE3BD"/><path d="M0 3.5h7" stroke="#CBD5A3" stroke-width="2"/>
                        </pattern>
                        <pattern id="peta-sawah-muda" width="7" height="7" patternUnits="userSpaceOnUse" patternTransform="rotate(16)">
                            <rect width="7" height="7" fill="#E7E8C9"/><path d="M0 3.5h7" stroke="#D6DCB2" stroke-width="2"/>
                        </pattern>
                        <radialGradient id="peta-sorot">
                            <stop offset="0" stop-color="#FFFDF5" stop-opacity=".95"/><stop offset="1" stop-color="#FFFDF5" stop-opacity="0"/>
                        </radialGradient>
                    </defs>

                    {{-- Sawah --}}
                    <path d="M0 0H112V124H0Z" fill="url(#peta-sawah)"/>
                    <path d="M300 0H400V150H310Z" fill="url(#peta-sawah-muda)"/>
                    <path d="M0 232H104V300H0Z" fill="url(#peta-sawah-muda)"/>
                    <path d="M252 226H400V300H262Z" fill="url(#peta-sawah)"/>
                    <path d="M150 0H276V96H156Z" fill="url(#peta-sawah-muda)" opacity=".7"/>

                    {{-- Saluran irigasi --}}
                    <path d="M72 -10C60 40 98 70 84 112S36 162 -10 172" stroke="#BCD5D8" stroke-width="9" fill="none" stroke-linecap="round"/>
                    <path d="M72 -10C60 40 98 70 84 112S36 162 -10 172" stroke="#D9E9EA" stroke-width="2" fill="none" stroke-linecap="round"/>

                    {{-- Jalan kecil --}}
                    <g stroke="#D8CFB8" stroke-width="10" fill="none"><path d="M120 -10V210M302 196V310M302 96H410M200 190V202"/></g>
                    <g stroke="#FBF8F0" stroke-width="7" fill="none"><path d="M120 -10V210M302 196V310M302 96H410M200 190V202"/></g>

                    {{-- Permukiman --}}
                    <g fill="#E1D6BE">
                        <rect x="14" y="166" width="30" height="20" rx="3"/><rect x="50" y="162" width="26" height="24" rx="3"/><rect x="132" y="160" width="20" height="24" rx="3"/>
                        <rect x="262" y="158" width="26" height="22" rx="3"/><rect x="316" y="152" width="30" height="22" rx="3"/><rect x="352" y="146" width="26" height="22" rx="3"/>
                        <rect x="20" y="214" width="34" height="14" rx="3"/><rect x="134" y="218" width="30" height="20" rx="3"/><rect x="176" y="220" width="24" height="18" rx="3"/>
                        <rect x="214" y="218" width="30" height="20" rx="3"/><rect x="316" y="212" width="28" height="18" rx="3"/><rect x="132" y="108" width="22" height="18" rx="3"/>
                        <rect x="316" y="108" width="34" height="20" rx="3"/><rect x="356" y="108" width="30" height="24" rx="3"/>
                    </g>

                    {{-- Jalan Raya Pantura --}}
                    <path id="peta-pantura" d="M-10 214C100 206 150 202 200 202S320 198 410 186" stroke="#D3C9AE" stroke-width="22" fill="none"/>
                    <path d="M-10 214C100 206 150 202 200 202S320 198 410 186" stroke="#FFFDF8" stroke-width="18" fill="none"/>
                    <path d="M-10 214C100 206 150 202 200 202S320 198 410 186" stroke="#E2C470" stroke-width="1.6" stroke-dasharray="10 9" fill="none"/>
                    <text font-family="Inter Variable, sans-serif" font-size="7.5" font-weight="600" letter-spacing="2.2" fill="#8C8667" dy="-13">
                        <textPath href="#peta-pantura" startOffset="6%">JL. RAYA PANTURA</textPath>
                    </text>

                    {{-- Pepohonan --}}
                    <g fill="#A3AE74">
                        <circle cx="96" cy="150" r="7"/><circle cx="106" cy="142" r="5"/><circle cx="150" cy="150" r="6"/><circle cx="262" cy="134" r="6"/>
                        <circle cx="274" cy="142" r="5"/><circle cx="64" cy="246" r="6"/><circle cx="214" cy="256" r="7"/><circle cx="226" cy="262" r="5"/>
                        <circle cx="336" cy="248" r="6"/><circle cx="384" cy="70" r="7"/><circle cx="176" cy="40" r="6"/><circle cx="246" cy="56" r="5"/>
                    </g>
                    <g fill="#8C985B"><circle cx="102" cy="152" r="4"/><circle cx="270" cy="134" r="3.5"/><circle cx="220" cy="254" r="4"/><circle cx="180" cy="44" r="3.5"/></g>

                    {{-- Rute titik-titik menuju sekolah --}}
                    <path d="M-10 214C100 206 150 202 196 202L200 188" stroke="#A65F5F" stroke-width="3" stroke-dasharray="0.1 7" stroke-linecap="round" fill="none"/>

                    {{-- Kompleks SMAN 1 Kandanghaur --}}
                    <circle cx="204" cy="158" r="76" fill="url(#peta-sorot)"/>
                    <rect x="160" y="134" width="96" height="54" rx="7" fill="#DCD5B3" stroke="#948A4E" stroke-width="1.2" stroke-dasharray="3 3"/>
                    <rect x="226" y="142" width="24" height="38" rx="3" fill="#B6C388"/>
                    <path d="M226 161H250M238 142V180" stroke="#E9EED7" stroke-width="1.2"/>
                    <circle cx="238" cy="161" r="4" fill="none" stroke="#E9EED7" stroke-width="1.2"/>
                    <rect x="180" y="152" width="40" height="26" rx="2" fill="#FFFDF8" stroke="#5C5A2C" stroke-width="1.4"/>
                    <path d="M175 154L200 139L225 154Z" fill="#A65F5F"/>
                    <g fill="#5C5A2C" opacity=".55"><rect x="185" y="158" width="6" height="6" rx="1"/><rect x="209" y="158" width="6" height="6" rx="1"/><rect x="196" y="164" width="8" height="14" rx="1"/></g>
                    <path d="M168 178V156" stroke="#5C5A2C" stroke-width="1.2"/><path d="M168 156h9v3h-9z" fill="#C4555E"/><path d="M168 159h9v3h-9z" fill="#FFFDF8"/>
                </svg>

                {{-- Keterangan wilayah dan kompas --}}
                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-tinta shadow-sm">
                    <x-ikon name="lokasi" class="size-3.5 text-mauve" /> Kandanghaur, Indramayu
                </span>
                <span class="absolute top-3 right-3 flex size-11 flex-col items-center justify-center rounded-full bg-white/90 shadow-sm" aria-hidden="true">
                    <span class="text-[9px] leading-none font-bold text-mauve-tua">U</span>
                    <svg viewBox="0 0 24 24" class="size-5"><path d="M12 3l3.2 9H8.8z" fill="#A65F5F"/><path d="M12 21l-3.2-9h6.4z" fill="#C9C1A6"/></svg>
                </span>

                {{-- Pin lokasi: jatuh saat bagian ini muncul, lalu "berdenyut" di titik sekolah. --}}
                <div class="peta-pin absolute top-1/2 left-1/2 flex -translate-x-1/2 -translate-y-full flex-col items-center" aria-hidden="true">
                    <span class="mb-1.5 rounded-lg bg-tinta px-2.5 py-1 text-xs font-semibold whitespace-nowrap text-white shadow">SMAN 1 Kandanghaur</span>
                    {{-- Ujung pin (sudut kanan bawah yang diputar 45°) keluar ±10px dari kotaknya, jadi diberi margin negatif supaya tepat di tengah. --}}
                    <span class="-mb-2.5 flex size-11 rotate-45 items-center justify-center rounded-full rounded-br-none bg-mauve shadow-lg ring-4 ring-white/80">
                        <span class="-rotate-45"><x-ikon name="kampus" class="size-5 text-white" /></span>
                    </span>
                </div>
                <span class="absolute top-1/2 left-1/2 size-6 -translate-x-1/2 -translate-y-1/2 rounded-full" aria-hidden="true">
                    <span class="denyut absolute inset-0 rounded-full bg-mauve/60"></span>
                </span>

                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-linear-to-t from-zaitun-gelap/25 to-transparent" aria-hidden="true"></div>

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
