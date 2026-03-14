@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $stats = $portal === 'b2b'
        ? [
            ['label' => 'Active Orders', 'value' => '28', 'hint' => '6 waiting for dispatch', 'trend' => '+12%', 'trend_up' => true],
            ['label' => 'Client PIs', 'value' => '14', 'hint' => '4 require approval', 'trend' => '+5%', 'trend_up' => true],
            ['label' => 'Credit Utilization', 'value' => '62%', 'hint' => 'INR 6.2L of 10L', 'trend' => '-2.4%', 'trend_up' => false],
            ['label' => 'Open Tickets', 'value' => '3', 'hint' => '1 at supplier escalation', 'trend' => 'Stable', 'trend_up' => null],
        ]
        : [
            ['label' => 'Recent Orders', 'value' => '9', 'hint' => '2 currently in transit', 'trend' => '+18%', 'trend_up' => true],
            ['label' => 'Saved Cart Items', 'value' => '6', 'hint' => '1 is same-day eligible', 'trend' => '+2', 'trend_up' => true],
            ['label' => 'Self PIs', 'value' => '5', 'hint' => 'Latest PI expires in 2 days', 'trend' => 'Normal', 'trend_up' => null],
            ['label' => 'Reward Status', 'value' => 'Gold', 'hint' => 'Next discount unlock at 12 reviews', 'trend' => 'Level 3', 'trend_up' => true],
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
    <x-ui.action-link :href="route('products.index')">Browse Catalog</x-ui.action-link>
    <x-ui.action-link :href="route('proforma.create')" variant="secondary">Generate Quote</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="stat-card">
                <div class="flex items-start justify-between">
                    <p class="eyebrow text-slate-400">{{ $stat['label'] }}</p>
                    @if (isset($stat['trend']))
                        <div @class([
                            'flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase transition-transform group-hover:scale-110',
                            'bg-emerald-50 text-emerald-600' => $stat['trend_up'] === true,
                            'bg-rose-50 text-rose-600' => $stat['trend_up'] === false,
                            'bg-slate-100 text-slate-500' => $stat['trend_up'] === null,
                        ])>
                            @if ($stat['trend_up'] === true)
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                </svg>
                            @elseif ($stat['trend_up'] === false)
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                            {{ $stat['trend'] }}
                        </div>
                    @endif
                </div>
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
