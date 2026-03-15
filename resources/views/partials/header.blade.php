@php
    $currentRoute = request()->route()?->getName();
    $authUser = auth()->user();
    $guestProfileType = request('user_type', request('portal', 'b2c')) === 'b2b' ? 'b2b' : 'b2c';
    $profileHref = $authUser
        ? route('customer.profile.preview', ['user_type' => $authUser->user_type === 'b2b' ? 'b2b' : 'b2c'])
        : route('customer.profile.preview', ['user_type' => $guestProfileType]);
    $accountTypeLabel = $authUser
        ? strtoupper($authUser->user_type === 'b2b' ? 'B2B Account' : 'Customer Account')
        : strtoupper($guestProfileType === 'b2b' ? 'B2B Preview' : 'Customer Preview');
    $navItems = [
        ['label' => 'Home', 'route' => 'home', 'href' => route('home')],
        ['label' => 'Products & Solutions', 'route' => 'products.index', 'href' => route('products.index')],
        ['label' => 'Generate Quote', 'route' => 'proforma.create', 'href' => route('proforma.create')],
        ['label' => 'About Us', 'route' => 'about', 'href' => route('about')],
        ['label' => 'FAQ', 'route' => 'faq', 'href' => route('faq')],
        ['label' => 'Contact Us', 'route' => 'contact', 'href' => route('contact')],
        ['label' => 'Book Meeting', 'route' => 'book-meeting', 'href' => route('book-meeting')],
    ];
    $mobileQuickActions = [
        ['label' => 'My Profile', 'href' => $profileHref, 'icon' => 'profile'],
        ['label' => 'View Cart', 'href' => route('cart.page'), 'icon' => 'cart'],
        ['label' => 'Generate Quote', 'href' => route('proforma.create'), 'icon' => 'quote'],
        ['label' => 'Support', 'href' => route('contact'), 'icon' => 'support'],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="relative mx-auto flex min-h-[72px] w-full max-w-none items-center gap-4 px-4 py-2 sm:px-6 lg:px-8 xl:px-10">
        <a href="{{ route('home') }}" class="shrink-0">
            <img src="{{ asset('storage/slides/logo.jpg') }}" alt="Biogenix Logo" width="120" height="64" decoding="async" class="h-14 w-auto md:h-16">
        </a>

        {{-- Mobile hamburger --}}
        <button
            class="ml-auto inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 xl:hidden"
            data-menu-toggle
            aria-label="Open mobile menu"
            aria-expanded="false"
            aria-controls="mobileMenuDrawer"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Desktop nav --}}
        <nav
            id="headerMainNav"
            class="hidden items-center gap-1 xl:absolute xl:left-1/2 xl:top-1/2 xl:flex xl:-translate-x-1/2 xl:-translate-y-1/2"
            aria-label="Main Navigation"
        >
            @foreach ($navItems as $nav)
                <a
                    href="{{ $nav['href'] }}"
                    class="relative rounded-lg px-3 py-2 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900 {{ $currentRoute === $nav['route'] ? 'nav-link-active' : '' }}"
                >
                    {{ $nav['label'] }}
                </a>
            @endforeach
        </nav>


        {{-- Desktop auth & cart --}}
        <div class="ml-auto hidden items-center gap-2 xl:flex">
            @auth
                <span class="text-sm text-slate-600">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" id="logoutBtn" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" id="loginBtn" class="inline-flex h-11 items-center justify-center rounded-xl border border-primary-600 bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Login</a>
                <a href="{{ route('signup') }}" id="signupBtn" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Sign Up</a>
            @endauth

            <a
                href="{{ route('cart.page') }}"
                class="inline-flex items-center gap-2.5 rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 no-underline shadow-sm transition hover:-translate-y-0.5 hover:border-primary-100 hover:bg-white hover:text-slate-900 hover:shadow-md hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20"
                aria-label="View cart"
            >
                <span class="relative inline-flex h-7 w-7 items-center justify-center text-slate-900">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="9" cy="21" r="1.4"></circle>
                        <circle cx="17" cy="21" r="1.4"></circle>
                        <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                    </svg>
                    <span
                        id="globalCartCount"
                        class="absolute -right-2 -top-1 hidden h-5 min-w-5 items-center justify-center rounded-full bg-rose-600 px-1.5 text-xs font-bold leading-none text-white shadow-sm"
                        aria-live="polite"
                        aria-atomic="true"
                    >0</span>
                </span>
                <span class="text-sm font-bold leading-none text-slate-900">Cart</span>
            </a>

            {{-- Profile icon --}}
            <a
                href="{{ $profileHref }}"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 no-underline shadow-sm transition hover:-translate-y-0.5 hover:border-primary-100 hover:bg-white hover:text-slate-900 hover:shadow-md hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20"
                aria-label="{{ $authUser ? 'Open account profile' : 'Open account preview' }}"
                title="Profile"
            >
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                </svg>
            </a>
        </div>
    </div>
