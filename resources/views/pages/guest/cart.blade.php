@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    @php
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        $currentHost = parse_url(url()->to('/'), PHP_URL_HOST);
        $previousHost = $previousUrl ? parse_url($previousUrl, PHP_URL_HOST) : null;
        $backUrl = filled($previousUrl) && $previousUrl !== $currentUrl && (! $previousHost || $previousHost === $currentHost)
            ? $previousUrl
            : route('products.index');
        $pageWrapClass = 'mx-auto w-full max-w-none px-3 py-4 sm:px-6 sm:py-6 lg:px-8 xl:px-10';
        $backLinkClass = 'mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900';
        $heroClass = 'rounded-[28px] border border-slate-200 bg-[linear-gradient(135deg,#ffffff_0%,#f4f7fb_58%,#dbeafe_100%)] p-4 shadow-sm sm:p-6 md:rounded-[32px] md:p-8';
        $eyebrowClass = 'text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400';
        $titleClass = 'mt-3 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl md:text-4xl';
        $leadClass = 'mt-3 text-sm leading-7 text-slate-600 md:text-base';
        $chipSuccessClass = 'inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700';
        $buttonSecondaryClass = 'inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50';
        $buttonPrimaryClass = 'inline-flex h-14 items-center justify-center rounded-2xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700';
        $layoutGridClass = 'mt-6 grid gap-5 xl:mt-8 xl:grid-cols-[minmax(0,1fr)_24rem] xl:gap-6';
        $cardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-6 md:rounded-[28px] md:p-8';
        $summaryCardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-6 md:rounded-[28px] md:p-8 xl:sticky xl:top-6';
        $sectionTitleClass = 'text-xl font-semibold text-slate-950';
        $sectionCopyClass = 'mt-1 text-sm leading-6 text-slate-500';
        $iconTilePrimaryClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-50 text-primary-700';
        $iconTileSuccessClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700';
        $helpCardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5 md:rounded-[28px]';
    @endphp

    <div class="{{ $pageWrapClass }}">
        <div>
            <a href="{{ $backUrl }}" class="{{ $backLinkClass }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                <span>Back</span>
            </a>

            <section class="{{ $heroClass }}">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="{{ $eyebrowClass }}">Cart Overview</p>
                        <h1 class="{{ $titleClass }}">Review your scientific procurement cart</h1>
                        <p class="{{ $leadClass }}">
                            Validate quantities, compare line totals, and continue to checkout using the same shared design system as the catalog and product detail flows.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="{{ $chipSuccessClass }}">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Secure cart sync across pages
                        </div>
                        <a href="{{ route('products.index') }}" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </section>

            <div class="{{ $layoutGridClass }}">
                <section class="{{ $cardClass }}">
                    <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="{{ $sectionTitleClass }}">Cart Items</h2>
                            <p class="{{ $sectionCopyClass }}">All quantities stay editable before you continue to checkout.</p>
                        </div>
                        <div class="inline-flex items-center rounded-full border border-primary-100 bg-primary-50 px-3 py-1.5 text-sm font-semibold text-primary-700">
                            <span id="cartItemCount">0 items</span>
                        </div>
                    </div>

                    <div id="cartItemsList" class="mt-4 space-y-3"></div>

                    <x-ui.empty-state
                        id="cartEmptyState"
                        compact
                        icon="order"
                        title="Your cart is empty"
                        description="Add products from the catalog or product detail page to build your procurement order."
                        :action-href="route('products.index')"
                        action-label="Continue Shopping"
                    />
                </section>

                <div class="space-y-5">
                    <section class="{{ $summaryCardClass }}">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="{{ $sectionTitleClass }}">Order Summary</h3>
                                <p class="{{ $sectionCopyClass }}">Mirrors the purchase summary used across the storefront.</p>
                            </div>
                            <div class="{{ $iconTilePrimaryClass }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M3 7h18"></path>
                                    <path d="M7 3v4"></path>
                                    <path d="M17 3v4"></path>
                                    <path d="M6 12h4"></path>
                                    <path d="M6 16h6"></path>
                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                </svg>
                            </div>
                        </div>

                        <div id="cartSummaryItems" class="mt-4 space-y-2.5"></div>

                        <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span id="cartSummarySubtotal" class="font-medium text-slate-900">Rs. 0.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping</span>
                                <span class="font-semibold text-emerald-700">FREE</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>GST (18%)</span>
                                <span id="cartSummaryTax" class="font-medium text-slate-900">Rs. 0.00</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base font-semibold text-slate-950">
                                <span>Total</span>
                                <span id="cartSummaryTotal">Rs. 0.00</span>
                            </div>
                        </div>

                        <a id="cartCheckoutButton" href="{{ route('checkout.page') }}" class="{{ $buttonPrimaryClass }} mt-5 flex w-full gap-2">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                            Proceed to Checkout
                        </a>

                        <p class="mt-3 text-xs leading-6 text-slate-500">Continue to shipping, address selection, and payment review.</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <x-ui.status-badge type="cart" value="secure_enterprise_checkout" label="Secure enterprise checkout" />
                            <x-ui.status-badge type="cart" value="gst_ready_invoices" label="GST-ready invoices" />
                            <x-ui.status-badge type="cart" value="cold_chain_dispatch_support" label="Cold-chain dispatch support" />
                        </div>
                    </section>

                    <section class="{{ $helpCardClass }}">
                        <div class="flex items-start gap-3">
                            <div class="{{ $iconTileSuccessClass }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M22 12h-4l-3 8-5-16-3 8H2"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-base font-semibold text-slate-950">Need help with your order?</h4>
                                <p class="mt-1 text-sm leading-6 text-slate-500">Contact our bioscience support team for procurement assistance and delivery scheduling.</p>
                                <p class="mt-3 text-sm font-semibold text-primary-700">1800-BIO-GENIX</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cartList = document.getElementById('cartItemsList');
                const emptyState = document.getElementById('cartEmptyState');
                const summaryItems = document.getElementById('cartSummaryItems');
                const subtotalEl = document.getElementById('cartSummarySubtotal');
                const taxEl = document.getElementById('cartSummaryTax');
                const totalEl = document.getElementById('cartSummaryTotal');
                const itemCount = document.getElementById('cartItemCount');
                const checkoutButton = document.getElementById('cartCheckoutButton');

                if (!window.CartStore || !cartList || !summaryItems) {
                    return;
                }

                const formatInr = function (value) {
                    const numeric = Number(value);
                    if (!Number.isFinite(numeric)) {
                        return '<span class="currency-symbol">Rs.</span> 0.00';
                    }

                    return '<span class="currency-symbol">Rs.</span> ' + numeric.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                };

                const renderLineCard = function (item) {
                    const quantity = Math.max(1, Number(item.quantity || 1));
                    const unitPrice = Number(item.unitPrice || 0);
                    const subtotal = unitPrice * quantity;
                    const image = String(item.image || 'https://via.placeholder.com/220x220?text=Biogenix');
                    const model = String(item.model || 'N/A');
                    const name = String(item.name || 'Product');
                    const productId = Number(item.productId || 0);
                    const variantId = item.variantId === null || item.variantId === undefined ? '' : String(item.variantId);

                    return `
                        <article class="rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:rounded-[28px] sm:p-5">
                            <div class="flex flex-col gap-4 sm:gap-5 lg:flex-row lg:items-start">
                                <div class="h-24 w-24 shrink-0 overflow-hidden rounded-3xl bg-slate-100 sm:h-28 sm:w-28">
                                    <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Biogenix</p>
                                            <h3 class="mt-1 break-words text-lg font-semibold leading-tight text-slate-950 sm:text-xl">${name}</h3>
                                            <p class="mt-1.5 text-sm font-medium text-slate-500">Model No: ${model}</p>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center rounded-full border border-primary-100 bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">Dispatch 24-48h</span>
                                                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Procurement ready</span>
                                            </div>
                                        </div>
                                        <div class="text-left sm:text-right">
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Item Subtotal</p>
                                            <p class="mt-2 text-xl font-bold leading-none text-primary-700 sm:text-2xl">${formatInr(subtotal)}</p>
                                            <p class="mt-2 text-sm text-slate-500">Unit price ${formatInr(unitPrice)}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex flex-col gap-4 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="inline-flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-1 sm:w-auto sm:justify-normal">
                                            <button
                                                type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-lg font-semibold text-slate-700 transition hover:bg-white hover:text-primary-700"
                                                data-quantity-button
                                                data-direction="-1"
                                                data-product-id="${productId}"
                                                data-variant-id="${variantId}"
                                                aria-label="Decrease quantity"
                                            >
                                                -
                                            </button>
                                            <span class="inline-flex min-w-[44px] items-center justify-center px-3 text-base font-semibold text-slate-900">${quantity}</span>
                                            <button
                                                type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-lg font-semibold text-slate-700 transition hover:bg-white hover:text-primary-700"
                                                data-quantity-button
                                                data-direction="1"
                                                data-product-id="${productId}"
                                                data-variant-id="${variantId}"
                                                aria-label="Increase quantity"
                                            >
                                                +
                                            </button>
                                        </div>

                                        <div class="flex w-full flex-wrap items-center justify-between gap-3 sm:w-auto sm:justify-normal">
                                            <span class="text-base font-semibold text-slate-900">${formatInr(unitPrice)}</span>
                                            <button
                                                type="button"
                                                class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                                data-remove-button
                                                data-product-id="${productId}"
                                                data-variant-id="${variantId}"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    `;
                };

                const renderSummaryRow = function (item) {
                    const quantity = Math.max(1, Number(item.quantity || 1));
                    const total = Number(item.unitPrice || 0) * quantity;
                    const image = String(item.image || 'https://via.placeholder.com/96x96?text=Bio');
                    const name = String(item.name || 'Product');

                    return `
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                                <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">${name}</p>
                                <p class="mt-1 text-xs text-slate-500">Qty: ${quantity}</p>
                            </div>
                            <span class="text-sm font-semibold text-primary-700">${formatInr(total)}</span>
                        </div>
                    `;
                };

                const parseVariant = function (value) {
                    const stringValue = String(value || '').trim();
                    return stringValue === '' ? null : Number(stringValue);
                };

                const bindActions = function () {
                    document.querySelectorAll('[data-quantity-button]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const productId = Number(button.dataset.productId || 0);
                            const variantId = parseVariant(button.dataset.variantId);
                            const direction = Number(button.dataset.direction || 0);
                            const items = window.CartStore.getItems();
                            const targetItem = items.find(function (item) {
                                return Number(item.productId || 0) === productId && (item.variantId ?? null) === variantId;
                            });

                            if (!targetItem) {
                                return;
                            }

                            const nextQuantity = Math.max(1, Number(targetItem.quantity || 1) + direction);
                            window.CartStore.updateQuantity(productId, variantId, nextQuantity);
                        });
                    });

                    document.querySelectorAll('[data-remove-button]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const productId = Number(button.dataset.productId || 0);
                            const variantId = parseVariant(button.dataset.variantId);
                            window.CartStore.removeItem(productId, variantId);
                        });
                    });
                };

                const render = function () {
                    const items = window.CartStore.getItems();
                    const totalUnits = items.reduce(function (sum, item) {
                        return sum + Math.max(1, Number(item.quantity || 1));
                    }, 0);

                    cartList.innerHTML = '';
                    summaryItems.innerHTML = '';

                    if (!items.length) {
                        emptyState.classList.remove('hidden');
                        emptyState.classList.add('flex');
                        itemCount.textContent = '0 items';
                        subtotalEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        taxEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        totalEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        if (checkoutButton) {
                            checkoutButton.classList.add('opacity-70');
                        }
                        return;
                    }

                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                    if (checkoutButton) {
                        checkoutButton.classList.remove('opacity-70');
                    }

                    let subtotal = 0;
                    items.forEach(function (item) {
                        const quantity = Math.max(1, Number(item.quantity || 1));
                        const lineSubtotal = Number(item.unitPrice || 0) * quantity;
                        subtotal += lineSubtotal;

                        cartList.insertAdjacentHTML('beforeend', renderLineCard(item));
                        summaryItems.insertAdjacentHTML('beforeend', renderSummaryRow(item));
                    });

                    const tax = subtotal * 0.18;
                    itemCount.textContent = totalUnits + (totalUnits === 1 ? ' item' : ' items');
                    subtotalEl.innerHTML = formatInr(subtotal);
                    taxEl.innerHTML = formatInr(tax);
                    totalEl.innerHTML = formatInr(subtotal + tax);

                    bindActions();
                };

                window.CartStore.subscribe(render);
            });
        </script>
    @endpush
@endsection
