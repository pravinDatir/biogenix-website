<!DOCTYPE html>
<html lang="en">
@php
    $authUser = auth()->user();
    $portal = $authUser?->user_type ?? request('user_type', request('portal', 'b2b'));
    $portal = $portal === 'b2c' ? 'b2c' : 'b2b';
    $displayName = $authUser?->name ?? 'Dr. Sarah Chen';
    $accountLabel = $authUser
        ? ($portal === 'b2b' ? 'Institutional Account' : 'Retail Account')
        : 'Institutional Account';
    $searchValue = trim((string) $__env->yieldContent('storefront_search', request('search_text', request('search', ''))));
    $searchValue = $searchValue !== '' ? $searchValue : 'Search by SKU, Product Name, or Application...';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20260309">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f5f7] text-slate-900 antialiased" style="font-family: 'Inter', system-ui, sans-serif;">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-[1460px] items-center gap-5 px-5 py-4 xl:px-8">
                <a href="{{ route('home') }}" class="flex min-w-fit items-center gap-3 no-underline">
                    <div class="relative h-11 w-11 rounded-xl bg-[#2383eb] shadow-sm shadow-blue-600/20">
                        <span class="absolute left-2.5 top-2.5 h-2.5 w-2.5 rounded-[4px] bg-white"></span>
                        <span class="absolute bottom-2.5 right-2.5 h-2.5 w-2.5 rounded-[4px] bg-white"></span>
                    </div>
                    <span class="text-[19px] font-semibold tracking-[-0.03em] text-[#1570c9]">Biogenix</span>
                </a>

                <form action="{{ route('products.index') }}" method="GET" class="hidden flex-1 max-w-[430px] lg:block">
                    <div class="relative">
                        <input
                            type="text"
                            name="search_text"
                            value="{{ $searchValue === 'Search by SKU, Product Name, or Application...' ? '' : $searchValue }}"
                            placeholder="Search by SKU, Product Name, or Application..."
                            class="h-12 w-full rounded-2xl border border-slate-200 bg-[#f1f3f6] pl-11 pr-4 text-[14px] font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white"
                        >
                        <svg class="absolute left-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                    </div>
                </form>

                <nav class="ml-auto hidden items-center gap-8 lg:flex">
                    @php
                        $navItems = [
                            ['label' => 'Products', 'href' => route('products.index')],
                            ['label' => 'Solutions', 'href' => route('about')],
                            ['label' => 'Support', 'href' => route('contact')],
                        ];
                        $activeNav = trim($__env->yieldContent('storefront_nav', 'Products'));
                    @endphp
                    @foreach ($navItems as $navItem)
                        <a href="{{ $navItem['href'] }}" class="{{ $activeNav === $navItem['label'] ? 'text-[#1570c9]' : 'text-slate-800' }} relative py-2 text-[14px] font-medium no-underline">
                            {{ $navItem['label'] }}
                            @if ($activeNav === $navItem['label'])
                                <span class="absolute inset-x-0 -bottom-[19px] h-[2px] rounded-full bg-[#2383eb]"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <a href="{{ $authUser ? '#' : route('login') }}" class="relative hidden h-10 w-10 items-center justify-center rounded-full text-slate-700 no-underline lg:inline-flex">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path>
                    </svg>
                    <span class="absolute -right-0.5 -top-0.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-[#2383eb] text-[10px] font-semibold text-white">3</span>
                </a>

                <a href="{{ $authUser ? '#' : route('login') }}" class="hidden items-center gap-3 rounded-full border border-slate-200 bg-white pl-4 pr-3 py-2 no-underline lg:flex">
                    <div class="text-right leading-tight">
                        <p class="text-[14px] font-semibold text-slate-900">{{ $displayName }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-400">{{ $accountLabel }}</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#eef1f5] text-slate-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21a8 8 0 1 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-[1460px] px-5 py-8 xl:px-8">
            @yield('content')
        </main>

        <footer class="mt-16 border-t border-slate-200 bg-[#f0f1f3]">
            <div class="mx-auto grid max-w-[1460px] gap-10 px-5 py-12 lg:grid-cols-[1.3fr_1fr_1fr_1fr] xl:px-8">
                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="relative h-10 w-10 rounded-xl bg-[#2383eb]">
                            <span class="absolute left-2.5 top-2.5 h-2.5 w-2.5 rounded-[4px] bg-white"></span>
                            <span class="absolute bottom-2.5 right-2.5 h-2.5 w-2.5 rounded-[4px] bg-white"></span>
                        </div>
                        <span class="text-[19px] font-semibold tracking-[-0.03em] text-[#1570c9]">Biogenix</span>
                    </div>
                    <p class="max-w-[220px] text-[14px] leading-7 text-slate-500">
                        Advancing global healthcare through precision diagnostics and innovative life science research tools.
                    </p>
                </div>

                <div>
                    <h3 class="text-[14px] font-semibold uppercase tracking-[0.16em] text-slate-800">Company</h3>
                    <ul class="mt-5 space-y-3 text-[14px] text-slate-500">
                        <li><a href="{{ route('about') }}" class="text-inherit no-underline hover:text-slate-800">About Us</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Careers</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Global Network</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Newsroom</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[14px] font-semibold uppercase tracking-[0.16em] text-slate-800">Resources</h3>
                    <ul class="mt-5 space-y-3 text-[14px] text-slate-500">
                        <li><a href="{{ route('contact') }}" class="text-inherit no-underline hover:text-slate-800">Technical Support</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Safety Data Sheets (SDS)</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Whitepapers</a></li>
                        <li><a href="#" class="text-inherit no-underline hover:text-slate-800">Webinars</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[14px] font-semibold uppercase tracking-[0.16em] text-slate-800">Connect</h3>
                    <div class="mt-5 flex gap-3">
                        @foreach (['globe', 'mail', 'phone'] as $icon)
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm">
                                @if ($icon === 'globe')
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18"></path><path d="M12 3a15 15 0 0 1 0 18"></path><path d="M12 3a15 15 0 0 0 0 18"></path></svg>
                                @elseif ($icon === 'mail')
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m4 7 8 6 8-6"></path></svg>
                                @else
                                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.35 1.78.68 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.47-1.25a2 2 0 0 1 2.11-.45c.83.33 1.71.56 2.61.68A2 2 0 0 1 22 16.92z"></path></svg>
                                @endif
                            </span>
                        @endforeach
                    </div>
                    <p class="mt-7 text-[13px] text-slate-400">&copy; 2024 Biogenix Life Sciences. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
