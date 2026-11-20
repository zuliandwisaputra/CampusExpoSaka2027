{{-- Jadwal: rundown hari H urut waktu, acara utama ditonjolkan. --}}
<section id="jadwal" class="scroll-mt-20 py-12 sm:py-16" aria-labelledby="judul-jadwal">
    <div class="wadah flex flex-col gap-5">
        @include('partials.kepala-bagian', [
            'id' => 'judul-jadwal', 'label' => 'Jadwal', 'judul' => 'Rundown hari H',
            'pengantar' => $tanggal.' · '.$p['lokasi'],
        ])

        <div class="max-w-3xl">
            @if ($jadwal->isEmpty())
                @include('partials.kosong', ['ikon' => 'jam', 'judul' => 'Rundown segera diumumkan', 'teks' => 'Panitia sedang menyusun jam acara. Cek lagi bagian ini atau Instagram panitia.'])
            @else
                <ol class="relative flex flex-col gap-3 border-l-2 border-garis-tua pl-5">
                    @foreach ($jadwal as $item)
                        <li class="relative" data-reveal>
                            <span aria-hidden="true" @class([
                                'absolute top-5 -left-[29px] size-4 rounded-full border-2 border-krem',
                                'bg-mauve' => $item->acara_utama,
                                'bg-zaitun-brand' => ! $item->acara_utama,
                            ])></span>
                            <article @class([
                                'flex flex-col gap-1.5 rounded-2xl border p-4',
                                'border-rose bg-rose-muda' => $item->acara_utama,
                                'kartu' => ! $item->acara_utama,
                            ])>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 font-serif text-lg font-bold text-zaitun tabular-nums"><x-ikon name="jam" class="size-4" /> {{ str_replace(':', '.', $item->jam) }}</span>
                                    @if ($item->acara_utama)
                                        <span class="rounded-full bg-mauve px-2.5 py-0.5 text-xs font-semibold text-white">Acara utama</span>
                                    @endif
                                </div>
                                <h3 class="text-lg leading-snug font-semibold">{{ $item->judul }}</h3>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-redup">
                                    @if ($item->lokasi)
                                        <span class="inline-flex items-center gap-1"><x-ikon name="lokasi" class="size-4" /> {{ $item->lokasi }}</span>
                                    @endif
                                    @if ($item->kampus)
                                        <a href="#kampus/{{ $item->kampus->slug }}" data-buka-dialog="dialog-kampus-{{ $item->kampus->id }}" class="inline-flex items-center gap-1 hover:underline"><x-ikon name="kampus" class="size-4" /> {{ $item->kampus->nama }}</a>
                                    @endif
                                </div>
                                @if ($item->keterangan)
                                    <p class="text-[15px] leading-relaxed">{{ $item->keterangan }}</p>
                                @endif
                            </article>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</section>
