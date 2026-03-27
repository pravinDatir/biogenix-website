@extends('layouts.app')

@section('title', $pageTitle ?? 'Generate Quotation')

@section('content')
@php
    $oldProductIds = old('product_id', $prefilledProductId ? [$prefilledProductId] : ['']);
    $oldQuantities = old('quantity', array_fill(0, max(1, count($oldProductIds)), 1));
    $pageShellClass = 'mx-auto w-full max-w-none space-y-8 px-4 py-8 sm:px-6 lg:px-8 xl:px-10 md:py-12';
    $formCardClass = 'space-y-5 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $sidebarCardClass = 'space-y-4 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $textareaClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $buttonPrimaryClass = 'inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
    $buttonSecondaryClass = 'inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-70';
    $rowClass = 'space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-4';
    $previewClass = 'rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-slate-600';
    
    $pageTitle = 'Generate Quotation';
    $pageDescription = 'Select products, set quantities, and generate branded quotation PDFs using visible MRP values.';
    $sectionSubtitle = 'Choose product, quantity, and recipient information.';
    $formAction = route('quotation.store');
    $primarySubmitLabel = 'Generate Quotation';
    $showDownloadActions = true;
    $actionHelpText = 'Both actions use MRP-only values and server validation.';

    if (! is_array($oldProductIds) || $oldProductIds === []) {
        $oldProductIds = [''];
    }

    if (! is_array($oldQuantities) || $oldQuantities === []) {
        $oldQuantities = array_fill(0, count($oldProductIds), 1);
    }
@endphp

