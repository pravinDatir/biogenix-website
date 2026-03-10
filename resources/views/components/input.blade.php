@props([
    'name',
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'error' => null,
    'success' => false,
])

@php
    $inputId = $id ?? $name;
@endphp

<input
    id="{{ $inputId }}"
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    @required($required)
    {{ $attributes->class([
        'form-control',
        'border-red-500 ring-1 ring-red-100' => filled($error),
        'border-green-500 ring-1 ring-green-100' => $success,
    ]) }}
>
