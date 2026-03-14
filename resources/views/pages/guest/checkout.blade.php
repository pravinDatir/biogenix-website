@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    @php
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        $currentHost = parse_url(url()->to('/'), PHP_URL_HOST);
        $previousHost = $previousUrl ? parse_url($previousUrl, PHP_URL_HOST) : null;
        $backUrl = filled($previousUrl) && $previousUrl !== $currentUrl && (! $previousHost || $previousHost === $currentHost)
            ? $previousUrl
            : route('cart.page');
        $pageWrapClass = 'mx-auto w-full max-w-none px-3 py-4 sm:px-6 sm:py-6 lg:px-8 xl:px-10';
        $backLinkClass = 'inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:w-auto';
        $heroClass = 'rounded-[28px] border border-slate-200 bg-[linear-gradient(135deg,#ffffff_0%,#f4f7fb_58%,#dbeafe_100%)] p-4 shadow-sm sm:p-6 md:rounded-[32px] md:p-8';
        $eyebrowClass = 'text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400';
        $titleClass = 'mt-3 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl md:text-4xl';
        $leadClass = 'mt-3 text-sm leading-7 text-slate-600 md:text-base';
        $layoutGridClass = 'mt-6 grid gap-5 xl:mt-8 xl:grid-cols-[minmax(0,1fr)_24rem] xl:gap-6';
        $mainColumnClass = 'space-y-6';
        $sectionCardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-6 md:rounded-[28px] md:p-8';
        $summaryCardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-6 md:rounded-[28px] md:p-8 xl:sticky xl:top-6';
        $helpCardClass = 'rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5 md:rounded-[28px]';
        $stepClass = 'inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-sm font-semibold text-white shadow-sm';
        $sectionTitleClass = 'text-xl font-semibold text-slate-950';
        $sectionCopyClass = 'mt-1 text-sm leading-6 text-slate-500';
        $selectionCardBaseClass = 'relative flex min-h-[180px] flex-col gap-4 rounded-[24px] border p-4 text-left shadow-sm transition duration-200 sm:min-h-[220px] sm:rounded-[28px] sm:p-5';
        $selectionCardActiveClass = 'border-primary-200 bg-primary-50';
        $selectionCardInactiveClass = 'border-slate-200 bg-white hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-md';
        $paymentCardBaseClass = 'flex flex-col gap-3 rounded-[24px] border p-4 shadow-sm transition duration-200 sm:flex-row sm:items-start sm:gap-4 sm:rounded-[28px] sm:p-5';
        $paymentCardActiveClass = 'border-primary-200 bg-primary-50';
        $paymentCardInactiveClass = 'border-slate-200 bg-white hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-md';
        $iconTilePrimaryClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-50 text-primary-700';
        $iconTileNeutralClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600';
        $iconTileSuccessClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700';
        $buttonPrimaryClass = 'inline-flex h-14 items-center justify-center rounded-2xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
        $buttonSecondaryClass = 'inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50';
    @endphp

    <div class="{{ $pageWrapClass }}">
        <div>
            <a href="{{ $backUrl }}" class="{{ $backLinkClass }} mb-4">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                <span>Back</span>
            </a>

            <section class="{{ $heroClass }}">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="{{ $eyebrowClass }}">Checkout</p>
                        <h1 class="{{ $titleClass }}">Complete your professional supply order</h1>
                        <p class="{{ $leadClass }}">
                            Review shipping, delivery, payment method, and your final order summary in the same shared design system used across the storefront.
                        </p>
                    </div>
                    <a href="{{ route('cart.page') }}" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">
                        Back to Cart
                    </a>
                </div>
            </section>

            <div class="{{ $layoutGridClass }}">
                <div class="{{ $mainColumnClass }}">
                    <section class="{{ $sectionCardClass }}">
                        <div class="flex items-center gap-3">
                            <span class="{{ $stepClass }}">1</span>
                            <div>
                                <h2 class="{{ $sectionTitleClass }}">Shipping Address</h2>
                                <p class="{{ $sectionCopyClass }}">Choose the destination for this order.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            <button
                                type="button"
                                class="{{ $selectionCardBaseClass }} {{ $selectionCardActiveClass }}"
                                data-address-card
                                data-selected="true"
                            >
                                <span class="{{ $iconTilePrimaryClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M3 10.5 12 3l9 7.5"></path>
                                        <path d="M5 9.5V21h14V9.5"></path>
                                        <path d="M9 21v-6h6v6"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold text-slate-950">Corporate Lab - HQ</span>
                                <span class="text-sm leading-7 text-slate-500">123 Science Park, Phase II<br>Lucknow, UP 226010</span>
                                <span class="mt-auto pt-3 text-sm font-semibold text-primary-700">Selected</span>
                                <span class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary-700 shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            </button>

                            <button
                                type="button"
                                class="{{ $selectionCardBaseClass }} {{ $selectionCardInactiveClass }}"
                                data-address-card
                            >
                                <span class="{{ $iconTileNeutralClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M4 4h16v16H4z"></path>
                                        <path d="M8 2v4"></path>
                                        <path d="M16 2v4"></path>
                                        <path d="M4 10h16"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold text-slate-950">Research Center Alpha</span>
                                <span class="text-sm leading-7 text-slate-500">45 Biotech Zone, South Gate<br>Lucknow, UP 226002</span>
                                <span class="mt-auto pt-3 text-sm font-medium text-slate-400">Available address</span>
                            </button>

                            <button
                                type="button"
                                class="{{ $selectionCardBaseClass }} {{ $selectionCardInactiveClass }} items-center justify-center border-dashed text-center text-slate-500"
                                data-address-card
                            >
                                <span class="{{ $iconTilePrimaryClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M12 5v14"></path>
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold">Add New Address</span>
                                <span class="text-sm leading-6">Create another dispatch point for a different lab or facility.</span>
                            </button>
                        </div>
                    </section>

                    <section class="{{ $sectionCardClass }}">
                        <div class="flex items-center gap-3">
                            <span class="{{ $stepClass }}">2</span>
                            <div>
                                <h2 class="{{ $sectionTitleClass }}">Delivery Method</h2>
                                <p class="{{ $sectionCopyClass }}">Fast tracked medical and biotech shipment options.</p>
                            </div>
                        </div>

                        <div class="checkout-delivery-card mt-5 rounded-3xl border border-primary-100 bg-primary-50 p-5">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-start gap-4">
                                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-primary-700 shadow-sm">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 3 4 14h7l-1 7 10-12h-7l1-6Z"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-lg font-semibold text-primary-700">Lucknow Same-Day Delivery</p>
                                            <x-ui.status-badge type="cart" value="fastest" label="Fastest" />
                                        </div>
                                        <p class="mt-2 text-sm leading-7 text-slate-600">
                                            Order within the next <span class="font-semibold text-slate-900">2h 14m</span> to receive it before 8 PM today.
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-slate-950">FREE</p>
                                    <p class="mt-1 text-sm text-slate-500">Priority tier included</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="{{ $sectionCardClass }}">
                        <div class="flex items-center gap-3">
                            <span class="{{ $stepClass }}">3</span>
                            <div>
                                <h2 class="{{ $sectionTitleClass }}">Payment Method</h2>
                                <p class="{{ $sectionCopyClass }}">Select the payment option best aligned to your procurement workflow.</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-4">
                            <label class="{{ $paymentCardBaseClass }} {{ $paymentCardActiveClass }}" data-payment-card>
                                <input type="radio" name="payment_method" value="card" class="h-4 w-4 text-primary-600" checked>
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-primary-700">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-base font-semibold text-slate-950">Credit / Debit Card</p>
                                    <p class="mt-1 text-sm text-slate-500">Secure via Stripe</p>
                                </div>
                            </label>

                            <label class="{{ $paymentCardBaseClass }} {{ $paymentCardInactiveClass }}" data-payment-card>
                                <input type="radio" name="payment_method" value="banking" class="h-4 w-4 text-primary-600">
                                <span class="{{ $iconTileNeutralClass }}">
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
                                    <p class="text-base font-semibold text-slate-950">Net Banking</p>
                                    <p class="mt-1 text-sm text-slate-500">Corporate banking for institutional purchases.</p>
                                </div>
                            </label>

                            <label class="{{ $paymentCardBaseClass }} {{ $paymentCardInactiveClass }}" data-payment-card>
                                <input type="radio" name="payment_method" value="po" class="h-4 w-4 text-primary-600">
                                <span class="{{ $iconTileNeutralClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <path d="M8 13h8"></path>
                                        <path d="M8 17h5"></path>
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-base font-semibold text-slate-950">B2B Purchase Order (PO)</p>
                                    <p class="mt-1 text-sm text-slate-500">Available for verified institutional procurement teams.</p>
                                </div>
                            </label>
                        </div>

                        <div id="poUploadPanel" class="mt-4 hidden rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6">
                            <div class="text-center">
                                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700">
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M12 3v12"></path>
                                        <path d="m7 10 5-5 5 5"></path>
                                        <path d="M4 20h16"></path>
                                    </svg>
                                </div>
                                <p class="mt-4 text-base font-semibold text-slate-950">Upload Signed PO Document</p>
                            </div>
                            <x-ui.file-upload
                                id="po_document"
                                name="po_document"
                                label="Signed purchase order"
                                hint="PDF or JPG up to 10MB. This is a UI-only placeholder and does not change backend submission."
                                class="mt-4"
                                disabled
                            />
                        </div>
                    </section>
                </div>

                <div class="space-y-5">
                    <section class="{{ $summaryCardClass }}">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="{{ $sectionTitleClass }}">Order Summary</h3>
                                <p class="{{ $sectionCopyClass }}">Shared pricing and summary treatment across checkout.</p>
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

                        <x-ui.empty-state
                            id="checkoutEmptyState"
                            compact
                            icon="order"
                            title="No items available"
                            description="Return to the cart page to add products before placing the order."
                        >
                            <x-slot:action>
                                <a href="{{ route('cart.page') }}" class="{{ $buttonSecondaryClass }} mt-5">
                                    Back to Cart
                                </a>
                            </x-slot:action>
                        </x-ui.empty-state>

                        <div id="checkoutSummaryItems" class="mt-4 space-y-2.5"></div>

                        <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm text-slate-600">
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
                            <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base font-semibold text-slate-950">
                                <span>Total</span>
                                <span id="checkoutTotal">Rs. 0.00</span>
                            </div>
                        </div>

                        <div id="checkoutLoginWarning" class="mt-4 hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
                            <p class="font-semibold">Login required</p>
                            <p class="mt-1 leading-6 text-amber-800">Please sign in at the final checkout step to place this order and continue with billing.</p>
                            <a href="{{ route('login') }}" class="{{ $buttonPrimaryClass }} mt-4">
                                Login
                            </a>
                        </div>

                        <button id="placeOrderButton" type="button" class="{{ $buttonPrimaryClass }} mt-5 w-full">
                            Place Order
                        </button>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <x-ui.status-badge type="cart" value="bank_grade_encrypted_transaction" label="Bank-grade encrypted transaction" />
                            <x-ui.status-badge type="cart" value="validated_lab_documentation" label="Validated lab documentation" />
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
                                <p class="mt-1 text-sm leading-6 text-slate-500">Contact our bioscience experts for delivery scheduling, compliance, and procurement support.</p>
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
                        return '<span class="currency-symbol">Rs.</span> 0.00';
                    }

                    return '<span class="currency-symbol">Rs.</span> ' + numeric.toLocaleString('en-IN', {
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
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                                <img src="${image}" alt="${name}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">${name}</p>
                                <p class="mt-1 text-xs text-slate-500">Qty: ${quantity} | ${model}</p>
                            </div>
                            <span class="text-sm font-semibold text-primary-700">${formatInr(total)}</span>
                        </div>
                    `;
                };

                const render = function () {
                    const items = window.CartStore.getItems();
                    summaryItems.innerHTML = '';

                    if (!items.length) {
                        emptyState.classList.remove('hidden');
                        summaryItems.classList.add('hidden');
                        subtotalEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        taxEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        totalEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
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
                    subtotalEl.innerHTML = formatInr(subtotal);
                    taxEl.innerHTML = formatInr(tax);
                    totalEl.innerHTML = formatInr(subtotal + tax);
                };

                const setActiveAddress = function (card) {
                    addressCards.forEach(function (node) {
                        const selected = node === card;
                        if (selected) {
                            node.dataset.selected = 'true';
                            node.classList.add('border-primary-200', 'bg-primary-50');
                            node.classList.remove('border-slate-200', 'bg-white');
                        } else {
                            node.dataset.selected = 'false';
                            node.classList.remove('border-primary-200', 'bg-primary-50');
                            node.classList.add('border-slate-200', 'bg-white');
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
                            card.classList.add('border-primary-200', 'bg-primary-50');
                            card.classList.remove('border-slate-200', 'bg-white');
                        } else {
                            card.classList.remove('border-primary-200', 'bg-primary-50');
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

                if (!isAuthenticated && loginWarning) {
                    loginWarning.classList.remove('hidden');
                }

                if (placeOrderButton) {
                    placeOrderButton.addEventListener('click', function () {
                        if (!isAuthenticated) {
                            if (loginWarning) {
                                loginWarning.classList.remove('hidden');
                                loginWarning.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                            return;
                        }

                        placeOrderButton.disabled = true;
                        placeOrderButton.classList.add('opacity-70', 'cursor-not-allowed');
                        setTimeout(function () {
                            placeOrderButton.disabled = false;
                            placeOrderButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        }, 800);
                    });
                }

                window.CartStore.subscribe(render);
                syncPaymentCards();
            });
        </script>
    @endpush
@endsection
