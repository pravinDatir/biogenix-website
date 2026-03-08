@extends('layouts.app')

@section('content')
    @php
        $oldProductIds = old('product_id', $prefilledProductId ? [$prefilledProductId] : ['']);
        $oldQuantities = old('quantity', array_fill(0, max(1, count($oldProductIds)), 1));
        $discountPercent = 2;

        if (! is_array($oldProductIds) || $oldProductIds === []) {
            $oldProductIds = [''];
        }

        if (! is_array($oldQuantities) || $oldQuantities === []) {
            $oldQuantities = array_fill(0, count($oldProductIds), 1);
        }
    @endphp

    <div class="card">
        <h1>Generate Proforma Invoice (PI)</h1>
        <p class="muted">This form uses the current visible price, GST, quantity rules, and a hardcoded {{ $discountPercent }}% discount for each item.</p>

        @guest
            <p class="muted">Guest users can generate PI for self or another customer with basic details only.</p>
        @endguest

        @auth
            @if (auth()->user()->isB2c())
                <p class="muted">B2C users can generate PI for self only.</p>
            @elseif (auth()->user()->isB2b())
                <p class="muted">B2B users can generate PI for self and assigned clients only (if permission is available).</p>
            @endif
        @endauth
    </div>

    <div class="card">
        <h2>Visible Product Pricing Snapshot</h2>
        <p class="muted">These values are currently available for PI generation.</p>

        @if ($products->isEmpty())
            <p class="muted">No products are currently visible for PI generation.</p>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price Type</th>
                            <th>Base Price</th>
                            <th>GST %</th>
                            <th>Tax / Unit</th>
                            <th>Price After GST</th>
                            <th>Quantity Rule</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <div class="muted">SKU: {{ $product->sku }}</div>
                                </td>
                                <td>{{ strtoupper($product->visible_price_type ?? 'NA') }}</td>
                                <td>{{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->visible_price ?? 0), 2) }}</td>
                                <td>{{ number_format((float) ($product->gst_rate ?? 0), 2) }}</td>
                                <td>{{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->tax_amount ?? 0), 2) }}</td>
                                <td>{{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->price_after_gst ?? 0), 2) }}</td>
                                <td>
                                    Min {{ (int) ($product->min_order_quantity ?? 1) }},
                                    Lot {{ (int) ($product->lot_size ?? 1) }}
                                    @if ($product->max_order_quantity !== null)
                                        , Max {{ (int) $product->max_order_quantity }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="field">
                @foreach ($errors->all() as $error)
                    <p class="muted">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('proforma.store') }}">
            @csrf

            <div class="field">
                <label>Items</label>
                <div id="pi-item-list">
                    @foreach ($oldProductIds as $index => $oldProductId)
                        <div class="pi-item-row" style="padding: 12px; border: 1px solid #ddd; margin-bottom: 12px;">
                            <div class="field">
                                <label for="product_id_{{ $index }}">Product</label>
                                <select id="product_id_{{ $index }}" name="product_id[]" class="pi-product-select" required>
                                    <option value="">Select product</option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-price-type="{{ $product->visible_price_type ?? '' }}"
                                            data-currency="{{ $product->visible_currency ?? 'INR' }}"
                                            data-base-price="{{ $product->visible_price ?? '' }}"
                                            data-gst-rate="{{ $product->gst_rate ?? 0 }}"
                                            data-tax-amount="{{ $product->tax_amount ?? 0 }}"
                                            data-min-order-quantity="{{ $product->min_order_quantity ?? 1 }}"
                                            data-max-order-quantity="{{ $product->max_order_quantity ?? '' }}"
                                            data-lot-size="{{ $product->lot_size ?? 1 }}"
                                            data-price-after-gst="{{ $product->price_after_gst ?? '' }}"
                                            @selected((int) $oldProductId === (int) $product->id)
                                        >
                                            {{ $product->name }} ({{ $product->sku }}) - {{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->price_after_gst ?? 0), 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label for="quantity_{{ $index }}">Quantity</label>
                                <input id="quantity_{{ $index }}" name="quantity[]" class="pi-quantity-input" type="number" min="1" step="1" value="{{ $oldQuantities[$index] ?? 1 }}" required>
                            </div>

                            <div class="pi-product-preview muted" style="padding: 10px; border: 1px solid #e5e7eb; background: #f8fafc; border-radius: 6px; margin-bottom: 8px;">
                                Select a product to see price, GST, and invoice calculation details.
                            </div>
                            <p class="muted pi-quantity-rule">Select a product to see quantity rule and price details.</p>
                            <p class="muted pi-line-total">Estimated line total after GST and {{ $discountPercent }}% discount will appear here.</p>

                            <button type="button" class="btn remove-pi-item" @if (count($oldProductIds) === 1) style="display:none;" @endif>Remove Item</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn" id="add-pi-item">Add Another Item</button>
            </div>

            <div class="field">
                <label for="purpose">PI Purpose</label>
                <select id="purpose" name="purpose" required>
                    <option value="self" @selected(old('purpose') === 'self')>Self</option>
                    <option value="other" @selected(old('purpose') === 'other')>Another Customer</option>
                </select>
            </div>

            <div class="field">
                <label for="customer_name">Customer Name</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
            </div>

            <div class="field">
                <label for="customer_email">Customer Email</label>
                <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
            </div>

            <div class="field">
                <label for="customer_phone">Customer Phone (Optional)</label>
                <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
            </div>

            @auth
                @if (auth()->user()->isB2b())
                    <div class="field">
                        <label for="target_company_id">Target Company (for B2B "other" flow)</label>
                        <select id="target_company_id" name="target_company_id">
                            <option value="">Not selected</option>
                            @foreach ($clientCompanies as $company)
                                <option value="{{ $company->id }}" @selected((int) old('target_company_id') === (int) $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            @endauth

            <div class="field">
                <label for="notes">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn">Generate PI</button>
        </form>
    </div>

    <div class="card">
        <h2>Current Invoice Estimate</h2>
        <p class="muted">This preview is only for quick testing. Final totals are recalculated again on the server while saving the PI.</p>
        <div id="pi-summary-body" class="muted">
            Select products and quantities to view subtotal, GST, discount, and final total.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemList = document.getElementById('pi-item-list');
            const addItemButton = document.getElementById('add-pi-item');
            const summaryBody = document.getElementById('pi-summary-body');
            const discountPercent = {{ $discountPercent }};

            function updateRemoveButtons() {
                const rows = itemList.querySelectorAll('.pi-item-row');

                rows.forEach(function (row) {
                    const removeButton = row.querySelector('.remove-pi-item');
                    removeButton.style.display = rows.length > 1 ? 'inline-block' : 'none';
                });
            }

            function reindexRows() {
                itemList.querySelectorAll('.pi-item-row').forEach(function (row, index) {
                    row.querySelector('.pi-product-select').id = 'product_id_' + index;
                    row.querySelector('.pi-quantity-input').id = 'quantity_' + index;
                });
            }

            function formatMoney(currency, amount) {
                return currency + ' ' + Number(amount || 0).toFixed(2);
            }

            function updateInvoiceSummary() {
                let currency = 'INR';
                let subtotal = 0;
                let totalTax = 0;
                let totalAfterGst = 0;
                let totalDiscount = 0;
                let grandTotal = 0;
                let itemCount = 0;

                itemList.querySelectorAll('.pi-item-row').forEach(function (row) {
                    const productSelect = row.querySelector('.pi-product-select');
                    const quantityInput = row.querySelector('.pi-quantity-input');
                    const selectedOption = productSelect.options[productSelect.selectedIndex];

                    if (!selectedOption || !selectedOption.value) {
                        return;
                    }

                    const quantity = parseInt(quantityInput.value || '0', 10);

                    if (! quantity || quantity < 1) {
                        return;
                    }

                    currency = selectedOption.dataset.currency || 'INR';

                    const unitPrice = parseFloat(selectedOption.dataset.basePrice || '0');
                    const unitTaxAmount = parseFloat(selectedOption.dataset.taxAmount || '0');
                    const unitPriceAfterGst = parseFloat(selectedOption.dataset.priceAfterGst || '0');
                    const unitDiscountAmount = (unitPriceAfterGst * discountPercent) / 100;

                    subtotal += unitPrice * quantity;
                    totalTax += unitTaxAmount * quantity;
                    totalAfterGst += unitPriceAfterGst * quantity;
                    totalDiscount += unitDiscountAmount * quantity;
                    grandTotal += (unitPriceAfterGst - unitDiscountAmount) * quantity;
                    itemCount++;
                });

                if (itemCount === 0) {
                    summaryBody.textContent = 'Select products and quantities to view subtotal, GST, discount, and final total.';
                    return;
                }

                summaryBody.innerHTML =
                    '<p><strong>Selected Items:</strong> ' + itemCount + '</p>' +
                    '<p><strong>Subtotal:</strong> ' + formatMoney(currency, subtotal) + '</p>' +
                    '<p><strong>Total GST:</strong> ' + formatMoney(currency, totalTax) + '</p>' +
                    '<p><strong>Total After GST:</strong> ' + formatMoney(currency, totalAfterGst) + '</p>' +
                    '<p><strong>Total Discount (' + discountPercent + '%):</strong> ' + formatMoney(currency, totalDiscount) + '</p>' +
                    '<p><strong>Estimated Grand Total:</strong> ' + formatMoney(currency, grandTotal) + '</p>';
            }

            function updateRowRules(row) {
                const productSelect = row.querySelector('.pi-product-select');
                const quantityInput = row.querySelector('.pi-quantity-input');
                const previewBox = row.querySelector('.pi-product-preview');
                const ruleText = row.querySelector('.pi-quantity-rule');
                const lineTotalText = row.querySelector('.pi-line-total');
                const selectedOption = productSelect.options[productSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    quantityInput.min = 1;
                    quantityInput.step = 1;
                    quantityInput.removeAttribute('max');
                    previewBox.textContent = 'Select a product to see price, GST, and invoice calculation details.';
                    ruleText.textContent = 'Select a product to see quantity rule and price details.';
                    lineTotalText.textContent = 'Estimated line total after GST and ' + discountPercent + '% discount will appear here.';
                    updateInvoiceSummary();
                    return;
                }

                const productName = selectedOption.dataset.name || '';
                const sku = selectedOption.dataset.sku || '';
                const priceType = selectedOption.dataset.priceType || 'NA';
                const currency = selectedOption.dataset.currency || 'INR';
                const basePrice = parseFloat(selectedOption.dataset.basePrice || '0');
                const gstRate = parseFloat(selectedOption.dataset.gstRate || '0');
                const taxAmount = parseFloat(selectedOption.dataset.taxAmount || '0');
                const minOrderQuantity = parseInt(selectedOption.dataset.minOrderQuantity || '1', 10);
                const maxOrderQuantity = selectedOption.dataset.maxOrderQuantity || '';
                const lotSize = parseInt(selectedOption.dataset.lotSize || '1', 10);
                const priceAfterGst = parseFloat(selectedOption.dataset.priceAfterGst || '0');

                quantityInput.min = minOrderQuantity;
                quantityInput.step = lotSize;

                if (maxOrderQuantity !== '') {
                    quantityInput.max = maxOrderQuantity;
                } else {
                    quantityInput.removeAttribute('max');
                }

                if (!quantityInput.value || parseInt(quantityInput.value, 10) < minOrderQuantity) {
                    quantityInput.value = minOrderQuantity;
                }

                const quantity = parseInt(quantityInput.value || minOrderQuantity, 10);
                const unitDiscountAmount = (priceAfterGst * discountPercent) / 100;
                const lineTotal = (priceAfterGst - unitDiscountAmount) * quantity;

                previewBox.innerHTML =
                    '<strong>' + productName + '</strong><br>' +
                    'SKU: ' + sku + '<br>' +
                    'Price Type: ' + String(priceType).toUpperCase() + '<br>' +
                    'Base Price: ' + formatMoney(currency, basePrice) + '<br>' +
                    'GST: ' + gstRate.toFixed(2) + '%<br>' +
                    'Tax / Unit: ' + formatMoney(currency, taxAmount) + '<br>' +
                    'Price After GST / Unit: ' + formatMoney(currency, priceAfterGst);

                let ruleMessage = 'Min Qty: ' + minOrderQuantity + ' | Lot Size: ' + lotSize;

                if (maxOrderQuantity !== '') {
                    ruleMessage += ' | Max Qty: ' + maxOrderQuantity;
                }

                ruleText.textContent = ruleMessage;
                lineTotalText.textContent = 'Estimated line total after GST and ' + discountPercent + '% discount: ' + formatMoney(currency, lineTotal);
                updateInvoiceSummary();
            }

            function attachRowEvents(row) {
                row.querySelector('.pi-product-select').addEventListener('change', function () {
                    updateRowRules(row);
                });

                row.querySelector('.pi-quantity-input').addEventListener('input', function () {
                    updateRowRules(row);
                });

                row.querySelector('.remove-pi-item').addEventListener('click', function () {
                    row.remove();
                    reindexRows();
                    updateRemoveButtons();
                    updateInvoiceSummary();
                });

                updateRowRules(row);
            }

            itemList.querySelectorAll('.pi-item-row').forEach(function (row) {
                attachRowEvents(row);
            });

            addItemButton.addEventListener('click', function () {
                const firstRow = itemList.querySelector('.pi-item-row');
                const newRow = firstRow.cloneNode(true);
                newRow.querySelector('.pi-product-select').value = '';
                newRow.querySelector('.pi-quantity-input').value = '';
                newRow.querySelector('.pi-product-preview').textContent = 'Select a product to see price, GST, and invoice calculation details.';
                newRow.querySelector('.pi-quantity-rule').textContent = 'Select a product to see quantity rule and price details.';
                newRow.querySelector('.pi-line-total').textContent = 'Estimated line total after GST and ' + discountPercent + '% discount will appear here.';

                itemList.appendChild(newRow);
                attachRowEvents(newRow);
                reindexRows();
                updateRemoveButtons();
            });

            reindexRows();
            updateRemoveButtons();
            updateInvoiceSummary();
        });
    </script>
@endsection
