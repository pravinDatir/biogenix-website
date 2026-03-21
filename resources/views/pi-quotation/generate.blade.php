@extends('layouts.app')

@section('title', 'Generate PI Quotation')

@php
    $loggedInUser = auth()->user();
    $productSearchCatalog = collect($products ?? [])->map(function ($product) {
        $lotSize = max(1, (int) ($product->lot_size ?? 1));

        return [
            'id' => (int) $product->id,
            'name' => (string) ($product->name ?? ''),
            'sku' => (string) ($product->sku ?? ''),
            'search_label' => trim((string) ($product->name ?? '').' '.(string) ($product->sku ?? '')),
            'category_id' => (int) ($product->category_id ?? 0),
            'category_name' => (string) ($product->category_name ?? optional($product->category)->name ?? ''),
            'subcategory_id' => (int) ($product->subcategory_id ?? 0),
            'subcategory_name' => (string) ($product->subcategory_name ?? optional($product->subcategory)->name ?? ''),
            'pack_size' => $lotSize > 1 ? 'Lot of '.$lotSize : 'Standard Pack',
            'min_order_quantity' => max(1, (int) ($product->min_order_quantity ?? 1)),
            'lot_size' => $lotSize,
            'rate' => round((float) ($product->visible_price ?? 0), 2),
            'gst' => round((float) ($product->gst_rate ?? 0), 2),
        ];
    })->values()->all();
@endphp

