@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $fieldLabelClass = 'mb-2 block text-sm font-semibold text-slate-700';
    $fieldClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10';
    $actionPrimaryClass = 'inline-flex h-9 items-center justify-center rounded-lg bg-primary-600 px-3.5 text-xs font-semibold text-white shadow-sm transition hover:bg-primary-700';
    $actionSecondaryClass = 'inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50';

    $orders = $portal === 'b2b'
        ? [
            ['id' => 'ORD-2048', 'scope' => 'Metro Care Lab', 'status' => 'Awaiting Dispatch', 'amount' => 'INR 1,84,000'],
            ['id' => 'ORD-2035', 'scope' => 'Apollo Diagnostics', 'status' => 'Partially Shipped', 'amount' => 'INR 74,500'],
            ['id' => 'ORD-2026', 'scope' => 'Own Company', 'status' => 'Delivered', 'amount' => 'INR 28,300'],
        ]
        : [
            ['id' => 'ORD-1194', 'scope' => 'Self', 'status' => 'Delivered', 'amount' => 'INR 8,420'],
            ['id' => 'ORD-1188', 'scope' => 'Self', 'status' => 'In Transit', 'amount' => 'INR 4,980'],
            ['id' => 'ORD-1172', 'scope' => 'Self', 'status' => 'Cancelled', 'amount' => 'INR 1,760'],
        ];
@endphp

@section('title', 'My Orders Prototype')
@section('customer_title', 'My Orders Prototype')
@section('customer_description', 'A list page for order history, filters, reorder actions, and commercial context.')
@section('customer_active', 'orders')

@section('customer_actions')
    <x-ui.action-link :href="route('products.index')">Browse Catalog</x-ui.action-link>
    <x-ui.action-link :href="route('proforma.create')" variant="secondary">Generate Quote</x-ui.action-link>
@endsection

@section('customer_content')
    <x-ui.surface-card title="Filters" subtitle="Static list filter bar for order history.">
        <div class="grid gap-3 md:grid-cols-4">
            <div>
                <label class="{{ $fieldLabelClass }}">Status</label>
                <select class="{{ $fieldClass }}"><option>All statuses</option></select>
            </div>
            <div>
                <label class="{{ $fieldLabelClass }}">Date Range</label>
                <select class="{{ $fieldClass }}"><option>Last 90 days</option></select>
            </div>
            <div>
                <label class="{{ $fieldLabelClass }}">{{ $portal === 'b2b' ? 'Client Scope' : 'Order Type' }}</label>
                <select class="{{ $fieldClass }}"><option>{{ $portal === 'b2b' ? 'All client scopes' : 'All orders' }}</option></select>
            </div>
            <div>
                <label class="{{ $fieldLabelClass }}">Search</label>
                <input type="text" value="ORD-" class="{{ $fieldClass }}">
            </div>
        </div>
    </x-ui.surface-card>

    <x-ui.surface-card title="Order List" subtitle="This table is a visual placeholder for the eventual order-history module.">
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 bg-white text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">{{ $portal === 'b2b' ? 'Client / Scope' : 'Scope' }}</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-primary-700">{{ $order['id'] }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $order['scope'] }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $orderStatusClass = match($order['status']) {
                                        'Delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'In Transit', 'Partially Shipped' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'Awaiting Dispatch' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $orderStatusClass }}">{{ $order['status'] }}</span>
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-900">{{ $order['amount'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('contact') }}" class="{{ $actionPrimaryClass }}">Support</a>
                                    <a href="{{ route('products.index') }}" class="{{ $actionSecondaryClass }}">Reorder</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.surface-card>
@endsection
