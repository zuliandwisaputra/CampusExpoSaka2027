@props(['action', 'pesan', 'label' => 'Hapus', 'kecil' => false])
{{-- Setiap tombol hapus meminta konfirmasi lebih dulu (aturan PRD). --}}
<form method="post" action="{{ $action }}" data-confirm="{{ $pesan }}">
    @csrf
    @method('DELETE')
    @if ($kecil)
        <button type="submit" class="flex size-11 items-center justify-center rounded-xl border border-[#E8C4C4] bg-white text-mauve-tua hover:bg-rose-muda" aria-label="{{ $label }}">
            <x-ikon name="hapus" class="size-[18px]" />
        </button>
    @else
        <button type="submit" class="tombol-bahaya"><x-ikon name="hapus" class="size-[18px]" /> {{ $label }}</button>
    @endif
</form>
