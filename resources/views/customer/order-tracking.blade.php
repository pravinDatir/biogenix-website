@extends('customer.layout')

@php
    $trackingSteps = [
        ['label' => 'Packed', 'time' => 'Mar 13, 09:10 AM'],
        ['label' => 'Dispatched', 'time' => 'Mar 13, 02:35 PM'],
        ['label' => 'In Transit', 'time' => 'Mar 14, 08:20 AM'],
        ['label' => 'Delivered', 'time' => 'Expected Mar 16'],
    ];
    $currentStep = 3;
    $trackingHistory = [
        ['title' => 'Shipment packed at origin warehouse', 'time' => 'March 13, 2026 - 09:10 AM', 'copy' => 'The order was packed, verified, and prepared for courier handoff.'],
        ['title' => 'Courier pickup completed', 'time' => 'March 13, 2026 - 02:35 PM', 'copy' => 'The parcel left the Lucknow facility with tracking reference TRK-99821.'],
        ['title' => 'Reached transit hub', 'time' => 'March 14, 2026 - 08:20 AM', 'copy' => 'The package reached the regional hub and is moving toward the final delivery center.'],
        ['title' => 'Out for delivery pending', 'time' => 'Expected update on March 16, 2026', 'copy' => 'The final delivery scan will appear here once the parcel is assigned to the last-mile rider.'],
    ];
@endphp

@section('title', 'Order Tracking')
@section('customer_title', 'Order Tracking')
@section('customer_description', 'A shipment page with progress visibility, courier details, and full tracking history.')
@section('customer_active', 'tracking')

@section('customer_actions')
    <x-ui.action-link :href="route('order.confirmation')">Order Confirmation</x-ui.action-link>
    <x-ui.action-link :href="route('contact')" variant="secondary">Courier Help</x-ui.action-link>
@endsection

@section('customer_content')
    <section class="rounded-[32px] border border-slate-200 bg-[linear-gradient(145deg,#ffffff_0%,#f8fbff_56%,#eff6ff_100%)] p-6 shadow-sm md:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">In Transit</span>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">Tracking Ref TRK-99821</span>
                </div>
                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Your order is moving through the courier network.</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">The progress bar below follows the same pattern users expect from modern order-tracking flows: packed, dispatched, in transit, then delivered.</p>
            </div>

            <div class="rounded-3xl border border-white/80 bg-white/80 p-4 shadow-sm backdrop-blur sm:min-w-[18rem]">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Delivery Estimate</p>
                <p class="mt-2 text-lg font-semibold text-slate-950">March 16, 2026 by 7:00 PM</p>
                <p class="mt-2 text-sm text-slate-500">Courier: BlueDart Priority</p>
            </div>
        </div>

        <div class="mt-8 hidden md:grid md:grid-cols-4 md:gap-3">
            @foreach ($trackingSteps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isActive = $stepNumber <= $currentStep;
                    $isCurrent = $stepNumber === $currentStep;
                @endphp
                <div>
                    <div class="flex items-center {{ $loop->last ? '' : 'pr-2' }}">
                        <span class="{{ $isActive ? 'border-primary-600 bg-primary-600 text-white' : 'border-slate-300 bg-white text-slate-400' }} {{ $isCurrent ? 'ring-4 ring-primary-100' : '' }} inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full border-2 text-sm font-semibold shadow-sm">
                            {{ $stepNumber }}
                        </span>
                        @if (! $loop->last)
                            <span class="{{ $stepNumber < $currentStep ? 'bg-primary-600' : 'bg-slate-200' }} ml-3 h-1 flex-1 rounded-full"></span>
                        @endif
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $step['label'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $step['time'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 space-y-3 md:hidden">
            @foreach ($trackingSteps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isActive = $stepNumber <= $currentStep;
                @endphp
                <article class="flex items-start gap-3 rounded-2xl border {{ $isActive ? 'border-primary-100 bg-primary-50/70' : 'border-slate-200 bg-white' }} p-4">
                    <span class="{{ $isActive ? 'bg-primary-600 text-white' : 'bg-slate-200 text-slate-500' }} inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold">{{ $stepNumber }}</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $step['label'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $step['time'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(20rem,0.88fr)]">
        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
            <h3 class="text-lg font-semibold text-slate-950">Tracking History</h3>
            <p class="mt-1 text-sm text-slate-500">Every milestone appears in order, with the newest shipping movement easiest to scan.</p>

            <div class="mt-6 space-y-4">
                @foreach ($trackingHistory as $event)
                    <article class="flex gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <span class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-primary-700 shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 6v6l4 2"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900">{{ $event['title'] }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $event['time'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $event['copy'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="space-y-6">
            <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <h3 class="text-lg font-semibold text-slate-950">Shipment Summary</h3>
                <div class="mt-5 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center justify-between gap-4">
                        <span>Order ID</span>
                        <span class="font-semibold text-slate-900">ORD-20260311-1194</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span>Courier</span>
                        <span class="font-semibold text-slate-900">BlueDart Priority</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span>Tracking Number</span>
                        <span class="font-semibold text-slate-900">TRK-99821</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span>Shipment Mode</span>
                        <span class="font-semibold text-slate-900">Temperature-safe parcel</span>
                    </div>
                </div>
            </section>

            <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <h3 class="text-lg font-semibold text-slate-950">Delivery Address</h3>
                <p class="mt-4 text-sm font-semibold text-slate-900">Prakhar Kapoor</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    123 Medical Park Drive<br>
                    Lucknow, Uttar Pradesh<br>
                    India
                </p>
                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    The courier will call before final delivery if the parcel requires signature or handling confirmation.
                </div>
            </section>
        </div>
    </div>
@endsection
