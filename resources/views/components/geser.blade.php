{{-- Daftar yang digeser ke samping. Di HP bisa langsung diusap; tombol panah menggeser satu layar
     dan otomatis tersembunyi kalau semua isi sudah muat di layar.
     $kolom: kelas lebar kolom (geser-lebar, geser-kampus, geser-anggota, geser-foto). $baris: jumlah baris kartu. --}}
@props(['id', 'label', 'kolom' => 'geser-lebar', 'baris' => 1])
<div {{ $attributes->merge(['class' => 'flex flex-col gap-3']) }} data-geser data-reveal>
    <div class="flex min-h-11 items-center justify-between gap-3">
        <div class="min-w-0">{{ $kepala ?? '' }}</div>
        <div class="flex shrink-0 gap-2" data-geser-tombol hidden>
            <button type="button" class="tombol-geser" data-geser-arah="-1" aria-controls="{{ $id }}" aria-label="Geser ke kiri">
                <x-ikon name="kiri" />
            </button>
            <button type="button" class="tombol-geser" data-geser-arah="1" aria-controls="{{ $id }}" aria-label="Geser ke kanan">
                <x-ikon name="kanan" />
            </button>
        </div>
    </div>
    <div id="{{ $id }}" class="geser-jalur {{ $kolom }}" style="--baris: {{ $baris }}" data-geser-jalur role="region" aria-label="{{ $label }}" tabindex="0">
        {{ $slot }}
    </div>
</div>
