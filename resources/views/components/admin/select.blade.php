@props(['name', 'label', 'options' => [], 'value' => null, 'placeholder' => null, 'help' => null, 'required' => false])
@php
    $id = 'f-'.$name;
    $terpilih = (string) old($name, $value);
@endphp
<div>
    <label for="{{ $id }}" class="label">{{ $label }}@if ($required) <span class="text-mauve-tua" aria-hidden="true">*</span>@endif</label>
    <select id="{{ $id }}" name="{{ $name }}" @if ($required) required aria-required="true" @endif
            @error($name) aria-invalid="true" aria-describedby="{{ $id }}-galat" @enderror
            {{ $attributes->merge(['class' => 'input']) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $nilai => $teks)
            <option value="{{ $nilai }}" @selected($terpilih === (string) $nilai)>{{ $teks }}</option>
        @endforeach
    </select>
    @if ($help)
        <p class="bantuan">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $id }}-galat" class="galat">{{ $message }}</p>
    @enderror
</div>
