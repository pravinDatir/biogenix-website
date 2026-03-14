@props([
    'title',
    'subtitle' => null,
    'center' => false,
])

<div {{ $attributes->class([$center ? 'text-center' : '']) }}>
    <h2 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-[1.75rem]">{{ $title }}</h2>
    @if ($subtitle)
        <p class="{{ $center ? 'mx-auto' : '' }} mt-2 max-w-2xl text-sm leading-7 text-slate-500 md:text-base">{{ $subtitle }}</p>
    @endif
</div>
