@props([
    'type' => 'info',
])

@php
    $style = match ($type) {
        'success' => 'border border-green-200 bg-green-100 text-green-700',
        'error' => 'border border-red-200 bg-red-100 text-red-700',
        'warning' => 'border border-amber-200 bg-amber-100 text-amber-800',
        default => 'border border-blue-200 bg-blue-100 text-blue-700',
    };
@endphp

<div role="alert" {{ $attributes->class(["rounded-xl px-4 py-3 text-sm", $style]) }}>
    {{ $slot }}
</div>
