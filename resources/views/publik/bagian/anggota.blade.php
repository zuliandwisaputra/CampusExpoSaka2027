{{-- Anggota: cari nama, filter kampus dan divisi tanpa memuat ulang. Kartu satu baris bergeser sendiri, tombol "Lihat semua" membuka semua anggota. --}}
@php
    $kampusAnggota = $anggota->pluck('kampus')->filter()->unique('id')->sortBy('nama')->values();
@endphp
<section id="anggota" class="scroll-mt-20 py-12 sm:py-16" aria-labelledby="judul-anggota">
    <div class="wadah flex flex-col gap-5" data-daftar="anggota" data-satuan="anggota">
        @include('partials.kepala-bagian', [
            'id' => 'judul-anggota', 'label' => 'Anggota', 'judul' => 'Kenalan dengan alumni',
            'pengantar' => 'Alumni SMAN 1 Kandanghaur yang mewakili kampusnya. Sapa mereka lewat Instagram atau LinkedIn kalau ingin bertanya lebih jauh.',
        ])

        <div class="flex flex-col gap-3" data-reveal>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_240px_240px]">
                <div class="relative sm:col-span-2 lg:col-span-1">
                    <label for="cari-anggota" class="sr-only">Cari nama anggota</label>
                    <input id="cari-anggota" type="search" placeholder="Cari nama…" class="input pl-11" data-saring="cari" autocomplete="off">
                    <x-ikon name="cari" class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-redup" />
                </div>
                <div>
                    <label for="kampus-anggota" class="sr-only">Kampus</label>
                    <select id="kampus-anggota" class="input" data-saring="kampus">
                        <option value="">Semua kampus</option>
                        @foreach ($kampusAnggota as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="divisi-anggota" class="sr-only">Divisi</label>
                    <select id="divisi-anggota" class="input" data-saring="divisi">
                        <option value="">Semua divisi</option>
                        @foreach ($divisi as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <x-geser id="geser-anggota" label="Daftar anggota" kolom="geser-anggota" semua="Lihat semua anggota">
            <x-slot:kepala>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <p class="text-sm text-redup" aria-live="polite" data-jumlah>{{ $anggota->count() }} anggota</p>
                    <button type="button" class="min-h-11 text-sm font-semibold text-zaitun hover:underline" data-reset hidden>Hapus filter</button>
                </div>
            </x-slot:kepala>

            @foreach ($anggota as $a)
                @include('partials.kartu-anggota', ['a' => $a, 'daftar' => true])
            @endforeach
        </x-geser>

        <div hidden data-kosong>
            @include('partials.kosong', ['ikon' => 'orang', 'judul' => 'Anggota tidak ditemukan', 'teks' => 'Coba nama lain atau ganti filter kampus dan divisi.'])
        </div>
    </div>
</section>
