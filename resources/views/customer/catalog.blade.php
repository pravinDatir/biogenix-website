@extends('customer.storefront-layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $products = [
        [
            'brand' => 'Biogenix Premium',
            'name' => 'Genomic-X Ultra PCR Reagent Master Mix',
            'sku' => 'BGX-PCR-00124-MI',
            'list_price' => '840.00',
            'your_price' => '724.50',
            'moq' => '5 Units',
            'discounts' => ['10+ units: 10% off', '25+ units: 18% off'],
            'badge' => 'In Stock',
            'badge_style' => 'bg-emerald-500/90 text-white',
            'variant' => 'vials',
        ],
        [
            'brand' => 'Abbott Molecular',
            'name' => 'Vysis ALK Break Apart FISH Probe Kit',
            'sku' => 'ABI-VYS-9012-P',
            'list_price' => '1,250.00',
            'your_price' => '1,100.00',
            'moq' => '2 Units',
            'discounts' => ['5+ units: 5% off', '10+ units: 12% off'],
            'badge' => 'Low Stock',
            'badge_style' => 'bg-amber-500/90 text-white',
            'variant' => 'tubes',
        ],
        [
            'brand' => 'Bio-Rad',
            'name' => 'SsoAdvanced Universal SYBR Green',
            'sku' => 'BRD-SSO-882-U',
            'list_price' => '420.00',
            'your_price' => '385.20',
            'moq' => '10 Units',
            'discounts' => ['20+ units: 8% off', '50+ units: 15% off'],
            'badge' => 'New Arrival',
            'badge_style' => 'bg-blue-500/90 text-white',
            'variant' => 'machine',
        ],
        [
            'brand' => 'Biogenix Biotools',
            'name' => 'Rapid-Flow RNA Isolation Kit (50 Preps)',
            'sku' => 'BGX-RNA-50P-44',
            'list_price' => '310.00',
            'your_price' => '265.00',
            'moq' => '1 Unit',
            'discounts' => ['5+ units: 5% off', '20+ units: 10% off'],
            'badge' => 'Special Offer',
            'badge_style' => 'bg-sky-500/90 text-white',
            'variant' => 'tray',
        ],
        [
            'brand' => 'Thermo Fisher',
            'name' => 'Applied Biosystems TaqMan Gene Expression Assay',
            'sku' => 'TFA-TMG-99-ASS',
            'list_price' => '560.00',
            'your_price' => '495.00',
            'moq' => '3 Units',
            'discounts' => ['5+ units: 4% off', '10+ units: 9% off'],
            'badge' => 'Institutional Fav',
            'badge_style' => 'bg-indigo-500/90 text-white',
            'variant' => 'microscope',
        ],
        [
            'brand' => 'Biogenix Labware',
            'name' => 'High-Throughput ELISA Plate Sealers',
            'sku' => 'BGX-ELS-PLT-S',
            'list_price' => '145.00',
            'your_price' => '128.00',
            'moq' => '25 Units',
            'discounts' => ['50+ units: 15% off', '100+ units: 25% off'],
            'badge' => 'Legacy Support',
            'badge_style' => 'bg-slate-100 text-slate-700',
            'variant' => 'petri',
        ],
    ];
@endphp

@section('title', 'Product Catalog')
@section('storefront_nav', 'Products')

