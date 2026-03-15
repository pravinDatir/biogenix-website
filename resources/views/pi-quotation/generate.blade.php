@extends('layouts.app')

@section('title', 'Generate PI Quotation')

@section('content')
<div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6">

    {{-- ═══ PI Header Info ═══ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center gap-2.5">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl" style="background:#fff3e0;color:#e65100;">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <h2 class="text-lg font-bold tracking-tight text-slate-900">PI Header Info</h2>
        </div>
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">PI Number</label>
                <input id="piNumber" type="text" readonly
                    class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm font-semibold text-slate-800 outline-none"
                    style="background:#f8fafc;">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">Date</label>
                <input id="piDate" type="date"
                    class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-slate-500">State Code</label>
                <input id="piStateCode" type="text" placeholder="27 (Maharashtra)"
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
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl" style="background:#e3f2fd;color:#1565c0;">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0113 0"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Customer Details</h2>
            </div>
            <label class="flex cursor-pointer items-center gap-2 select-none" id="sameAsBillingLabel">
                <span class="text-sm font-semibold text-slate-600">Same as Billing</span>
                <div id="toggleTrack" style="width:42px;height:24px;border-radius:12px;background:#cbd5e1;position:relative;cursor:pointer;transition:background 0.25s;">
                    <div id="toggleThumb" style="width:18px;height:18px;border-radius:50%;background:#fff;position:absolute;top:3px;left:3px;box-shadow:0 1px 3px rgba(0,0,0,0.2);transition:transform 0.25s;"></div>
                </div>
                <input id="sameAsBilling" type="checkbox" style="display:none;">
            </label>
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

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <input id="contactPerson" type="text" placeholder="Contact Person"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <input id="customerGstin" type="text" placeholder="GSTIN (Customer)"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
            <div>
                <input id="deliveryPhone" type="text" placeholder="Delivery Contact / Phone"
                    class="h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:border-blue-500">
            </div>
        </div>
    </div>

    {{-- ═══ Product Details ═══ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl" style="background:#fff3e0;color:#e65100;">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Product Details</h2>
            </div>
            <button id="addProductRow" type="button"
                style="background:#e65100;color:#fff;border:none;border-radius:12px;padding:8px 18px;font-size:0.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(230,81,0,0.2);transition:background 0.2s;">
                <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14m-7-7h14"/></svg>
                Add Product Row
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="productTable">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                        <th class="whitespace-nowrap px-2 py-2.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500" style="width:45px;">S.No</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:130px;">Cat. No</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="min-width:130px;">Product Name</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:100px;">Pack Size</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:60px;">Qty</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:95px;">Rate (₹)</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:95px;">Value (₹)</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500" style="width:60px;">GST %</th>
                        <th class="whitespace-nowrap px-2 py-2.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500" style="width:100px;">Total (₹)</th>
                        <th style="width:36px;"></th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                </tbody>
            </table>
            <p id="emptyTableMsg" class="py-6 text-center text-sm text-slate-400">No products added yet. Click <strong>"+ Add Product Row"</strong> to add items.</p>
        </div>
    </div>

    {{-- ═══ Bottom: Terms & Totals ═══ --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        {{-- Terms & Conditions --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl" style="background:#fce4ec;color:#c62828;">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Terms & Conditions</h2>
            </div>
            <ol id="termsList" class="space-y-2">
                <li class="flex items-baseline gap-2 text-sm text-slate-700">
                    <span class="shrink-0 font-bold" style="color:#e65100;">1.</span>
                    <input type="text" value="Supply within 3-4 week after confirmation order along with 100% advance payment."
                        class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none">
                </li>
                <li class="flex items-baseline gap-2 text-sm text-slate-700">
                    <span class="shrink-0 font-bold" style="color:#e65100;">2.</span>
                    <input type="text" value="All Disputes are subject to Lucknow Jurisdiction only"
                        class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none">
                </li>
            </ol>
            <button id="addTermBtn" type="button" class="mt-3 text-xs font-semibold text-blue-600 hover:underline cursor-pointer">+ Add Term</button>
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

            <div class="mt-3 flex items-center justify-between pt-3" style="border-top:2px solid #1e293b;">
                <span class="text-xs font-extrabold uppercase tracking-widest text-slate-900">Grand Total</span>
                <span class="text-2xl font-extrabold text-slate-900" id="sumGrandTotal">₹ 0.00</span>
            </div>

            <div class="mt-4 rounded-xl border border-slate-200 px-4 py-3" style="background:#f8fafc;">
                <p class="text-xs font-bold uppercase tracking-widest" style="color:#e65100;">Amount in Words</p>
                <p class="mt-0.5 text-sm font-medium italic text-slate-700" id="sumAmountWords">Zero Only</p>
            </div>

            <button id="generatePdfBtn" type="button"
                style="margin-top:16px;width:100%;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 20px;font-size:0.9rem;font-weight:700;color:#fff;background:#1e3a5f;border:none;border-radius:12px;cursor:pointer;box-shadow:0 4px 14px rgba(30,58,95,0.25);transition:background 0.2s,transform 0.15s;">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Generate PDF
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ADD PRODUCT MODAL
     ═══════════════════════════════════════════════════════ --}}
<div id="addProductModal" style="display:none;position:fixed;inset:0;z-index:9999;">
    {{-- Backdrop --}}
    <div id="modalBackdrop" style="position:absolute;inset:0;background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);opacity:0;transition:opacity 0.3s;"></div>

    {{-- Dialog --}}
    <div style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;padding:16px;pointer-events:none;">
        <div style="pointer-events:auto;">
        <div id="modalDialog" style="position:relative;background:#fff;border-radius:20px;box-shadow:0 25px 60px rgba(15,23,42,0.22);width:100%;max-width:520px;padding:32px;transform:scale(0.95) translateY(10px);opacity:0;transition:transform 0.3s cubic-bezier(0.32,0.72,0,1),opacity 0.3s;">

            {{-- Close button --}}
            <button id="modalCloseBtn" type="button" style="position:absolute;top:16px;right:16px;width:36px;height:36px;border-radius:50%;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Header --}}
            <h3 style="font-size:1.3rem;font-weight:800;color:#1e293b;margin:0;">Add Product to Invoice</h3>
            <p style="font-size:0.82rem;color:#64748b;margin-top:4px;">Search and configure product details for Biogenix Inventory</p>

            {{-- Form fields --}}
            <div style="margin-top:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                {{-- Cat NO --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">Cat NO</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e2e8f0;border-radius:10px;background:#fff;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#94a3b8;flex-shrink:0;">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        </span>
                        <input id="modalCatNo" type="text" placeholder="e.g., BG-9920" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#1e293b;background:transparent;">
                    </div>
                </div>

                {{-- Product Name --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">Product Name</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e2e8f0;border-radius:10px;background:#fff;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#94a3b8;flex-shrink:0;">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        </span>
                        <input id="modalProductName" type="text" placeholder="Search or select product" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#1e293b;background:transparent;">
                    </div>
                </div>

                {{-- Pack Size (read-only styled) --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">Pack Size</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e8edf4;border-radius:10px;background:#eef2f7;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#64748b;flex-shrink:0;">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </span>
                        <input id="modalPackSize" type="text" placeholder="e.g. Box of 5" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#475569;background:transparent;">
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">Quantity</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e2e8f0;border-radius:10px;background:#fff;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#94a3b8;flex-shrink:0;">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3v4M8 3v4"/></svg>
                        </span>
                        <input id="modalQty" type="number" min="1" value="1" placeholder="Enter qty" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#1e293b;background:transparent;">
                    </div>
                </div>

                {{-- Rate (Unit Price) --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">Rate (Unit Price)</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e8edf4;border-radius:10px;background:#eef2f7;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#64748b;flex-shrink:0;">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </span>
                        <input id="modalRate" type="number" min="0" step="0.01" value="0" placeholder="₹ 0.00" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#475569;background:transparent;">
                    </div>
                </div>

                {{-- GST % --}}
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:700;color:#1e293b;margin-bottom:6px;">GST (%)</label>
                    <div style="display:flex;align-items:center;height:44px;border:1px solid #e2e8f0;border-radius:10px;background:#fff;overflow:hidden;">
                        <span style="display:flex;align-items:center;justify-content:center;width:38px;height:100%;color:#94a3b8;flex-shrink:0;font-size:0.82rem;font-weight:700;">%</span>
                        <input id="modalGst" type="number" min="0" max="100" step="0.01" value="18" style="flex:1;height:100%;border:none;outline:none;font-size:0.875rem;color:#1e293b;background:transparent;">
                    </div>
                </div>
            </div>

            {{-- Total Calculated Amount --}}
            <div style="margin-top:20px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#e3f2fd;color:#1565c0;">
                        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </span>
                    <span style="font-size:0.88rem;font-weight:600;color:#475569;">Total Calculated Amount</span>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#e65100;margin:0;">INCL. TAXES</p>
                    <p id="modalTotalAmount" style="font-size:1.5rem;font-weight:800;color:#1e293b;margin:0;">₹ 0.00</p>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="margin-top:24px;display:flex;justify-content:flex-end;gap:12px;">
                <button id="modalCancelBtn" type="button"
                    style="height:44px;padding:0 24px;border:1px solid #e2e8f0;border-radius:12px;background:#fff;color:#475569;font-size:0.875rem;font-weight:600;cursor:pointer;transition:background 0.2s;">
                    Cancel
                </button>
                <button id="modalAddBtn" type="button"
                    style="height:44px;padding:0 28px;border:none;border-radius:12px;background:#e65100;color:#fff;font-size:0.875rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 2px 10px rgba(230,81,0,0.25);transition:background 0.2s;">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Invoice
                </button>
            </div>
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

    // ─── Same as Billing toggle ───
    var toggleTrack = document.getElementById('toggleTrack');
    var toggleThumb = document.getElementById('toggleThumb');
    var sameCheckbox = document.getElementById('sameAsBilling');
    var billingAddr = document.getElementById('billingAddress');
    var shippingAddr = document.getElementById('shippingAddress');
    var toggleOn = false;

    toggleTrack.addEventListener('click', function () {
        toggleOn = !toggleOn;
        sameCheckbox.checked = toggleOn;
        if (toggleOn) {
            toggleTrack.style.background = '#1e3a5f';
            toggleThumb.style.transform = 'translateX(18px)';
            shippingAddr.value = billingAddr.value;
            shippingAddr.readOnly = true;
            shippingAddr.style.background = '#f1f5f9';
        } else {
            toggleTrack.style.background = '#cbd5e1';
            toggleThumb.style.transform = 'translateX(0)';
            shippingAddr.readOnly = false;
            shippingAddr.style.background = '#fff';
        }
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
        tr.style.borderBottom = '1px solid #f1f5f9';
        tr.className = 'product-row';
        tr.innerHTML =
            '<td style="padding:8px;text-align:center;font-weight:600;color:#64748b;font-size:0.875rem;" class="sno">' + String(sno).padStart(2, '0') + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;">' + escHtml(data.catNo) + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;">' + escHtml(data.productName) + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;">' + escHtml(data.packSize) + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;text-align:center;" class="row-qty-val">' + data.qty + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;text-align:right;" class="row-rate-val">' + formatNum(data.rate) + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#475569;text-align:right;" class="row-value">' + formatNum(value) + '</td>' +
            '<td style="padding:8px;font-size:0.85rem;color:#1e293b;text-align:center;" class="row-gst-val">' + data.gst + '</td>' +
            '<td style="padding:8px;font-size:0.875rem;font-weight:700;color:#1e293b;text-align:right;" class="row-total">' + formatNum(total) + '</td>' +
            '<td style="padding:8px;text-align:center;"><button type="button" class="del-row-btn" style="width:30px;height:30px;border:1px solid #fecaca;border-radius:8px;background:none;color:#dc2626;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;"><svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></td>';

        // Store data attributes for calc
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
        emptyMsg.style.display = rows.length > 0 ? 'none' : 'block';
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

    // ─── Modal logic ───
    var modal = document.getElementById('addProductModal');
    // Move modal to body to guarantee it breaks out of relative stacking contexts
    document.body.appendChild(modal);
    
    var backdrop = document.getElementById('modalBackdrop');
    var dialog = document.getElementById('modalDialog');
    var closeBtn = document.getElementById('modalCloseBtn');
    var cancelBtn = document.getElementById('modalCancelBtn');
    var addBtn = document.getElementById('modalAddBtn');

    var mCatNo = document.getElementById('modalCatNo');
    var mProdName = document.getElementById('modalProductName');
    var mPackSize = document.getElementById('modalPackSize');
    var mQty = document.getElementById('modalQty');
    var mRate = document.getElementById('modalRate');
    var mGst = document.getElementById('modalGst');
    var mTotal = document.getElementById('modalTotalAmount');

    function openModal() {
        // Reset fields
        mCatNo.value = '';
        mProdName.value = '';
        mPackSize.value = '';
        mQty.value = '1';
        mRate.value = '0';
        mGst.value = '18';
        updateModalTotal();

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        // Animate in
        requestAnimationFrame(function () {
            backdrop.style.opacity = '1';
            dialog.style.transform = 'scale(1) translateY(0)';
            dialog.style.opacity = '1';
        });
    }

    function closeModal() {
        backdrop.style.opacity = '0';
        dialog.style.transform = 'scale(0.95) translateY(10px)';
        dialog.style.opacity = '0';
        document.body.style.overflow = '';
        setTimeout(function () {
            modal.style.display = 'none';
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

    // Live total calc in modal
    mQty.addEventListener('input', updateModalTotal);
    mRate.addEventListener('input', updateModalTotal);
    mGst.addEventListener('input', updateModalTotal);

    // Open modal on Add Product Row click
    document.getElementById('addProductRow').addEventListener('click', openModal);

    // Close modal
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.style.display === 'block') closeModal(); });

    // Add to Invoice
    addBtn.addEventListener('click', function () {
        var catNo = mCatNo.value.trim();
        var productName = mProdName.value.trim();
        var qty = parseFloat(mQty.value) || 1;
        var rate = parseFloat(mRate.value) || 0;
        var gst = parseFloat(mGst.value) || 0;
        var packSize = mPackSize.value.trim();

        if (!productName) {
            mProdName.style.borderColor = '#ef4444';
            mProdName.focus();
            return;
        }
        mProdName.style.borderColor = '';

        addRowToTable({
            catNo: catNo,
            productName: productName,
            packSize: packSize,
            qty: qty,
            rate: rate,
            gst: gst
        });

        closeModal();
    });

    // Hover effects for buttons
    var addRowBtn = document.getElementById('addProductRow');
    addRowBtn.addEventListener('mouseenter', function() { addRowBtn.style.background = '#bf360c'; });
    addRowBtn.addEventListener('mouseleave', function() { addRowBtn.style.background = '#e65100'; });

    var pdfBtn = document.getElementById('generatePdfBtn');
    pdfBtn.addEventListener('mouseenter', function() { pdfBtn.style.background = '#15294a'; pdfBtn.style.transform = 'translateY(-1px)'; });
    pdfBtn.addEventListener('mouseleave', function() { pdfBtn.style.background = '#1e3a5f'; pdfBtn.style.transform = 'translateY(0)'; });

    addBtn.addEventListener('mouseenter', function() { addBtn.style.background = '#bf360c'; });
    addBtn.addEventListener('mouseleave', function() { addBtn.style.background = '#e65100'; });

    cancelBtn.addEventListener('mouseenter', function() { cancelBtn.style.background = '#f8fafc'; });
    cancelBtn.addEventListener('mouseleave', function() { cancelBtn.style.background = '#fff'; });

    closeBtn.addEventListener('mouseenter', function() { closeBtn.style.background = '#f1f5f9'; });
    closeBtn.addEventListener('mouseleave', function() { closeBtn.style.background = '#fff'; });

    // ─── Freight input recalc ───
    document.getElementById('freightCharges').addEventListener('input', recalcTotals);

    // ─── Add Term button ───
    document.getElementById('addTermBtn').addEventListener('click', function () {
        var list = document.getElementById('termsList');
        var count = list.querySelectorAll('li').length + 1;
        var li = document.createElement('li');
        li.className = 'flex items-baseline gap-2 text-sm text-slate-700';
        li.innerHTML = '<span class="shrink-0 font-bold" style="color:#e65100;">' + count + '.</span>' +
            '<input type="text" value="" placeholder="Enter term..." class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none">';
        list.appendChild(li);
    });

    // ─── Generate PDF (placeholder) ───
    document.getElementById('generatePdfBtn').addEventListener('click', function () {
        if (window.BiogenixToast) {
            window.BiogenixToast.show('PDF generation coming soon! All form data is captured.', 'info');
        } else {
            alert('PDF generation coming soon! All form data is captured.');
        }
    });

    // ─── Show empty message initially ───
    toggleEmptyMsg();
});
</script>
@endpush
