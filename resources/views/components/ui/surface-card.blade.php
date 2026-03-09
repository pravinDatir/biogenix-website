@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->class(['saas-card']) }}>
    @if ($title)
        <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    @endif
    @if ($subtitle)
        <p class="mt-1 text-sm text-slate-600">{{ $subtitle }}</p>
    @endif
    <div class="{{ $title || $subtitle ? 'mt-3' : '' }}">
        {{ $slot }}
    </div>
</div>
