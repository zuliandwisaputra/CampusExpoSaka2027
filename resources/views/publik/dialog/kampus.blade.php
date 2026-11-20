{{-- Detail kampus di pop-up. Alamat #kampus/{slug} langsung membuka pop-up ini dan bisa dibagikan. --}}
@php
    $tautanKampus = route('beranda').'#kampus/'.$k->slug;
    $belumDiisi = 'Belum diisi panitia. Pantau lagi menjelang hari H.';
@endphp
<dialog id="dialog-kampus-{{ $k->id }}" data-hash="kampus/{{ $k->slug }}" class="dialog" aria-labelledby="dialog-kampus-{{ $k->id }}-judul">
    <div class="flex max-h-[inherit] flex-col">
        <div class="flex items-start gap-3 border-b border-garis p-4 sm:p-5">
            <img src="{{ $k->logo_url }}" alt="Logo {{ $k->nama }}" width="64" height="64" loading="lazy" class="size-14 shrink-0 rounded-xl border border-garis bg-white object-contain p-1 sm:size-16">
            <div class="flex min-w-0 flex-1 flex-col gap-1">
                <h3 id="dialog-kampus-{{ $k->id }}-judul" class="font-serif text-xl leading-tight font-semibold sm:text-2xl">{{ $k->nama }}</h3>
                <p class="text-sm text-redup">
                    <span class="rounded-full bg-zaitun px-2 py-0.5 text-xs font-semibold text-white">{{ $k->jenis_label }}</span>
                    @if ($k->lokasi) <span class="ml-1">{{ $k->lokasi }}</span> @endif
                </p>
            </div>
            <button type="button" data-tutup-dialog class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-garis-tua bg-white hover:bg-krem-tua" aria-label="Tutup">
                <x-ikon name="x" />
            </button>
        </div>

        <div class="flex flex-col gap-5 overflow-y-auto p-4 sm:p-5">
            <section>
                <h4 class="font-semibold text-zaitun">Profil singkat</h4>
                <div class="isi-teks mt-1.5 text-[15px]">@if ($k->deskripsi) {{ \App\Support\Teks::paragraf($k->deskripsi) }} @else <p class="text-redup">{{ $belumDiisi }}</p> @endif</div>
            </section>

            <section>
                <h4 class="font-semibold text-zaitun">Jurusan</h4>
                @if ($k->jurusan->isEmpty())
                    <p class="mt-1.5 text-[15px] text-redup">{{ $belumDiisi }}</p>
                @else
                    <div class="mt-2 flex flex-col gap-3">
                        @foreach ($k->jurusan->groupBy(fn ($j) => $j->jenjang ?: 'Lainnya') as $jenjang => $daftarJurusan)
                            <div>
                                <p class="text-xs font-semibold tracking-wide text-redup uppercase">{{ $jenjang }}</p>
                                <ul class="mt-1.5 flex flex-wrap gap-1.5">
                                    @foreach ($daftarJurusan as $j)
                                        <li class="rounded-full border border-garis bg-krem px-3 py-1 text-sm">{{ $j->nama }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @foreach (['jalur_masuk' => 'Jalur masuk', 'kisaran_biaya' => 'Kisaran biaya kuliah', 'beasiswa' => 'Info beasiswa'] as $kolom => $judul)
                <section>
                    <h4 class="font-semibold text-zaitun">{{ $judul }}</h4>
                    <div class="isi-teks mt-1.5 text-[15px]">@if ($k->$kolom) {{ \App\Support\Teks::paragraf($k->$kolom) }} @else <p class="text-redup">{{ $belumDiisi }}</p> @endif</div>
                </section>
            @endforeach

            <section>
                <h4 class="font-semibold text-zaitun">Alumni yang bisa kamu tanyai ({{ $k->anggota->count() }})</h4>
                @if ($k->anggota->isEmpty())
                    <p class="mt-1.5 text-[15px] text-redup">Belum ada alumni yang ditampilkan dari kampus ini.</p>
                @else
                    <ul class="mt-2 flex flex-col divide-y divide-garis rounded-2xl border border-garis">
                        @foreach ($k->anggota as $a)
                            <li class="flex items-center gap-3 p-3">
                                <img src="{{ $a->foto_url }}" alt="Foto {{ $a->nama }}" width="48" height="48" loading="lazy" class="size-12 shrink-0 rounded-xl bg-foto object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold leading-snug">{{ $a->nama }}</p>
                                    <p class="text-xs text-redup">{{ collect([$a->jurusan, $a->angkatan ? 'Angkatan '.$a->angkatan : null, $a->jabatan.' · '.$a->divisi?->nama])->filter()->implode(' · ') }}</p>
                                </div>
                                @if ($a->instagram)
                                    <a href="{{ $a->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram {{ $a->nama }}" class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-krem-tua text-zaitun hover:bg-garis"><x-ikon name="instagram" /></a>
                                @endif
                                @if ($a->linkedin)
                                    <a href="{{ $a->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn {{ $a->nama }}" class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-krem-tua text-zaitun hover:bg-garis"><x-ikon name="linkedin" /></a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
                @if ($k->website)
                    <a href="{{ $k->website }}" target="_blank" rel="noopener noreferrer" class="tombol-garis"><x-ikon name="web" /> Website resmi</a>
                @endif
                @if ($k->instagram)
                    <a href="{{ $k->instagram_url }}" target="_blank" rel="noopener noreferrer" class="tombol-garis"><x-ikon name="instagram" /> {{ '@'.$k->instagram }}</a>
                @endif
                <a href="https://wa.me/?text={{ rawurlencode("Cek {$k->nama} di Campus Expo SMAN 1 Kandanghaur 2027: {$tautanKampus}") }}" target="_blank" rel="noopener noreferrer" class="tombol bg-[#1F7A47] text-white hover:bg-[#17633A]">
                    <x-ikon name="bagikan" /> Bagikan ke WhatsApp
                </a>
            </div>
        </div>
    </div>
</dialog>
