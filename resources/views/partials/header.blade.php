@php
    $currentRoute = request()->route()?->getName();
    $navItems = [
        ['label' => 'Home', 'route' => 'home', 'href' => route('home')],
        ['label' => 'Products & Solutions', 'route' => 'products.index', 'href' => route('products.index')],
        ['label' => 'Generate Quote', 'route' => 'proforma.create', 'href' => route('proforma.create')],
        ['label' => 'About Us', 'route' => 'about', 'href' => route('about')],
        ['label' => 'FAQ', 'route' => 'faq', 'href' => route('faq')],
        ['label' => 'Contact Us', 'route' => 'contact', 'href' => route('contact')],
        ['label' => 'Book Meeting', 'route' => 'book-meeting', 'href' => route('book-meeting')],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="relative mx-auto flex min-h-[72px] w-full max-w-none items-center gap-4 px-4 py-2 sm:px-6 lg:px-8 xl:px-10">
        <a href="{{ route('home') }}" class="shrink-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Biogenix Logo" width="120" height="64" decoding="async" class="h-14 w-auto md:h-16">
        </a>

        {{-- Mobile hamburger --}}
        <button
            class="ml-auto inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 md:hidden"
            data-menu-toggle
            aria-label="Toggle navigation"
            aria-expanded="false"
            aria-controls="headerMainNav"
        >
            <svg class="hamburger-icon h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg class="close-icon hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Desktop nav --}}
        <nav id="headerMainNav" class="header-nav hidden items-center gap-1 md:absolute md:left-1/2 md:top-1/2 md:flex md:-translate-x-1/2 md:-translate-y-1/2" aria-label="Main Navigation">
            @foreach ($navItems as $nav)
                <a
                    href="{{ $nav['href'] }}"
                    class="relative rounded-lg px-3 py-2 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900 {{ $currentRoute === $nav['route'] ? 'nav-link-active' : '' }}"
                >
                    {{ $nav['label'] }}
                </a>
            @endforeach

            {{-- Mobile-only auth & cart (inside nav for toggle) --}}
            <div class="auth-links mt-3 flex flex-col gap-2 border-t border-slate-200 pt-3 md:hidden">
                @auth
                    <span class="px-3 text-sm text-slate-600">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" id="logoutBtnMobile" class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" id="loginBtnMobile" class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-primary-600 bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Login</a>
                    <a href="{{ route('signup') }}" id="signupBtnMobile" class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Sign Up</a>
                @endauth
                <a href="{{ route('cart.page') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    View Cart
                </a>
                <a href="{{ route('customer.profile.preview') }}" class="inline-flex h-11 items-center gap-2 justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 10-16 0M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                    My Profile
                </a>
            </div>
        </nav>

        {{-- Desktop auth & cart --}}
        <div class="ml-auto hidden items-center gap-2 md:flex">
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
                href="{{ route('customer.profile.preview') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 no-underline shadow-sm transition hover:-translate-y-0.5 hover:border-primary-100 hover:bg-white hover:text-slate-900 hover:shadow-md hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/20"
                aria-label="My Profile"
            >
                <span class="inline-flex h-7 w-7 items-center justify-center text-slate-900">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M5.5 21a7.5 7.5 0 0113 0"></path>
                    </svg>
                </span>
                <span class="text-sm font-bold leading-none text-slate-900">Profile</span>
            </a>
        </div>
    </div>
</header>

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
        const mainNav = document.getElementById('headerMainNav');
        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function () {
                const isOpen = mainNav.classList.contains('active');
                mainNav.classList.toggle('active', !isOpen);
                menuToggle.setAttribute('aria-expanded', String(!isOpen));

                // Toggle hamburger/close icons
                const hamburger = menuToggle.querySelector('.hamburger-icon');
                const close = menuToggle.querySelector('.close-icon');
                if (hamburger && close) {
                    hamburger.classList.toggle('hidden', !isOpen);
                    close.classList.toggle('hidden', isOpen);
                }

                if (!isOpen) {
                    mainNav.classList.add('mobile-menu-slide');
                } else {
                    mainNav.classList.remove('mobile-menu-slide');
                }
            });
        }
    });
</script>
