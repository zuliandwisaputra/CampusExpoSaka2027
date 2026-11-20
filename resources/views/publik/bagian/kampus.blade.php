{{-- Kampus: cari, filter jenis dan provinsi tanpa memuat ulang halaman. Detail dibuka di pop-up. --}}
@php
    $provinsi = $kampus->pluck('provinsi')->filter()->unique()->sort()->values();
    $awalKampus = 8;
@endphp
<section id="kampus" class="scroll-mt-20 bg-krem-tua/60 py-12 sm:py-16" aria-labelledby="judul-kampus">
    <div class="wadah flex flex-col gap-5" data-daftar="kampus" data-awal="{{ $awalKampus }}" data-tambah="0" data-satuan="kampus">
        @include('partials.kepala-bagian', [
            'id' => 'judul-kampus', 'label' => 'Kampus', 'judul' => 'Kampus yang hadir',
            'pengantar' => 'Ketuk kampus untuk melihat jurusan, jalur masuk, kisaran biaya, beasiswa, dan alumni yang bisa kamu tanyai.',
        ])

        <div class="flex flex-col gap-3" data-reveal>
            <div class="grid gap-3 sm:grid-cols-[1fr_240px]">
                <div class="relative">
                    <label for="cari-kampus" class="sr-only">Cari nama kampus</label>
                    <input id="cari-kampus" type="search" placeholder="Cari nama kampus atau kota…" class="input pl-11" data-saring="cari" autocomplete="off">
                    <x-ikon name="cari" class="pointer-events-none absolute top-3.5 left-3.5 size-5 text-redup" />
                </div>
                <div>
                    <label for="provinsi-kampus" class="sr-only">Provinsi</label>
                    <select id="provinsi-kampus" class="input" data-saring="provinsi">
                        <option value="">Semua provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov }}">{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="-mx-4 overflow-x-auto px-4" role="group" aria-label="Filter jenis kampus">
                <div class="flex gap-2 pb-1">
                    <button type="button" class="chip chip-aktif" data-saring-chip="jenis" data-nilai="" aria-pressed="true">Semua</button>
                    @foreach (\App\Models\Kampus::JENIS as $kode => $label)
                        <button type="button" class="chip" data-saring-chip="jenis" data-nilai="{{ $kode }}" aria-pressed="false">{{ $label }}</button>
                    @endforeach
                </div>
            </div>
            <p class="text-sm text-redup" aria-live="polite" data-jumlah>{{ $kampus->count() }} kampus</p>
        </div>

        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($kampus as $k)
                @include('partials.kartu-kampus', ['k' => $k, 'lebih' => $loop->index >= $awalKampus])
                @push('dialog')
                    @include('publik.dialog.kampus', ['k' => $k])
                @endpush
            @endforeach
        </div>

        <div hidden data-kosong>
            @include('partials.kosong', ['judul' => 'Kampus tidak ditemukan', 'teks' => 'Coba kata kunci lain atau ubah filter.'])
        </div>

        @if ($kampus->count() > $awalKampus)
            <button type="button" class="tombol-garis self-center" data-tampil-lebih>Lihat semua {{ $kampus->count() }} kampus <x-ikon name="kanan" class="size-4 rotate-90" /></button>
        @endif
    </div>
</section>
