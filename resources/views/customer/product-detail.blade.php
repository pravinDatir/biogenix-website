@extends('customer.storefront-layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $pricingTiers = $portal === 'b2b'
        ? [
            ['qty' => '1-4', 'price' => 'INR 4,850', 'note' => 'Dealer price'],
            ['qty' => '5-9', 'price' => 'INR 4,540', 'note' => 'Bulk slab'],
            ['qty' => '10+', 'price' => 'INR 4,250', 'note' => 'Contract review'],
        ]
        : [
            ['qty' => '1', 'price' => 'INR 5,900', 'note' => 'MRP'],
            ['qty' => '2-4', 'price' => 'INR 5,700', 'note' => 'Checkout bundle offer'],
            ['qty' => '5+', 'price' => 'INR 5,450', 'note' => 'Campaign promo'],
        ];

    $documents = $portal === 'b2b'
        ? ['Brochure', 'COA', 'IFU', 'Commercial Spec Sheet']
        : ['Brochure', 'IFU'];

    $isGuestPreview = ! auth()->check();
@endphp

@section('title', 'Product Details')
@section('storefront_nav', 'Products')

@section('storefront_content')
    <div class="space-y-10">
        <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <span>Home</span>
            <span>/</span>
            <span>Laboratory Equipment</span>
            <span>/</span>
            <span>Centrifuges</span>
            <span>/</span>
            <span class="text-slate-700">Model X1 Precision</span>
        </div>

        <div class="grid gap-8 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
            <div class="space-y-5">
                <div class="rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm">
                    @include('customer.partials.product-visual', ['variant' => 'centrifuge', 'class' => 'h-[520px]'])
                </div>
                <div class="grid grid-cols-5 gap-4">
                    @foreach (['centrifuge', 'vials', 'machine', 'rack', 'tubes'] as $index => $variant)
                        <div class="{{ $index === 0 ? 'border-blue-500' : 'border-slate-200' }} rounded-2xl border bg-white p-2 shadow-sm">
                            @include('customer.partials.product-visual', ['variant' => $variant, 'class' => 'h-[82px]'])
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-700">Premium Series</span>
                    <div class="space-y-2">
                        <h1 class="max-w-2xl text-5xl font-semibold leading-[1.05] tracking-tight text-slate-950">Biogenix Model X1 Precision Centrifuge</h1>
                        <p class="text-lg leading-8 text-slate-500">Model No: BGX-7892-X1 | Professional Grade Biotech Centrifugation System</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="m10 1.5 2.5 5.1 5.7.8-4.1 4 1 5.7L10 14.4 4.9 17l1-5.7-4.1-4 5.7-.8L10 1.5Z"></path></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-slate-500">(48 Customer Reviews)</span>
                        <span class="inline-flex items-center gap-2 text-sm text-emerald-600">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            In Stock
                        </span>
                    </div>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Market Retail Price (MRP)</p>
                    <div class="mt-3 flex flex-wrap items-end gap-3">
                        <span class="text-5xl font-semibold tracking-tight text-slate-950">${{ $portal === 'b2b' ? '13,775.00' : '14,500.00' }}</span>
                        <span class="pb-2 text-lg text-slate-400 line-through">$18,200.00</span>
                    </div>

                    <div class="mt-5 rounded-2xl bg-[#f7f9fb] p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="mt-1 flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="10" width="12" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $portal === 'b2b' ? 'Institutional pricing and bulk contract rates are active.' : 'Unlock wholesale pricing and bulk contract rates.' }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $portal === 'b2b' ? 'Contract pricing, MOQ slabs, and company scope rules are applied.' : 'Use institutional login to access B2B-specific price ladders.' }}
                                    </p>
                                </div>
                            </div>
                            @if ($isGuestPreview || $portal === 'b2c')
                                <a href="{{ route('login', ['user_type' => 'b2b']) }}" class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-semibold text-white no-underline hover:bg-blue-700">
                                    Login to See B2B Price
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-[150px_minmax(0,1fr)_minmax(0,1fr)] lg:items-end">
                        <div class="space-y-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Quantity</p>
                            <div class="flex h-14 items-center justify-between rounded-2xl border border-slate-200 bg-white px-4">
                                <button type="button" class="text-xl text-slate-400">-</button>
                                <span class="text-lg font-semibold text-slate-900">1</span>
                                <button type="button" class="text-xl text-slate-400">+</button>
                            </div>
                        </div>
                        <button type="button" class="inline-flex h-14 items-center justify-center gap-3 rounded-2xl bg-blue-600 px-6 text-base font-semibold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle><path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path></svg>
                            Add to Cart
                        </button>
                        <button type="button" class="inline-flex h-14 items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-6 text-base font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                            Add to Quote
                        </button>
                    </div>

                    <button type="button" class="mt-4 inline-flex h-12 w-full items-center justify-center gap-3 rounded-2xl bg-[#f6f7fb] text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                        Download Full Brochure
                    </button>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Bulk Tier Pricing (Institutional)</h2>
                    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200">
                        <div class="grid grid-cols-3 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-500">
                            <span>Quantity</span>
                            <span>Discount</span>
                            <span class="text-right">Price/Unit</span>
                        </div>
                        @foreach ($pricingTiers as $tier)
                            <div class="grid grid-cols-3 border-t border-slate-200 px-5 py-4 text-sm text-slate-700">
                                <span>{{ $tier['qty'] }}</span>
                                <span>{{ $tier['note'] }}</span>
                                <span class="text-right font-semibold text-slate-900">{{ $tier['price'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_330px]">
            <div class="space-y-8">
                <section class="rounded-[28px] border border-slate-200 bg-white p-8 shadow-sm">
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Product Overview</h2>
                    <div class="mt-5 space-y-5 text-base leading-8 text-slate-600">
                        <p>The Biogenix Model X1 is a high-performance, refrigerated benchtop centrifuge designed for the demanding molecular biology and clinical laboratory applications.</p>
                        <p>Equipped with an intuitive touchscreen interface, the X1 allows seamless programming of complex protocols, rapid spin-downs, and stable gradients while maintaining a compact bench footprint.</p>
                        <ul class="list-disc space-y-2 pl-5">
                            <li>Advanced brushless induction motor for maintenance-free operation.</li>
                            <li>Eco-friendly CFC-free refrigeration system maintaining -10°C at max speed.</li>
                            <li>Automatic rotor identification and imbalance detection system.</li>
                            <li>Durable stainless steel chamber for easy sterilization.</li>
                        </ul>
                    </div>
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-white p-8 shadow-sm">
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Technical Specifications</h2>
                    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        @foreach ([
                            ['Max. RPM', '18,500 RPM'],
                            ['Max. RCF', '30,130 x g'],
                            ['Capacity', '4 x 250 mL (Swing-out)'],
                            ['Temperature Range', '-20°C to +40°C'],
                            ['Run Time', '10s to 99h 59min or Continuous'],
                            ['Noise Level', '< 54 dB(A)'],
                            ['Dimensions (W x D x H)', '460 x 550 x 370 mm'],
                        ] as $row)
                            <div class="grid border-b border-slate-200 px-5 py-4 text-sm last:border-b-0 sm:grid-cols-[220px_minmax(0,1fr)]">
                                <div class="font-semibold text-slate-700">{{ $row[0] }}</div>
                                <div class="text-slate-600">{{ $row[1] }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="space-y-5">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Frequently Bought Together</h2>
                        <a href="#" class="text-sm font-semibold text-blue-700 no-underline">View All Laboratory Equipment</a>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ([
                            ['Precision Pipette Master Set', '$1,299.00', 'pipette'],
                            ['X-Gen Vortex Mixer', '$450.00', 'vortex'],
                            ['Conical Tubes 15mL / 50mL', '$185.00', 'tubes'],
                            ['Bio-Safe Tube Rack System', '$42.00', 'rack'],
                        ] as $item)
                            <article class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                                <div class="p-3">
                                    @include('customer.partials.product-visual', ['variant' => $item[2], 'class' => 'h-[230px]'])
                                </div>
                                <div class="space-y-2 px-4 pb-5">
                                    <h3 class="text-xl font-semibold text-slate-900">{{ $item[0] }}</h3>
                                    <p class="text-2xl font-semibold tracking-tight text-blue-600">{{ $item[1] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-2xl font-semibold tracking-tight text-slate-950">Technical Resources</h3>
                    <div class="mt-5 space-y-3">
                        @foreach ([
                            ['Certificate of Analysis (COA)', 'Batch: BGX-2024-001'],
                            ['Safety Data Sheet (SDS)', 'v3.2 Updated Dec 2023'],
                            ['User Manual & Installation', 'English / Spanish / German'],
                            ['Maintenance Schedule', 'Standard GLP Protocols'],
                        ] as $doc)
                            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-[#f7f9fb] px-4 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $doc[0] }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $doc[1] }}</p>
                                </div>
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-gradient-to-br from-blue-50 to-slate-100 p-6 shadow-sm">
                    <h3 class="text-2xl font-semibold tracking-tight text-slate-950">Need a Custom Setup?</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Our specialists can help configure the Model X1 with specific rotors and adapters for your workflow.
                    </p>
                    <button type="button" class="mt-5 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-blue-600 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">
                        Consult an Expert
                    </button>
                </section>
            </div>
        </div>
    </div>
@endsection