@section('storefront_content')
    <div class="space-y-8">
        <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <span>Home</span>
            <span>/</span>
            <span>Catalog</span>
            <span>/</span>
            <span class="text-slate-700">IVD Kits &amp; Reagents</span>
        </div>

        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl space-y-3">
                <h1 class="text-5xl font-semibold tracking-tight text-slate-950">Product Catalog</h1>
                <p class="text-lg leading-8 text-slate-500">
                    Explore our comprehensive range of high-performance biotech reagents, IVD kits, and life science research tools engineered for precision.
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <span class="text-sm text-slate-400">Sort by:</span>
                <span class="text-sm font-semibold text-slate-800">Most Relevant</span>
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[240px_minmax(0,1fr)]">
            <aside class="space-y-8 rounded-[28px] border border-slate-200 bg-[#f1f2f4] p-6">
                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-400">Category</h2>
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                    </div>
                    @foreach ([['IVD Kits', 142, true], ['PCR Reagents', 86, false], ['Antibodies', 210, false]] as $item)
                        <label class="flex items-center justify-between gap-3 text-sm text-slate-700">
                            <span class="flex items-center gap-3">
                                <span class="flex h-5 w-5 items-center justify-center rounded-md border {{ $item[2] ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 bg-white text-transparent' }}">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 12 4 4L19 6"></path></svg>
                                </span>
                                {{ $item[0] }}
                            </span>
                            <span class="text-xs text-slate-400">{{ $item[1] }}</span>
                        </label>
                    @endforeach
                </section>

                @foreach (['Application' => ['Oncology', 'Infectious Diseases', 'Cardiology', 'Genomics'], 'Brand' => ['Bio-Rad', 'Abbott Molecular', 'Biogenix Labware']] as $group => $items)
                    <section class="space-y-4">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $group }}</h2>
                        @foreach ($items as $item)
                            <label class="flex items-center gap-3 text-sm text-slate-700">
                                <span class="h-5 w-5 rounded-md border border-slate-300 bg-white"></span>
                                {{ $item }}
                            </label>
                        @endforeach
                    </section>
                @endforeach

                <section class="space-y-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-400">Price Range</h2>
                    <div class="space-y-3">
                        <div class="relative h-2 rounded-full bg-slate-300">
                            <span class="absolute left-[22%] right-[18%] top-0 h-2 rounded-full bg-blue-500"></span>
                            <span class="absolute left-[22%] top-1/2 h-5 w-5 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-blue-500 bg-white shadow"></span>
                            <span class="absolute right-[18%] top-1/2 h-5 w-5 translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-blue-500 bg-white shadow"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-slate-700">
                            <span>$150</span>
                            <span>$2,500+</span>
                        </div>
                    </div>
                </section>
            </aside>

            <div class="space-y-8">
                <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach ($products as $product)
                        <article class="overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-sm">
                            <div class="relative p-4">
                                @include('customer.partials.product-visual', ['variant' => $product['variant'], 'class' => 'h-[280px]'])
                                <span class="{{ $product['badge_style'] }} absolute left-6 top-6 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em]">
                                    {{ $product['badge'] }}
                                </span>
                            </div>

                            <div class="space-y-4 px-5 pb-5">
                                <div class="space-y-1">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">{{ $product['brand'] }}</p>
                                    <h3 class="text-[28px] leading-8 font-semibold text-slate-950">{{ $product['name'] }}</h3>
                                    <p class="text-sm text-slate-400">SKU: {{ $product['sku'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-[#f7f9fb] p-4">
                                    <div class="flex items-center justify-between text-sm text-slate-400">
                                        <span>List MRP:</span>
                                        <span class="line-through">${{ $product['list_price'] }}</span>
                                    </div>
                                    <div class="mt-2 flex items-end justify-between">
                                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-blue-700">{{ $portal === 'b2b' ? 'Your Price' : 'Member Price' }}</span>
                                        <span class="text-4xl font-semibold tracking-tight text-blue-600">${{ $product['your_price'] }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 8h10M7 12h10M7 16h6"></path><path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8l-5 0V6a2 2 0 0 1 2-2Z"></path></svg>
                                    MOQ: {{ $product['moq'] }}
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-[#f7f9fb] px-4 py-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-blue-700">Bulk Discounts Available</p>
                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-600">
                                        @foreach ($product['discounts'] as $discount)
                                            <span>{{ $discount }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="button" class="inline-flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-blue-600 text-base font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle><path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path></svg>
                                    Add to Cart
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="flex items-center justify-center gap-3">
                    @foreach (['<', '1', '2', '3', '...', '12', '>'] as $pager)
                        <span class="{{ $pager === '1' ? 'bg-blue-600 text-white shadow' : 'bg-white text-slate-600' }} flex h-11 min-w-11 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm font-semibold">
                            {{ $pager }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
