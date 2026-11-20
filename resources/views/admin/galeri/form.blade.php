@extends('layouts.admin')

@php $baru = ! $item->exists; @endphp
@section('judul', $baru ? 'Buat album' : $item->judul)

@section('isi')
    <x-admin.judul :judul="$baru ? 'Buat album' : 'Album: '.$item->judul">
        <a href="{{ route('admin.galeri.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" action="{{ $baru ? route('admin.galeri.store') : route('admin.galeri.update', $item) }}" class="kartu flex max-w-2xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless
        <div class="grid gap-4 sm:grid-cols-[1fr_200px]">
            <x-admin.input name="judul" label="Judul album" :value="$item->judul" required maxlength="150" />
            <x-admin.input name="tanggal" label="Tanggal" type="date" :value="$item->tanggal?->format('Y-m-d')" />
        </div>
        <div><button type="submit" class="tombol-zaitun">{{ $baru ? 'Buat album' : 'Simpan album' }}</button></div>
    </form>

    @unless ($baru)
        <section class="mt-6" aria-labelledby="unggah">
            <h2 id="unggah" class="font-serif text-xl font-semibold">Unggah foto</h2>
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.galeri.upload', $item) }}" class="kartu mt-3 flex max-w-2xl flex-col gap-4 p-5" data-lock>
                @csrf
                <div>
                    <label for="foto" class="label">Pilih foto (bisa banyak sekaligus, maksimal {{ config('campus.galeri_maks_per_unggah') }})</label>
                    <input id="foto" type="file" name="foto[]" multiple required accept="image/jpeg,image/png,image/webp"
                           class="block w-full rounded-2xl border border-dashed border-garis-tua bg-white p-3 text-sm file:mr-3 file:min-h-10 file:rounded-xl file:border-0 file:bg-zaitun file:px-4 file:text-sm file:font-semibold file:text-white">
                    <p class="bantuan">JPG, PNG, atau WebP, maksimal {{ \App\Support\ImageUploader::batasMb() }} MB per foto. Foto dikecilkan ke 1200 × 900 px dan dibuatkan thumbnail.</p>
                    @error('foto') <p class="galat">{{ $message }}</p> @enderror
                    @error('foto.*') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <x-admin.input name="keterangan" label="Keterangan (opsional, berlaku untuk semua foto ini)" maxlength="255" />
                <div><button type="submit" class="tombol-zaitun"><x-ikon name="galeri" /> Unggah</button></div>
            </form>
        </section>

        <section class="mt-6" aria-labelledby="isi-album">
            <h2 id="isi-album" class="font-serif text-xl font-semibold">Isi album ({{ $item->foto->count() }} foto)</h2>
            <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @forelse ($item->foto as $foto)
                    <figure class="kartu flex flex-col overflow-hidden">
                        <img src="{{ $foto->thumb_url }}" alt="{{ $foto->keterangan }}" loading="lazy" class="aspect-[4/3] w-full bg-foto object-cover">
                        <figcaption class="flex items-center gap-2 p-2">
                            <span class="min-w-0 flex-1 truncate text-xs text-redup">{{ $foto->keterangan ?: 'Tanpa keterangan' }}</span>
                            @can('super-admin')
                                <x-admin.hapus :action="route('admin.galeri.foto.destroy', $foto)" pesan="Hapus foto ini?" label="Hapus foto" kecil />
                            @endcan
                        </figcaption>
                    </figure>
                @empty
                    <p class="col-span-full text-sm text-redup">Belum ada foto di album ini.</p>
                @endforelse
            </div>
        </section>
    @endunless
@endsection
