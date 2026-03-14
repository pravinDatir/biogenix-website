@php
    $oldProductIds = old('product_id', $prefilledProductId ? [$prefilledProductId] : ['']);
    $oldQuantities = old('quantity', array_fill(0, max(1, count($oldProductIds)), 1));
    $pageShellClass = 'mx-auto w-full max-w-none space-y-8 px-4 py-6 sm:px-6 lg:px-8 xl:px-10';
    $formCardClass = 'space-y-5 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $sidebarCardClass = 'space-y-4 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $textareaClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $buttonPrimaryClass = 'inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
    $buttonSecondaryClass = 'inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-70';
    $rowClass = 'space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-4';
    $previewClass = 'rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-slate-600';

    if (! is_array($oldProductIds) || $oldProductIds === []) {
        $oldProductIds = [''];
    }

    if (! is_array($oldQuantities) || $oldQuantities === []) {
        $oldQuantities = array_fill(0, count($oldProductIds), 1);
    }
@endphp

<div class="{{ $pageShellClass }}">
    <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:underline">Products</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Order / Generate Quote</span>
    </nav>

    <section class="section-stack">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-r from-slate-950 via-slate-900 to-primary-950 p-5 text-white shadow-xl md:p-8">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <x-badge variant="inverse">MRP-Only Guest Flow</x-badge>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white md:text-4xl">Generate Quotation / PI</h1>
                    <p class="mt-3 max-w-2xl text-base leading-8 text-slate-100">
                        Select products, set quantities, and generate branded quotation PDFs using visible MRP values.
                    </p>
                </div>
                <div class="lg:col-span-4">
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-50">Access Rules</p>
                        <p class="mt-2 text-sm text-slate-100">Guests can generate and download quote documents.</p>
                        <p class="mt-1 text-sm text-slate-100">Login is required for customer pricing and order placement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-stack">
        <x-ui.section-heading title="Product Selection" subtitle="Choose product, quantity, and recipient information." />
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-8">
                <x-ui.surface-card class="{{ $formCardClass }}">
                    @if ($errors->any())
                        <div class="errors mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="generateQuoteForm" method="POST" action="{{ route('proforma.store') }}" class="space-y-3">
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

                                        <button type="button" class="{{ $buttonSecondaryClass }} remove-quote-item @if (count($oldProductIds) === 1) hidden @endif">Remove Item</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-quote-item" class="{{ $buttonSecondaryClass }} mt-2">Add Product</button>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="purpose" class="text-sm font-semibold text-slate-700">Quote Purpose</label>
                                <select id="purpose" name="purpose" class="{{ $inputClass }} @error('purpose') border-red-500 ring-1 ring-red-100 @enderror" required>
                                    <option value="self" @selected(old('purpose') === 'self')>Self</option>
                                    <option value="other" @selected(old('purpose') === 'other')>Custom Recipient</option>
                                </select>
                                @error('purpose')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

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
                            <button type="submit" id="generateQuoteSubmitBtn" class="{{ $buttonPrimaryClass }} w-full sm:w-auto">Generate Quotation</button>
                            <button type="submit" id="downloadQuoteSubmitBtn" name="download_pdf" value="1" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">Download Branded PDF</button>
                            <button type="button" id="saveQuoteDraftBtn" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">Save Draft</button>
                            <button type="button" id="clearQuoteDraftBtn" class="{{ $buttonSecondaryClass }} w-full sm:w-auto">Clear Draft</button>
                            <p class="text-xs text-slate-500">Both actions use MRP-only values and server validation.</p>
                            <p id="quoteDraftStatus" class="w-full text-xs text-slate-500"></p>
                        </div>
                    </form>
                </x-ui.surface-card>
            </div>

            <div class="lg:col-span-4">
                <x-ui.surface-card class="{{ $sidebarCardClass }}">
                    <h3 class="text-lg font-semibold text-slate-900">Preview Quote</h3>
                    <p class="mt-1 text-sm text-slate-600">Review recipient and estimated MRP totals before generating PDF.</p>
                    <div id="quote-summary-body" class="mt-3 text-sm text-slate-600">
                        Select products and quantities to preview estimated MRP totals.
                    </div>
                    @guest
                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <div class="rounded-lg border border-primary-100 bg-primary-50 p-4">
                                <p class="text-sm text-gray-700">Login to access personalized pricing and ordering features.</p>
                                <a href="{{ route('login') }}" class="text-primary-700 font-medium hover:underline">Login Now</a>
                            </div>
                            <button type="button" id="downloadFromPreviewBtn" class="{{ $buttonSecondaryClass }} mt-3 w-full">Download Branded PDF</button>
                        </div>
                    @endguest
                    @auth
                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <x-ui.action-link :href="route('proforma.index')" variant="secondary">Download Branded PDF (Recent Quotes)</x-ui.action-link>
                        </div>
                    @endauth
                </x-ui.surface-card>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quoteForm = document.getElementById('generateQuoteForm');
        const quoteSubmitBtn = document.getElementById('generateQuoteSubmitBtn');
        const downloadQuoteSubmitBtn = document.getElementById('downloadQuoteSubmitBtn');
        const saveDraftBtn = document.getElementById('saveQuoteDraftBtn');
        const clearDraftBtn = document.getElementById('clearQuoteDraftBtn');
        const downloadFromPreviewBtn = document.getElementById('downloadFromPreviewBtn');
        const itemList = document.getElementById('quote-item-list');
        const addItemButton = document.getElementById('add-quote-item');
        const summaryBody = document.getElementById('quote-summary-body');
        const quoteDraftStatus = document.getElementById('quoteDraftStatus');
        const draftKey = 'biogenix_quote_draft_v1';
        let activeSubmitBtn = null;

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

        if (downloadFromPreviewBtn && quoteForm && downloadQuoteSubmitBtn) {
            downloadFromPreviewBtn.addEventListener('click', function () {
                activeSubmitBtn = downloadQuoteSubmitBtn;
                downloadQuoteSubmitBtn.click();
            });
        }

        function formatMoney(currency, amount) {
            return currency + ' ' + Number(amount || 0).toFixed(2);
        }

        function updateRemoveButtons() {
            const rows = itemList.querySelectorAll('[data-quote-row]');
            rows.forEach(function (row) {
                const removeButton = row.querySelector('.remove-quote-item');
                removeButton.classList.toggle('hidden', rows.length <= 1);
            });
        }

        function reindexRows() {
            itemList.querySelectorAll('[data-quote-row]').forEach(function (row, index) {
                row.querySelector('[data-quote-product-select]').id = 'product_id_' + index;
                row.querySelector('[data-quote-quantity-input]').id = 'quantity_' + index;
            });
        }

        function updateSummary() {
            let itemCount = 0;
            let total = 0;
            let currency = 'INR';

            itemList.querySelectorAll('[data-quote-row]').forEach(function (row) {
                const productSelect = row.querySelector('[data-quote-product-select]');
                const quantityInput = row.querySelector('[data-quote-quantity-input]');
                const selected = productSelect.options[productSelect.selectedIndex];

                if (!selected || !selected.value) return;

                const qty = parseInt(quantityInput.value || '0', 10);
                if (!qty || qty < 1) return;

                const mrp = parseFloat(selected.dataset.mrp || '0');
                currency = selected.dataset.currency || 'INR';

                total += mrp * qty;
                itemCount++;
            });

            if (!itemCount) {
                summaryBody.textContent = 'Select products and quantities to preview estimated MRP totals.';
                return;
            }

            summaryBody.innerHTML =
                '<p><strong>Items Selected:</strong> ' + itemCount + '</p>' +
                '<p><strong>Estimated MRP Total:</strong> ' + formatMoney(currency, total) + '</p>' +
                '<p class="mt-2 text-xs text-slate-500">Final calculations are validated on the server during quotation generation.</p>';
        }

        function serializeDraft() {
            const rows = Array.from(itemList.querySelectorAll('[data-quote-row]')).map(function (row) {
                return {
                    productId: row.querySelector('[data-quote-product-select]').value || '',
                    quantity: row.querySelector('[data-quote-quantity-input]').value || '1'
                };
            });

            return {
                purpose: document.getElementById('purpose')?.value || 'self',
                customerName: document.getElementById('customer_name')?.value || '',
                customerEmail: document.getElementById('customer_email')?.value || '',
                customerPhone: document.getElementById('customer_phone')?.value || '',
                notes: document.getElementById('notes')?.value || '',
                rows: rows.length ? rows : [{ productId: '', quantity: '1' }]
            };
        }

        function buildRow(rowData) {
            const firstRow = itemList.querySelector('[data-quote-row]');
            const row = firstRow.cloneNode(true);

            row.querySelector('[data-quote-product-select]').value = rowData.productId || '';
            row.querySelector('[data-quote-quantity-input]').value = rowData.quantity || '1';
            row.querySelector('[data-quote-preview]').textContent = 'Select a product to preview MRP and quantity rules.';
            row.querySelector('[data-quote-rule]').textContent = 'Select a product to view quantity constraints.';
            row.querySelector('[data-quote-line-total]').textContent = 'Estimated MRP line total will appear here.';

            return row;
        }

        function applyDraft(draft) {
            if (!draft || !Array.isArray(draft.rows) || !draft.rows.length) return;

            itemList.innerHTML = '';
            draft.rows.forEach(function (rowData) {
                const row = buildRow(rowData);
                itemList.appendChild(row);
                attachRowEvents(row);
            });

            const purpose = document.getElementById('purpose');
            const customerName = document.getElementById('customer_name');
            const customerEmail = document.getElementById('customer_email');
            const customerPhone = document.getElementById('customer_phone');
            const notes = document.getElementById('notes');

            if (purpose) purpose.value = draft.purpose || 'self';
            if (customerName) customerName.value = draft.customerName || '';
            if (customerEmail) customerEmail.value = draft.customerEmail || '';
            if (customerPhone) customerPhone.value = draft.customerPhone || '';
            if (notes) notes.value = draft.notes || '';

            reindexRows();
            updateRemoveButtons();
            updateSummary();
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
                updateSummary();
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
            updateSummary();
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
                updateSummary();
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

        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function () {
                try {
                    const draft = serializeDraft();
                    localStorage.setItem(draftKey, JSON.stringify(draft));
                    if (quoteDraftStatus) {
                        quoteDraftStatus.textContent = 'Draft saved locally in this browser.';
                        quoteDraftStatus.classList.remove('text-red-600');
                        quoteDraftStatus.classList.add('text-emerald-600');
                    }
                } catch (error) {
                    console.error('Failed to save quote draft', error);
                }
            });
        }

        if (clearDraftBtn) {
            clearDraftBtn.addEventListener('click', function () {
                localStorage.removeItem(draftKey);
                summaryBody.innerHTML = 'Select products and quantities to preview estimated MRP totals.';
                if (quoteDraftStatus) {
                    quoteDraftStatus.textContent = 'Draft cleared.';
                    quoteDraftStatus.classList.remove('text-red-600');
                    quoteDraftStatus.classList.add('text-emerald-600');
                }
            });
        }

        try {
            const savedDraft = localStorage.getItem(draftKey);
            if (savedDraft) {
                applyDraft(JSON.parse(savedDraft));
                if (quoteDraftStatus) {
                    quoteDraftStatus.textContent = 'A saved draft has been restored.';
                    quoteDraftStatus.classList.remove('text-red-600');
                    quoteDraftStatus.classList.add('text-emerald-600');
                }
            }
        } catch (error) {
            console.error('Failed to restore quote draft', error);
        }

        reindexRows();
        updateRemoveButtons();
        updateSummary();
    });
</script>
@endpush
