@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $items = $portal === 'b2b'
        ? [
            ['name' => 'CBC Analyzer Kit', 'qty' => 6, 'unit' => 'INR 4,540', 'total' => 'INR 27,240', 'note' => 'Contract slab applied'],
            ['name' => 'Cold-Chain Consumables Pack', 'qty' => 12, 'unit' => 'INR 1,980', 'total' => 'INR 23,760', 'note' => 'MOQ satisfied'],
        ]
        : [
            ['name' => 'CBC Analyzer Kit', 'qty' => 2, 'unit' => 'INR 5,700', 'total' => 'INR 11,400', 'note' => 'Retail bundle offer'],
            ['name' => 'Sample Collection Tubes', 'qty' => 4, 'unit' => 'INR 460', 'total' => 'INR 1,840', 'note' => 'Personal reorder'],
        ];
@endphp

@section('title', strtoupper($portal).' Cart Prototype')
@section('customer_title', $portal === 'b2b' ? 'B2B Cart Prototype' : 'B2C Cart Prototype')
@section('customer_description', $portal === 'b2b'
    ? 'Cart page showing slab-aware totals, commercial notes, and company purchase readiness.'
    : 'Retail cart page with direct quantity management, same-day hints, and checkout summary.')
@section('customer_active', 'cart')

@section('customer_actions')
    <x-ui.action-link href="#">Proceed to Checkout</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Continue Shopping</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
        <x-ui.surface-card title="Cart Items" subtitle="Quantity controls are static placeholders in this view-only version.">
            <div class="space-y-4">
                @foreach ($items as $item)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h3 class="text-base font-semibold text-slate-900">{{ $item['name'] }}</h3>
                                <p class="text-sm text-slate-500">{{ $item['note'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Line Total</p>
                                <p class="text-lg font-semibold text-slate-900">{{ $item['total'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4 grid gap-3 md:grid-cols-3">
                            <div class="rounded-xl bg-white px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Qty</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $item['qty'] }}</p>
                            </div>
                            <div class="rounded-xl bg-white px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Unit Price</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $item['unit'] }}</p>
                            </div>
                            <div class="rounded-xl bg-white px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Delivery Flag</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'PO review optional' : 'Lucknow same-day eligible' }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </x-ui.surface-card>

        <div class="space-y-5">
            <x-ui.surface-card title="Order Summary" subtitle="Cart totals are hard-coded to preserve the backend contract.">
                <div class="space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'INR 51,000' : 'INR 13,240' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>GST</span>
                        <span class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'INR 9,180' : 'INR 2,383.20' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Delivery</span>
                        <span class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'To be finalized by dispatch rule' : 'Free above threshold' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base">
                        <span class="font-semibold text-slate-900">Estimated Total</span>
                        <span class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'INR 60,180' : 'INR 15,623.20' }}</span>
                    </div>
                </div>
                <div class="mt-5 space-y-3">
                    <x-alert type="{{ $portal === 'b2b' ? 'warning' : 'success' }}">
                        {{ $portal === 'b2b'
                            ? 'Company payment terms, client assignment, and approval checks can be surfaced here.'
                            : 'Delivery ETA and same-day eligibility can be shown before checkout.' }}
                    </x-alert>
                </div>
            </x-ui.surface-card>

            <x-ui.surface-card title="Commercial Logic Hook" subtitle="Static note block for future pricing and logistics engines.">
                <ul class="space-y-3 text-sm text-slate-600">
                    <li class="rounded-xl bg-slate-50 px-3 py-3">
                        {{ $portal === 'b2b' ? 'Display negotiated slab breaks, credit usage, and purchase-order notes.' : 'Display retail coupon, shipping threshold, and self-order restrictions.' }}
                    </li>
                    <li class="rounded-xl bg-slate-50 px-3 py-3">
                        Same-day delivery rule remains applicable only for Lucknow orders placed before 3 PM.
                    </li>
                </ul>
            </x-ui.surface-card>
        </div>
    </div>
@endsection
