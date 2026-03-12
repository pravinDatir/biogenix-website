@props([
    'portal' => 'b2c',
    'active' => 'profile',
    'backUrl' => null,
    'backLabel' => 'Back',
    'title' => null,
    'description' => null,
    'eyebrow' => null,
])

@php
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $flashSuccess = session('success') ?: session('status');
    $flashError = session('error');
@endphp

<div class="mx-auto grid w-full max-w-none gap-8 px-4 pb-12 sm:px-6 lg:grid-cols-[15.5rem_minmax(0,1fr)] lg:px-8 xl:px-10">
    @include('customer.partials.account-sidebar', ['portal' => $portal, 'active' => $active])

    <div class="space-y-6 lg:border-l lg:border-slate-200 lg:pl-10">
        <div {{ $attributes->class(['space-y-6 rounded-[32px] border border-slate-200 bg-slate-50 p-6 shadow-sm md:p-8']) }}>
            <div class="space-y-2">
                @if ($backUrl)
                    <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ $backLabel }}
                    </a>
                @endif

                @if ($eyebrow || $title || $description)
                    <div class="space-y-1">
                        @if ($eyebrow)
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $eyebrow }}</p>
                        @endif
                        @if ($title)
                            <h1 class="text-2xl font-bold text-slate-900">{{ $title }}</h1>
                        @endif
                        @if ($description)
                            <p class="text-sm text-slate-600">{{ $description }}</p>
                        @endif
                    </div>
                @endif
            </div>

            @if ($flashSuccess)
                <x-alert type="success">
                    {{ $flashSuccess }}
                </x-alert>
            @endif

            @if ($flashError)
                <x-alert type="error">
                    {{ $flashError }}
                </x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error">
                    <p class="font-semibold">Please review the highlighted fields.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @isset($metrics)
                <div>
                    {{ $metrics }}
                </div>
            @endisset

            <div class="space-y-6">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex flex-wrap items-center justify-end gap-3">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
