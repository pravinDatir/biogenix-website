@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

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
    <x-ui.action-link href="#">Apply Filters</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Export List</x-ui.action-link>
@endsection

@section('customer_content')
    <x-ui.surface-card title="Filters" subtitle="Static list filter bar for order history.">
        <div class="grid gap-3 md:grid-cols-4">
            <div class="field !mb-0">
                <label>Status</label>
                <select><option>All statuses</option></select>
            </div>
            <div class="field !mb-0">
                <label>Date Range</label>
                <select><option>Last 90 days</option></select>
            </div>
            <div class="field !mb-0">
                <label>{{ $portal === 'b2b' ? 'Client Scope' : 'Order Type' }}</label>
                <select><option>{{ $portal === 'b2b' ? 'All client scopes' : 'All orders' }}</option></select>
            </div>
            <div class="field !mb-0">
                <label>Search</label>
                <input type="text" value="ORD-">
            </div>
        </div>
    </x-ui.surface-card>

    <x-ui.surface-card title="Order List" subtitle="This table is a visual placeholder for the eventual order-history module.">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>{{ $portal === 'b2b' ? 'Client / Scope' : 'Scope' }}</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="font-semibold text-primary-700">{{ $order['id'] }}</td>
                            <td>{{ $order['scope'] }}</td>
                            <td>
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
                            <td class="font-semibold text-slate-900">{{ $order['amount'] }}</td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-sm" type="button">Track</button>
                                    <button class="btn secondary btn-sm" type="button">Reorder</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.surface-card>
@endsection
