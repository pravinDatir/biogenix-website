@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    <div class="relative left-1/2 w-screen -translate-x-1/2 bg-[#f4f6f8] py-6 lg:py-8" style="font-family: Inter, system-ui, sans-serif;">
        <div class="mx-auto max-w-[1700px] px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-[32px] border border-white/70 bg-[linear-gradient(120deg,#ffffff_0%,#f8fbff_58%,#edf5ff_100%)] p-6 shadow-[0_30px_70px_rgba(15,23,42,0.08)] lg:p-8">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-[760px] space-y-3">
                        <p class="text-[13px] font-semibold uppercase tracking-[0.32em] text-[#7f92b8]">Cart overview</p>
                        <div class="space-y-2">
                            <h1 class="text-[34px] font-bold leading-tight text-slate-950 lg:text-[40px]">Review your scientific procurement cart</h1>
                            <p class="max-w-[640px] text-[15px] leading-7 text-slate-600">
                                Validate quantities, compare line totals, and continue to checkout using the same premium workflow as the product pages.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="inline-flex items-center gap-2 rounded-full border border-[#dce9fb] bg-white/90 px-4 py-2 text-[13px] font-medium text-slate-600 shadow-sm">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Secure cart sync across pages
                        </div>
                        <a href="{{ route('products.index') }}" class="inline-flex h-12 items-center justify-center rounded-[16px] border border-slate-200 bg-white px-5 text-[14px] font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-[#2383eb] hover:text-[#2383eb]">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </section>

            <div class="mt-6 grid gap-6 lg:grid-cols-12 lg:items-start">
                <section class="rounded-[32px] border border-white/70 bg-white p-5 shadow-[0_28px_70px_rgba(15,23,42,0.08)] lg:col-span-8 lg:p-6">
                    <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-[22px] font-semibold text-slate-950">Cart Items</h2>
                            <p class="mt-1 text-[14px] text-slate-500">All quantities stay editable before you continue to checkout.</p>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-[#dbe7f8] bg-[#f6faff] px-4 py-2 text-[13px] font-semibold text-[#1d72d8]">
                            <span id="cartItemCount">0 items</span>
                        </div>
                    </div>

                    <div id="cartItemsList" class="mt-5 space-y-4"></div>

                    <div id="cartEmptyState" class="hidden flex-col items-center justify-center rounded-[28px] border border-dashed border-slate-200 bg-[#f8fafc] px-6 py-14 text-center shadow-sm">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#e8f2ff] text-[#2383eb] shadow-sm">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="9" cy="21" r="1.4"></circle>
                                <circle cx="17" cy="21" r="1.4"></circle>
                                <path d="M5 6h2l1.4 8h12.1l1.5-6H8"></path>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-[22px] font-semibold text-slate-950">Your cart is empty</h3>
                        <p class="mt-2 max-w-[420px] text-[14px] leading-7 text-slate-500">
                            Add products from the catalog or product detail page to build your procurement order.
                        </p>
                        <a href="{{ route('products.index') }}" class="mt-6 inline-flex h-12 items-center justify-center rounded-[16px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] px-6 text-[14px] font-semibold text-white shadow-[0_18px_34px_rgba(35,131,235,0.22)] transition hover:-translate-y-0.5">
                            Continue Shopping
                        </a>
                    </div>
                </section>

                <div class="space-y-5 lg:col-span-4">
                    <section class="sticky top-6 rounded-[32px] border border-white/70 bg-white p-6 shadow-[0_28px_70px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-[22px] font-semibold text-slate-950">Order Summary</h3>
                                <p class="mt-1 text-[13px] text-slate-500">Mirrors the purchase card on the product detail page.</p>
                            </div>
                            <div class="inline-flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#eef5ff] text-[#2383eb] shadow-sm">
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

                        <div id="cartSummaryItems" class="mt-5 space-y-3"></div>

                        <div class="mt-5 space-y-3 border-t border-slate-100 pt-5 text-[14px] text-slate-600">
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
                            <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-[18px] font-semibold text-slate-950">
                                <span>Total</span>
                                <span id="cartSummaryTotal" class="text-[#2383eb]">Rs. 0.00</span>
                            </div>
                        </div>

                        <a id="cartCheckoutButton" href="{{ route('checkout.page') }}" class="mt-6 flex h-14 w-full items-center justify-center gap-2 rounded-[18px] px-5 text-[15px] font-semibold text-white no-underline shadow-[0_18px_38px_rgba(35,131,235,0.24)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_42px_rgba(35,131,235,0.28)]" style="background: linear-gradient(135deg, #2f8fff 0%, #1d72d8 100%);">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                            Proceed to Checkout
                        </a>

                        <p class="mt-3 text-[12px] leading-6 text-slate-500">Continue to shipping, address selection, and payment review.</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-[#f4f7fb] px-3 py-2 text-[12px] font-medium text-slate-600">Secure enterprise checkout</span>
                            <span class="rounded-full bg-[#f4f7fb] px-3 py-2 text-[12px] font-medium text-slate-600">GST-ready invoices</span>
                            <span class="rounded-full bg-[#f4f7fb] px-3 py-2 text-[12px] font-medium text-slate-600">Cold-chain dispatch support</span>
                        </div>
                    </section>

                    <section class="rounded-[28px] border border-white/70 bg-white p-5 shadow-[0_24px_50px_rgba(15,23,42,0.06)]">
                        <div class="flex items-start gap-3">
                            <div class="inline-flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#ecf8ef] text-emerald-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M22 12h-4l-3 8-5-16-3 8H2"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-[16px] font-semibold text-slate-950">Need help with your order?</h4>
                                <p class="mt-1 text-[13px] leading-6 text-slate-500">Contact our bioscience support team for procurement assistance and delivery scheduling.</p>
                                <p class="mt-3 text-[13px] font-semibold text-[#2383eb]">1800-BIO-GENIX</p>
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
                        return 'Rs. 0.00';
                    }

                    return 'Rs. ' + numeric.toLocaleString('en-IN', {
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
                        <article class="rounded-[26px] border border-slate-200/80 bg-[#fbfdff] p-5 shadow-[0_18px_40px_rgba(15,23,42,0.05)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_46px_rgba(15,23,42,0.08)]">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start">
                                <div class="h-28 w-28 shrink-0 overflow-hidden rounded-[22px] bg-slate-100 shadow-sm">
                                    <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="min-w-0">
                                            <p class="text-[12px] font-semibold uppercase tracking-[0.28em] text-[#7f92b8]">Biogenix</p>
                                            <h3 class="mt-2 text-[22px] font-semibold leading-tight text-slate-950">${name}</h3>
                                            <p class="mt-2 text-[13px] font-medium text-slate-500">Model No: ${model}</p>
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                <span class="rounded-full bg-[#eef5ff] px-3 py-1.5 text-[12px] font-medium text-[#1d72d8]">Dispatch 24-48h</span>
                                                <span class="rounded-full bg-[#edf8f1] px-3 py-1.5 text-[12px] font-medium text-emerald-700">Procurement ready</span>
                                            </div>
                                        </div>
                                        <div class="rounded-[20px] border border-slate-200 bg-white px-4 py-4 text-right shadow-sm lg:min-w-[190px]">
                                            <p class="text-[12px] font-medium uppercase tracking-[0.22em] text-slate-400">Item subtotal</p>
                                            <p class="mt-2 text-[28px] font-bold leading-none text-[#2383eb]">${formatInr(subtotal)}</p>
                                            <p class="mt-2 text-[13px] text-slate-500">Unit price ${formatInr(unitPrice)}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex flex-col gap-4 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="inline-flex w-fit items-center rounded-[16px] border border-slate-200 bg-white p-1 shadow-sm">
                                            <button
                                                type="button"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-[12px] text-[20px] font-medium text-slate-500 transition hover:bg-slate-100"
                                                data-quantity-button
                                                data-direction="-1"
                                                data-product-id="${productId}"
                                                data-variant-id="${variantId}"
                                                aria-label="Decrease quantity"
                                            >
                                                -
                                            </button>
                                            <span class="inline-flex min-w-[44px] items-center justify-center px-3 text-[16px] font-semibold text-slate-900">${quantity}</span>
                                            <button
                                                type="button"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-[12px] text-[20px] font-medium text-slate-500 transition hover:bg-slate-100"
                                                data-quantity-button
                                                data-direction="1"
                                                data-product-id="${productId}"
                                                data-variant-id="${variantId}"
                                                aria-label="Increase quantity"
                                            >
                                                +
                                            </button>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="text-[14px] font-semibold text-slate-900">${formatInr(unitPrice)}</span>
                                            <button
                                                type="button"
                                                class="inline-flex h-11 items-center justify-center rounded-[14px] border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-600 shadow-sm transition hover:border-[#ef4444] hover:text-[#ef4444]"
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
                        <div class="flex items-center gap-3 rounded-[20px] border border-slate-100 bg-[#fbfdff] px-3 py-3 shadow-sm">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-[16px] bg-slate-100">
                                <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[13px] font-semibold text-slate-900">${name}</p>
                                <p class="mt-1 text-[12px] text-slate-500">Qty: ${quantity}</p>
                            </div>
                            <span class="text-[13px] font-semibold text-[#2383eb]">${formatInr(total)}</span>
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
                        subtotalEl.textContent = 'Rs. 0.00';
                        taxEl.textContent = 'Rs. 0.00';
                        totalEl.textContent = 'Rs. 0.00';
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
                    subtotalEl.textContent = formatInr(subtotal);
                    taxEl.textContent = formatInr(tax);
                    totalEl.textContent = formatInr(subtotal + tax);

                    bindActions();
                };

                window.CartStore.subscribe(render);
            });
        </script>
    @endpush
@endsection
