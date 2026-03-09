@props([
    'variant' => 'default',
])

@php
    $style = match ($variant) {
        'success' => 'border border-green-200 bg-green-50 text-green-700',
        'warning' => 'border border-amber-200 bg-amber-50 text-amber-700',
        'danger' => 'border border-red-200 bg-red-50 text-red-700',
        'info' => 'border border-blue-200 bg-blue-50 text-blue-700',
        default => 'border border-slate-200 bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->class(["inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold", $style]) }}>
    {{ $slot }}
</span>
