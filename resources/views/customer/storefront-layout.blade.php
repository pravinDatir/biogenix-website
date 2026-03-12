<!DOCTYPE html>
<html lang="en">
@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $authUser = auth()->user();
    $displayName = $authUser?->name ?? ($portal === 'b2b' ? 'Dr. Sarah Chen' : 'Sarah Chen');
    $accountLabel = $authUser
        ? ($portal === 'b2b' ? 'Institutional Account' : 'Retail Account')
        : 'Guest Access';
    $navLinkBaseClass = 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900';
    $navLinkActiveClass = 'bg-slate-900 text-white hover:bg-slate-900 hover:text-white';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20260309">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex w-full max-w-none items-center gap-6 px-4 py-4 sm:px-6 lg:px-8 xl:px-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline">
                    <div class="relative h-11 w-11 rounded-2xl bg-primary-600 shadow-sm">
                        <span class="absolute left-2 top-2 h-3 w-3 rounded-sm bg-white"></span>
                        <span class="absolute bottom-2 right-2 h-3 w-3 rounded-sm bg-white/90"></span>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-xl font-semibold tracking-tight text-slate-900">Biogenix</p>
                    </div>
                </a>

                <div class="hidden min-w-0 flex-1 lg:block">
                    <div class="relative max-w-xl">
                        <input
                            type="text"
                            value="@yield('search_placeholder', 'Search by SKU, product name, or application...')"
                            readonly
                            class="block h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400"
                        >
                        <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                    </div>
                </div>

                <nav class="hidden items-center gap-1 lg:flex">
                    @foreach (['Products', 'Solutions', 'Support'] as $item)
                        <a href="#" class="{{ $navLinkBaseClass }} {{ $item === trim($__env->yieldContent('storefront_nav', 'Products')) ? $navLinkActiveClass : '' }}">
                            {{ $item }}
                        </a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-3">
                    <button type="button" class="relative hidden h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm lg:inline-flex">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="20" r="1"></circle>
                            <circle cx="18" cy="20" r="1"></circle>
                            <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path>
                        </svg>
                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white">3</span>
                    </button>

                    @if ($authUser)
                        <div class="hidden items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm lg:flex">
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
                    @else
                        <div class="hidden items-center gap-2 lg:flex">
                            <a href="{{ route('login') }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Login</a>
                            <a href="{{ route('signup') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Register</a>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <main>
            @yield('storefront_content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto grid w-full max-w-none gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8 xl:px-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="relative h-10 w-10 rounded-xl bg-primary-600">
                            <span class="absolute left-2 top-2 h-3 w-3 rounded-sm bg-white"></span>
                            <span class="absolute bottom-2 right-2 h-3 w-3 rounded-sm bg-white/90"></span>
                        </div>
                        <p class="text-xl font-semibold tracking-tight text-slate-900">Biogenix</p>
                    </div>
                    <p class="max-w-xs text-sm leading-7 text-slate-500">
                        Advancing global healthcare through precision diagnostics and innovative life science research tools.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-800">Company</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li>About Us</li>
                        <li>Careers</li>
                        <li>Global Network</li>
                        <li>Newsroom</li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-800">Resources</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li>Technical Support</li>
                        <li>Safety Data Sheets (SDS)</li>
                        <li>Whitepapers</li>
                        <li>Webinars</li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-800">Connect</p>
                    <div class="mt-4 flex gap-3">
                        @foreach (['globe', 'mail', 'phone'] as $icon)
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-50 text-slate-500 shadow-sm">
                                @if ($icon === 'globe')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18"></path><path d="M12 3a15 15 0 0 1 0 18"></path><path d="M12 3a15 15 0 0 0 0 18"></path></svg>
                                @elseif ($icon === 'mail')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m4 7 8 6 8-6"></path></svg>
                                @else
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.35 1.78.68 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.25a2 2 0 0 1 2.11-.45c.83.33 1.71.56 2.61.68A2 2 0 0 1 22 16.92z"></path></svg>
                                @endif
                            </span>
                        @endforeach
                    </div>
                    <p class="mt-6 text-sm text-slate-400">&copy; 2026 Biogenix Life Sciences. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