@section('content')
<div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6">

    {{-- ═══ PI Header Info ═══ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center gap-2.5">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[#fff3e0] text-[#e65100]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <h2 class="text-lg font-bold tracking-tight text-slate-900">PI Header Info</h2>
        </div>
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">PI Number</label>
                <input id="piNumber" type="text" readonly
                    class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Date</label>
                <input id="piDate" type="date"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">State</label>
                <input id="piStateCode" type="text" placeholder="Maharashtra"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">GSTIN</label>
                <input id="piGstin" type="text" placeholder="27AAACB1234F1Z5"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
        </div>
    </div>

    {{-- ═══ Customer Details ═══ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[#e3f2fd] text-[#1565c0]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0113 0"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Customer Details</h2>
            </div>
            <div class="flex items-center gap-2 select-none">
                <span class="text-sm font-semibold text-slate-600">Same as Billing</span>
                <button id="toggleTrack" type="button" role="switch" aria-checked="false"
                    class="relative h-6 w-[42px] rounded-full bg-slate-300 transition-colors duration-200">
                    <span id="toggleThumb"
                        class="absolute left-[3px] top-[3px] h-[18px] w-[18px] translate-x-0 rounded-full bg-white shadow-[0_1px_3px_rgba(0,0,0,0.2)] transition-transform duration-200"></span>
                </button>
                <input id="sameAsBilling" type="checkbox" class="hidden">
            </div>
        </div>

        <div class="mb-1.5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Billing Address</label>
                <textarea id="billingAddress" rows="4" placeholder="Enter full billing address..."
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500"></textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Shipping Address</label>
                <textarea id="shippingAddress" rows="4" placeholder="Enter full shipping address..."
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500"></textarea>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <input id="contactPerson" type="text" value="{{ old('customer_name', $loggedInUser?->name) }}" placeholder="Contact Person"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <input id="customerEmail" type="email" value="{{ old('customer_email', $loggedInUser?->email) }}" placeholder="Customer Email"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <input id="customerGstin" type="text" placeholder="GSTIN (Customer)"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <input id="deliveryPhone" type="text" value="{{ old('customer_phone', $loggedInUser?->phone) }}" placeholder="Delivery Contact / Phone"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
        </div>
    </div>

    {{-- ═══ Product Details ═══ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[#fff3e0] text-[#e65100]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Product Details</h2>
            </div>
            <button id="addProductRow" type="button"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[#e65100] px-4 py-2 text-[0.82rem] font-bold text-white shadow-[0_2px_8px_rgba(230,81,0,0.2)] transition-colors hover:bg-[#bf360c]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14m-7-7h14"/></svg>
                Add Product Row
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="productTable">
                <thead>
                    <tr class="border-b-2 border-slate-200 bg-slate-50">
                        <th class="w-[45px] whitespace-nowrap px-2 py-2.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500">S.No</th>
                        <th class="w-[130px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Cat. No</th>
                        <th class="min-w-[130px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Product Name</th>
                        <th class="w-[100px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Pack Size</th>
                        <th class="w-[60px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Qty</th>
                        <th class="w-[95px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Rate (&#8377;)</th>
                        <th class="w-[95px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Value (&#8377;)</th>
                        <th class="w-[60px] whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">GST %</th>
                        <th class="w-[100px] whitespace-nowrap px-2 py-2.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Total (&#8377;)</th>
                        <th class="w-9"></th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                </tbody>
            </table>
            <p id="emptyTableMsg" class="py-6 text-center text-sm text-slate-400">No products added yet. Click "Add Product Row" to add items.</p>
        </div>
    </div>

    {{-- ═══ Bottom: Terms & Totals ═══ --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        {{-- Terms & Conditions --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[#fce4ec] text-[#c62828]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Terms & Conditions</h2>
            </div>
            <ol id="termsList" class="space-y-2">
                <li class="flex items-baseline gap-2 text-sm text-slate-700">
                    <span class="shrink-0 font-bold text-[#e65100]">1.</span>
                    <input type="text" value="Supply within 3-4 week after confirmation order along with 100% advance payment."
                        class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none">
                </li>
                <li class="flex items-baseline gap-2 text-sm text-slate-700">
                    <span class="shrink-0 font-bold text-[#e65100]">2.</span>
                    <input type="text" value="All Disputes are subject to Lucknow Jurisdiction only"
                        class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none">
                </li>
            </ol>
            </ol>
        </div>

        {{-- Summary / Totals --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <div class="flex justify-between border-b border-slate-100 py-2.5 text-sm">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-semibold text-slate-800" id="sumSubtotal">₹ 0.00</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 py-2.5 text-sm">
                    <span class="text-slate-500">GST Total</span>
                    <span class="font-semibold text-slate-800" id="sumGst">₹ 0.00</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-100 py-2.5 text-sm">
                    <span class="text-slate-500">Freight Charges</span>
                    <input id="freightCharges" type="number" value="0" min="0" step="1"
                        class="w-24 rounded-lg border border-slate-200 bg-white px-2 py-1 text-right text-sm font-semibold text-slate-800 outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-between border-b border-slate-100 py-2.5 text-sm">
                    <span class="text-slate-500">Freight Tax (18%)</span>
                    <span class="font-semibold text-slate-800" id="sumFreightTax">₹ 0.00</span>
                </div>
                <div class="flex justify-between py-2.5 text-sm">
                    <span class="text-slate-500">Round Off</span>
                    <span class="font-semibold text-slate-800" id="sumRoundOff">₹ 0.00</span>
                </div>
            </div>

            <div class="mt-3 flex items-center justify-between border-t-2 border-slate-800 pt-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-slate-900">Grand Total</span>
                <span class="text-2xl font-extrabold text-slate-900" id="sumGrandTotal">₹ 0.00</span>
            </div>

            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs font-bold uppercase tracking-widest text-[#e65100]">Amount in Words</p>
                <p class="mt-0.5 text-sm font-medium italic text-slate-700" id="sumAmountWords">Zero Only</p>
            </div>

            <button id="requestPiBtn" type="button"
                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-3 text-sm font-bold text-white shadow-[0_4px_14px_rgba(30,58,95,0.25)] transition-transform transition-colors hover:-translate-y-px hover:bg-[#15294a]">
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Request Proforma Invoice
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ADD PRODUCT MODAL
     ═══════════════════════════════════════════════════════ --}}
<form id="piRequestForm" method="POST" action="{{ route('pi-quotation.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="purpose" value="self">
    <input type="hidden" name="customer_name" id="requestCustomerName">
    <input type="hidden" name="customer_email" id="requestCustomerEmail">
    <input type="hidden" name="customer_phone" id="requestCustomerPhone">
    <input type="hidden" name="notes" id="requestNotes">
    <div id="requestProductFields"></div>
</form>

<div id="addProductModal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div id="modalBackdrop" class="absolute inset-0 bg-slate-950/55 opacity-0 backdrop-blur-[4px] transition-opacity duration-300"></div>

    {{-- Dialog --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div id="modalDialog" class="pointer-events-auto relative w-full max-w-[520px] translate-y-[10px] scale-95 rounded-[20px] bg-white p-8 opacity-0 shadow-[0_25px_60px_rgba(15,23,42,0.22)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">

            {{-- Close button --}}
            <button id="modalCloseBtn" type="button"
                class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-100">
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Header --}}
            <h3 class="text-[1.3rem] font-extrabold text-slate-800">Add Product to Invoice</h3>
            <p class="mt-1 text-[0.82rem] text-slate-500">Search and configure product details for Biogenix Inventory</p>

            {{-- Form fields --}}
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                {{-- Category --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Category</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-blue-500">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </span>
                        <select id="modalCategory" class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                </div>

                {{-- Subcategory --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Subcategory</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-blue-500">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                        </span>
                        <select id="modalSubcategory" class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none">
                            <option value="">All Subcategories</option>
                        </select>
                    </div>
                </div>

                {{-- Product Name --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Product Name</label>
                    <div class="relative">
                        <div id="modalProductNameField" class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-blue-500">
                            <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            </span>
                            <input id="modalProductName" type="text" autocomplete="off" placeholder="Search or select product"
                                class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                        </div>
                        <div id="modalProductSuggestions" class="absolute left-0 right-0 top-[calc(100%+0.35rem)] z-20 hidden max-h-56 overflow-y-auto rounded-[12px] border border-slate-200 bg-white p-1 shadow-[0_16px_30px_rgba(15,23,42,0.14)]"></div>
                    </div>
                </div>

                {{-- Min Order Qty --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Min Order Qty</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-slate-100">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v12M6 12h12"/></svg>
                        </span>
                        <input id="modalMinOrderQty" type="number" readonly placeholder="Auto-filled"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-600 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Quantity</label>
                    <div id="modalQtyField" class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-white focus-within:border-blue-500">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3v4M8 3v4"/></svg>
                        </span>
                        <input id="modalQty" type="number" min="1" value="1" placeholder="Enter qty"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    <p id="modalQtyError" class="mt-1 hidden text-[0.74rem] font-medium text-rose-600"></p>
                </div>

                {{-- Lot Size --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Lot Size</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-slate-100">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </span>
                        <input id="modalPackSize" type="text" readonly placeholder="Auto-filled"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-600 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- Rate (Unit Price) --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">Rate (Unit Price)</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-slate-100">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </span>
                        <input id="modalRate" type="number" readonly min="0" step="0.01" value="0" placeholder="&#8377; 0.00"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-600 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                {{-- GST % --}}
                <div>
                    <label class="mb-1.5 block text-[0.78rem] font-bold text-slate-800">GST (%)</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-[10px] border border-slate-200 bg-slate-100">
                        <span class="flex h-full w-[38px] shrink-0 items-center justify-center text-[0.82rem] font-bold text-slate-500">%</span>
                        <input id="modalGst" type="number" readonly min="0" max="100" step="0.01" value="18"
                            class="h-full flex-1 bg-transparent pr-3 text-sm text-slate-600 outline-none">
                    </div>
                </div>
            </div>

            {{-- Total Calculated Amount --}}
            <div class="mt-5 flex items-center justify-between rounded-[14px] border border-slate-200 bg-slate-50 px-5 py-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#e3f2fd] text-[#1565c0]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </span>
                    <span class="text-[0.88rem] font-semibold text-slate-600">Total Calculated Amount</span>
                </div>
                <div class="text-right">
                    <p class="text-[0.65rem] font-bold uppercase tracking-[0.1em] text-[#e65100]">INCL. TAXES</p>
                    <p id="modalTotalAmount" class="text-2xl font-extrabold text-slate-800">&#8377; 0.00</p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                <button id="modalCancelBtn" type="button"
                    class="h-11 rounded-xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50">
                    Cancel
                </button>
                <button id="modalAddBtn" type="button"
                    class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#e65100] px-7 text-sm font-bold text-white shadow-[0_2px_10px_rgba(230,81,0,0.25)] transition-colors hover:bg-[#bf360c]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ─── Auto-fill PI Number and Date ───
    var now = new Date();
    var fy = now.getMonth() >= 3 ? now.getFullYear() : now.getFullYear() - 1;
    var piNum = 'PI/' + fy + '-' + String(fy + 1).slice(2) + '/' + String(Math.floor(1000 + Math.random() * 9000));
    document.getElementById('piNumber').value = piNum;
    document.getElementById('piDate').value = now.toISOString().split('T')[0];

    // Business step: keep a lightweight product catalog on the page so the popup can search visible products instantly.
    var productCatalog = @json($productSearchCatalog);
    var requestForm = document.getElementById('piRequestForm');
    var requestProductFields = document.getElementById('requestProductFields');
    var requestCustomerName = document.getElementById('requestCustomerName');
    var requestCustomerEmail = document.getElementById('requestCustomerEmail');
    var requestCustomerPhone = document.getElementById('requestCustomerPhone');
    var requestNotes = document.getElementById('requestNotes');
    var customerNameInput = document.getElementById('contactPerson');
    var customerEmailInput = document.getElementById('customerEmail');
    var customerPhoneInput = document.getElementById('deliveryPhone');
    var customerGstinInput = document.getElementById('customerGstin');
    var stateCodeInput = document.getElementById('piStateCode');
    var piGstinInput = document.getElementById('piGstin');
    var piDateInput = document.getElementById('piDate');
    var piNumberInput = document.getElementById('piNumber');

    // ─── Same as Billing toggle ───
    var toggleTrack = document.getElementById('toggleTrack');
    var toggleThumb = document.getElementById('toggleThumb');
    var sameCheckbox = document.getElementById('sameAsBilling');
    var billingAddr = document.getElementById('billingAddress');
    var shippingAddr = document.getElementById('shippingAddress');
    var toggleOn = false;

    function syncShippingToggleState() {
        sameCheckbox.checked = toggleOn;
        toggleTrack.setAttribute('aria-checked', toggleOn ? 'true' : 'false');
        toggleTrack.classList.toggle('bg-[#1e3a5f]', toggleOn);
        toggleTrack.classList.toggle('bg-slate-300', !toggleOn);
        toggleThumb.classList.toggle('translate-x-[18px]', toggleOn);
        toggleThumb.classList.toggle('translate-x-0', !toggleOn);

        if (toggleOn) {
            shippingAddr.value = billingAddr.value;
        }

        shippingAddr.readOnly = toggleOn;
        shippingAddr.classList.toggle('bg-slate-100', toggleOn);
        shippingAddr.classList.toggle('bg-white', !toggleOn);
    }

    toggleTrack.addEventListener('click', function () {
        toggleOn = !toggleOn;
        syncShippingToggleState();
    });

    billingAddr.addEventListener('input', function () {
        if (toggleOn) { shippingAddr.value = billingAddr.value; }
    });

    // ─── Product Table ───
    var tableBody = document.getElementById('productTableBody');
    var emptyMsg = document.getElementById('emptyTableMsg');

    function addRowToTable(data) {
        var rows = tableBody.querySelectorAll('.product-row');
        var sno = rows.length + 1;
        var value = data.qty * data.rate;
        var gstAmt = value * data.gst / 100;
        var total = value + gstAmt;

        var tr = document.createElement('tr');
        tr.className = 'product-row border-b border-slate-100';
        tr.innerHTML =
            '<td class="sno px-2 py-2 text-center text-sm font-semibold text-slate-500">' + String(sno).padStart(2, '0') + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.catNo) + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.productName) + '</td>' +
            '<td class="px-2 py-2 text-[0.85rem] text-slate-800">' + escHtml(data.packSize) + '</td>' +
            '<td class="row-qty-val px-2 py-2 text-center text-[0.85rem] text-slate-800">' + data.qty + '</td>' +
            '<td class="row-rate-val px-2 py-2 text-right text-[0.85rem] text-slate-800">' + formatNum(data.rate) + '</td>' +
            '<td class="row-value px-2 py-2 text-right text-[0.85rem] text-slate-600">' + formatNum(value) + '</td>' +
            '<td class="row-gst-val px-2 py-2 text-center text-[0.85rem] text-slate-800">' + data.gst + '</td>' +
            '<td class="row-total px-2 py-2 text-right text-sm font-bold text-slate-800">' + formatNum(total) + '</td>' +
            '<td class="px-2 py-2 text-center"><button type="button" class="del-row-btn inline-flex h-[30px] w-[30px] items-center justify-center rounded-lg border border-rose-200 text-rose-600 transition-colors hover:bg-rose-50"><svg class="h-[15px] w-[15px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></td>';

        // Store data attributes for calc
        tr.setAttribute('data-product-id', data.productId || '');
        tr.setAttribute('data-qty', data.qty);
        tr.setAttribute('data-rate', data.rate);
        tr.setAttribute('data-gst', data.gst);

        tableBody.appendChild(tr);

        // Delete button
        tr.querySelector('.del-row-btn').addEventListener('click', function () {
            tr.remove();
            renumberRows();
            recalcTotals();
            toggleEmptyMsg();
        });

        renumberRows();
        recalcTotals();
        toggleEmptyMsg();
    }

    function escHtml(str) {
        var div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function renumberRows() {
        tableBody.querySelectorAll('.product-row').forEach(function (r, i) {
            r.querySelector('.sno').textContent = String(i + 1).padStart(2, '0');
        });
    }

    function toggleEmptyMsg() {
        var rows = tableBody.querySelectorAll('.product-row');
        emptyMsg.classList.toggle('hidden', rows.length > 0);
    }

    function recalcTotals() {
        var subtotal = 0;
        var gstTotal = 0;
        tableBody.querySelectorAll('.product-row').forEach(function (row) {
            var qty = parseFloat(row.getAttribute('data-qty')) || 0;
            var rate = parseFloat(row.getAttribute('data-rate')) || 0;
            var gstPct = parseFloat(row.getAttribute('data-gst')) || 0;
            var val = qty * rate;
            subtotal += val;
            gstTotal += val * gstPct / 100;
        });

        var freight = parseFloat(document.getElementById('freightCharges').value) || 0;
        var freightTax = freight * 0.18;
        var rawTotal = subtotal + gstTotal + freight + freightTax;
        var rounded = Math.round(rawTotal);
        var roundOff = rounded - rawTotal;

        document.getElementById('sumSubtotal').textContent = '\u20B9 ' + formatNum(subtotal);
        document.getElementById('sumGst').textContent = '\u20B9 ' + formatNum(gstTotal);
        document.getElementById('sumFreightTax').textContent = '\u20B9 ' + formatNum(freightTax);
        document.getElementById('sumRoundOff').textContent = '\u20B9 ' + (roundOff >= 0 ? '' : '-') + formatNum(Math.abs(roundOff));
        document.getElementById('sumGrandTotal').textContent = '\u20B9 ' + formatNum(rounded);
        document.getElementById('sumAmountWords').textContent = numberToWords(rounded) + ' Only';
    }

    function formatNum(n) {
        return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function numberToWords(num) {
        if (num === 0) return 'Zero';
        var ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        function convert(n) {
            if (n < 0) return 'Minus ' + convert(-n);
            if (n < 20) return ones[n];
            if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + ones[n % 10] : '');
            if (n < 1000) return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + convert(n % 100) : '');
            if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + convert(n % 1000) : '');
            if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + convert(n % 100000) : '');
            return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + convert(n % 10000000) : '');
        }
        return convert(Math.abs(Math.round(num)));
    }

    // Business step: collect compact notes so the internal team can review delivery, tax, and commercial context from this request page.
    function buildPiRequestNotes() {
        var terms = Array.from(document.querySelectorAll('#termsList input')).map(function (input) {
            return String(input.value || '').trim();
        }).filter(Boolean);

        var noteLines = [
            'PI Header',
            'PI Number: ' + String(piNumberInput.value || '').trim(),
            'PI Date: ' + String(piDateInput.value || '').trim(),
            'State Code: ' + String(stateCodeInput.value || '').trim(),
            'GSTIN: ' + String(piGstinInput.value || '').trim(),
            'Customer GSTIN: ' + String(customerGstinInput.value || '').trim(),
            'Billing Address: ' + String(billingAddr.value || '').trim(),
            'Shipping Address: ' + String(shippingAddr.value || '').trim(),
            'Freight Charges: ' + String(document.getElementById('freightCharges').value || '0').trim(),
            'Terms: ' + terms.join(' | '),
        ].filter(function (line) {
            return !line.endsWith(': ');
        });

        return noteLines.join("\n").slice(0, 1000);
    }

    // Business step: add one hidden request field so the backend receives product ids and quantities in standard form arrays.
    function appendRequestField(name, value) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        requestProductFields.appendChild(input);
    }

    // Business step: submit the final PI request to the backend review flow instead of generating a PDF in the browser.
    function submitPiRequest() {
        var customerName = String(customerNameInput.value || '').trim();
        var customerEmail = String(customerEmailInput.value || '').trim();
        var customerPhone = String(customerPhoneInput.value || '').trim();
        var rows = Array.from(tableBody.querySelectorAll('.product-row'));

        if (!customerName) {
            customerNameInput.focus();
            alert('Please enter contact person name.');
            return;
        }

        if (!customerEmail) {
            customerEmailInput.focus();
            alert('Please enter customer email.');
            return;
        }

        if (rows.length === 0) {
            alert('Please add at least one product to the PI request.');
            return;
        }

        requestProductFields.innerHTML = '';

        rows.forEach(function (row) {
            appendRequestField('product_id[]', row.getAttribute('data-product-id') || '');
            appendRequestField('quantity[]', row.getAttribute('data-qty') || '1');
        });

        requestCustomerName.value = customerName;
        requestCustomerEmail.value = customerEmail;
        requestCustomerPhone.value = customerPhone;
        requestNotes.value = buildPiRequestNotes();
        requestForm.submit();
    }

    // ─── Modal logic ───
    var modal = document.getElementById('addProductModal');
    // Move modal to body to guarantee it breaks out of relative stacking contexts
    document.body.appendChild(modal);
    
    var backdrop = document.getElementById('modalBackdrop');
    var dialog = document.getElementById('modalDialog');
    var closeBtn = document.getElementById('modalCloseBtn');
    var cancelBtn = document.getElementById('modalCancelBtn');
    var addBtn = document.getElementById('modalAddBtn');

    var mCategory = document.getElementById('modalCategory');
    var mSubcategory = document.getElementById('modalSubcategory');
    var mProdName = document.getElementById('modalProductName');
    var mProdNameField = document.getElementById('modalProductNameField');
    var mProductSuggestions = document.getElementById('modalProductSuggestions');
    var mMinOrderQty = document.getElementById('modalMinOrderQty');
    var mPackSize = document.getElementById('modalPackSize');
    var mQtyField = document.getElementById('modalQtyField');
    var mQtyError = document.getElementById('modalQtyError');
    var mQty = document.getElementById('modalQty');
    var mRate = document.getElementById('modalRate');
    var mGst = document.getElementById('modalGst');
    var mTotal = document.getElementById('modalTotalAmount');
    var modalIsOpen = false;
    var closeTimer = null;
    var selectedCatalogProduct = null;

    // Business step: normalize search text once so product matching stays simple and consistent.
    function normalizeProductSearchValue(value) {
        return String(value || '').trim().toLowerCase();
    }

    // Business step: hide the popup suggestion panel when no search choices should be shown.
    function hideProductSuggestions() {
        mProductSuggestions.classList.add('hidden');
        mProductSuggestions.innerHTML = '';
    }

    // Business step: keep the category filter simple by showing one clean option per visible category.
    function renderCategoryOptions() {
        var categories = productCatalog
            .filter(function (product) {
                return product.category_id && product.category_name;
            })
            .reduce(function (uniqueCategories, product) {
                if (!uniqueCategories.some(function (category) { return category.id === product.category_id; })) {
                    uniqueCategories.push({ id: product.category_id, name: product.category_name });
                }

                return uniqueCategories;
            }, [])
            .sort(function (left, right) {
                return left.name.localeCompare(right.name);
            });

        mCategory.innerHTML = '<option value="">All Categories</option>' + categories.map(function (category) {
            return '<option value="' + category.id + '">' + escHtml(category.name) + '</option>';
        }).join('');
    }

    // Business step: keep subcategory choices aligned to the chosen category so the team can narrow products faster.
    function renderSubcategoryOptions() {
        var selectedCategoryId = parseInt(mCategory.value || '0', 10);
        var sourceProducts = selectedCategoryId
            ? productCatalog.filter(function (product) { return product.category_id === selectedCategoryId; })
            : productCatalog;

        var subcategories = sourceProducts
            .filter(function (product) {
                return product.subcategory_id && product.subcategory_name;
            })
            .reduce(function (uniqueSubcategories, product) {
                if (!uniqueSubcategories.some(function (subcategory) { return subcategory.id === product.subcategory_id; })) {
                    uniqueSubcategories.push({ id: product.subcategory_id, name: product.subcategory_name });
                }

                return uniqueSubcategories;
            }, [])
            .sort(function (left, right) {
                return left.name.localeCompare(right.name);
            });

        mSubcategory.innerHTML = '<option value="">All Subcategories</option>' + subcategories.map(function (subcategory) {
            return '<option value="' + subcategory.id + '">' + escHtml(subcategory.name) + '</option>';
        }).join('');
    }

    // Business step: only narrow product choices when both category and subcategory are selected; otherwise keep the full visible list.
    function getActiveCatalogProducts() {
        var selectedCategoryId = parseInt(mCategory.value || '0', 10);
        var selectedSubcategoryId = parseInt(mSubcategory.value || '0', 10);

        if (!selectedCategoryId || !selectedSubcategoryId) {
            return productCatalog;
        }

        return productCatalog.filter(function (product) {
            return product.category_id === selectedCategoryId && product.subcategory_id === selectedSubcategoryId;
        });
    }

    // Business step: show the minimum quantity rule directly in the popup so the requester knows the ordering threshold before saving.
    function validateModalQuantity(showError) {
        var quantity = parseInt(mQty.value || '0', 10);
        var minimumQuantity = parseInt(mMinOrderQty.value || '1', 10);
        var hasError = quantity > 0 && quantity < minimumQuantity;

        mQtyField.classList.toggle('border-rose-400', hasError);
        mQtyField.classList.toggle('ring-1', hasError);
        mQtyField.classList.toggle('ring-rose-200', hasError);
        mQtyError.classList.toggle('hidden', !hasError || !showError);
        mQtyError.textContent = hasError ? 'Quantity must be at least ' + minimumQuantity + '.' : '';

        return !hasError;
    }

    // Business step: return the visible products that match the typed name or catalogue number.
    function searchCatalogProducts(searchValue) {
        var normalizedSearchValue = normalizeProductSearchValue(searchValue);
        var activeProducts = getActiveCatalogProducts();

        if (!normalizedSearchValue) {
            return activeProducts.slice(0, 8);
        }

        return activeProducts.filter(function (product) {
            return normalizeProductSearchValue(product.name).includes(normalizedSearchValue)
                || normalizeProductSearchValue(product.sku).includes(normalizedSearchValue)
                || normalizeProductSearchValue(product.search_label).includes(normalizedSearchValue);
        }).slice(0, 8);
    }

    // Business step: match the user entry against visible product name, catalogue number, or the combined label.
    function findCatalogProduct(searchValue) {
        var normalizedSearchValue = normalizeProductSearchValue(searchValue);

        if (!normalizedSearchValue) {
            return null;
        }

        return getActiveCatalogProducts().find(function (product) {
            return normalizeProductSearchValue(product.name) === normalizedSearchValue
                || normalizeProductSearchValue(product.sku) === normalizedSearchValue
                || normalizeProductSearchValue(product.search_label) === normalizedSearchValue;
        }) || null;
    }

    // Business step: fill the modal with the selected product so rate, GST, and pack size follow backend pricing data.
    function applyCatalogProduct(product) {
        if (!product) {
            return;
        }

        mProdName.value = product.name || '';
        mMinOrderQty.value = product.min_order_quantity || 1;
        mPackSize.value = product.lot_size || 1;
        mQty.min = product.min_order_quantity || 1;
        if ((parseInt(mQty.value || '0', 10) || 0) < (product.min_order_quantity || 1)) {
            mQty.value = product.min_order_quantity || 1;
        }
        mRate.value = product.rate || 0;
        mGst.value = product.gst || 0;
        validateModalQuantity(true);
        updateModalTotal();
    }

    // Business step: show the best matching products so the team can pick the right item quickly in the popup.
    function renderProductSuggestions(searchValue) {
        var matches = searchCatalogProducts(searchValue);

        if (matches.length === 0) {
            hideProductSuggestions();
            return;
        }

        mProductSuggestions.innerHTML = matches.map(function (product) {
            return '<button type="button" class="product-suggestion-item flex w-full items-start justify-between rounded-[10px] px-3 py-2.5 text-left transition-colors hover:bg-slate-50" data-product-id="' + product.id + '">' +
                '<span class="min-w-0">' +
                    '<span class="block truncate text-sm font-semibold text-slate-800">' + escHtml(product.name) + '</span>' +
                    '<span class="block truncate text-[0.76rem] text-slate-500">' + escHtml(product.sku || 'No catalogue number') + '</span>' +
                '</span>' +
                '<span class="ml-3 shrink-0 text-right">' +
                    '<span class="block text-[0.76rem] font-semibold text-[#e65100]">' + escHtml(product.pack_size) + '</span>' +
                    '<span class="block text-[0.76rem] text-slate-500">&#8377; ' + formatNum(product.rate) + '</span>' +
                '</span>' +
            '</button>';
        }).join('');

        mProductSuggestions.classList.remove('hidden');
    }

    // Business step: keep track of the chosen product so Add to Invoice uses the matched catalog item.
    function syncSelectedCatalogProduct() {
        selectedCatalogProduct = findCatalogProduct(mProdName.value);

        if (selectedCatalogProduct) {
            setProductNameError(false);
            applyCatalogProduct(selectedCatalogProduct);
        }
    }

    function setProductNameError(hasError) {
        mProdNameField.classList.toggle('border-rose-400', hasError);
        mProdNameField.classList.toggle('ring-1', hasError);
        mProdNameField.classList.toggle('ring-rose-200', hasError);
    }

    function openModal() {
        mCategory.value = '';
        mSubcategory.value = '';
        mProdName.value = '';
        mMinOrderQty.value = '1';
        mPackSize.value = '1';
        mQty.value = '1';
        mRate.value = '0';
        mGst.value = '18';
        selectedCatalogProduct = null;
        renderCategoryOptions();
        renderSubcategoryOptions();
        hideProductSuggestions();
        setProductNameError(false);
        validateModalQuantity(false);
        updateModalTotal();

        clearTimeout(closeTimer);
        modal.classList.remove('hidden');
        modalIsOpen = true;
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(function () {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            dialog.classList.remove('opacity-0', 'scale-95', 'translate-y-[10px]');
            dialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        });
    }

    function closeModal() {
        if (!modalIsOpen && modal.classList.contains('hidden')) {
            return;
        }

        modalIsOpen = false;
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        dialog.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        dialog.classList.add('opacity-0', 'scale-95', 'translate-y-[10px]');
        document.body.classList.remove('overflow-hidden');
        hideProductSuggestions();
        clearTimeout(closeTimer);
        closeTimer = setTimeout(function () {
            if (!modalIsOpen) {
                modal.classList.add('hidden');
            }
        }, 300);
    }

    function updateModalTotal() {
        var qty = parseFloat(mQty.value) || 0;
        var rate = parseFloat(mRate.value) || 0;
        var gst = parseFloat(mGst.value) || 0;
        var value = qty * rate;
        var total = value + (value * gst / 100);
        mTotal.textContent = '\u20B9 ' + formatNum(total);
    }

    mQty.addEventListener('input', function () {
        updateModalTotal();
        validateModalQuantity(true);
    });
    mRate.addEventListener('input', updateModalTotal);
    mGst.addEventListener('input', updateModalTotal);
    mCategory.addEventListener('change', function () {
        // Business step: refresh the subcategory choices and product suggestions whenever the category filter changes.
        mSubcategory.value = '';
        selectedCatalogProduct = null;
        renderSubcategoryOptions();
        renderProductSuggestions(mProdName.value);
    });
    mSubcategory.addEventListener('change', function () {
        // Business step: once both filters are selected, the popup should only suggest products from that exact branch.
        selectedCatalogProduct = null;
        renderProductSuggestions(mProdName.value);
    });
    mProdName.addEventListener('input', function () {
        // Business step: clear the previous selection while the user is typing a fresh product search.
        selectedCatalogProduct = null;
        setProductNameError(false);

        // Business step: when the user types, keep showing matching products from the visible catalog.
        renderProductSuggestions(mProdName.value);

        // Business step: when the typed value matches a visible product exactly, auto-fill the rest of the row details.
        syncSelectedCatalogProduct();
    });
    mProdName.addEventListener('focus', function () {
        // Business step: on focus, show the available products so the team can choose without typing the full name.
        renderProductSuggestions(mProdName.value);
    });
    mProdName.addEventListener('change', syncSelectedCatalogProduct);
    mProductSuggestions.addEventListener('click', function (event) {
        // Business step: when the user clicks a suggested product, use that item as the invoice row source.
        var suggestionButton = event.target.closest('.product-suggestion-item');

        if (!suggestionButton) {
            return;
        }

        var productId = parseInt(suggestionButton.getAttribute('data-product-id'), 10);
        var matchedProduct = productCatalog.find(function (product) {
            return product.id === productId;
        }) || null;

        if (!matchedProduct) {
            return;
        }

        selectedCatalogProduct = matchedProduct;
        applyCatalogProduct(matchedProduct);
        hideProductSuggestions();
        mQty.focus();
    });
    document.addEventListener('click', function (event) {
        // Business step: close suggestions when the user clicks outside the search field area.
        if (!event.target.closest('#modalProductNameField') && !event.target.closest('#modalProductSuggestions')) {
            hideProductSuggestions();
        }
    });

    document.getElementById('addProductRow').addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modalIsOpen) closeModal(); });

    addBtn.addEventListener('click', function () {
        // Business step: resolve the final selected product one last time before the invoice row is added.
        var matchedProduct = selectedCatalogProduct || findCatalogProduct(mProdName.value);
        var productName = mProdName.value.trim();
        var qty = parseFloat(mQty.value) || 1;
        var rate = parseFloat(mRate.value) || 0;
        var gst = parseFloat(mGst.value) || 0;
        var packSize = mPackSize.value.trim();

        if (matchedProduct) {
            selectedCatalogProduct = matchedProduct;
            productName = matchedProduct.name || productName;
            packSize = matchedProduct.pack_size || packSize;
            rate = parseFloat(mRate.value) || matchedProduct.rate || 0;
            gst = parseFloat(mGst.value) || matchedProduct.gst || 0;
        }

        if (!matchedProduct) {
            setProductNameError(true);
            mProdName.focus();
            alert('Please select a product from the available search results.');
            return;
        }

        if (!productName) {
            setProductNameError(true);
            mProdName.focus();
            return;
        }

        if (!validateModalQuantity(true)) {
            mQty.focus();
            return;
        }

        setProductNameError(false);

        addRowToTable({
            productId: matchedProduct.id,
            catNo: matchedProduct.sku || '',
            productName: productName,
            packSize: packSize,
            qty: qty,
            rate: rate,
            gst: gst
        });

        closeModal();
    });

    // ─── Freight input recalc ───
    document.getElementById('freightCharges').addEventListener('input', recalcTotals);

    // ─── Generate PDF (placeholder) ───
    document.getElementById('requestPiBtn').addEventListener('click', submitPiRequest);

    // ─── Show empty message initially ───
    toggleEmptyMsg();
    updateModalTotal();
    recalcTotals();
    syncShippingToggleState();
});
</script>
@endpush
