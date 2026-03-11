@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $stats = $portal === 'b2b'
        ? [
            ['label' => 'Active Orders', 'value' => '28', 'hint' => '6 waiting for dispatch'],
            ['label' => 'Client PIs', 'value' => '14', 'hint' => '4 require approval'],
            ['label' => 'Credit Utilization', 'value' => '62%', 'hint' => 'INR 6.2L of 10L'],
            ['label' => 'Open Tickets', 'value' => '3', 'hint' => '1 at supplier escalation'],
        ]
        : [
            ['label' => 'Recent Orders', 'value' => '9', 'hint' => '2 currently in transit'],
            ['label' => 'Saved Cart Items', 'value' => '6', 'hint' => '1 is same-day eligible'],
            ['label' => 'Self PIs', 'value' => '5', 'hint' => 'Latest PI expires in 2 days'],
            ['label' => 'Reward Status', 'value' => 'Gold', 'hint' => 'Next discount unlock at 12 reviews'],
        ];

    $activity = $portal === 'b2b'
        ? [
            ['time' => '09:15', 'title' => 'Hospital PI submitted', 'body' => 'Client-facing PI for Metro Care Lab is waiting for account owner approval.'],
            ['time' => '10:40', 'title' => 'Shipment split created', 'body' => 'Order BGX-2048 was split between Lucknow and Delhi stock points.'],
            ['time' => '12:05', 'title' => 'Credit review flag', 'body' => 'Large reagent reorder is above soft credit threshold but still within approved band.'],
        ]
        : [
            ['time' => '08:50', 'title' => 'Order packed', 'body' => 'Your CBC kit bundle moved from confirmed to packed at Lucknow warehouse.'],
            ['time' => '11:10', 'title' => 'PI downloaded', 'body' => 'You downloaded PI-202603110915 for your next instrument reorder.'],
            ['time' => '13:30', 'title' => 'Ticket updated', 'body' => 'Support added a courier update for your cold-chain consumables request.'],
        ];

    $recommendations = $portal === 'b2b'
        ? [
            ['title' => 'Client-specific quotation board', 'copy' => 'Prioritize expiring PIs and follow-ups for accounts tagged hospital and lab.', 'badge' => 'Commercial'],
            ['title' => 'Low MOQ upgrade path', 'copy' => 'Bundle three reagent SKUs to hit the next contract slab automatically.', 'badge' => 'Pricing'],
            ['title' => 'Dispatch planning', 'copy' => 'Two Lucknow orders can still meet same-day dispatch if packed before 3 PM.', 'badge' => 'Logistics'],
        ]
        : [
            ['title' => 'Self-service reorder', 'copy' => 'Repeat your previous diagnostic consumables order with one-click quantity presets.', 'badge' => 'Orders'],
            ['title' => 'Smart quote reminder', 'copy' => 'One PI is close to expiry. Regenerate now to keep the same product mix.', 'badge' => 'Quotation'],
            ['title' => 'Delivery window tip', 'copy' => 'Orders to Lucknow before 3 PM remain eligible for same-day dispatch.', 'badge' => 'Delivery'],
        ];
@endphp

@section('title', strtoupper($portal).' Dashboard Prototype')
@section('customer_title', $portal === 'b2b' ? 'B2B Dashboard Prototype' : 'B2C Dashboard Prototype')
@section('customer_description', $portal === 'b2b'
    ? 'A company-facing dashboard with approvals, client PI visibility, credit monitoring, and dispatch awareness.'
    : 'A personal customer dashboard centered on retail pricing, self-service PI flow, and order/support visibility.')
@section('customer_active', 'dashboard')

@section('customer_actions')
    <x-ui.action-link href="#">Open Live Dashboard Route Later</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Review Portal Modules</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
        <x-ui.surface-card title="Recent Activity" subtitle="Static timeline blocks for later replacement with role-scoped event data.">
            <div class="timeline">
                @foreach ($activity as $item)
                    <article class="timeline-item">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-base font-semibold text-slate-900">{{ $item['title'] }}</h3>
                            <x-badge variant="info">{{ $item['time'] }}</x-badge>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </x-ui.surface-card>

        <div class="space-y-5">
            <x-ui.surface-card title="Workspace Summary" subtitle="Role-specific highlights shown above the main widgets.">
                <div class="grid gap-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Portal Mode</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ strtoupper($portal) }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Primary Commercial Rule</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $portal === 'b2b' ? 'Company pricing with approval-aware ordering' : 'Retail MRP pricing for self-service checkout' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Next UI Expansion</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $portal === 'b2b' ? 'Sub-user, client assignment, and approval widgets' : 'Rewards, saved lists, and repeat-order accelerators' }}
                        </p>
                    </div>
                </div>
            </x-ui.surface-card>

            <x-ui.surface-card title="Recommendations" subtitle="Role-aware AI-style cards without backend dependency.">
                <div class="space-y-3">
                    @foreach ($recommendations as $item)
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-base font-semibold text-slate-900">{{ $item['title'] }}</h3>
                                <x-badge variant="success">{{ $item['badge'] }}</x-badge>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </x-ui.surface-card>
        </div>
    </div>
@endsection
