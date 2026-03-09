@props([
    'variant' => 'primary',
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
])

@php
    $variantClass = match ($variant) {
        'secondary' => 'secondary',
        'outline' => 'btn-outline',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class(['btn', $variantClass, 'is-loading' => $loading]) }}
    @disabled($disabled || $loading)
    aria-disabled="{{ ($disabled || $loading) ? 'true' : 'false' }}"
>
    <span>{{ $slot }}</span>
</button>
