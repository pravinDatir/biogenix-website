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
        $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 outline-none transition focus:border-primary-400 focus:bg-white focus:ring-2 focus:ring-primary-600/15';
        $labelClass = 'mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500';
    @endphp

    <div class="{{ $pageWrapClass }}">
        <div>
            <a href="{{ $backUrl }}" class="{{ $backLinkClass }} mb-4">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                <span>Back</span>
            </a>

            {{-- ─── Hero ─── --}}
            <section class="{{ $heroClass }}">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="{{ $eyebrowClass }}">Checkout</p>
                        <h1 class="{{ $titleClass }}">Complete your professional supply order</h1>
                        <p class="{{ $leadClass }}">
                            Review shipping, delivery, payment method, and your final order summary in the same shared design system used across the storefront.
                        </p>
                    </div>
                    <a href="{{ route('cart.page') }}" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">Back to Cart</a>
                </div>
            </section>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- STEP PROGRESS BAR --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            <div class="mt-6 overflow-hidden rounded-[24px] border border-slate-200 bg-white px-6 py-5 shadow-sm md:rounded-[28px]">
                <div class="relative flex items-center justify-between">
                    {{-- Connecting line --}}
                    <div class="absolute left-0 right-0 top-[22px] h-0.5 bg-slate-200" aria-hidden="true">
                        <div id="stepProgressLine" class="h-full bg-primary-600 transition-all duration-500" style="width: 16.66%"></div>
                    </div>

                    @foreach ([
                        ['num' => 1, 'label' => 'Shipping Address', 'icon' => 'M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6'],
                        ['num' => 2, 'label' => 'Delivery Method',  'icon' => 'M13 3 4 14h7l-1 7 10-12h-7l1-6Z'],
                        ['num' => 3, 'label' => 'Payment',          'icon' => 'M3 6h18M3 10h18M5 6v14h14V6'],
                    ] as $step)
                        <div class="relative z-10 flex flex-col items-center gap-2 text-center" id="stepIndicator{{ $step['num'] }}">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border-2 border-primary-600 bg-white text-sm font-bold text-primary-600 transition-all duration-300 step-circle">
                                {{ $step['num'] }}
                            </span>
                            <span class="hidden text-xs font-semibold text-slate-600 sm:block">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="{{ $layoutGridClass }}">
                <div class="{{ $mainColumnClass }}">

                    {{-- ════════════════════════════════════════════════════════ --}}
                    {{-- STEP 1: SHIPPING ADDRESS --}}
                    {{-- ════════════════════════════════════════════════════════ --}}
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
                                        <path d="M3 10.5 12 3l9 7.5"></path><path d="M5 9.5V21h14V9.5"></path><path d="M9 21v-6h6v6"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold text-slate-950">Corporate Lab - HQ</span>
                                <span class="text-sm leading-7 text-slate-500">123 Science Park, Phase II<br>Lucknow, UP 226010</span>
                                <span class="mt-auto pt-3 text-sm font-semibold text-primary-700">Selected</span>
                                <span class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary-700 shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"></path></svg>
                                </span>
                            </button>

                            <button
                                type="button"
                                class="{{ $selectionCardBaseClass }} {{ $selectionCardInactiveClass }}"
                                data-address-card
                            >
                                <span class="{{ $iconTileNeutralClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M4 4h16v16H4z"></path><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M4 10h16"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold text-slate-950">Research Center Alpha</span>
                                <span class="text-sm leading-7 text-slate-500">45 Biotech Zone, South Gate<br>Lucknow, UP 226002</span>
                                <span class="mt-auto pt-3 text-sm font-medium text-slate-400">Available address</span>
                            </button>

                            {{-- Add New Address — toggle button --}}
                            <button
                                type="button"
                                id="addAddressToggleBtn"
                                class="{{ $selectionCardBaseClass }} {{ $selectionCardInactiveClass }} items-center justify-center border-dashed text-center text-slate-500"
                            >
                                <span class="{{ $iconTilePrimaryClass }}">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M12 5v14"></path><path d="M5 12h14"></path>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold">Add New Address</span>
                                <span class="text-sm leading-6">Create another dispatch point for a different lab or facility.</span>
                            </button>
                        </div>

                        {{-- ─── Add New Address Inline Form (hidden by default) ─── --}}
                        <div id="addAddressForm" class="mt-5 hidden">
                            <div class="rounded-[22px] border border-primary-200 bg-primary-50/40 p-5 md:p-6">
                                <div class="mb-5 flex items-center justify-between">
                                    <h3 class="text-base font-semibold text-slate-900">New Address Details</h3>
                                    <button type="button" id="addAddressCloseBtn" aria-label="Close form" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-900">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="{{ $labelClass }}">Address Label / Facility Name</label>
                                        <input type="text" class="{{ $inputClass }}" placeholder="e.g. North Wing Lab, Warehouse B">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="{{ $labelClass }}">Street Address</label>
                                        <input type="text" class="{{ $inputClass }}" placeholder="Building no., street, locality">
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">City</label>
                                        <input type="text" class="{{ $inputClass }}" placeholder="City">
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Pincode</label>
                                        <input type="text" class="{{ $inputClass }}" placeholder="6-digit pincode" maxlength="6" inputmode="numeric">
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">State</label>
                                        <input type="text" class="{{ $inputClass }}" placeholder="State">
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Contact Phone</label>
                                        <input type="tel" class="{{ $inputClass }}" placeholder="+91 XXXXX XXXXX">
                                    </div>
                                </div>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <button type="button" id="saveAddressBtn" class="{{ $buttonPrimaryClass }} h-11 text-sm">
                                        Save & Use This Address
                                    </button>
                                    <button type="button" id="addAddressCloseBtn2" class="{{ $buttonSecondaryClass }}">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- GST / PAN Field --}}
                        <div class="mt-5 rounded-[20px] border border-slate-200 bg-slate-50 p-4 md:p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="h-4 w-4 shrink-0 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">GST / Business Invoice Details <span class="ml-1 text-xs font-normal text-slate-400">(optional — for B2B buyers)</span></p>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="{{ $labelClass }}">GSTIN Number</label>
                                    <input
                                        type="text"
                                        id="gstinInput"
                                        class="{{ $inputClass }}"
                                        placeholder="22AAAAA0000A1Z5"
                                        maxlength="15"
                                        style="text-transform:uppercase"
                                    >
                                    <p class="mt-1 text-xs text-slate-500">15-character GST Identification Number</p>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">PAN Number</label>
                                    <input
                                        type="text"
                                        id="panInput"
                                        class="{{ $inputClass }}"
                                        placeholder="AAAPL1234C"
                                        maxlength="10"
                                        style="text-transform:uppercase"
                                    >
                                    <p class="mt-1 text-xs text-slate-500">Required for high-value orders (&gt; ₹2L)</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">Registered Business Name (for invoice)</label>
                                    <input type="text" class="{{ $inputClass }}" placeholder="As per GST registration">
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ════════════════════════════════════════════════════════ --}}
                    {{-- STEP 2: DELIVERY METHOD --}}
                    {{-- ════════════════════════════════════════════════════════ --}}
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

                        {{-- Order Notes / Special Instructions --}}
                        <div class="mt-5">
                            <label class="{{ $labelClass }}" for="orderNotes">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m-6 4h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    </svg>
                                    Order Notes / Special Instructions
                                    <span class="font-normal text-slate-400">(optional)</span>
                                </span>
                            </label>
                            <textarea
                                id="orderNotes"
                                rows="3"
                                class="{{ $inputClass }} resize-none"
                                placeholder="e.g. Deliver to loading bay B, fragile items, leave with reception, specific handling instructions..."
                            ></textarea>
                            <p class="mt-1.5 text-xs text-slate-400">Your delivery team will see this note on the dispatch sheet.</p>
                        </div>
                    </section>

                    {{-- ════════════════════════════════════════════════════════ --}}
                    {{-- STEP 3: PAYMENT METHOD --}}
                    {{-- ════════════════════════════════════════════════════════ --}}
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
                                        <rect x="3" y="6" width="18" height="12" rx="2"></rect><path d="M3 10h18"></path>
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
                                        <path d="M3 10h18"></path><path d="M6 10v8"></path><path d="M10 10v8"></path><path d="M14 10v8"></path><path d="M18 10v8"></path><path d="M2 21h20"></path><path d="M12 3 2 8h20L12 3Z"></path>
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
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M8 13h8"></path><path d="M8 17h5"></path>
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
                                        <path d="M12 3v12"></path><path d="m7 10 5-5 5 5"></path><path d="M4 20h16"></path>
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

                {{-- ════════════════════════════════════════════════════════ --}}
                {{-- RIGHT COLUMN: ORDER SUMMARY --}}
                {{-- ════════════════════════════════════════════════════════ --}}
                <div class="space-y-5">
                    <section class="{{ $summaryCardClass }}">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="{{ $sectionTitleClass }}">Order Summary</h3>
                                <p class="{{ $sectionCopyClass }}">Shared pricing and summary treatment across checkout.</p>
                            </div>
                            <div class="{{ $iconTilePrimaryClass }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M3 7h18"></path><path d="M7 3v4"></path><path d="M17 3v4"></path><path d="M6 12h4"></path><path d="M6 16h6"></path><rect x="3" y="5" width="18" height="16" rx="2"></rect>
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
                                <a href="{{ route('cart.page') }}" class="{{ $buttonSecondaryClass }} mt-5">Back to Cart</a>
                            </x-slot:action>
                        </x-ui.empty-state>

                        <div id="checkoutSummaryItems" class="mt-4 space-y-2.5"></div>

                        {{-- Pricing rows --}}
                        <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span id="checkoutSubtotal" class="font-medium text-slate-900">Rs. 0.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping (Same-Day)</span>
                                <span class="font-semibold text-emerald-700">FREE</span>
                            </div>
                            <div class="flex items-center justify-between" id="couponDiscountRow" style="display:none!important">
                                <span class="text-emerald-700">Coupon Discount</span>
                                <span id="couponDiscountAmount" class="font-semibold text-emerald-700">– Rs. 0.00</span>
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

                        {{-- ─── COUPON CODE FIELD ─── --}}
                        <div class="mt-4 rounded-[18px] border border-slate-200 bg-slate-50 p-3">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Coupon / Promo Code</p>
                            <div class="flex gap-2">
                                <input
                                    id="couponInput"
                                    type="text"
                                    class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-900 placeholder-slate-400 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-600/15"
                                    placeholder="Enter code (e.g. BIO10)"
                                    autocomplete="off"
                                    style="text-transform:uppercase"
                                >
                                <button
                                    id="couponApplyBtn"
                                    type="button"
                                    class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white transition hover:bg-primary-700"
                                >
                                    Apply
                                </button>
                            </div>
                            <p id="couponMsg" class="mt-2 min-h-[1.1rem] text-xs font-medium text-slate-500"></p>
                        </div>

                        {{-- Login warning --}}
                        <div id="checkoutLoginWarning" class="mt-4 hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
                            <p class="font-semibold">Login required</p>
                            <p class="mt-1 leading-6 text-amber-800">Please sign in at the final checkout step to place this order and continue with billing.</p>
                            <a href="{{ route('login') }}" class="{{ $buttonPrimaryClass }} mt-4">Login</a>
                        </div>

                        <button id="placeOrderButton" type="button" class="{{ $buttonPrimaryClass }} mt-5 w-full gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Place Order
                        </button>

                        {{-- ─── PROMINENT TRUST BADGES ─── --}}
                        <div class="mt-5 grid grid-cols-3 gap-2">
                            @foreach ([
                                ['icon' => 'M12 3 5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3Zm0 4v5m0 2.5h.01', 'title' => 'SSL Secured', 'sub' => '256-bit encryption', 'color' => 'bg-emerald-50 text-emerald-700'],
                                ['icon' => 'M3 6h18M3 10h18M5 6v14h14V6M9 10v8M15 10v8',                          'title' => 'Secure Pay',   'sub' => 'PCI-DSS compliant', 'color' => 'bg-blue-50 text-blue-700'],
                                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                       'title' => 'Safe Order',   'sub' => '100% guaranteed',   'color' => 'bg-violet-50 text-violet-700'],
                            ] as $badge)
                                <div class="flex flex-col items-center gap-1.5 rounded-2xl border border-slate-100 bg-slate-50 p-3 text-center">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ $badge['color'] }}">
                                        <svg class="h-4.5 w-4.5 h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}" />
                                        </svg>
                                    </span>
                                    <p class="text-[11px] font-bold text-slate-800 leading-tight">{{ $badge['title'] }}</p>
                                    <p class="text-[10px] text-slate-500 leading-tight">{{ $badge['sub'] }}</p>
                                </div>
                            @endforeach
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
                /* ── selectors ── */
                const summaryItems     = document.getElementById('checkoutSummaryItems');
                const subtotalEl       = document.getElementById('checkoutSubtotal');
                const taxEl            = document.getElementById('checkoutTax');
                const totalEl          = document.getElementById('checkoutTotal');
                const emptyState       = document.getElementById('checkoutEmptyState');
                const loginWarning     = document.getElementById('checkoutLoginWarning');
                const placeOrderButton = document.getElementById('placeOrderButton');
                const paymentCards     = Array.from(document.querySelectorAll('[data-payment-card]'));
                const paymentInputs    = Array.from(document.querySelectorAll('input[name="payment_method"]'));
                const addressCards     = Array.from(document.querySelectorAll('[data-address-card]'));
                const poUploadPanel    = document.getElementById('poUploadPanel');
                const isAuthenticated  = @json(auth()->check());

                /* ── Add New Address inline form ── */
                const addToggleBtn  = document.getElementById('addAddressToggleBtn');
                const addForm       = document.getElementById('addAddressForm');
                const addCloseBtn   = document.getElementById('addAddressCloseBtn');
                const addCloseBtn2  = document.getElementById('addAddressCloseBtn2');
                const saveAddressBtn = document.getElementById('saveAddressBtn');

                function openAddressForm() {
                    if (addForm) {
                        addForm.classList.remove('hidden');
                        addForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
                function closeAddressForm() {
                    if (addForm) addForm.classList.add('hidden');
                }

                if (addToggleBtn) addToggleBtn.addEventListener('click', openAddressForm);
                if (addCloseBtn)  addCloseBtn.addEventListener('click', closeAddressForm);
                if (addCloseBtn2) addCloseBtn2.addEventListener('click', closeAddressForm);
                if (saveAddressBtn) {
                    saveAddressBtn.addEventListener('click', function () {
                        closeAddressForm();
                    });
                }

                /* ── Step progress bar ── */
                const progressLine = document.getElementById('stepProgressLine');
                function setStep(n) {
                    if (!progressLine) return;
                    const widths = { 1: '16.66%', 2: '50%', 3: '100%' };
                    progressLine.style.width = widths[n] || '16.66%';
                    [1, 2, 3].forEach(function (s) {
                        var circle = document.querySelector('#stepIndicator' + s + ' .step-circle');
                        if (!circle) return;
                        if (s <= n) {
                            circle.classList.remove('border-slate-300', 'text-slate-400');
                            circle.classList.add('border-primary-600', 'bg-primary-600', 'text-white');
                        } else {
                            circle.classList.add('border-slate-300', 'text-slate-400');
                            circle.classList.remove('border-primary-600', 'bg-primary-600', 'text-white');
                        }
                    });
                }
                setStep(1);

                /* ── Coupon code ── */
                const couponInput       = document.getElementById('couponInput');
                const couponApplyBtn    = document.getElementById('couponApplyBtn');
                const couponMsg         = document.getElementById('couponMsg');
                const couponDiscountRow = document.getElementById('couponDiscountRow');
                const couponDiscountAmt = document.getElementById('couponDiscountAmount');
                const VALID_COUPONS     = { 'BIO10': 0.10, 'SAVE5': 0.05, 'LABFIRST': 0.15 };
                var appliedDiscount = 0;

                if (couponApplyBtn && couponInput) {
                    couponApplyBtn.addEventListener('click', function () {
                        const code = (couponInput.value || '').trim().toUpperCase();
                        couponInput.value = code;
                        if (!code) {
                            couponMsg.textContent = 'Please enter a coupon code.';
                            couponMsg.className = 'mt-2 min-h-[1.1rem] text-xs font-medium text-slate-500';
                            return;
                        }
                        if (VALID_COUPONS[code] !== undefined) {
                            appliedDiscount = VALID_COUPONS[code];
                            couponMsg.textContent = '✓ Coupon applied — ' + (appliedDiscount * 100) + '% off!';
                            couponMsg.className = 'mt-2 min-h-[1.1rem] text-xs font-semibold text-emerald-700';
                            if (couponDiscountRow) couponDiscountRow.style.removeProperty('display');
                        } else {
                            appliedDiscount = 0;
                            couponMsg.textContent = '✗ Invalid or expired coupon code.';
                            couponMsg.className = 'mt-2 min-h-[1.1rem] text-xs font-semibold text-rose-600';
                            if (couponDiscountRow) couponDiscountRow.style.setProperty('display', 'none', 'important');
                        }
                        render();
                    });
                }

                /* ── formatInr ── */
                const formatInr = function (value) {
                    const numeric = Number(value);
                    if (!Number.isFinite(numeric)) return '<span class="currency-symbol">Rs.</span> 0.00';
                    return '<span class="currency-symbol">Rs.</span> ' + numeric.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                };

                /* ── render summary row ── */
                const renderSummaryRow = function (item) {
                    const quantity = Math.max(1, Number(item.quantity || 1));
                    const total    = Number(item.unitPrice || 0) * quantity;
                    const image    = String(item.image || 'https://via.placeholder.com/96x96?text=Bio');
                    const name     = String(item.name || 'Product');
                    const model    = String(item.model || 'N/A');
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

                /* ── render totals ── */
                const render = function () {
                    const items = window.CartStore ? window.CartStore.getItems() : [];
                    if (summaryItems) summaryItems.innerHTML = '';

                    if (!items.length) {
                        if (emptyState) emptyState.classList.remove('hidden');
                        if (summaryItems) summaryItems.classList.add('hidden');
                        if (subtotalEl) subtotalEl.innerHTML = '<span class="currency-symbol">Rs.</span> 0.00';
                        if (taxEl)      taxEl.innerHTML      = '<span class="currency-symbol">Rs.</span> 0.00';
                        if (totalEl)    totalEl.innerHTML    = '<span class="currency-symbol">Rs.</span> 0.00';
                        return;
                    }

                    if (emptyState) emptyState.classList.add('hidden');
                    if (summaryItems) summaryItems.classList.remove('hidden');

                    var subtotal = 0;
                    items.forEach(function (item) {
                        const qty = Math.max(1, Number(item.quantity || 1));
                        subtotal += Number(item.unitPrice || 0) * qty;
                        if (summaryItems) summaryItems.insertAdjacentHTML('beforeend', renderSummaryRow(item));
                    });

                    const discount = subtotal * appliedDiscount;
                    const discounted = subtotal - discount;
                    const tax = discounted * 0.18;

                    if (subtotalEl) subtotalEl.innerHTML = formatInr(subtotal);
                    if (taxEl) taxEl.innerHTML = formatInr(tax);
                    if (totalEl) totalEl.innerHTML = formatInr(discounted + tax);

                    if (couponDiscountAmt && discount > 0) {
                        couponDiscountAmt.innerHTML = '– ' + formatInr(discount);
                    }
                };

                /* ── address selection ── */
                const setActiveAddress = function (card) {
                    addressCards.forEach(function (node) {
                        const selected = node === card;
                        node.dataset.selected = selected ? 'true' : 'false';
                        node.classList.toggle('border-primary-200', selected);
                        node.classList.toggle('bg-primary-50', selected);
                        node.classList.toggle('border-slate-200', !selected);
                        node.classList.toggle('bg-white', !selected);
                    });
                };

                /* ── payment card sync ── */
                const syncPaymentCards = function () {
                    paymentCards.forEach(function (card) {
                        const input = card.querySelector('input[type="radio"]');
                        if (!input) return;
                        card.classList.toggle('border-primary-200', input.checked);
                        card.classList.toggle('bg-primary-50', input.checked);
                        card.classList.toggle('border-slate-200', !input.checked);
                        card.classList.toggle('bg-white', !input.checked);
                    });
                    const active = paymentInputs.find(function (i) { return i.checked; });
                    if (poUploadPanel) {
                        poUploadPanel.classList.toggle('hidden', !(active && active.value === 'po'));
                    }
                    /* update step bar based on section interaction */
                    setStep(3);
                };

                /* ── events ── */
                addressCards.forEach(function (card) {
                    card.addEventListener('click', function () {
                        if (!card.classList.contains('border-dashed')) {
                            setActiveAddress(card);
                            setStep(2);
                        }
                    });
                });

                paymentInputs.forEach(function (input) {
                    input.addEventListener('change', syncPaymentCards);
                });

                if (!isAuthenticated && loginWarning) loginWarning.classList.remove('hidden');

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

                /* ── GSTIN auto-uppercase ── */
                var gstinInput = document.getElementById('gstinInput');
                var panInput   = document.getElementById('panInput');
                if (gstinInput) gstinInput.addEventListener('input', function () { this.value = this.value.toUpperCase(); });
                if (panInput)   panInput.addEventListener('input',   function () { this.value = this.value.toUpperCase(); });

                /* ── init ── */
                if (window.CartStore) window.CartStore.subscribe(render);
                syncPaymentCards();
            });
        </script>
    @endpush
@endsection
