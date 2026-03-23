@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $panelClass = 'rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] md:p-7';
    $compactPanelClass = 'rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] md:p-6';
    $buttonBaseClass = 'inline-flex h-10 items-center justify-center rounded-xl px-5 text-[13px] font-bold transition focus-visible:outline-none';
    $statusFilters = ['All', 'Processing', 'Shipped', 'Delivered'];
    $periodFilters = ['Last 3 months', 'Last 6 months', '2023'];
    $statusStyles = [
        'delivered' => [
            'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
            'reorder' => 'bg-[#091b3f] text-white shadow-sm hover:bg-slate-800',
        ],
        'processing' => [
            'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
            'reorder' => 'bg-[#091b3f] text-white shadow-sm hover:bg-slate-800',
        ],
        'shipped' => [
            'badge' => 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200',
            'reorder' => 'bg-[#091b3f] text-white shadow-sm hover:bg-slate-800',
        ],
        'archived' => [
            'badge' => 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-200',
            'reorder' => 'bg-slate-300 text-white',
        ],
    ];
    $orders = [
        [
            'id' => 'BGX-99203-TRK',
            'reference' => '#ORD-99203-TRK',
            'status' => 'Delivered',
            'status_key' => 'delivered',
            'product' => 'Premium Reagent Grade Kit V4',
            'date' => 'Oct 12, 2023',
            'total' => '$1,240.00',
            'image' => asset('upload/categories/image1.jpg'),
            'image_background' => 'bg-[#fdf2e9]',
            'summary_note' => 'Priority temperature-safe delivery',
            'tracking_id' => 'BGX-7742019902',
            'carrier' => 'Via Biogenix Logistics Pro',
            'address_lines' => [
                'Central Lab Division',
                '1200 Innovation Way, Suite 400',
                'Cambridge, MA 02142',
            ],
            'subtotal' => '$1,240.00',
            'tax' => '$0.00',
            'shipping' => '$0.00',
            'grand_total' => '$1,240.00',
            'invoice_note' => 'Scientific exemption applied',
            'items' => [
                [
                    'name' => 'Precision Pipette Kit X10',
                    'subtitle' => 'Calibrated G-Series',
                    'sku' => 'BGX-PP-1022',
                    'qty' => 2,
                    'price' => '$420.00',
                    'total' => '$840.00',
                    'image' => asset('upload/categories/image1.jpg'),
                    'background' => 'bg-emerald-50',
                ],
                [
                    'name' => 'Agarose Sterile Plates',
                    'subtitle' => 'Pack of 50',
                    'sku' => 'BGX-AG-5044',
                    'qty' => 5,
                    'price' => '$80.00',
                    'total' => '$400.00',
                    'image' => asset('upload/categories/image2.jpg'),
                    'background' => 'bg-[#fdf2e9]',
                ],
            ],
        ],
        [
            'id' => 'BGX-88124-TRK',
            'reference' => '#ORD-88124-TRK',
            'status' => 'Shipped',
            'status_key' => 'shipped',
            'product' => 'Biogenix Microcentrifuge X-10',
            'date' => 'Oct 28, 2023',
            'total' => '$4,850.00',
            'image' => asset('upload/categories/image2.jpg'),
            'image_background' => 'bg-slate-100',
            'summary_note' => 'In transit to your primary lab',
            'tracking_id' => 'BGX-5501842307',
            'carrier' => 'Via Biogenix Cold Chain Express',
            'address_lines' => [
                'North Research Wing',
                '77 Discovery Park, Level 2',
                'Palo Alto, CA 94304',
            ],
            'subtotal' => '$4,850.00',
            'tax' => '$0.00',
            'shipping' => '$0.00',
            'grand_total' => '$4,850.00',
            'invoice_note' => 'GST locked after dispatch',
            'items' => [
                [
                    'name' => 'Biogenix Microcentrifuge X-10',
                    'subtitle' => 'Benchtop centrifuge',
                    'sku' => 'BGX-MC-88124',
                    'qty' => 1,
                    'price' => '$4,450.00',
                    'total' => '$4,450.00',
                    'image' => asset('upload/categories/image2.jpg'),
                    'background' => 'bg-slate-100',
                ],
                [
                    'name' => 'Rotor Adapter Bundle',
                    'subtitle' => 'Accessory kit',
                    'sku' => 'BGX-RA-2019',
                    'qty' => 1,
                    'price' => '$400.00',
                    'total' => '$400.00',
                    'image' => asset('upload/categories/image1.jpg'),
                    'background' => 'bg-sky-50',
                ],
            ],
        ],
        [
            'id' => 'BGX-12005-TRK',
            'reference' => '#ORD-12005-TRK',
            'status' => 'Archived',
            'status_key' => 'archived',
            'product' => 'Precision Pipette Series Z',
            'date' => 'Aug 05, 2023',
            'total' => '$890.00',
            'image' => asset('upload/categories/image5.jpg'),
            'image_background' => 'bg-slate-100',
            'summary_note' => 'Archived for procurement records',
            'tracking_id' => 'BGX-1028349921',
            'carrier' => 'Delivered via Biogenix Standard',
            'address_lines' => [
                'Analytical Sciences Group',
                '19 Genome Street',
                'Boston, MA 02115',
            ],
            'subtotal' => '$890.00',
            'tax' => '$0.00',
            'shipping' => '$0.00',
            'grand_total' => '$890.00',
            'invoice_note' => 'Historical record only',
            'items' => [
                [
                    'name' => 'Precision Pipette Series Z',
                    'subtitle' => 'Single channel starter set',
                    'sku' => 'BGX-PP-12005',
                    'qty' => 1,
                    'price' => '$890.00',
                    'total' => '$890.00',
                    'image' => asset('upload/categories/image5.jpg'),
                    'background' => 'bg-slate-100',
                ],
            ],
        ],
    ];
