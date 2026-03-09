@props([
    'title',
    'open' => false,
])

<details {{ $attributes->class(['ui-accordion']) }} @if($open) open @endif>
    <summary class="ui-accordion-summary">
        <span>{{ $title }}</span>
        <svg class="ui-accordion-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </summary>
    <div class="ui-accordion-content">
        <div class="ui-accordion-content-inner">
            {{ $slot }}
        </div>
    </div>
</details>
