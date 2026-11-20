@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null, 'required' => false])
@php $id = 'f-'.str_replace(['[', ']', '.'], '-', $name); @endphp
<div>
    <label for="{{ $id }}" class="label">{{ $label }}@if ($required) <span class="text-mauve-tua" aria-hidden="true">*</span>@endif</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
           @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
           @if ($required) required aria-required="true" @endif
           @error($name) aria-invalid="true" aria-describedby="{{ $id }}-galat" @enderror
           {{ $attributes->merge(['class' => 'input']) }}>
    @if ($help)
        <p class="bantuan">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $id }}-galat" class="galat">{{ $message }}</p>
    @enderror
</div>
