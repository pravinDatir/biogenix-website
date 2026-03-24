@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $backUrl = url()->previous() ?: route('home');
    $orderItems = collect($orders->items());
    $draftCount = $orderItems->where('status', 'draft')->count();
    $submittedCount = $orderItems->where('status', 'submitted')->count();
    $cancelledCount = $orderItems->where('status', 'cancelled')->count();
    $totalValue = $orderItems->sum(fn ($order) => (float) $order->total_amount);
    $companyOptions = $orderItems
        ->map(fn ($order) => $order->company?->name ?? 'Self')
        ->filter()
        ->unique()
        ->sort()
        ->values();
    $productIds = old('product_id', $editingItems->pluck('product_id')->all());
    $quantities = old('quantity', $editingItems->pluck('quantity')->all());
    $lineCount = max(3, count($productIds), count($quantities));
    $metricCardClass = 'rounded-3xl border border-slate-200 bg-white p-4 shadow-sm md:p-5';
    $panelClass = 'space-y-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $textareaClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $buttonSecondary = 'inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50';
@endphp

@section('title', 'Orders')
@section('customer_active', 'orders')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="orders"
        :back-url="$backUrl"
        back-label="Back"
        :title="$editingOrder ? 'Update Order' : 'Order Workspace'"
        description="Create, review, and manage live orders using the same account workspace pattern as the rest of the customer portal."
    >
        <x-slot:metrics>
            <div class="grid gap-4 md:grid-cols-4">
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Visible Products</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $products->count() }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Draft Orders</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $draftCount }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Submitted Orders</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $submittedCount }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Visible Order Value</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">INR {{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </x-slot:metrics>

        <div class="{{ $panelClass }}">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $editingOrder ? 'Edit Order #'.$editingOrder->id : 'Create New Order' }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Fast-track your order by selecting products and specifying quantities below.</p>
                </div>
                <div class="hidden w-full max-w-md lg:block">
                    <x-ui.progress-indicator 
                        :steps="['Lines', 'Details', 'Review', 'Finish']" 
                        :current-step="$editingOrder ? 2 : 1" 
                    />
                </div>
                @if ($editingOrder)
                    <a href="{{ route('orders.index') }}" class="{{ $buttonSecondary }}">Clear Edit Mode</a>
                @endif
            </div>

            <form method="POST" action="{{ $editingOrder ? route('orders.update', $editingOrder->id) : route('orders.store') }}" class="space-y-6">
                @csrf
                @if ($editingOrder)
                    @method('PUT')
                @endif

                <div class="grid gap-4 md:grid-cols-4">
                    <div class="space-y-2">
                        <label for="status" class="text-sm font-semibold text-slate-700">Order Status</label>
                        <select id="status" name="status" class="{{ $inputClass }}" required>
                            @foreach (['draft', 'submitted', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $editingOrder?->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="shipping_amount" class="text-sm font-semibold text-slate-700">Shipping Amount</label>
                        <input id="shipping_amount" name="shipping_amount" type="number" step="0.01" class="{{ $inputClass }}" value="{{ old('shipping_amount', $editingOrder?->shipping_amount ?? 0) }}">
                    </div>
                    <div class="space-y-2">
                        <label for="adjustment_amount" class="text-sm font-semibold text-slate-700">Adjustment Amount</label>
                        <input id="adjustment_amount" name="adjustment_amount" type="number" step="0.01" class="{{ $inputClass }}" value="{{ old('adjustment_amount', $editingOrder?->adjustment_amount ?? 0) }}">
                    </div>
                    <div class="space-y-2">
                        <label for="rounding_amount" class="text-sm font-semibold text-slate-700">Rounding Amount</label>
                        <input id="rounding_amount" name="rounding_amount" type="number" step="0.01" class="{{ $inputClass }}" value="{{ old('rounding_amount', $editingOrder?->rounding_amount ?? 0) }}">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-900">Order Lines</h3>
                        <span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">{{ $lineCount }} prepared rows</span>
                    </div>

                    @for ($index = 0; $index < $lineCount; $index++)
                        @php
                            $selectedProductId = old('product_id.'.$index, $editingItems[$index]->product_id ?? null);
                            $selectedQuantity = old('quantity.'.$index, $editingItems[$index]->quantity ?? null);
                        @endphp
                        <div class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_10rem]">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Product {{ $index + 1 }}</label>
                                <select name="product_id[]" class="{{ $inputClass }}">
                                    <option value="">Select a product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected((string) $selectedProductId === (string) $product->id)>
                                            {{ $product->name }}{{ $product->variant_name ? ' - '.$product->variant_name : '' }}{{ $product->visible_price ? ' | INR '.number_format((float) $product->visible_price, 2) : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Quantity</label>
                                <input name="quantity[]" type="number" min="1" class="{{ $inputClass }}" value="{{ $selectedQuantity }}">
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="space-y-2">
                    <label for="notes" class="text-sm font-semibold text-slate-700">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="{{ $textareaClass }}">{{ old('notes', $editingOrder?->notes) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('orders.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">{{ $editingOrder ? 'Update Order' : 'Create Order' }}</button>
                </div>
            </form>
        </div>

        <div class="{{ $panelClass }}">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">My Orders</h2>
                    <p class="mt-1 text-sm text-slate-500">Search, narrow, review, and reorder from the currently visible order list.</p>
                </div>
                <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ $orders->total() }} total orders
                </div>
            </div>

            <div class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.7fr)_minmax(0,0.7fr)_auto]">
                <div>
                    <label for="ordersFilterSearch" class="mb-2 block text-sm font-semibold text-slate-700">Search Orders</label>
                    <input id="ordersFilterSearch" type="search" placeholder="Search by order ID or company" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="ordersFilterStatus" class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select id="ordersFilterStatus" class="{{ $inputClass }}">
                        <option value="all">All statuses</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="ordersFilterCompany" class="mb-2 block text-sm font-semibold text-slate-700">{{ $portal === 'b2b' ? 'Company' : 'Scope' }}</label>
                    <select id="ordersFilterCompany" class="{{ $inputClass }}">
                        <option value="all">All {{ $portal === 'b2b' ? 'companies' : 'scopes' }}</option>
                        @foreach ($companyOptions as $companyOption)
                            <option value="{{ strtolower($companyOption) }}">{{ $companyOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="ordersFilterReset" type="button" class="{{ $buttonSecondary }} w-full">Reset Filters</button>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                <p>Filters work on the orders visible in the current page.</p>
                <p class="font-semibold text-slate-900"><span id="ordersVisibleCount">{{ $orderItems->count() }}</span> shown</p>
            </div>

            <div id="ordersList" class="mt-6 space-y-4">
                @forelse ($orders as $order)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        @php
                            $companyName = $order->company?->name ?? 'Self';
                        @endphp
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div
                                class="space-y-3"
                                data-order-card
                                data-order-id="{{ $order->id }}"
                                data-order-status="{{ strtolower((string) $order->status) }}"
                                data-order-company="{{ strtolower($companyName) }}"
                            >
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-900">Order #{{ $order->id }}</h3>
                                    <x-ui.status-badge type="status" :value="$order->status" :label="ucfirst($order->status)" />
                                </div>
                                <div class="grid gap-3 text-sm text-slate-600 sm:grid-cols-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Company</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ $companyName }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Created</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->total_amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('orders.show', $order->id) }}" class="{{ $buttonSecondary }}">View</a>
                                <a href="{{ route('orders.index', ['edit_order_id' => $order->id]) }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-primary-200 bg-primary-50 px-4 text-sm font-semibold text-primary-700 transition hover:bg-primary-100">Edit</a>
                                <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl border border-primary-200 bg-primary-50 px-4 text-sm font-semibold text-primary-600 transition hover:bg-emerald-100" data-reorder-order-id="{{ $order->id }}">Reorder</button>
                                <form method="POST" action="{{ route('orders.destroy', $order->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">Delete</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-ui.empty-state
                        title="No orders yet"
                        subtitle="Create your first order from the form above. It will appear here immediately after save."
                    />
                @endforelse
            </div>

            @if ($orderItems->count())
                <div id="ordersFilterEmpty" class="hidden rounded-2xl border border-slate-200 bg-slate-50 px-4 py-8 text-center">
                    <p class="text-base font-semibold text-slate-900">No matching orders</p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Try a different search term or reset the filters to view the full list again.</p>
                </div>
            @endif

            <x-ui.pagination :paginator="$orders" />
        </div>
    </x-account.workspace>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('ordersFilterSearch');
            const statusSelect = document.getElementById('ordersFilterStatus');
            const companySelect = document.getElementById('ordersFilterCompany');
            const resetButton = document.getElementById('ordersFilterReset');
            const visibleCount = document.getElementById('ordersVisibleCount');
            const emptyState = document.getElementById('ordersFilterEmpty');
            const cards = Array.from(document.querySelectorAll('[data-order-card]')).map(function (content) {
                return content.closest('article');
            }).filter(Boolean);

            if (cards.length && searchInput && statusSelect && companySelect && resetButton && visibleCount) {
                const applyFilters = function () {
                    const searchValue = searchInput.value.trim().toLowerCase();
                    const statusValue = statusSelect.value;
                    const companyValue = companySelect.value;
                    let shown = 0;

                    cards.forEach(function (card) {
                        const content = card.querySelector('[data-order-card]');
                        if (!content) {
                            return;
                        }

                        const orderId = String(content.dataset.orderId || '').toLowerCase();
                        const orderStatus = String(content.dataset.orderStatus || '').toLowerCase();
                        const orderCompany = String(content.dataset.orderCompany || '').toLowerCase();

                        const matchesSearch = searchValue === '' || orderId.includes(searchValue) || orderCompany.includes(searchValue);
                        const matchesStatus = statusValue === 'all' || orderStatus === statusValue;
                        const matchesCompany = companyValue === 'all' || orderCompany === companyValue;
                        const isVisible = matchesSearch && matchesStatus && matchesCompany;

                        card.classList.toggle('hidden', !isVisible);
                        if (isVisible) {
                            shown += 1;
                        }
                    });

                    visibleCount.textContent = String(shown);
                    if (emptyState) {
                        emptyState.classList.toggle('hidden', shown !== 0);
                    }
                };

                searchInput.addEventListener('input', applyFilters);
                statusSelect.addEventListener('change', applyFilters);
                companySelect.addEventListener('change', applyFilters);
                resetButton.addEventListener('click', function () {
                    searchInput.value = '';
                    statusSelect.value = 'all';
                    companySelect.value = 'all';
                    applyFilters();
                });

                applyFilters();
            }

            document.querySelectorAll('[data-reorder-order-id]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const orderId = button.getAttribute('data-reorder-order-id');
                    if (window.BiogenixToast && typeof window.BiogenixToast.show === 'function') {
                        window.BiogenixToast.show('Reorder preview started for order #' + orderId + '.', 'success', 3500);
                    }
                });
            });
        });
    </script>
@endpush