<div>
    <section class="relative overflow-hidden bg-primary-800 py-16 text-white md:py-24">
        <img src="{{ asset('upload/corousel/image3.jpg') }}" alt="Biogenix Quotation" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-800/95 via-primary-800/70 to-primary-600/30"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <h1 class="mx-auto max-w-4xl font-display text-4xl font-bold tracking-tight text-secondary-600 md:text-5xl lg:text-6xl">
                {{ $pageTitle }}
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-secondary-600 md:text-lg">
                {{ $pageDescription }}
            </p>
        </div>
    </section>

    <div class="{{ $pageShellClass }}">
        <section class="space-y-6">
            <div class="mx-auto max-w-4xl">
                <div class="mb-6 space-y-1">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Product Selection</h2>
                    <p class="text-base text-slate-500">{{ $sectionSubtitle }}</p>
                </div>

                <div class="{{ $formCardClass }}">
                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="generateQuoteForm" method="POST" action="{{ $formAction }}" class="space-y-3">
                        @csrf

                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">Products</label>
                            <div id="quote-item-list" class="space-y-3">
                                @foreach ($oldProductIds as $index => $oldProductId)
                                    <div class="{{ $rowClass }}" data-quote-row>
                                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                            <div class="space-y-2">
                                                <label for="product_id_{{ $index }}" class="text-sm font-semibold text-slate-700">Product</label>
                                                <select id="product_id_{{ $index }}" name="product_id[]" data-quote-product-select class="{{ $inputClass }} @error('product_id.' . $index) border-red-500 ring-1 ring-red-100 @enderror" required>
                                                    <option value="">Select product</option>
                                                    @foreach ($products as $product)
                                                        <option
                                                            value="{{ $product->id }}"
                                                            data-name="{{ $product->name }}"
                                                            data-sku="{{ $product->sku }}"
                                                            data-currency="{{ $product->visible_currency ?? 'INR' }}"
                                                            data-mrp="{{ (float) ($product->visible_price ?? 0) }}"
                                                            data-min-order-quantity="{{ $product->min_order_quantity ?? 1 }}"
                                                            data-max-order-quantity="{{ $product->max_order_quantity ?? '' }}"
                                                            data-lot-size="{{ $product->lot_size ?? 1 }}"
                                                            @selected((int) $oldProductId === (int) $product->id)
                                                        >
                                                            {{ $product->name }} ({{ $product->sku }}) - MRP {{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->visible_price ?? 0), 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('product_id.' . $index)
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="quantity_{{ $index }}" class="text-sm font-semibold text-slate-700">Quantity</label>
                                                <input id="quantity_{{ $index }}" name="quantity[]" data-quote-quantity-input class="{{ $inputClass }} @error('quantity.' . $index) border-red-500 ring-1 ring-red-100 @enderror" type="number" min="1" step="1" value="{{ $oldQuantities[$index] ?? 1 }}" required>
                                                @error('quantity.' . $index)
                                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="{{ $previewClass }}" data-quote-preview>Select a product to preview MRP and quantity rules.</div>
                                        <p class="text-sm text-slate-500" data-quote-rule>Select a product to view quantity constraints.</p>
                                        <p class="text-sm text-slate-500" data-quote-line-total>Estimated MRP line total will appear here.</p>

                                        <button type="button" class="{{ $buttonSecondaryClass }} remove-quote-item @if ($index === 0 || count($oldProductIds) === 1) hidden @endif">Remove Item</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-quote-item" class="{{ $buttonSecondaryClass }} mt-2">Add Product</button>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <input type="hidden" id="purpose" name="purpose" value="other">

                            <div class="space-y-2">
                                <label for="customer_name" class="text-sm font-semibold text-slate-700">Recipient Name</label>
                                <input id="customer_name" name="customer_name" class="{{ $inputClass }} @error('customer_name') border-red-500 ring-1 ring-red-100 @enderror" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                                @error('customer_name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="customer_email" class="text-sm font-semibold text-slate-700">Recipient Email</label>
                                <input id="customer_email" name="customer_email" type="email" class="{{ $inputClass }} @error('customer_email') border-red-500 ring-1 ring-red-100 @enderror" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
                                @error('customer_email')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="customer_phone" class="text-sm font-semibold text-slate-700">Recipient Phone</label>
                                <input id="customer_phone" name="customer_phone" class="{{ $inputClass }} @error('customer_phone') border-red-500 ring-1 ring-red-100 @enderror" value="{{ old('customer_phone') }}">
                                @error('customer_phone')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @auth
                                @if (auth()->user()->isB2b())
                                    <div class="space-y-2 md:col-span-2">
                                        <label for="target_company_id" class="text-sm font-semibold text-slate-700">Target Company (optional)</label>
                                        <select id="target_company_id" name="target_company_id" class="{{ $inputClass }} @error('target_company_id') border-red-500 ring-1 ring-red-100 @enderror">
                                            <option value="">Not selected</option>
                                            @foreach ($clientCompanies as $company)
                                                <option value="{{ $company->id }}" @selected((int) old('target_company_id') === (int) $company->id)>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('target_company_id')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="space-y-2">
                            <label for="notes" class="text-sm font-semibold text-slate-700">Notes</label>
                            <textarea id="notes" name="notes" class="{{ $textareaClass }} @error('notes') border-red-500 ring-1 ring-red-100 @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <button type="submit" id="generateQuoteSubmitBtn" class="{{ $buttonPrimaryClass }} w-full sm:w-auto">{{ $primarySubmitLabel }}</button>
                            @if ($showDownloadActions)
                                <button type="submit" id="downloadQuoteSubmitBtn" name="download_pdf" value="1" class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-neutral-800 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-neutral-700 sm:w-auto">Download PDF</button>
                            @endif
                            <p class="text-xs text-slate-500">{{ $actionHelpText }}</p>
                            <p id="quoteDraftStatus" class="w-full text-xs text-slate-500"></p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- Product Selection Modal --}}
