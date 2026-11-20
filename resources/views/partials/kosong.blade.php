<div class="kartu flex flex-col items-center gap-2 px-6 py-10 text-center">
    <x-ikon :name="$ikon ?? 'cari'" class="size-8 text-zaitun-brand" />
    <p class="font-semibold">{{ $judul }}</p>
    @isset($teks)
        <p class="max-w-md text-sm text-redup">{{ $teks }}</p>
    @endisset
</div>
