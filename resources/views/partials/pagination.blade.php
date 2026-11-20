@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="mt-8 flex items-center justify-between gap-2">
        @if ($paginator->onFirstPage())
            <span class="tombol min-w-12 border border-garis bg-krem-tua px-3 text-redup opacity-60 sm:px-5" aria-disabled="true">
                <x-ikon name="kiri" /> <span class="hidden sm:inline">Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="tombol-garis min-w-12 px-3 sm:px-5" aria-label="Halaman sebelumnya">
                <x-ikon name="kiri" /> <span class="hidden sm:inline">Sebelumnya</span>
            </a>
        @endif

        <span class="text-center text-sm text-redup">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="tombol-garis min-w-12 px-3 sm:px-5" aria-label="Halaman berikutnya">
                <span class="hidden sm:inline">Berikutnya</span> <x-ikon name="kanan" />
            </a>
        @else
            <span class="tombol min-w-12 border border-garis bg-krem-tua px-3 text-redup opacity-60 sm:px-5" aria-disabled="true">
                <span class="hidden sm:inline">Berikutnya</span> <x-ikon name="kanan" />
            </span>
        @endif
    </nav>
@endif
