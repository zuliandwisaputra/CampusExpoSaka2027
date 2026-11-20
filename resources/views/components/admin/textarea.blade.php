@props(['name', 'label', 'value' => null, 'help' => null, 'required' => false, 'rows' => 4])
@php $id = 'f-'.$name; @endphp
<div>
    <label for="{{ $id }}" class="label">{{ $label }}@if ($required) <span class="text-mauve-tua" aria-hidden="true">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
              @if ($required) required aria-required="true" @endif
              @error($name) aria-invalid="true" aria-describedby="{{ $id }}-galat" @enderror
              {{ $attributes->merge(['class' => 'input py-3 leading-relaxed']) }}>{{ old($name, $value) }}</textarea>
    @if ($help)
        <p class="bantuan">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $id }}-galat" class="galat">{{ $message }}</p>
    @enderror
</div>
