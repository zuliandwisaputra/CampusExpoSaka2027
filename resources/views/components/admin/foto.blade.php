@props(['name', 'label', 'url', 'dummy' => true, 'hapus' => null, 'fit' => 'cover', 'rasio' => 'aspect-square', 'lebar' => 'w-36', 'help' => null])
@php $id = 'f-'.$name; @endphp
<div>
    <span class="label">{{ $label }}</span>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
        <img id="{{ $id }}-pratinjau" src="{{ $url }}" alt="Pratinjau {{ strtolower($label) }}"
             class="{{ $lebar }} {{ $rasio }} shrink-0 rounded-2xl border border-garis bg-krem-tua {{ $fit === 'contain' ? 'object-contain p-2' : 'object-cover' }}">
        <div class="flex flex-1 flex-col gap-2">
            <label for="{{ $id }}" class="sr-only">Pilih file {{ strtolower($label) }}</label>
            <input id="{{ $id }}" type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp" data-preview="{{ $id }}-pratinjau"
                   class="block w-full rounded-2xl border border-dashed border-garis-tua bg-white p-3 text-sm file:mr-3 file:min-h-10 file:rounded-xl file:border-0 file:bg-zaitun file:px-4 file:text-sm file:font-semibold file:text-white">
            <p class="bantuan">{{ $help ?? 'JPG, PNG, atau WebP, maksimal '.\App\Support\ImageUploader::batasMb().' MB. Foto dikecilkan dan dikompres otomatis.' }}@if ($dummy) Saat ini masih memakai foto dummy.@endif</p>
            @if ($hapus && ! $dummy)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="{{ $hapus }}" value="1" class="size-4 accent-[#7A3E44]"> Hapus foto ini (kembali ke dummy)
                </label>
            @endif
            @error($name)
                <p class="galat">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
