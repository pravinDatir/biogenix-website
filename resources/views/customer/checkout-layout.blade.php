<!DOCTYPE html>
<html lang="en">
@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2b'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $authUser = auth()->user();
    $displayName = $authUser?->name ?? ($portal === 'b2b' ? 'Dr. Aristotle' : 'Sarah Chen');
    $accountLabel = $authUser
        ? ($portal === 'b2b' ? 'Institute of Genomics' : 'Retail Account')
        : ($portal === 'b2b' ? 'Institute of Genomics' : 'Guest Checkout');
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biogenix Checkout')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20260309">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex w-full max-w-none flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8 xl:px-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline">
                    <div class="relative h-11 w-11 rounded-2xl bg-primary-600 shadow-sm">
                        <span class="absolute left-2 top-2 h-3 w-3 rounded-sm bg-white"></span>
                        <span class="absolute bottom-2 right-2 h-3 w-3 rounded-sm bg-white/90"></span>
                    </div>
                    <p class="text-xl font-semibold tracking-tight text-slate-900">Biogenix</p>
                </a>

                <a href="{{ route('cart.page') }}" class="hidden h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 md:inline-flex">
                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"></path></svg>
                    Back to Cart
                </a>

                <div class="ml-auto flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2 shadow-sm">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900">{{ $displayName }}</p>
                        <p class="text-xs text-slate-500">{{ $accountLabel }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21a8 8 0 1 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <main class="py-10">
            @yield('checkout_content')
        </main>

        <footer class="px-4 pb-8 pt-2 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
            &copy; 2026 Biogenix Scientific Solutions. All rights reserved.
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
