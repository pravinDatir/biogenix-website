@extends('layouts.app')

@section('content')
    @php
        $defaultProductIds = $editingItems->pluck('product_id')->all();
        $defaultQuantities = $editingItems->pluck('quantity')->all();
        $productRows = old('product_id', $defaultProductIds ?: ['']);
        $quantityRows = old('quantity', $defaultQuantities ?: array_fill(0, max(1, count($productRows)), 1));

        if (! is_array($productRows) || $productRows === []) {
            $productRows = [''];
        }

        if (! is_array($quantityRows) || $quantityRows === []) {
            $quantityRows = array_fill(0, count($productRows), 1);
        }
    @endphp

    <div class="card">
        <h1>{{ $editingOrder ? 'Edit Order #'.$editingOrder->id : 'Create Order' }}</h1>
        <p class="muted">This order form uses the current visible product price, GST, and quantity rules for the logged-in user.</p>
    </div>

    <div class="card">
        @if (session('success'))
            <p class="muted">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="field">
                @foreach ($errors->all() as $error)
                    <p class="muted">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ $editingOrder ? route('orders.update', $editingOrder->id) : route('orders.store') }}">
            @csrf
            @if ($editingOrder)
                @method('PUT')
            @endif

            <div class="field">
                <label for="order_status">Status</label>
                <select id="order_status" name="status" required>
                    <option value="draft" @selected(old('status', $editingOrder->status ?? 'draft') === 'draft')>Draft</option>
                    <option value="submitted" @selected(old('status', $editingOrder->status ?? '') === 'submitted')>Submitted</option>
                    <option value="cancelled" @selected(old('status', $editingOrder->status ?? '') === 'cancelled')>Cancelled</option>
                </select>
            </div>

            <div class="field">
                <label>Order Items</label>
                <div id="order-item-list">
                    @foreach ($productRows as $index => $productId)
                        <div class="order-item-row" style="padding: 12px; border: 1px solid #ddd; margin-bottom: 12px;">
                            <div class="field">
                                <label for="product_id_{{ $index }}">Product</label>
                                <select id="product_id_{{ $index }}" name="product_id[]" class="order-product-select" required>
                                    <option value="">Select product</option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->variant_sku ?? $product->sku }}"
                                            data-variant-name="{{ $product->variant_name ?? '' }}"
                                            data-price-type="{{ $product->visible_price_type ?? '' }}"
                                            data-currency="{{ $product->visible_currency ?? 'INR' }}"
                                            data-base-price="{{ $product->visible_price ?? 0 }}"
                                            data-gst-rate="{{ $product->gst_rate ?? 0 }}"
                                            data-tax-amount="{{ $product->tax_amount ?? 0 }}"
                                            data-price-after-gst="{{ $product->price_after_gst ?? 0 }}"
                                            data-min-order-quantity="{{ $product->min_order_quantity ?? 1 }}"
                                            data-max-order-quantity="{{ $product->max_order_quantity ?? '' }}"
                                            data-lot-size="{{ $product->lot_size ?? 1 }}"
                                            @selected((int) $productId === (int) $product->id)
                                        >
                                            {{ $product->name }} ({{ $product->variant_sku ?? $product->sku }}) - {{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->price_after_gst ?? 0), 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label for="quantity_{{ $index }}">Quantity</label>
                                <input id="quantity_{{ $index }}" name="quantity[]" class="order-quantity-input" type="number" min="1" step="1" value="{{ $quantityRows[$index] ?? 1 }}" required>
                            </div>

                            <div class="order-product-preview muted" style="padding: 10px; border: 1px solid #e5e7eb; background: #f8fafc; border-radius: 6px; margin-bottom: 8px;">
                                Select a product to view price and rule details.
                            </div>

                            <p class="muted order-line-total">The line total will be shown here after you select a product.</p>

                            <button type="button" class="btn remove-order-item" @if (count($productRows) === 1) style="display:none;" @endif>Remove Item</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn" id="add-order-item">Add Another Item</button>
            </div>

            <div class="field">
                <label for="shipping_amount">Shipping Amount</label>
                <input id="shipping_amount" name="shipping_amount" type="number" step="0.0001" min="0" value="{{ old('shipping_amount', $editingOrder->shipping_amount ?? 0) }}">
            </div>

            <div class="field">
                <label for="adjustment_amount">Adjustment Amount</label>
                <input id="adjustment_amount" name="adjustment_amount" type="number" step="0.0001" value="{{ old('adjustment_amount', $editingOrder->adjustment_amount ?? 0) }}">
            </div>

            <div class="field">
                <label for="rounding_amount">Rounding Amount</label>
                <input id="rounding_amount" name="rounding_amount" type="number" step="0.0001" value="{{ old('rounding_amount', $editingOrder->rounding_amount ?? 0) }}">
            </div>

            <div class="field">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4">{{ old('notes', $editingOrder->notes ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn">{{ $editingOrder ? 'Update Order' : 'Create Order' }}</button>

            @if ($editingOrder)
                <a class="btn secondary" href="{{ route('orders.index') }}">Cancel Edit</a>
            @endif
        </form>
    </div>

    <div class="card">
        <h2>Current Order Estimate</h2>
        <p class="muted">This preview helps during testing. Final totals are recalculated on the server before the order is saved.</p>
        <div id="order-summary" class="muted">Select products and quantities to view the current order estimate.</div>
    </div>

    <div class="card">
        <h2>My Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Company</th>
                    <th>Total</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ strtoupper($order->status) }}</td>
                        <td>{{ $order->company?->name ?? '-' }}</td>
                        <td>{{ $order->currency }} {{ number_format((float) $order->total_amount, 2) }}</td>
                        <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                        <td>
                            <a class="btn secondary" href="{{ route('orders.show', $order->id) }}">View</a>
                            <a class="btn secondary" href="{{ route('orders.index', ['edit_order_id' => $order->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('orders.destroy', $order->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn secondary" onclick="return confirm('Delete this order?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $orders->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemList = document.getElementById('order-item-list');
            const addItemButton = document.getElementById('add-order-item');
            const orderSummary = document.getElementById('order-summary');
            const shippingInput = document.getElementById('shipping_amount');
            const adjustmentInput = document.getElementById('adjustment_amount');
            const roundingInput = document.getElementById('rounding_amount');

            function formatMoney(currency, amount) {
                return currency + ' ' + Number(amount || 0).toFixed(2);
            }

            function updateRemoveButtons() {
                const rows = itemList.querySelectorAll('.order-item-row');

                rows.forEach(function (row) {
                    const removeButton = row.querySelector('.remove-order-item');
                    removeButton.style.display = rows.length > 1 ? 'inline-block' : 'none';
                });
            }

            function reindexRows() {
                itemList.querySelectorAll('.order-item-row').forEach(function (row, index) {
                    row.querySelector('.order-product-select').id = 'product_id_' + index;
                    row.querySelector('.order-quantity-input').id = 'quantity_' + index;
                });
            }

            function updateOrderSummary() {
                let currency = 'INR';
                let subtotal = 0;
                let tax = 0;
                let total = 0;
                let rowCount = 0;
                const shippingAmount = parseFloat(shippingInput.value || '0');
                const adjustmentAmount = parseFloat(adjustmentInput.value || '0');
                const roundingAmount = parseFloat(roundingInput.value || '0');

                itemList.querySelectorAll('.order-item-row').forEach(function (row) {
                    const productSelect = row.querySelector('.order-product-select');
                    const quantityInput = row.querySelector('.order-quantity-input');
                    const selectedOption = productSelect.options[productSelect.selectedIndex];

                    if (!selectedOption || !selectedOption.value) {
                        return;
                    }

                    const quantity = parseInt(quantityInput.value || '0', 10);

                    if (!quantity || quantity < 1) {
                        return;
                    }

                    currency = selectedOption.dataset.currency || 'INR';

                    const unitPrice = parseFloat(selectedOption.dataset.basePrice || '0');
                    const unitTaxAmount = parseFloat(selectedOption.dataset.taxAmount || '0');
                    const unitPriceAfterGst = parseFloat(selectedOption.dataset.priceAfterGst || '0');

                    subtotal += unitPrice * quantity;
                    tax += unitTaxAmount * quantity;
                    total += unitPriceAfterGst * quantity;
                    rowCount++;
                });

                if (rowCount === 0) {
                    orderSummary.textContent = 'Select products and quantities to view the current order estimate.';
                    return;
                }

                const grandTotal = total + shippingAmount + adjustmentAmount + roundingAmount;

                orderSummary.innerHTML =
                    '<p><strong>Selected Items:</strong> ' + rowCount + '</p>' +
                    '<p><strong>Subtotal:</strong> ' + formatMoney(currency, subtotal) + '</p>' +
                    '<p><strong>Total Tax:</strong> ' + formatMoney(currency, tax) + '</p>' +
                    '<p><strong>Items Total:</strong> ' + formatMoney(currency, total) + '</p>' +
                    '<p><strong>Shipping:</strong> ' + formatMoney(currency, shippingAmount) + '</p>' +
                    '<p><strong>Adjustment:</strong> ' + formatMoney(currency, adjustmentAmount) + '</p>' +
                    '<p><strong>Rounding:</strong> ' + formatMoney(currency, roundingAmount) + '</p>' +
                    '<p><strong>Grand Total:</strong> ' + formatMoney(currency, grandTotal) + '</p>';
            }

            function updateRow(row) {
                const productSelect = row.querySelector('.order-product-select');
                const quantityInput = row.querySelector('.order-quantity-input');
                const previewBox = row.querySelector('.order-product-preview');
                const lineTotalText = row.querySelector('.order-line-total');
                const selectedOption = productSelect.options[productSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    quantityInput.min = 1;
                    quantityInput.step = 1;
                    quantityInput.removeAttribute('max');
                    previewBox.textContent = 'Select a product to view price and rule details.';
                    lineTotalText.textContent = 'The line total will be shown here after you select a product.';
                    updateOrderSummary();
                    return;
                }

                const quantity = parseInt(quantityInput.value || '1', 10);
                const name = selectedOption.dataset.name || '';
                const sku = selectedOption.dataset.sku || '';
                const variantName = selectedOption.dataset.variantName || '';
                const priceType = selectedOption.dataset.priceType || 'NA';
                const currency = selectedOption.dataset.currency || 'INR';
                const unitPrice = parseFloat(selectedOption.dataset.basePrice || '0');
                const gstRate = parseFloat(selectedOption.dataset.gstRate || '0');
                const unitTaxAmount = parseFloat(selectedOption.dataset.taxAmount || '0');
                const unitPriceAfterGst = parseFloat(selectedOption.dataset.priceAfterGst || '0');
                const minOrderQuantity = parseInt(selectedOption.dataset.minOrderQuantity || '1', 10);
                const maxOrderQuantity = selectedOption.dataset.maxOrderQuantity || '';
                const lotSize = parseInt(selectedOption.dataset.lotSize || '1', 10);
                const lineTotal = unitPriceAfterGst * quantity;

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

                previewBox.innerHTML =
                    '<strong>' + name + '</strong><br>' +
                    'SKU: ' + sku + '<br>' +
                    'Variant: ' + (variantName || 'Default Variant') + '<br>' +
                    'Price Type: ' + String(priceType).toUpperCase() + '<br>' +
                    'Base Price: ' + formatMoney(currency, unitPrice) + '<br>' +
                    'GST: ' + gstRate.toFixed(2) + '%<br>' +
                    'Tax / Unit: ' + formatMoney(currency, unitTaxAmount) + '<br>' +
                    'Min Qty: ' + minOrderQuantity + ' | Lot Size: ' + lotSize + (maxOrderQuantity !== '' ? ' | Max Qty: ' + maxOrderQuantity : '');

                lineTotalText.textContent = 'Current line total: ' + formatMoney(currency, lineTotal);
                updateOrderSummary();
            }

            function attachRowEvents(row) {
                row.querySelector('.order-product-select').addEventListener('change', function () {
                    updateRow(row);
                });

                row.querySelector('.order-quantity-input').addEventListener('input', function () {
                    updateRow(row);
                });

                row.querySelector('.remove-order-item').addEventListener('click', function () {
                    row.remove();
                    reindexRows();
                    updateRemoveButtons();
                    updateOrderSummary();
                });

                updateRow(row);
            }

            itemList.querySelectorAll('.order-item-row').forEach(function (row) {
                attachRowEvents(row);
            });

            addItemButton.addEventListener('click', function () {
                const firstRow = itemList.querySelector('.order-item-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelector('.order-product-select').value = '';
                newRow.querySelector('.order-quantity-input').value = '1';
                newRow.querySelector('.order-product-preview').textContent = 'Select a product to view price and rule details.';
                newRow.querySelector('.order-line-total').textContent = 'The line total will be shown here after you select a product.';

                itemList.appendChild(newRow);
                attachRowEvents(newRow);
                reindexRows();
                updateRemoveButtons();
            });

            shippingInput.addEventListener('input', updateOrderSummary);
            adjustmentInput.addEventListener('input', updateOrderSummary);
            roundingInput.addEventListener('input', updateOrderSummary);

            reindexRows();
            updateRemoveButtons();
            updateOrderSummary();
        });
    </script>
@endsection
