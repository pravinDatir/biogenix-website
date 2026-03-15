{{-- Cart Sidebar Drawer --}}
<div id="cartSidebarOverlay" class="cart-sidebar-overlay" aria-hidden="true">
    <button id="cartSidebarBackdrop" type="button" class="cart-sidebar-backdrop" aria-label="Close cart"></button>

    <aside
        id="cartSidebarDrawer"
        class="cart-sidebar-drawer"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cartSidebarTitle"
    >
        {{-- Header --}}
        <div class="cart-sidebar__header">
            <div class="flex items-center gap-3">
                <h2 id="cartSidebarTitle" class="text-lg font-bold text-slate-950">Your Cart</h2>
                <span
                    id="cartSidebarBadge"
                    class="inline-flex items-center justify-center rounded-full bg-[#1A62E8] px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-white"
                >0 ITEMS</span>
            </div>
            <button
                id="cartSidebarClose"
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
                aria-label="Close cart"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Location Banner --}}
        <div class="cart-sidebar__location">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
            </span>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-900">LUCKNOW ORDER DETECTED</p>
                <p class="text-[10px] text-emerald-600 font-semibold">Eligible for Same-day Delivery!</p>
            </div>
        </div>

        {{-- Cart Items --}}
        <div id="cartSidebarItems" class="cart-sidebar__items"></div>

        {{-- Empty State --}}
        <div id="cartSidebarEmpty" class="cart-sidebar__empty hidden">
            <svg class="mx-auto h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="9" cy="21" r="1.4"></circle>
                <circle cx="17" cy="21" r="1.4"></circle>
                <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
            </svg>
            <p class="mt-4 text-sm font-semibold text-slate-700">Your cart is empty</p>
            <p class="mt-1 text-xs text-slate-500">Add products from the catalog to get started.</p>
            <a href="{{ route('products.index') }}" class="cart-sidebar__continue mt-5">
                Continue Shopping
            </a>
        </div>

        {{-- Summary & Checkout --}}
        <div id="cartSidebarFooter" class="cart-sidebar__footer">
            <div class="space-y-2.5 text-sm">
                <div class="flex items-center justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span id="cartSidebarSubtotal" class="font-semibold text-slate-900">Rs. 0.00</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>GST (18%)</span>
                    <span id="cartSidebarTax" class="font-semibold text-slate-900">Rs. 0.00</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>Delivery Charge</span>
                    <span class="font-bold text-emerald-600">FREE</span>
                </div>
            </div>

            <div class="mt-4 border-t border-slate-200 pt-4">
                <div class="flex items-baseline justify-between">
                    <span class="text-sm font-semibold text-primary-700">ESTIMATED TOTAL</span>
                    <div class="text-right">
                        <span id="cartSidebarTotal" class="text-2xl font-extrabold tracking-tight text-slate-950">Rs. 0.00</span>
                        <p class="mt-0.5 text-[10px] text-slate-400">Tax calculated for Other Product</p>
                    </div>
                </div>
            </div>

            <a
                id="cartSidebarCheckout"
                href="{{ route('checkout.page') }}"
                class="cart-sidebar__checkout"
            >
                Proceed to Checkout &rarr;
            </a>
        </div>
    </aside>
</div>

