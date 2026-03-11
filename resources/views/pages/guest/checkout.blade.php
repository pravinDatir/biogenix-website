@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="relative left-1/2 w-screen -translate-x-1/2 bg-[#f4f6f8] py-6 lg:py-8" style="font-family: Inter, system-ui, sans-serif;">
        <div class="mx-auto max-w-[1700px] px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-[32px] border border-white/70 bg-[linear-gradient(120deg,#ffffff_0%,#f8fbff_62%,#edf5ff_100%)] p-6 shadow-[0_30px_70px_rgba(15,23,42,0.08)] lg:p-8">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-[760px] space-y-3">
                        <p class="text-[13px] font-semibold uppercase tracking-[0.32em] text-[#7f92b8]">Checkout</p>
                        <h1 class="text-[34px] font-bold leading-tight text-slate-950 lg:text-[40px]">Complete your professional supply order</h1>
                        <p class="max-w-[640px] text-[15px] leading-7 text-slate-600">
                            Review shipping, delivery, payment method, and your final order summary in the same design system used across the catalog and product detail pages.
                        </p>
                    </div>
                    <a href="{{ route('cart.page') }}" class="inline-flex h-12 items-center justify-center rounded-[16px] border border-slate-200 bg-white px-5 text-[14px] font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-[#2383eb] hover:text-[#2383eb]">
                        Back to Cart
                    </a>
                </div>
            </section>

            <div class="mt-6 grid gap-6 lg:grid-cols-12 lg:items-start">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-[32px] border border-white/70 bg-white p-5 shadow-[0_28px_70px_rgba(15,23,42,0.08)] lg:p-6">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#2383eb] text-[18px] font-semibold text-white shadow-[0_14px_28px_rgba(35,131,235,0.24)]">1</span>
                            <div>
                                <h2 class="text-[22px] font-semibold text-slate-950">Shipping Address</h2>
                                <p class="mt-1 text-[14px] text-slate-500">Choose the destination for this order.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 lg:grid-cols-3">
                            <button
                                type="button"
                                class="checkout-address-card group relative flex min-h-[190px] flex-col rounded-[24px] border-2 border-[#2383eb] bg-[linear-gradient(160deg,#ffffff_0%,#f3f8ff_100%)] p-5 text-left shadow-[0_18px_34px_rgba(35,131,235,0.16)] transition"
                                data-address-card
                                data-selected="true"
                            >
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-[#e7f1ff] text-[#2383eb] shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M3 10.5 12 3l9 7.5"></path>
                                        <path d="M5 9.5V21h14V9.5"></path>
                                        <path d="M9 21v-6h6v6"></path>
                                    </svg>
                                </span>
                                <span class="mt-5 text-[16px] font-semibold text-slate-950">Corporate Lab - HQ</span>
                                <span class="mt-2 text-[14px] leading-7 text-slate-500">123 Science Park, Phase II<br>Lucknow, UP 226010</span>
                                <span class="mt-auto pt-5 text-[13px] font-semibold text-[#2383eb]">Selected</span>
                                <span class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#2383eb] shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            </button>

                            <button
                                type="button"
                                class="checkout-address-card flex min-h-[190px] flex-col rounded-[24px] border border-slate-200 bg-white p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-[#cfe0f7] hover:shadow-[0_18px_32px_rgba(15,23,42,0.06)]"
                                data-address-card
                            >
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-slate-100 text-slate-400 shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M4 4h16v16H4z"></path>
                                        <path d="M8 2v4"></path>
                                        <path d="M16 2v4"></path>
                                        <path d="M4 10h16"></path>
                                    </svg>
                                </span>
                                <span class="mt-5 text-[16px] font-semibold text-slate-950">Research Center Alpha</span>
                                <span class="mt-2 text-[14px] leading-7 text-slate-500">45 Biotech Zone, South Gate<br>Lucknow, UP 226002</span>
                                <span class="mt-auto pt-5 text-[13px] font-medium text-slate-400">Available address</span>
                            </button>

                            <button
                                type="button"
                                class="checkout-address-card flex min-h-[190px] flex-col items-center justify-center rounded-[24px] border border-dashed border-slate-300 bg-white/70 p-5 text-center text-slate-500 shadow-sm transition hover:border-[#2383eb] hover:text-[#2383eb]"
                                data-address-card
                            >
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-[#eef5ff] text-[#2383eb]">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M12 5v14"></path>
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </span>
                                <span class="mt-5 text-[16px] font-semibold">Add New Address</span>
                                <span class="mt-2 text-[13px] leading-6">Create another dispatch point for a different lab or facility.</span>
                            </button>
                        </div>
                    </section>

                    <section class="rounded-[32px] border border-white/70 bg-white p-5 shadow-[0_28px_70px_rgba(15,23,42,0.08)] lg:p-6">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#2383eb] text-[18px] font-semibold text-white shadow-[0_14px_28px_rgba(35,131,235,0.24)]">2</span>
                            <div>
                                <h2 class="text-[22px] font-semibold text-slate-950">Delivery Method</h2>
                                <p class="mt-1 text-[14px] text-slate-500">Fast tracked medical and biotech shipment options.</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-[24px] border border-[#d8e8ff] bg-[linear-gradient(160deg,#f8fbff_0%,#eef5ff_100%)] p-5 shadow-sm">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-start gap-4">
                                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-[#2383eb] shadow-sm">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 3 4 14h7l-1 7 10-12h-7l1-6Z"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-[18px] font-semibold text-[#1d72d8]">Lucknow Same-Day Delivery</p>
                                            <span class="rounded-full bg-[#2383eb] px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white">Fastest</span>
                                        </div>
                                        <p class="mt-2 text-[14px] leading-7 text-slate-600">
                                            Order within the next <span class="font-semibold text-slate-900">2h 14m</span> to receive it before 8 PM today.
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[18px] font-semibold text-slate-950">FREE</p>
                                    <p class="mt-1 text-[13px] text-slate-500">Priority tier included</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[32px] border border-white/70 bg-white p-5 shadow-[0_28px_70px_rgba(15,23,42,0.08)] lg:p-6">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#2383eb] text-[18px] font-semibold text-white shadow-[0_14px_28px_rgba(35,131,235,0.24)]">3</span>
                            <div>
                                <h2 class="text-[22px] font-semibold text-slate-950">Payment Method</h2>
                                <p class="mt-1 text-[14px] text-slate-500">Use the same premium payment card style as the product detail buy box.</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-4">
                            <label class="payment-option group flex cursor-pointer items-center gap-4 rounded-[22px] border-2 border-[#2383eb] bg-[#f7fbff] px-5 py-4 shadow-sm transition" data-payment-card>
                                <input type="radio" name="payment_method" value="card" class="h-4 w-4 text-[#2383eb]" checked>
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[#2383eb] shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[16px] font-semibold text-slate-950">Credit / Debit Card</p>
                                    <p class="mt-1 text-[13px] text-slate-500">Secure via Stripe</p>
                                </div>
                            </label>

                            <label class="payment-option group flex cursor-pointer items-center gap-4 rounded-[22px] border border-slate-200 bg-white px-5 py-4 shadow-sm transition hover:border-[#d8e8ff] hover:shadow-[0_16px_32px_rgba(15,23,42,0.05)]" data-payment-card>
                                <input type="radio" name="payment_method" value="banking" class="h-4 w-4 text-[#2383eb]">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-slate-100 text-slate-500 shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M3 10h18"></path>
                                        <path d="M6 10v8"></path>
                                        <path d="M10 10v8"></path>
                                        <path d="M14 10v8"></path>
                                        <path d="M18 10v8"></path>
                                        <path d="M2 21h20"></path>
                                        <path d="M12 3 2 8h20L12 3Z"></path>
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[16px] font-semibold text-slate-950">Net Banking</p>
                                    <p class="mt-1 text-[13px] text-slate-500">Corporate banking for institutional purchases.</p>
                                </div>
                            </label>

                            <label class="payment-option group flex cursor-pointer items-center gap-4 rounded-[22px] border border-slate-200 bg-white px-5 py-4 shadow-sm transition hover:border-[#d8e8ff] hover:shadow-[0_16px_32px_rgba(15,23,42,0.05)]" data-payment-card>
                                <input type="radio" name="payment_method" value="po" class="h-4 w-4 text-[#2383eb]">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] bg-slate-100 text-slate-500 shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <path d="M8 13h8"></path>
                                        <path d="M8 17h5"></path>
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[16px] font-semibold text-slate-950">B2B Purchase Order (PO)</p>
                                    <p class="mt-1 text-[13px] text-slate-500">Available for verified institutional procurement teams.</p>
                                </div>
                            </label>
                        </div>

                        <div id="poUploadPanel" class="mt-4 hidden rounded-[24px] border border-dashed border-slate-300 bg-[#fafcff] p-6 text-center shadow-sm">
                            <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#eef5ff] text-[#2383eb]">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M12 3v12"></path>
                                    <path d="m7 10 5-5 5 5"></path>
                                    <path d="M4 20h16"></path>
                                </svg>
                            </div>
                            <p class="mt-4 text-[16px] font-semibold text-slate-950">Upload Signed PO Document</p>
                            <p class="mt-2 text-[13px] text-slate-500">PDF or JPG up to 10MB. This is a UI-only placeholder and does not change backend submission.</p>
                        </div>
                    </section>
                </div>

                <div class="space-y-5 lg:col-span-4">
                    <section class="sticky top-6 rounded-[32px] border border-white/70 bg-white p-6 shadow-[0_28px_70px_rgba(15,23,42,0.08)]">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-[22px] font-semibold text-slate-950">Order Summary</h3>
                                <p class="mt-1 text-[13px] text-slate-500">Same visual treatment as the product detail price card.</p>
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

                        <div id="checkoutEmptyState" class="hidden mt-5 rounded-[24px] border border-dashed border-slate-200 bg-[#f8fafc] px-5 py-8 text-center">
                            <p class="text-[18px] font-semibold text-slate-950">No items available</p>
                            <p class="mt-2 text-[13px] leading-6 text-slate-500">Return to the cart page to add products before placing the order.</p>
                            <a href="{{ route('cart.page') }}" class="mt-5 inline-flex h-11 items-center justify-center rounded-[14px] border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm transition hover:border-[#2383eb] hover:text-[#2383eb]">
                                Back to Cart
                            </a>
                        </div>

                        <div id="checkoutSummaryItems" class="mt-5 space-y-3"></div>

                        <div class="mt-5 space-y-3 border-t border-slate-100 pt-5 text-[14px] text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span id="checkoutSubtotal" class="font-medium text-slate-900">Rs. 0.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping (Same-Day)</span>
                                <span class="font-semibold text-emerald-700">FREE</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>GST (18%)</span>
                                <span id="checkoutTax" class="font-medium text-slate-900">Rs. 0.00</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-[18px] font-semibold text-slate-950">
                                <span>Total</span>
                                <span id="checkoutTotal" class="text-[#2383eb]">Rs. 0.00</span>
                            </div>
                        </div>

                        <div id="checkoutLoginWarning" class="mt-5 hidden rounded-[20px] border border-amber-200 bg-amber-50 px-4 py-4 text-[13px] text-amber-900 shadow-sm">
                            <p class="font-semibold">Login required</p>
                            <p class="mt-1 leading-6 text-amber-800">Please sign in at the final checkout step to place this order and continue with billing.</p>
                            <a href="{{ route('login') }}" class="mt-4 inline-flex h-11 items-center justify-center rounded-[14px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] px-4 text-[13px] font-semibold text-white shadow-[0_12px_24px_rgba(35,131,235,0.18)] transition hover:-translate-y-0.5">
                                Login
                            </a>
                        </div>

                        <button id="placeOrderButton" type="button" class="mt-6 inline-flex h-14 w-full items-center justify-center rounded-[18px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] text-[15px] font-semibold text-white shadow-[0_18px_38px_rgba(35,131,235,0.24)] transition hover:-translate-y-0.5">
                            Place Order
                        </button>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-[#f4f7fb] px-3 py-2 text-[12px] font-medium text-slate-600">Bank-grade encrypted transaction</span>
                            <span class="rounded-full bg-[#f4f7fb] px-3 py-2 text-[12px] font-medium text-slate-600">Validated lab documentation</span>
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
                                <p class="mt-1 text-[13px] leading-6 text-slate-500">Contact our bioscience experts for delivery scheduling, compliance, and procurement support.</p>
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
                const summaryItems = document.getElementById('checkoutSummaryItems');
                const subtotalEl = document.getElementById('checkoutSubtotal');
                const taxEl = document.getElementById('checkoutTax');
                const totalEl = document.getElementById('checkoutTotal');
                const emptyState = document.getElementById('checkoutEmptyState');
                const loginWarning = document.getElementById('checkoutLoginWarning');
                const placeOrderButton = document.getElementById('placeOrderButton');
                const paymentCards = Array.from(document.querySelectorAll('[data-payment-card]'));
                const paymentInputs = Array.from(document.querySelectorAll('input[name="payment_method"]'));
                const addressCards = Array.from(document.querySelectorAll('[data-address-card]'));
                const poUploadPanel = document.getElementById('poUploadPanel');
                const isAuthenticated = @json(auth()->check());

                if (!window.CartStore || !summaryItems || !subtotalEl || !taxEl || !totalEl) {
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

                const renderSummaryRow = function (item) {
                    const quantity = Math.max(1, Number(item.quantity || 1));
                    const total = Number(item.unitPrice || 0) * quantity;
                    const image = String(item.image || 'https://via.placeholder.com/96x96?text=Bio');
                    const name = String(item.name || 'Product');
                    const model = String(item.model || 'N/A');

                    return `
                        <div class="flex items-center gap-3 rounded-[20px] border border-slate-100 bg-[#fbfdff] px-3 py-3 shadow-sm">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-[18px] bg-slate-100">
                                <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[14px] font-semibold text-slate-900">${name}</p>
                                <p class="mt-1 text-[12px] text-slate-500">Qty: ${quantity} | ${model}</p>
                            </div>
                            <span class="text-[14px] font-semibold text-[#2383eb]">${formatInr(total)}</span>
                        </div>
                    `;
                };

                const render = function () {
                    const items = window.CartStore.getItems();
                    summaryItems.innerHTML = '';

                    if (!items.length) {
                        emptyState.classList.remove('hidden');
                        summaryItems.classList.add('hidden');
                        subtotalEl.textContent = 'Rs. 0.00';
                        taxEl.textContent = 'Rs. 0.00';
                        totalEl.textContent = 'Rs. 0.00';
                        return;
                    }

                    emptyState.classList.add('hidden');
                    summaryItems.classList.remove('hidden');

                    let subtotal = 0;
                    items.forEach(function (item) {
                        const quantity = Math.max(1, Number(item.quantity || 1));
                        subtotal += Number(item.unitPrice || 0) * quantity;
                        summaryItems.insertAdjacentHTML('beforeend', renderSummaryRow(item));
                    });

                    const tax = subtotal * 0.18;
                    subtotalEl.textContent = formatInr(subtotal);
                    taxEl.textContent = formatInr(tax);
                    totalEl.textContent = formatInr(subtotal + tax);
                };

                const setActiveAddress = function (card) {
                    addressCards.forEach(function (node) {
                        const selected = node === card;
                        if (selected) {
                            node.dataset.selected = 'true';
                            node.classList.remove('border-slate-200', 'bg-white');
                            node.classList.add('border-[#2383eb]', 'bg-[linear-gradient(160deg,#ffffff_0%,#f3f8ff_100%)]', 'shadow-[0_18px_34px_rgba(35,131,235,0.16)]');
                        } else {
                            node.dataset.selected = 'false';
                            node.classList.remove('border-[#2383eb]', 'bg-[linear-gradient(160deg,#ffffff_0%,#f3f8ff_100%)]', 'shadow-[0_18px_34px_rgba(35,131,235,0.16)]');
                            if (!node.classList.contains('border-dashed')) {
                                node.classList.add('border-slate-200', 'bg-white');
                            }
                        }
                    });
                };

                const syncPaymentCards = function () {
                    paymentCards.forEach(function (card) {
                        const input = card.querySelector('input[type="radio"]');
                        if (!input) {
                            return;
                        }

                        if (input.checked) {
                            card.classList.remove('border-slate-200', 'bg-white');
                            card.classList.add('border-[#2383eb]', 'bg-[#f7fbff]', 'shadow-[0_16px_30px_rgba(35,131,235,0.10)]');
                        } else {
                            card.classList.remove('border-[#2383eb]', 'bg-[#f7fbff]', 'shadow-[0_16px_30px_rgba(35,131,235,0.10)]');
                            card.classList.add('border-slate-200', 'bg-white');
                        }
                    });

                    const activePayment = paymentInputs.find(function (input) {
                        return input.checked;
                    });

                    if (poUploadPanel) {
                        if (activePayment && activePayment.value === 'po') {
                            poUploadPanel.classList.remove('hidden');
                        } else {
                            poUploadPanel.classList.add('hidden');
                        }
                    }
                };

                addressCards.forEach(function (card) {
                    card.addEventListener('click', function () {
                        if (!card.classList.contains('border-dashed')) {
                            setActiveAddress(card);
                        }
                    });
                });

                paymentInputs.forEach(function (input) {
                    input.addEventListener('change', syncPaymentCards);
                });

                if (placeOrderButton) {
                    placeOrderButton.addEventListener('click', function () {
                        const items = window.CartStore.getItems();

                        if (!items.length) {
                            if (emptyState) {
                                emptyState.classList.remove('hidden');
                                emptyState.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                            return;
                        }

                        if (!isAuthenticated) {
                            if (loginWarning) {
                                loginWarning.classList.remove('hidden');
                                loginWarning.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                            return;
                        }

                        if (loginWarning) {
                            loginWarning.classList.add('hidden');
                        }
                    });
                }

                syncPaymentCards();
                render();
                window.CartStore.subscribe(render);
            });
        </script>
    @endpush
@endsection
