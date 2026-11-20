@props(['judul', 'pengantar' => null])
<div class="mb-5 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="font-serif text-2xl font-semibold sm:text-3xl">{{ $judul }}</h1>
        @if ($pengantar)
            <p class="mt-1 text-sm text-redup">{{ $pengantar }}</p>
        @endif
    </div>
    @if (trim($slot) !== '')
        <div class="flex flex-wrap gap-2">{{ $slot }}</div>
    @endif
</div>
