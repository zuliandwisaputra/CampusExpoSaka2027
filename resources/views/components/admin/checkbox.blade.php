@props(['name', 'label', 'checked' => false, 'help' => null])
@php $nyala = (bool) old($name, $checked); @endphp
<div>
    <label class="flex min-h-11 cursor-pointer items-center gap-3 rounded-2xl border border-garis-tua bg-white px-4 py-2.5">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" name="{{ $name }}" value="1" @checked($nyala) class="size-5 accent-[#5C5A2C]">
        <span class="text-[15px] font-medium">{{ $label }}</span>
    </label>
    @if ($help)
        <p class="bantuan">{{ $help }}</p>
    @endif
</div>
