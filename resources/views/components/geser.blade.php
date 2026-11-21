{{-- Daftar satu baris yang digeser ke samping. Di HP bisa langsung diusap, ada tombol panah,
     dan daftar mengalir pelan sendiri (berhenti saat disentuh, diarahkan mouse, atau tombol jeda ditekan).
     $kolom: kelas lebar kartu (geser-lebar, geser-kampus, geser-anggota, geser-foto).
     $semua: teks tombol untuk membuka semua isi sebagai grid, misalnya "Lihat semua kampus".
     $kecepatan: kecepatan geser otomatis dalam piksel per detik. --}}
@props(['id', 'label', 'kolom' => 'geser-lebar', 'semua' => null, 'kecepatan' => 70])
<div {{ $attributes->merge(['class' => 'flex scroll-mt-24 flex-col gap-3']) }} data-geser data-geser-kecepatan="{{ $kecepatan }}" data-reveal>
    <div class="flex min-h-11 items-center justify-between gap-3">
        <div class="min-w-0">{{ $kepala ?? '' }}</div>
        <div class="flex shrink-0 gap-2" data-geser-tombol hidden>
            <button type="button" class="tombol-geser" data-geser-jeda aria-controls="{{ $id }}" aria-label="Jeda geser otomatis">
                <x-ikon name="jeda" class="size-[18px]" data-ikon-jeda />
                <x-ikon name="putar" class="hidden size-[18px]" data-ikon-putar />
            </button>
            <button type="button" class="tombol-geser" data-geser-arah="-1" aria-controls="{{ $id }}" aria-label="Geser ke kiri">
                <x-ikon name="kiri" />
            </button>
            <button type="button" class="tombol-geser" data-geser-arah="1" aria-controls="{{ $id }}" aria-label="Geser ke kanan">
                <x-ikon name="kanan" />
            </button>
        </div>
        @if ($semua)
            <button type="button" class="min-h-11 shrink-0 text-sm font-semibold text-zaitun hover:underline" data-geser-semua data-geser-ringkas aria-controls="{{ $id }}" hidden>
                Tampilkan ringkas
            </button>
        @endif
    </div>

    <div id="{{ $id }}" class="geser-jalur {{ $kolom }}" data-geser-jalur role="region" aria-label="{{ $label }}" tabindex="0">
        {{ $slot }}
    </div>

    @if ($semua)
        <button type="button" class="tombol-garis self-center" data-geser-semua data-geser-semua-bawah aria-controls="{{ $id }}" aria-expanded="false" hidden>
            <span data-geser-semua-teks data-awal="{{ $semua }}">{{ $semua }}</span>
            <x-ikon name="kanan" class="size-4 rotate-90 transition-transform duration-200" data-geser-semua-ikon />
        </button>
    @endif
</div>
