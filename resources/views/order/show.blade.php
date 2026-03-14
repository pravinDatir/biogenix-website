@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $backUrl = route('orders.index');
    $metricCardClass = 'rounded-3xl border border-slate-200 bg-white p-4 shadow-sm md:p-5';
    $panelClass = 'space-y-5 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $sidePanelClass = 'space-y-4 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm';
@endphp

@section('title', $order ? 'Order #'.$order->id : 'Order Details')
@section('customer_active', 'orders')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="orders"
        :back-url="$backUrl"
        back-label="Back to Orders"
        :title="$order ? 'Order #'.$order->id : 'Order Details'"
        description="Review pricing, line items, and the saved commercial snapshot for this order."
    >
        @if ($order)
            <div class="grid gap-4 md:grid-cols-4">
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                    <div class="mt-3">
                        <x-ui.status-badge type="status" :value="$order->status" :label="ucfirst($order->status)" />
                    </div>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->total_amount, 2) }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Placed By</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $order->placedByUser?->name ?? 'Unknown' }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Company</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $order->company?->name ?? 'Self' }}</p>
                </div>
            </div>

            <div class="{{ $panelClass }}">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Order Items</h2>
                    <p class="mt-1 text-sm text-slate-500">Saved line items with the commercial snapshot used at the time of order creation.</p>
                </div>

                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-900">{{ $item->product_name }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $item->sku }}{{ $item->variant_name ? ' / '.$item->variant_name : '' }}</p>
                                </div>
                                <div class="text-left md:text-right">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Line Total</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $item->total_amount, 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-4">
                                <div class="rounded-xl bg-slate-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Quantity</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $item->quantity }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Unit Price</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $item->unit_price, 2) }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Tax</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $item->tax_amount, 2) }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Subtotal</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $item->subtotal_amount, 2) }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
                <div class="{{ $sidePanelClass }}">
                    <h2 class="text-lg font-semibold text-slate-900">Notes</h2>
                    <p class="text-sm leading-7 text-slate-600">{{ $order->notes ?: 'No internal notes were added to this order.' }}</p>
                </div>

                <div class="{{ $sidePanelClass }}">
                    <h2 class="text-lg font-semibold text-slate-900">Financial Summary</h2>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->subtotal_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Tax</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Discount</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Shipping</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->shipping_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Adjustment</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->adjustment_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Rounding</span>
                            <span class="font-semibold text-slate-900">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->rounding_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base">
                            <span class="font-semibold text-slate-900">Grand Total</span>
                            <span class="font-bold text-slate-950">{{ $order->currency ?? 'INR' }} {{ number_format((float) $order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <x-ui.empty-state
                icon="order"
                title="Order not available"
                description="This order could not be loaded for the current user."
            />
        @endif
    </x-account.workspace>
@endsection
