<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Biogenix Logo" width="120" height="64" decoding="async">
        </a>

        <button class="menu-toggle" data-menu-toggle aria-label="Toggle navigation" aria-expanded="false" aria-controls="headerMainNav">Menu</button>

        <nav id="headerMainNav" class="header-nav" aria-label="Main Navigation">
            <a href="{{ route('home') }}">Home</a>
            <div class="nav-item has-dropdown">
                <button
                    type="button"
                    class="products-link"
                    data-products-toggle
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="productsDropdownMenu"
                >
                    Products &amp; Solutions
                </button>
                <div id="productsDropdownMenu" class="products-dropdown">
                    <ul id="productCategories">
                        <li><span class="ui-small">Loading categories...</span></li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('proforma.create') }}">Generate Quote</a>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('faq') }}">FAQ</a>
            <a href="{{ route('contact') }}">Contact Us</a>
            <a href="{{ route('book-meeting') }}">Book Meeting</a>

            <div class="links auth-links">
                @auth
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" id="logoutBtn" class="btn secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" id="loginBtn" class="btn">Login</a>
                    <a href="{{ route('signup') }}" id="signupBtn" class="btn secondary">Sign Up</a>
                @endauth
            </div>
            <a
                href="{{ route('cart.page') }}"
                class="ml-4 inline-flex items-center gap-2.5 rounded-2xl border border-slate-200 bg-slate-50/90 px-3.5 py-2.5 text-slate-900 no-underline shadow-[0_12px_28px_rgba(15,23,42,0.05)] transition hover:-translate-y-0.5 hover:border-[#2383eb]/25 hover:bg-white hover:text-slate-900 hover:shadow-[0_16px_34px_rgba(15,23,42,0.08)] hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#2383eb]/20 md:ml-4"
                aria-label="View cart"
            >
                <span class="relative inline-flex h-7 w-7 items-center justify-center text-slate-900">
                    <svg class="h-[1.45rem] w-[1.45rem]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="9" cy="21" r="1.4"></circle>
                        <circle cx="17" cy="21" r="1.4"></circle>
                        <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                    </svg>
                    <span
                        id="globalCartCount"
                        class="absolute -right-2 -top-1 hidden min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-red-600 px-1.5 text-[11px] font-bold leading-none text-white shadow-[0_10px_20px_rgba(239,68,68,0.24)] h-5"
                        aria-live="polite"
                        aria-atomic="true"
                    >0</span>
                </span>
                <span class="text-[13px] font-bold leading-none text-slate-900">Cart</span>
            </a>
        </nav>
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
        const cartCount = document.getElementById('globalCartCount');
        if (!window.CartStore || !cartCount) {
            return;
        }

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
    });
</script>