@endphp

@section('title', 'My Orders')
@section('customer_active', 'orders')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="orders"
        title="Order History"
        description="Manage and track your laboratory supply procurements."
    >
        <section class="{{ $compactPanelClass }}">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end">
                <div class="min-w-0 lg:max-w-[65%] lg:flex-1">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Status</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($statusFilters as $filter)
                            <button
                                type="button"
                                class="inline-flex h-10 items-center justify-center rounded-xl px-4 text-[13px] font-bold transition {{ $filter === 'All' ? 'bg-[#091b3f] text-white shadow-md hover:bg-slate-800' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm' }}"
                            >
                                {{ $filter }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="min-w-0 lg:ml-auto lg:w-full lg:max-w-[35%]">
                    <label for="order-range" class="block text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Date Range</label>
                    <div class="relative mt-3">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <select
                            id="order-range"
                            class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-10 text-[13px] font-semibold text-slate-700 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]"
                        >
                            @foreach ($periodFilters as $period)
                                <option>{{ $period }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="space-y-5">
            @foreach ($orders as $index => $order)
                @php($statusStyle = $statusStyles[$order['status_key']] ?? $statusStyles['processing'])

                <article class="{{ $panelClass }}">
                    <div class="grid gap-6 xl:grid-cols-[auto_minmax(0,1fr)_auto] xl:items-center">
                        <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl {{ $order['image_background'] }} p-3 shadow-inner">
                            <img src="{{ $order['image'] }}" alt="{{ $order['product'] }}" class="h-full w-full rounded-xl object-cover mix-blend-multiply">
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $statusStyle['badge'] }}">
                                    {{ $order['status'] }}
                                </span>
                                <span class="text-[12px] font-bold tracking-tight text-slate-400"># {{ $order['id'] }}</span>
                            </div>

                            <h3 class="mt-3 text-xl font-bold tracking-tight text-slate-900">{{ $order['product'] }}</h3>

                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $order['date'] }}
                                </span>
                                <span class="text-lg font-bold text-slate-900">{{ $order['total'] }}</span>
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-[12px] font-semibold text-slate-500">
                                    {{ $order['summary_note'] }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button
                                type="button"
                                onclick="openOrderModal({{ $index }})"
                                class="{{ $buttonBaseClass }} border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50"
                            >
                                View Details
                            </button>
                            <button
                                type="button"
                                class="{{ $buttonBaseClass }} {{ $statusStyle['reorder'] }} {{ $order['status_key'] === 'archived' ? 'cursor-not-allowed opacity-70' : '' }}"
                                @disabled($order['status_key'] === 'archived')
                            >
                                Reorder
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @include('userProfile.orders.order-modal')
    </x-account.workspace>
@endsection
