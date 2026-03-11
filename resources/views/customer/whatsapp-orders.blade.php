@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'WhatsApp Orders Prototype')
@section('customer_title', 'WhatsApp Order Status Prototype')
@section('customer_description', 'A dedicated page for orders placed or updated through WhatsApp workflows.')
@section('customer_active', 'whatsapp')

@section('customer_actions')
    <x-ui.action-link href="#">Continue in Portal</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Open Order History</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)]">
        <x-ui.surface-card title="WhatsApp Order Thread" subtitle="Static message-style layout for order updates sent through WhatsApp.">
            <div class="space-y-3">
                <div class="rounded-2xl bg-slate-100 px-4 py-3 text-sm text-slate-700">
                    Order ORD-20260311-0047 has been confirmed and dispatched from Lucknow.
                </div>
                <div class="rounded-2xl bg-blue-600 px-4 py-3 text-sm text-white">
                    Thanks. Share the delivery ETA and invoice copy.
                </div>
                <div class="rounded-2xl bg-slate-100 px-4 py-3 text-sm text-slate-700">
                    Invoice link sent. ETA is tomorrow before 4:00 PM.
                </div>
            </div>
        </x-ui.surface-card>

        <x-ui.surface-card title="Portal Sync Summary" subtitle="Use this panel to show WhatsApp-originated orders inside the main customer workspace.">
            <div class="space-y-3 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                    <span>Source</span>
                    <span class="font-semibold text-slate-900">WhatsApp assisted order</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Tracking</span>
                    <span class="font-semibold text-slate-900">Same UI as normal orders</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Commercial Context</span>
                    <span class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Account-managed' : 'Customer-managed' }}</span>
                </div>
            </div>
        </x-ui.surface-card>
    </div>
@endsection