<div id="addProductModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/40 opacity-0 transition-opacity duration-300 backdrop-blur-sm" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div id="modalDialog" class="relative inline-block w-full max-w-lg origin-center translate-y-4 scale-95 transform rounded-[28px] border border-slate-200 bg-white p-6 text-left align-middle opacity-0 shadow-2xl transition-all duration-300 sm:p-8">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900" id="modal-title">Search & Add Product</h3>
                <button id="modalCloseBtn" type="button" class="rounded-full p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="space-y-5">
                <div class="space-y-2">
                    <label for="modalProductSelect" class="text-sm font-semibold text-slate-700">Product</label>
                    <select id="modalProductSelect" class="{{ $inputClass }}">
                        <option value="">Select product to add</option>
                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-sku="{{ $product->sku }}"
                                data-currency="{{ $product->visible_currency ?? 'INR' }}"
                                data-mrp="{{ (float) ($product->visible_price ?? 0) }}"
                                data-min-order-quantity="{{ $product->min_order_quantity ?? 1 }}"
                                data-max-order-quantity="{{ $product->max_order_quantity ?? '' }}"
                                data-lot-size="{{ $product->lot_size ?? 1 }}"
                            >
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="modalQtyInput" class="text-sm font-semibold text-slate-700">Quantity</label>
                    <input id="modalQtyInput" type="number" min="1" value="1" class="{{ $inputClass }}">
                </div>

                <div class="{{ $previewClass }}">
                    <p id="modalPreview" class="text-slate-600">Select a product to preview MRP and quantity rules.</p>
                </div>

                <div class="space-y-1">
                    <p id="modalRule" class="text-xs font-medium text-slate-500">Select a product to view quantity constraints.</p>
                    <p id="modalLineTotal" class="text-sm font-bold text-primary-600">Estimated MRP line total will appear here.</p>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <button id="modalCancelBtn" type="button" class="{{ $buttonSecondaryClass }}">Cancel</button>
                <button id="modalAddBtn" type="button" class="{{ $buttonPrimaryClass }}">Add to List</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quoteForm = document.getElementById('generateQuoteForm');
        const quoteSubmitBtn = document.getElementById('generateQuoteSubmitBtn');
        const downloadQuoteSubmitBtn = document.getElementById('downloadQuoteSubmitBtn');
        const itemList = document.getElementById('quote-item-list');
        const addItemButton = document.getElementById('add-quote-item');
        const quoteDraftStatus = document.getElementById('quoteDraftStatus');
        const draftKey = 'biogenix_quote_draft_v1';
        let activeSubmitBtn = null;

        // Modal Elements
        const addProductModal = document.getElementById('addProductModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalDialog = document.getElementById('modalDialog');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalAddBtn = document.getElementById('modalAddBtn');
        const modalProductSelect = document.getElementById('modalProductSelect');
        const modalQtyInput = document.getElementById('modalQtyInput');
        const modalPreview = document.getElementById('modalPreview');
        const modalRule = document.getElementById('modalRule');
        const modalLineTotal = document.getElementById('modalLineTotal');

        if (addProductModal) {
            document.body.appendChild(addProductModal);
        }

        function openAddProductModal() {
            if (!addProductModal) return;
            modalProductSelect.value = '';
            modalQtyInput.value = '1';
            updateModalPreview();
            addProductModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            requestAnimationFrame(() => {
                modalBackdrop.classList.replace('opacity-0', 'opacity-100');
                modalDialog.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                modalDialog.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            });
        }

        function closeAddProductModal() {
            if (!addProductModal) return;
            modalBackdrop.classList.replace('opacity-100', 'opacity-0');
            modalDialog.classList.replace('opacity-100', 'opacity-0');
            modalDialog.classList.replace('scale-100', 'scale-95');
            modalDialog.classList.replace('translate-y-0', 'translate-y-4');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => {
                addProductModal.classList.add('hidden');
            }, 300);
        }

        function updateModalPreview() {
            const selected = modalProductSelect.options[modalProductSelect.selectedIndex];
            if (!selected || !selected.value) {
                modalQtyInput.min = 1;
                modalQtyInput.step = 1;
                modalQtyInput.removeAttribute('max');
                modalPreview.textContent = 'Select a product to preview MRP and quantity rules.';
                modalRule.textContent = 'Select a product to view quantity constraints.';
                modalLineTotal.textContent = 'Estimated MRP line total will appear here.';
                return;
            }

            const minQty = parseInt(selected.dataset.minOrderQuantity || '1', 10);
            const lotSize = parseInt(selected.dataset.lotSize || '1', 10);
            const maxQty = selected.dataset.maxOrderQuantity || '';
            const currency = selected.dataset.currency || 'INR';
            const mrp = parseFloat(selected.dataset.mrp || '0');

            modalQtyInput.min = minQty;
            modalQtyInput.step = lotSize;
            if (maxQty !== '') modalQtyInput.max = maxQty;
            else modalQtyInput.removeAttribute('max');

            if (!modalQtyInput.value || parseInt(modalQtyInput.value, 10) < minQty) {
                modalQtyInput.value = minQty;
            }

            const qty = parseInt(modalQtyInput.value, 10);
            const total = mrp * qty;

            modalPreview.innerHTML = `<strong>${selected.dataset.name}</strong><br>SKU: ${selected.dataset.sku || '-'}<br>MRP / Unit: ${currency} ${mrp.toFixed(2)}`;
            modalRule.textContent = `Min Qty: ${minQty} | Lot Size: ${lotSize}${maxQty ? ' | Max Qty: ' + maxQty : ''}`;
            modalLineTotal.textContent = `Estimated line MRP total: ${currency} ${total.toFixed(2)}`;
        }

        addItemButton.addEventListener('click', openAddProductModal);
        modalCloseBtn?.addEventListener('click', closeAddProductModal);
        modalCancelBtn?.addEventListener('click', closeAddProductModal);
        modalBackdrop?.addEventListener('click', closeAddProductModal);
        modalProductSelect?.addEventListener('change', updateModalPreview);
        modalQtyInput?.addEventListener('input', updateModalPreview);

        modalAddBtn?.addEventListener('click', function() {
            const selected = modalProductSelect.options[modalProductSelect.selectedIndex];
            if (!selected || !selected.value) {
                alert('Please select a product.');
                return;
            }

            const firstRow = itemList.querySelector('[data-quote-row]');
            let newRow;
            
            // If the first row is empty/placeholder, use it
            if (firstRow && !firstRow.querySelector('[data-quote-product-select]').value) {
                newRow = firstRow;
            } else {
                newRow = firstRow.cloneNode(true);
                itemList.appendChild(newRow);
            }

            const rowSelect = newRow.querySelector('[data-quote-product-select]');
            const rowQty = newRow.querySelector('[data-quote-quantity-input]');

            rowSelect.value = modalProductSelect.value;
            rowQty.value = modalQtyInput.value;

            attachRowEvents(newRow);
            reindexRows();
            updateRemoveButtons();
            closeAddProductModal();
        });

        [quoteSubmitBtn, downloadQuoteSubmitBtn].forEach(function (btn) {
            if (!btn) return;
            btn.addEventListener('click', function () {
                activeSubmitBtn = btn;
            });
        });

        if (quoteForm && quoteSubmitBtn) {
            quoteForm.addEventListener('submit', function () {
                const submitTarget = activeSubmitBtn || quoteSubmitBtn;
                submitTarget.disabled = true;
                submitTarget.classList.add('opacity-70', 'cursor-not-allowed');
                submitTarget.setAttribute('aria-disabled', 'true');
            });
        }

        function formatMoney(currency, amount) {
            return currency + ' ' + Number(amount || 0).toFixed(2);
        }

        function updateRemoveButtons() {
            const rows = itemList.querySelectorAll('[data-quote-row]');
            rows.forEach(function (row, index) {
                const removeButton = row.querySelector('.remove-quote-item');
                removeButton.classList.toggle('hidden', index === 0 || rows.length <= 1);
            });
        }

        function reindexRows() {
            itemList.querySelectorAll('[data-quote-row]').forEach(function (row, index) {
                row.querySelector('[data-quote-product-select]').id = 'product_id_' + index;
                row.querySelector('[data-quote-quantity-input]').id = 'quantity_' + index;
            });
        }

        function updateRow(row) {
            const productSelect = row.querySelector('[data-quote-product-select]');
            const quantityInput = row.querySelector('[data-quote-quantity-input]');
            const preview = row.querySelector('[data-quote-preview]');
            const rule = row.querySelector('[data-quote-rule]');
            const lineTotal = row.querySelector('[data-quote-line-total]');
            const selected = productSelect.options[productSelect.selectedIndex];

            if (!selected || !selected.value) {
                quantityInput.min = 1;
                quantityInput.step = 1;
                quantityInput.removeAttribute('max');
                preview.textContent = 'Select a product to preview MRP and quantity rules.';
                rule.textContent = 'Select a product to view quantity constraints.';
                lineTotal.textContent = 'Estimated MRP line total will appear here.';
                return;
            }

            const minQty = parseInt(selected.dataset.minOrderQuantity || '1', 10);
            const lotSize = parseInt(selected.dataset.lotSize || '1', 10);
            const maxQty = selected.dataset.maxOrderQuantity || '';
            const currency = selected.dataset.currency || 'INR';
            const mrp = parseFloat(selected.dataset.mrp || '0');

            quantityInput.min = minQty;
            quantityInput.step = lotSize;

            if (maxQty !== '') {
                quantityInput.max = maxQty;
            } else {
                quantityInput.removeAttribute('max');
            }

            if (!quantityInput.value || parseInt(quantityInput.value, 10) < minQty) {
                quantityInput.value = minQty;
            }

            const qty = parseInt(quantityInput.value || minQty, 10);
            const total = mrp * qty;

            preview.innerHTML =
                '<strong>' + selected.dataset.name + '</strong><br>' +
                'SKU: ' + (selected.dataset.sku || '-') + '<br>' +
                'MRP / Unit: ' + formatMoney(currency, mrp);

            let ruleMessage = 'Min Qty: ' + minQty + ' | Lot Size: ' + lotSize;
            if (maxQty !== '') {
                ruleMessage += ' | Max Qty: ' + maxQty;
            }

            rule.textContent = ruleMessage;
            lineTotal.textContent = 'Estimated line MRP total: ' + formatMoney(currency, total);
        }

        function attachRowEvents(row) {
            row.querySelector('[data-quote-product-select]').addEventListener('change', function () {
                updateRow(row);
            });

            row.querySelector('[data-quote-quantity-input]').addEventListener('input', function () {
                updateRow(row);
            });

            row.querySelector('.remove-quote-item').addEventListener('click', function () {
                row.remove();
                reindexRows();
                updateRemoveButtons();
            });

            updateRow(row);
        }

        itemList.querySelectorAll('[data-quote-row]').forEach(function (row) {
            attachRowEvents(row);
        });

        addItemButton.addEventListener('click', function () {
            const firstRow = itemList.querySelector('[data-quote-row]');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('[data-quote-product-select]').value = '';
            newRow.querySelector('[data-quote-quantity-input]').value = '';
            newRow.querySelector('[data-quote-preview]').textContent = 'Select a product to preview MRP and quantity rules.';
            newRow.querySelector('[data-quote-rule]').textContent = 'Select a product to view quantity constraints.';
            newRow.querySelector('[data-quote-line-total]').textContent = 'Estimated MRP line total will appear here.';

            itemList.appendChild(newRow);
            attachRowEvents(newRow);
            reindexRows();
            updateRemoveButtons();
        });

        reindexRows();
        updateRemoveButtons();
    });
</script>
@endpush
@endsection
