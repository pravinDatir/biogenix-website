@extends('customer.layout')

@section('title', 'Order Tracking Prototype')
@section('customer_title', 'Order Tracking Prototype')
@section('customer_description', 'An Amazon-style progress page with shipment stages, history, and courier context.')
@section('customer_active', 'tracking')

@section('customer_actions')
    <x-ui.action-link href="#">View Courier History</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Download Order Copy</x-ui.action-link>
@endsection

@section('customer_content')
    <x-ui.surface-card title="Shipment Progress" subtitle="Status bar prototype for packed, dispatched, in transit, and delivered stages.">
        <div class="grid gap-4 md:grid-cols-4">
            @foreach (['Packed', 'Dispatched', 'In Transit', 'Delivered'] as $index => $step)
                <div class="{{ $index < 3 ? 'border-primary-100 bg-primary-50 text-primary-700' : 'border-slate-200 bg-slate-50 text-slate-400' }} rounded-2xl border px-4 py-4 text-center">
                    <p class="text-sm font-semibold">{{ $step }}</p>
                </div>
            @endforeach
        </div>
    </x-ui.surface-card>

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)]">
        <x-ui.surface-card title="Tracking History" subtitle="Static courier event log ready for live shipment updates.">
            <div class="timeline">
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Warehouse packed shipment</h3>
                    <p class="mt-2 text-sm text-slate-600">Lucknow team packed the shipment and assigned cold-chain handling.</p>
                </article>
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Courier pickup completed</h3>
                    <p class="mt-2 text-sm text-slate-600">TrackOn pickup recorded with dispatch reference TRK-99821.</p>
                </article>
                <article class="timeline-item">
                    <h3 class="text-base font-semibold text-slate-900">Line-haul transfer</h3>
                    <p class="mt-2 text-sm text-slate-600">Shipment is moving to the destination hub with ETA updates pending.</p>
                </article>
            </div>
        </x-ui.surface-card>

        <x-ui.surface-card title="Tracking Summary" subtitle="A compact side panel for order and courier facts.">
            <div class="space-y-3 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                    <span>Order</span>
                    <span class="font-semibold text-slate-900">ORD-20260311-0047</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Courier</span>
                    <span class="font-semibold text-slate-900">TrackOn</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>ETA</span>
                    <span class="font-semibold text-slate-900">Tomorrow, 4:00 PM</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Delivery Rule</span>
                    <span class="font-semibold text-slate-900">Same-day not triggered</span>
                </div>
            </div>
        </x-ui.surface-card>
    </div>
@endsection
