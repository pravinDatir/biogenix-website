@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Order Confirmation Prototype')
@section('customer_title', 'Order Confirmation Prototype')
@section('customer_description', 'A confirmation page showing order ID, invoice access, and next-step communication blocks.')
@section('customer_active', 'orders')

@section('customer_actions')
    <x-ui.action-link href="#">Download Invoice</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Open Tracking View</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
        <x-ui.surface-card title="Order Confirmed" subtitle="Designed for the final state after checkout and payment success.">
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    <x-badge variant="success">Confirmed</x-badge>
                    <x-badge variant="info">{{ strtoupper($portal) }}</x-badge>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Order ID</p>
                        <p class="mt-2 font-semibold text-slate-900">ORD-20260311-0047</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Communication</p>
                        <p class="mt-2 font-semibold text-slate-900">Email + SMS triggered</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Next Stage</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Dispatch + approval review' : 'Packing and courier hand-off' }}</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    {{ $portal === 'b2b'
                        ? 'Use this panel for company buyer notes, purchase-order references, and account-team ownership.'
                        : 'Use this panel for invoice download, delivery ETA, and customer support shortcuts.' }}
                </div>
            </div>
        </x-ui.surface-card>

        <x-ui.surface-card title="Post-Order Timeline" subtitle="Simple event stack ready for live order events.">
            <div class="timeline">
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Order created</h3>
                    <p class="mt-2 text-sm text-slate-600">Checkout completed and order number reserved in the system.</p>
                </article>
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Invoice generated</h3>
                    <p class="mt-2 text-sm text-slate-600">Invoice and message notifications can be attached to this stage.</p>
                </article>
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Dispatch preparation</h3>
                    <p class="mt-2 text-sm text-slate-600">Same-day or standard courier logic can branch here.</p>
                </article>
            </div>
        </x-ui.surface-card>
    </div>
@endsection
