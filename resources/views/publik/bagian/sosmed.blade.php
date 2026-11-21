{{-- Media sosial panitia (Instagram, TikTok). Akun diatur di panel admin → Pengaturan; yang kosong tidak ditampilkan. --}}
@php $sosmed = \App\Models\Pengaturan::sosmed(); @endphp
@if ($sosmed)
    <section id="sosmed" class="relative isolate overflow-hidden bg-zaitun py-12 text-krem sm:py-16" aria-labelledby="judul-sosmed">
        {{-- Hiasan lingkaran, hanya warna datar supaya ringan di HP. --}}
        <div class="pointer-events-none absolute -top-24 -right-20 -z-10 size-72 rounded-full bg-zaitun-brand/40" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-28 -left-16 -z-10 size-64 rounded-full bg-mauve/25" aria-hidden="true"></div>

        <div class="wadah grid gap-7 md:grid-cols-2 md:items-center">
            <div class="flex flex-col gap-2" data-reveal>
                <span class="text-xs font-semibold tracking-[0.14em] text-rose-terang uppercase">Media sosial</span>
                <h2 id="judul-sosmed" class="font-serif text-2xl font-semibold text-white sm:text-3xl">Ikuti update Campus Expo</h2>
                <p class="max-w-md text-[15px] leading-relaxed text-[#E4DEC9] sm:text-base">Info kampus yang hadir, rundown, dan dokumentasi hari H diumumkan lebih dulu di akun panitia.</p>
            </div>

            <ul class="grid gap-3">
                @foreach ($sosmed as $akun)
                    <li data-reveal>
                        <a href="{{ $akun['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="group flex h-full items-center gap-4 rounded-2xl bg-krem p-4 text-tinta transition-transform duration-200 hover:-translate-y-0.5 sm:p-5">
                            <span @class([
                                'flex size-14 shrink-0 items-center justify-center rounded-2xl',
                                'bg-rose-muda text-mauve-tua' => $akun['ikon'] === 'instagram',
                                'bg-tinta text-white' => $akun['ikon'] !== 'instagram',
                            ])>
                                <x-ikon :name="$akun['ikon']" class="size-7" />
                            </span>
                            <span class="flex min-w-0 flex-1 flex-col">
                                <span class="text-xs font-semibold tracking-wide text-redup uppercase">{{ $akun['nama'] }}</span>
                                <span class="truncate text-lg font-semibold">{{ $akun['akun'] }}</span>
                                <span class="text-sm text-redup">{{ $akun['ajakan'] }}</span>
                            </span>
                            <x-ikon name="luar" class="size-5 shrink-0 text-zaitun transition-transform duration-200 group-hover:translate-x-0.5" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
