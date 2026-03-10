@props([
    'title',
    'subtitle' => null,
    'center' => false,
])

<div {{ $attributes->class([$center ? 'text-center' : '']) }}>
    <h2 class="section-title">{{ $title }}</h2>
    @if ($subtitle)
        <p class="section-subtitle {{ $center ? 'mx-auto' : '' }}">{{ $subtitle }}</p>
    @endif
</div>