<style>
    /* ─── Cart Sidebar Overlay ─── */
    .cart-sidebar-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: none;
        pointer-events: none; /* Let clicks pass through to the page when closed */
    }
    .cart-sidebar-overlay.is-open {
        display: block;
        pointer-events: none; /* Keep none so clicking the page works, drawer itself has auto */
    }

    /* ─── Backdrop ─── */
    .cart-sidebar-backdrop {
        display: none; /* Disable backdrop entirely since it's not an overlay anymore */
    }

    /* ─── Drawer ─── */
    .cart-sidebar-drawer {
        position: fixed;
        top: 73px; /* Start below the 72px sticky header */
        bottom: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        width: 26rem; /* Fixed width */
        max-width: 100vw;
        background: #ffffff;
        box-shadow: -4px 0 24px rgba(15, 23, 42, 0.08);
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.32, 0.72, 0, 1);
        pointer-events: auto; /* Ensure drawer itself is clickable */
        border-left: 1px solid #e2e8f0;
        z-index: 40; /* Sit below header's z-50 so the header shadow overlays it naturally */
        overflow: hidden; /* Prevent children from expanding past viewport bottom */
    }
    .cart-sidebar-overlay.is-open .cart-sidebar-drawer {
        transform: translateX(0);
    }

    /* Keep the navbar stable while only the page content makes room for the drawer. */
    #pageWrapper > main,
    #pageWrapper > footer {
        transition: padding-right 0.35s cubic-bezier(0.32, 0.72, 0, 1);
    }

    body.cart-is-open #pageWrapper > main,
    body.cart-is-open #pageWrapper > footer {
        padding-right: 26rem; /* Match drawer width */
    }

    @media (max-width: 1024px) {
        body.cart-is-open #pageWrapper > main,
        body.cart-is-open #pageWrapper > footer {
            padding-right: 0; /* On smaller screens, it acts as an overlay */
        }
    }

    /* ─── Header ─── */
    .cart-sidebar__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    /* ─── Location Banner ─── */
    .cart-sidebar__location {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0.75rem 1.25rem 0;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1px solid #a7f3d0;
        flex-shrink: 0;
    }

    /* ─── Items Container ─── */
    .cart-sidebar__items {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 0.75rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .cart-sidebar__items::-webkit-scrollbar {
        width: 4px;
    }
    .cart-sidebar__items::-webkit-scrollbar-track {
        background: transparent;
    }
    .cart-sidebar__items::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 99px;
    }

    /* ─── Cart Item Card ─── */
    .cart-sidebar-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.875rem;
        border-radius: 1rem;
        background: #ffffff;
        transition: background 0.2s ease;
    }
    .cart-sidebar-item:hover {
        background: #f8fafc;
    }
    .cart-sidebar-item__img {
        width: 4rem;
        height: 4rem;
        flex-shrink: 0;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem;
    }
    .cart-sidebar-item__img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .cart-sidebar-item__body {
        flex: 1;
        min-width: 0;
    }
    .cart-sidebar-item__name {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        padding-right: 1.5rem;
    }
    .cart-sidebar-item__sku {
        margin-top: 0.125rem;
        font-size: 0.625rem;
        font-weight: 500;
        color: #94a3b8;
    }
    .cart-sidebar-item__right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .cart-sidebar-item__price {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
    }
    .cart-sidebar-item__arrival {
        font-size: 0.5rem;
        font-weight: 700;
        color: #0ea5e9;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* ─── Quantity Stepper ─── */
    .cart-sidebar-qty {
        display: inline-flex;
        align-items: center;
        gap: 0;
        margin-top: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.625rem;
        overflow: hidden;
        background: #f8fafc;
    }
    .cart-sidebar-qty__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .cart-sidebar-qty__btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .cart-sidebar-qty__val {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.75rem;
        height: 1.75rem;
        font-size: 0.8125rem;
        font-weight: 700;
        color: #0f172a;
        border-inline: 1px solid #e2e8f0;
    }

    /* ─── Empty State ─── */
    .cart-sidebar__empty {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem 1.25rem;
        flex: 1;
    }
    .cart-sidebar__empty.is-visible {
        display: flex;
    }

    /* ─── Footer ─── */
    .cart-sidebar__footer {
        padding: 1.25rem;
        padding-bottom: calc(1.25rem + env(safe-area-inset-bottom, 0px));
        border-top: 1px solid #e2e8f0;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, #ffffff 24%);
        box-shadow: 0 -18px 36px rgba(15, 23, 42, 0.06);
        flex-shrink: 0;
    }

    .cart-sidebar__checkout,
    .cart-sidebar__continue {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 1rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    .cart-sidebar__checkout {
        width: 100%;
        min-height: 3.25rem;
        margin-top: 1rem;
        padding: 0.9rem 1rem;
        background: linear-gradient(135deg, #1a62e8 0%, #164db8 100%);
        color: #ffffff !important;
        font: 700 13px/1 var(--font-sans);
        box-shadow: 0 18px 34px rgba(26, 98, 232, 0.24);
    }

    .cart-sidebar__checkout:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #1658d3 0%, #123f98 100%);
        color: #ffffff !important;
        text-decoration: none;
        box-shadow: 0 22px 40px rgba(26, 98, 232, 0.28);
    }

    .cart-sidebar__continue {
        min-height: 2.75rem;
        padding: 0.7rem 1.2rem;
        background: linear-gradient(135deg, #1a62e8 0%, #164db8 100%);
        color: #ffffff !important;
        font: 700 13px/1 var(--font-sans);
        box-shadow: 0 14px 28px rgba(26, 98, 232, 0.22);
    }

    .cart-sidebar__continue:hover {
        transform: translateY(-1px);
        color: #ffffff !important;
        text-decoration: none;
        box-shadow: 0 18px 32px rgba(26, 98, 232, 0.26);
    }

    .cart-sidebar__checkout:focus-visible,
    .cart-sidebar__continue:focus-visible {
        outline: 3px solid rgba(26, 98, 232, 0.22);
        outline-offset: 3px;
    }

    /* ─── Remove Button ─── */
    .cart-sidebar-item__remove {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        border: none;
        background: #fee2e2;
        color: #ef4444;
        font-size: 0.625rem;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.15s;
    }
    .cart-sidebar-item {
        position: relative;
    }
    .cart-sidebar-item:hover .cart-sidebar-item__remove {
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('cartSidebarOverlay');
        const backdrop = document.getElementById('cartSidebarBackdrop');
        const drawer = document.getElementById('cartSidebarDrawer');
        const closeBtn = document.getElementById('cartSidebarClose');
        const itemsContainer = document.getElementById('cartSidebarItems');
        const emptyState = document.getElementById('cartSidebarEmpty');
        const footer = document.getElementById('cartSidebarFooter');
        const badge = document.getElementById('cartSidebarBadge');
        const subtotalEl = document.getElementById('cartSidebarSubtotal');
        const taxEl = document.getElementById('cartSidebarTax');
        const totalEl = document.getElementById('cartSidebarTotal');

        if (!overlay || !drawer || !itemsContainer) return;

        /* ─── Open / Close ─── */
        window.openCartSidebar = function () {
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('cart-is-open');
        };

        window.closeCartSidebar = function () {
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('cart-is-open');
        };

        backdrop.addEventListener('click', window.closeCartSidebar);
        closeBtn.addEventListener('click', window.closeCartSidebar);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
                window.closeCartSidebar();
            }
        });

        /* ─── Helpers ─── */
        const formatInr = function (value) {
            const n = Number(value);
            if (!Number.isFinite(n)) return 'Rs. 0.00';
            return 'Rs. ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        const parseVariant = function (v) {
            const s = String(v || '').trim();
            return s === '' ? null : Number(s);
        };

        /* ─── Render ─── */
        const getLineSubtotal = function (item) {
            // Step 1: prefer the backend subtotal when the current cart line came from the authenticated cart.
            if (Number.isFinite(Number(item.lineSubtotal))) {
                return Number(item.lineSubtotal);
            }

            // Step 2: keep the existing guest subtotal fallback for pre-login browsing.
            return Number(item.unitPrice || 0) * Math.max(1, Number(item.quantity || 1));
        };

        const getLineTax = function (item) {
            // Step 1: prefer the backend tax when the current cart line came from the authenticated cart.
            if (Number.isFinite(Number(item.taxAmount))) {
                return Number(item.taxAmount);
            }

            // Step 2: keep the existing guest GST fallback for pre-login browsing.
            return getLineSubtotal(item) * 0.18;
        };

        const getLineTotal = function (item) {
            // Step 1: prefer the backend total when the current cart line came from the authenticated cart.
            if (Number.isFinite(Number(item.lineTotal))) {
                return Number(item.lineTotal);
            }

            // Step 2: keep the existing guest total fallback for pre-login browsing.
            return getLineSubtotal(item) + getLineTax(item);
        };

        const renderItem = function (item) {
            const qty = Math.max(1, Number(item.quantity || 1));
            const lineTotal = getLineTotal(item);
            const img = String(item.image || 'https://via.placeholder.com/150x150?text=Bio');
            const name = String(item.name || 'Product');
            const model = String(item.model || '');
            const productId = Number(item.productId || 0);
            const variantId = item.variantId == null ? '' : String(item.variantId);

            return `
                <div class="cart-sidebar-item" data-pid="${productId}" data-vid="${variantId}">
                    <button type="button" class="cart-sidebar-item__remove" data-sidebar-remove data-product-id="${productId}" data-variant-id="${variantId}" title="Remove">&times;</button>
                    <div class="cart-sidebar-item__img">
                        <img src="${img}" alt="${name}" loading="lazy">
                    </div>
                    <div class="cart-sidebar-item__body">
                        <div class="cart-sidebar-item__name">${name}</div>
                        ${model ? '<div class="cart-sidebar-item__sku">SKU: ' + model + '</div>' : ''}
                        <div class="cart-sidebar-qty">
                            <button type="button" class="cart-sidebar-qty__btn" data-sidebar-qty="-1" data-product-id="${productId}" data-variant-id="${variantId}" aria-label="Decrease">−</button>
                            <span class="cart-sidebar-qty__val">${qty}</span>
                            <button type="button" class="cart-sidebar-qty__btn" data-sidebar-qty="1" data-product-id="${productId}" data-variant-id="${variantId}" aria-label="Increase">+</button>
                        </div>
                    </div>
                    <div class="cart-sidebar-item__right">
                        <div class="cart-sidebar-item__price">${formatInr(lineTotal)}</div>
                        <div class="cart-sidebar-item__arrival">ARRIVING TOMORROW</div>
                    </div>
                </div>
            `;
        };

        const render = function () {
            if (!window.CartStore) return;
            const items = window.CartStore.getItems();
            const totalUnits = items.reduce(function (s, i) {
                return s + Math.max(1, Number(i.quantity || 1));
            }, 0);

            badge.textContent = totalUnits + ' ITEM' + (totalUnits !== 1 ? 'S' : '');

            if (!items.length) {
                itemsContainer.innerHTML = '';
                itemsContainer.classList.add('hidden');
                emptyState.classList.add('is-visible');
                footer.classList.add('hidden');
                return;
            }

            emptyState.classList.remove('is-visible');
            itemsContainer.classList.remove('hidden');
            footer.classList.remove('hidden');

            itemsContainer.innerHTML = items.map(renderItem).join('');

            let subtotal = 0;
            let tax = 0;
            let total = 0;
            items.forEach(function (item) {
                subtotal += getLineSubtotal(item);
                tax += getLineTax(item);
                total += getLineTotal(item);
            });

            subtotalEl.textContent = formatInr(subtotal);
            taxEl.textContent = formatInr(tax);
            totalEl.textContent = formatInr(total);

            bindSidebarActions();
        };

        const bindSidebarActions = function () {
            document.querySelectorAll('[data-sidebar-qty]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const productId = Number(btn.dataset.productId || 0);
                    const variantId = parseVariant(btn.dataset.variantId);
                    const direction = Number(btn.dataset.sidebarQty || 0);
                    const items = window.CartStore.getItems();
                    const target = items.find(function (i) {
                        return Number(i.productId || 0) === productId && (i.variantId ?? null) === variantId;
                    });
                    if (!target) return;
                    const nextQty = Math.max(1, Number(target.quantity || 1) + direction);
                    window.CartStore.updateQuantity(productId, variantId, nextQty);
                });
            });

            document.querySelectorAll('[data-sidebar-remove]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const productId = Number(btn.dataset.productId || 0);
                    const variantId = parseVariant(btn.dataset.variantId);
                    window.CartStore.removeItem(productId, variantId);
                });
            });
        };

        if (window.CartStore) {
            window.CartStore.subscribe(render);
        }
    });
</script>
