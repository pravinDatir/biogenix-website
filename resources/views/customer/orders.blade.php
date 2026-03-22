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
    $statusBadgeMap = collect($statusStyles)->mapWithKeys(function ($tone, $key) {
        return [$key => $tone['badge']];
    })->all();
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
                                class="inline-flex h-10 items-center justify-center rounded-full px-4 text-[13px] font-semibold transition {{ $filter === 'All' ? 'bg-[#091b3f] text-white shadow-sm hover:bg-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }}"
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
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] {{ $statusStyle['badge'] }}">
                                    {{ $order['status'] }}
                                </span>
                                <span class="text-[12px] font-semibold tracking-[0.12em] text-slate-400">ID: {{ $order['id'] }}</span>
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

                        <div class="flex flex-col gap-3 sm:flex-row xl:flex-col xl:items-end">
                            <button
                                type="button"
                                onclick="openOrderModal({{ $index }})"
                                class="{{ $buttonBaseClass }} border border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-200"
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
    </x-account.workspace>

    <div id="orderModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300 sm:p-6">
        <button type="button" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" aria-label="Close order details" onclick="closeOrderModal()"></button>

        <div
            id="orderModalContent"
            class="relative w-full max-w-3xl grow-0 overflow-hidden rounded-3xl bg-white shadow-2xl transition duration-300 scale-95"
        >
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4 md:px-8 md:py-5">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h3 class="text-2xl font-bold text-slate-900">Order Details</h3>
                        <span id="orderModalStatus" class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]"></span>
                    </div>
                    <p id="orderModalMeta" class="mt-2 text-sm font-medium text-slate-500"></p>
                </div>
                <button type="button" onclick="closeOrderModal()" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[60vh] overflow-y-auto px-6 py-5 md:px-8 md:py-6">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Tracking ID</h4>
                        <p id="orderModalTracking" class="mt-2 text-[15px] font-bold text-slate-900"></p>
                        <p id="orderModalCarrier" class="mt-1 text-[13px] font-medium text-slate-500"></p>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Shipping Address</h4>
                        <div id="orderModalAddress" class="mt-2 space-y-1 text-[14px] font-medium leading-relaxed text-slate-800"></div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="mb-4 text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Items Summary</h4>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                                    <th class="pb-2 font-medium">Product</th>
                                    <th class="pb-2 text-center font-medium">SKU</th>
                                    <th class="pb-2 text-center font-medium">Qty</th>
                                    <th class="pb-2 text-right font-medium">Price</th>
                                    <th class="pb-2 text-right font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody id="orderModalItems" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <div class="w-full max-w-sm space-y-2.5 text-sm">
                        <div class="flex justify-between font-medium text-slate-600">
                            <span>Subtotal</span>
                            <span id="orderModalSubtotal"></span>
                        </div>
                        <div class="flex justify-between font-medium text-slate-600">
                            <span id="orderModalTaxLabel">Tax</span>
                            <span id="orderModalTax"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-3 font-medium text-slate-600">
                            <span>Shipping</span>
                            <span id="orderModalShipping"></span>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-base font-bold text-slate-900">Grand Total</span>
                            <span id="orderModalGrandTotal" class="text-2xl font-bold text-[#091b3f]"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-100 bg-slate-50/70 px-6 py-4 md:flex-row md:px-8">
                <button type="button" class="inline-flex w-full md:w-auto h-10 items-center justify-center gap-2 rounded-xl text-[14px] font-bold text-slate-600 transition hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Invoice
                </button>

                <div class="flex w-full justify-end items-center gap-3 md:w-auto">
                    <button type="button" onclick="closeOrderModal()" class="flex-1 rounded-xl h-10 px-6 text-[14px] font-bold text-slate-700 transition hover:bg-slate-200/60 md:flex-none">
                        Close
                    </button>
                    <button id="orderModalReorder" type="button" class="flex-1 whitespace-nowrap min-w-[140px] rounded-xl h-10 bg-[#091b3f] px-8 text-[14px] font-bold text-white shadow-sm transition hover:bg-slate-800 md:flex-none">
                        Reorder All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const previewOrders = @json($orders);
        const previewOrderToneClasses = @json($statusBadgeMap);
        let orderModalHideTimer = null;

        function escapeOrderHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (character) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                }[character];
            });
        }

        function setOrderModalVisibility(show) {
            const modal = document.getElementById('orderModal');
            const modalContent = document.getElementById('orderModalContent');
            if (!modal || !modalContent) return;

            if (show) {
                window.clearTimeout(orderModalHideTimer);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                window.requestAnimationFrame(function () {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                });
                document.body.style.overflow = 'hidden';
                return;
            }

            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            orderModalHideTimer = window.setTimeout(function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        function closeOrderModal() {
            setOrderModalVisibility(false);
        }

        function openOrderModal(index) {
            const order = previewOrders[index];
            if (!order) return;

            const statusElement = document.getElementById('orderModalStatus');
            const metaElement = document.getElementById('orderModalMeta');
            const trackingElement = document.getElementById('orderModalTracking');
            const carrierElement = document.getElementById('orderModalCarrier');
            const addressElement = document.getElementById('orderModalAddress');
            const itemsElement = document.getElementById('orderModalItems');
            const subtotalElement = document.getElementById('orderModalSubtotal');
            const taxLabelElement = document.getElementById('orderModalTaxLabel');
            const taxElement = document.getElementById('orderModalTax');
            const shippingElement = document.getElementById('orderModalShipping');
            const grandTotalElement = document.getElementById('orderModalGrandTotal');
            const reorderButton = document.getElementById('orderModalReorder');

            if (!statusElement || !metaElement || !trackingElement || !carrierElement || !addressElement || !itemsElement || !subtotalElement || !taxLabelElement || !taxElement || !shippingElement || !grandTotalElement || !reorderButton) {
                return;
            }

            statusElement.className = 'inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] ' + (previewOrderToneClasses[order.status_key] || '');
            statusElement.textContent = order.status;
            metaElement.innerHTML = 'ID: ' + escapeOrderHtml(order.reference) + ' &bull; Placed on ' + escapeOrderHtml(order.date);
            trackingElement.textContent = order.tracking_id;
            carrierElement.textContent = order.carrier;
            addressElement.innerHTML = order.address_lines.map(function (line) {
                return '<p>' + escapeOrderHtml(line) + '</p>';
            }).join('');
            itemsElement.innerHTML = order.items.map(function (item) {
                return `
                    <tr>
                        <td class="py-2.5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg ${escapeOrderHtml(item.background)} p-1.5">
                                    <img src="${escapeOrderHtml(item.image)}" alt="${escapeOrderHtml(item.name)}" class="h-full w-full rounded object-cover shadow-sm opacity-90">
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-slate-900">${escapeOrderHtml(item.name)}</p>
                                    <p class="text-[12px] font-medium text-slate-500">${escapeOrderHtml(item.subtitle)}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 text-center font-mono text-[13px] font-medium text-slate-500">${escapeOrderHtml(item.sku)}</td>
                        <td class="py-2.5 text-center text-[14px] font-bold text-slate-700">${escapeOrderHtml(item.qty)}</td>
                        <td class="py-2.5 text-right text-[14px] font-medium text-slate-600">${escapeOrderHtml(item.price)}</td>
                        <td class="py-2.5 text-right text-[14px] font-bold text-slate-900">${escapeOrderHtml(item.total)}</td>
                    </tr>
                `;
            }).join('');
            subtotalElement.textContent = order.subtotal;
            taxLabelElement.textContent = order.invoice_note ? 'Tax (' + order.invoice_note + ')' : 'Tax';
            taxElement.textContent = order.tax;
            shippingElement.textContent = order.shipping;
            grandTotalElement.textContent = order.grand_total;
            reorderButton.disabled = order.status_key === 'archived';
            reorderButton.classList.toggle('cursor-not-allowed', order.status_key === 'archived');
            reorderButton.classList.toggle('opacity-70', order.status_key === 'archived');

            setOrderModalVisibility(true);
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeOrderModal();
            }
        });
    </script>
@endsection
