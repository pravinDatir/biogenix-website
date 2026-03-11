@extends('layouts.app')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $profiles = [
        'b2c' => [
            'badge' => 'B2C Customer Workspace',
            'headline' => 'Retail self-service ordering, tracking, and support.',
            'summary' => 'MRP-led buying flow for personal orders, self-only PI generation, and customer support visibility.',
            'gradient' => 'from-emerald-950 via-slate-900 to-slate-950',
            'badgeStyle' => 'border-emerald-300/30 bg-emerald-400/15 text-emerald-50',
            'pricing' => 'Retail MRP and campaign offers',
            'scope' => 'Own profile, own addresses, own tickets',
            'quote' => 'PI generation for self only',
        ],
        'b2b' => [
            'badge' => 'B2B Account Workspace',
            'headline' => 'Company buying, approvals, and client-facing commercial workflows.',
            'summary' => 'Contract or dealer pricing, company-level operational controls, and PI/order workflows for self or assigned clients.',
            'gradient' => 'from-blue-950 via-slate-900 to-slate-950',
            'badgeStyle' => 'border-blue-300/30 bg-blue-400/15 text-blue-50',
            'pricing' => 'Customer-specific or contract pricing',
            'scope' => 'Own company plus assigned client visibility',
            'quote' => 'PI generation for self and assigned clients',
        ],
    ];

    $profile = $profiles[$portal];
    $active = trim($__env->yieldContent('customer_active'));
    $navItems = [
        ['key' => 'dashboard', 'label' => 'Dashboard'],
        ['key' => 'catalog', 'label' => 'Catalog'],
        ['key' => 'cart', 'label' => 'Cart'],
        ['key' => 'checkout', 'label' => 'Checkout'],
        ['key' => 'orders', 'label' => 'My Orders'],
        ['key' => 'quotations', 'label' => 'My Quotations'],
        ['key' => 'tracking', 'label' => 'Tracking'],
        ['key' => 'profile', 'label' => 'Profile'],
        ['key' => 'reviews', 'label' => 'Reviews'],
        ['key' => 'whatsapp', 'label' => 'WhatsApp Orders'],
    ];
@endphp

@section('content')
    <div class="page-shell !space-y-6 md:!space-y-8">
        <section class="hero-wrap relative !bg-transparent">
            <div class="absolute inset-0 bg-gradient-to-br {{ $profile['gradient'] }}"></div>
            <div class="relative z-10 grid gap-6 lg:grid-cols-[minmax(0,1fr)_21rem] lg:items-start">
                <div class="space-y-5">
                    <span class="hero-kicker {{ $profile['badgeStyle'] }}">
                        {{ $__env->yieldContent('customer_kicker', $profile['badge']) }}
                    </span>
                    <div class="space-y-3">
                        <h1 class="ui-page-title !text-white">
                            {{ $__env->yieldContent('customer_title', 'Customer Portal UI') }}
                        </h1>
                        <p class="max-w-3xl text-sm leading-7 text-slate-200 md:text-base">
                            {{ $__env->yieldContent('customer_description', $profile['headline'].' '.$profile['summary']) }}
                        </p>
                    </div>

                    @if (trim($__env->yieldContent('customer_actions')))
                        <div class="hero-actions">
                            @yield('customer_actions')
                        </div>
                    @endif
                </div>

                <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Pricing Model</p>
                        <p class="mt-2 text-base font-semibold text-white">{{ $profile['pricing'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Data Scope</p>
                        <p class="mt-2 text-base font-semibold text-white">{{ $profile['scope'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Quotation Rule</p>
                        <p class="mt-2 text-base font-semibold text-white">{{ $profile['quote'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="overflow-x-auto">
            <div class="flex min-w-max flex-wrap gap-2">
                @foreach ($navItems as $item)
                    @php
                        $isActive = $active === $item['key'];
                    @endphp
                    <span class="{{ $isActive ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600' }} inline-flex rounded-full border px-3 py-2 text-sm font-medium shadow-sm">
                        {{ $item['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="grid gap-5 xl:grid-cols-[18rem_minmax(0,1fr)]">
            <aside class="space-y-4">
                <x-ui.surface-card title="Role-Aware Notes" subtitle="These pages are view-only additions designed for later backend wiring.">
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="rounded-xl bg-slate-50 px-3 py-3">
                            Conditional rendering keys off <code>auth()->user()->user_type</code> and falls back to <code>?user_type=b2b</code> or <code>b2c</code>.
                        </li>
                        <li class="rounded-xl bg-slate-50 px-3 py-3">
                            Existing controllers, routes, and models remain unchanged.
                        </li>
                        <li class="rounded-xl bg-slate-50 px-3 py-3">
                            Sample metrics, cards, and tables are intentionally static to keep this work view-only.
                        </li>
                    </ul>
                </x-ui.surface-card>

                <x-ui.surface-card title="Flow Coverage" subtitle="Built from the PDF requirements for logged-in customer UX.">
                    <div class="flex flex-wrap gap-2">
                        <x-badge variant="info">Dashboard</x-badge>
                        <x-badge variant="info">Catalog</x-badge>
                        <x-badge variant="info">Cart</x-badge>
                        <x-badge variant="info">Checkout</x-badge>
                        <x-badge variant="info">Orders</x-badge>
                        <x-badge variant="info">PIs</x-badge>
                        <x-badge variant="info">Tracking</x-badge>
                        <x-badge variant="info">Profile</x-badge>
                    </div>
                </x-ui.surface-card>
            </aside>

            <div class="space-y-5">
                @yield('customer_content')
            </div>
        </div>
    </div>
@endsection