</header>

<div id="mobileMenuOverlay" class="fixed inset-0 z-[70] hidden xl:hidden" aria-hidden="true">
    <button id="mobileMenuBackdrop" type="button" class="absolute inset-0 bg-slate-950/45 opacity-0 backdrop-blur-sm transition duration-300" aria-label="Close mobile menu"></button>

    <aside
        id="mobileMenuDrawer"
        class="absolute inset-y-0 right-0 flex w-[min(92vw,25rem)] max-w-full translate-x-full flex-col bg-white shadow-[0_24px_80px_rgba(15,23,42,0.24)] transition duration-300 ease-out"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mobileMenuTitle"
    >
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/slides/logo.jpg') }}" alt="Biogenix Logo" width="40" height="40" decoding="async" class="h-10 w-10 rounded-2xl object-cover">
                <div>
                    <p id="mobileMenuTitle" class="text-base font-semibold tracking-tight text-slate-950">Biogenix Menu</p>
                    <p class="text-xs font-medium text-slate-400">Mobile navigation</p>
                </div>
            </div>

            <button
                id="mobileMenuClose"
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50"
                aria-label="Close mobile menu"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 space-y-4 overflow-y-auto px-4 py-4">
            <section class="rounded-[28px] border border-slate-200 bg-[linear-gradient(145deg,#eff6ff_0%,#dbeafe_100%)] p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-primary-700 shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-base font-semibold text-slate-950">{{ $authUser?->name ?? 'Welcome to Biogenix' }}</p>
                        <p class="mt-1 truncate text-sm text-slate-500">{{ $authUser?->email ?? 'Browse products, quotes, and support from one place.' }}</p>
                        <span class="mt-3 inline-flex rounded-full bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-primary-700">
                            {{ $accountTypeLabel }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    @auth
                        <a href="{{ $profileHref }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-4 text-sm font-semibold text-white no-underline transition hover:bg-slate-800 hover:text-white">
                            Open Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-4 text-sm font-semibold text-white no-underline transition hover:bg-slate-800 hover:text-white">
                            Login
                        </a>
                        <a href="{{ route('signup') }}" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </section>

            <section class="rounded-[26px] border border-slate-200 bg-white p-3 shadow-sm">
                <div class="mb-3 flex items-center justify-between px-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Navigate</p>
                    <span class="text-xs font-medium text-slate-400">{{ count($navItems) }} links</span>
                </div>

                <div class="space-y-2">
                    @foreach ($navItems as $nav)
                        <a
                            href="{{ $nav['href'] }}"
                            class="flex items-center justify-between rounded-2xl px-3 py-3 text-sm font-semibold no-underline transition {{ $currentRoute === $nav['route'] ? 'bg-primary-50 text-primary-700 hover:text-primary-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}"
                        >
                            <span>{{ $nav['label'] }}</span>
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m9 6 6 6-6 6"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[26px] border border-slate-200 bg-white p-3 shadow-sm">
                <div class="mb-3 flex items-center justify-between px-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Quick Actions</p>
                    <span class="text-xs font-medium text-slate-400">Fast access</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($mobileQuickActions as $action)
                        <a href="{{ $action['href'] }}" class="rounded-[22px] border border-slate-200 bg-slate-50 p-3 text-left no-underline transition hover:border-primary-200 hover:bg-primary-50 hover:text-slate-900">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-primary-700 shadow-sm">
                                @if ($action['icon'] === 'profile')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                                    </svg>
                                @elseif ($action['icon'] === 'cart')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <circle cx="9" cy="20" r="1.4"></circle>
                                        <circle cx="17" cy="20" r="1.4"></circle>
                                        <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                                    </svg>
                                @elseif ($action['icon'] === 'quote')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M3 5h18"></path>
                                        <path d="M7 3v4"></path>
                                        <path d="M17 3v4"></path>
                                        <rect x="4" y="7" width="16" height="13" rx="2"></rect>
                                        <path d="M8 11h8"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M8 10h8"></path>
                                        <path d="M8 14h5"></path>
                                        <path d="M12 3c4.97 0 9 3.58 9 8 0 1.95-.78 3.74-2.07 5.16L20 21l-5.04-1.68A10.5 10.5 0 0 1 12 20c-4.97 0-9-3.58-9-8s4.03-9 9-9Z"></path>
                                    </svg>
                                @endif
                            </span>
                            <span class="mt-3 block text-sm font-semibold text-slate-900">{{ $action['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[26px] border border-slate-200 bg-slate-50 p-4 shadow-sm">
                <p class="text-sm font-semibold text-slate-950">Need procurement help?</p>
                <p class="mt-2 text-sm leading-6 text-slate-500">Talk to our team about availability, delivery windows, or commercial quotations.</p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="tel:+91180024643649" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-100 hover:text-slate-900">
                        Call Support
                    </a>
                    <a href="mailto:support@biogenix.local" class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary-600 px-4 text-sm font-semibold text-white no-underline transition hover:bg-primary-700 hover:text-white">
                        Email Support
                    </a>
                </div>
            </section>
        </div>
    </aside>
</div>

<script>
    window.CartStore = (function () {
        const key = 'biogenix_cart_items';
        const listeners = [];

        const normalizeQuantity = function (item) {
            const raw = item && (item.quantity ?? item.qty ?? item.count ?? 1);
            const parsed = Number(raw);
            return Number.isFinite(parsed) && parsed > 0 ? parsed : 1;
        };

        const load = function () {
            try {
                return JSON.parse(localStorage.getItem(key) || '[]') || [];
            } catch (error) {
                console.error('CartStore parse failed', error);
                return [];
            }
        };

        const save = function (items) {
            localStorage.setItem(key, JSON.stringify(items));
            notify(items);
            return items;
        };

        const notify = function (items = load()) {
            listeners.forEach(function (listener) {
                listener(items);
            });
        };

        const getCount = function () {
            return load().reduce(function (sum, item) {
                return sum + normalizeQuantity(item);
            }, 0);
        };

        const mergeItem = function (newItem) {
            const items = load();
            const existing = items.find(function (item) {
                return item.productId === newItem.productId && item.variantId === newItem.variantId;
            });

            if (existing) {
                existing.quantity = normalizeQuantity(existing) + normalizeQuantity(newItem);
            } else {
                newItem.quantity = normalizeQuantity(newItem);
                items.push(newItem);
            }

            return save(items);
        };

        const updateQuantity = function (productId, variantId, quantity) {
            const items = load();
            const target = items.find(function (item) {
                return item.productId === productId && item.variantId === variantId;
            });

            if (!target) {
                return save(items);
            }

            target.quantity = Math.max(1, Number(quantity || 1));
            return save(items);
        };

        const removeItem = function (productId, variantId) {
            const items = load().filter(function (item) {
                return !(item.productId === productId && item.variantId === variantId);
            });

            return save(items);
        };

        const clear = function () {
            return save([]);
        };

        return {
            getItems: load,
            getCount: getCount,
            addItem: mergeItem,
            updateQuantity: updateQuantity,
            removeItem: removeItem,
            clear: clear,
            subscribe: function (listener) {
                listeners.push(listener);
                listener(load());
            },
        };
    }());

    document.addEventListener('DOMContentLoaded', function () {
        // ─── Cart badge sync ───
        const cartCount = document.getElementById('globalCartCount');
        if (window.CartStore && cartCount) {
            const syncCartBadge = function (items) {
                const total = items.reduce(function (sum, item) {
                    const raw = item && (item.quantity ?? item.qty ?? item.count ?? 1);
                    const parsed = Number(raw);
                    return sum + (Number.isFinite(parsed) && parsed > 0 ? parsed : 1);
                }, 0);
                cartCount.textContent = total > 99 ? '99+' : String(total);
                cartCount.classList.toggle('hidden', total <= 0);
                cartCount.classList.toggle('inline-flex', total > 0);
                cartCount.setAttribute('aria-label', total + ' items in cart');
            };

            window.CartStore.subscribe(syncCartBadge);
            window.addEventListener('pageshow', function () {
                syncCartBadge(window.CartStore.getItems());
            });
            window.addEventListener('storage', function (event) {
                if (event.key === 'biogenix_cart_items') {
                    syncCartBadge(window.CartStore.getItems());
                }
            });
        }

        // ─── Improved mobile menu toggle ───
        const menuToggle = document.querySelector('[data-menu-toggle]');
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        const menuDrawer = document.getElementById('mobileMenuDrawer');
        const menuBackdrop = document.getElementById('mobileMenuBackdrop');
        const menuClose = document.getElementById('mobileMenuClose');
        if (menuToggle && menuOverlay && menuDrawer && menuBackdrop && menuClose) {
            const mobileQuery = window.matchMedia('(max-width: 1279px)');

            const closeMenu = function () {
                menuBackdrop.classList.remove('opacity-100');
                menuBackdrop.classList.add('opacity-0');
                menuDrawer.classList.remove('translate-x-0');
                menuDrawer.classList.add('translate-x-full');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.documentElement.classList.remove('overflow-hidden');
                document.body.classList.remove('overflow-hidden');
                window.setTimeout(function () {
                    if (!menuDrawer.classList.contains('translate-x-0')) {
                        menuOverlay.classList.add('hidden');
                    }
                }, 300);
            };

            const openMenu = function () {
                if (!mobileQuery.matches) {
                    return;
                }

                menuOverlay.classList.remove('hidden');
                menuToggle.setAttribute('aria-expanded', 'true');
                document.documentElement.classList.add('overflow-hidden');
                document.body.classList.add('overflow-hidden');
                window.requestAnimationFrame(function () {
                    menuBackdrop.classList.remove('opacity-0');
                    menuBackdrop.classList.add('opacity-100');
                    menuDrawer.classList.remove('translate-x-full');
                    menuDrawer.classList.add('translate-x-0');
                });
            };

            menuToggle.addEventListener('click', function () {
                if (menuOverlay.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            menuBackdrop.addEventListener('click', closeMenu);
            menuClose.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !menuOverlay.classList.contains('hidden')) {
                    closeMenu();
                }
            });

            menuDrawer.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (mobileQuery.matches) {
                        closeMenu();
                    }
                });
            });

            const handleViewportChange = function () {
                if (!mobileQuery.matches) {
                    closeMenu();
                }
            };

            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', handleViewportChange);
            } else if (typeof mobileQuery.addListener === 'function') {
                mobileQuery.addListener(handleViewportChange);
            }

            menuBackdrop.classList.add('opacity-0');
            menuDrawer.classList.add('translate-x-full');
        }
    });
</script>
