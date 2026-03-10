@props([
    'href' => '#',
    'variant' => 'primary',
])

@php
    $base = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition';
    $style = $variant === 'secondary'
        ? 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100'
        : 'bg-blue-600 text-white hover:bg-blue-700';
@endphp

<a href="{{ $href }}" {{ $attributes->class(['ui-action-link', $base, $style]) }}>
    {{ $slot }}
</a>
