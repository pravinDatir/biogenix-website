@php
    $filters = [
        'Category' => [
            ['label' => 'IVD Kits', 'count' => 142, 'checked' => true],
            ['label' => 'PCR Reagents', 'count' => 86, 'checked' => false],
            ['label' => 'Antibodies', 'count' => 210, 'checked' => false],
        ],
        'Application' => [
            ['label' => 'Oncology', 'count' => null, 'checked' => false],
            ['label' => 'Infectious Diseases', 'count' => null, 'checked' => false],
            ['label' => 'Cardiology', 'count' => null, 'checked' => false],
            ['label' => 'Genomics', 'count' => null, 'checked' => false],
        ],
        'Brand' => [
            ['label' => 'Bio-Rad', 'count' => null, 'checked' => false],
            ['label' => 'Abbott Molecular', 'count' => null, 'checked' => false],
            ['label' => 'Biogenix Labware', 'count' => null, 'checked' => false],
        ],
    ];

    $products = [
        [
            'id' => 1,
            'slug' => 'genomic-x-ultra-pcr-reagent-master-mix',
            'brand' => 'Biogenix Premium',
            'title' => 'Genomic-X Ultra PCR Reagent Master Mix',
            'sku' => 'BGX-PCR-00124-MI',
            'list_price' => 840.00,
            'your_price' => 724.50,
            'moq' => '5 Units',
            'discounts' => ['10+ units: 10% off', '25+ units: 18% off'],
            'badge' => 'In Stock',
            'badge_class' => 'bg-emerald-500 text-white',
            'placeholder_top' => 'PCR Reagent',
            'placeholder_bottom' => 'Master Mix',
            'placeholder_accent' => '#14b8a6',
            'description' => 'High-performance ready-to-use PCR master mix engineered for precise amplification workflows across molecular diagnostics and research settings.',
            'overview' => [
                'Optimized enzyme blend for reliable amplification performance.',
                'Validated for routine molecular biology and translational workflows.',
                'Cold-chain stable packaging for biotech and institutional ordering.',
            ],
            'specifications' => [
                'Format' => '2x Master Mix',
                'Pack Size' => '100 reactions',
                'Storage' => '-20 C',
                'Application' => 'PCR / qPCR prep',
            ],
        ],
        [
            'id' => 2,
            'slug' => 'vysis-alk-break-apart-fish-probe-kit',
            'brand' => 'Abbott Molecular',
            'title' => 'Vysis ALK Break Apart FISH Probe Kit',
            'sku' => 'ABI-VYS-9012-P',
            'list_price' => 1250.00,
            'your_price' => 1100.00,
            'moq' => '2 Units',
            'discounts' => ['5+ units: 5% off', '10+ units: 12% off'],
            'badge' => 'Low Stock',
            'badge_class' => 'bg-amber-500 text-white',
            'placeholder_top' => 'FISH Probe',
            'placeholder_bottom' => 'Glassware',
            'placeholder_accent' => '#0ea5e9',
            'description' => 'Clinical research FISH probe kit designed for fluorescence-based break apart detection with strong signal clarity and lab consistency.',
            'overview' => [
                'Clear signal discrimination for high-confidence interpretation.',
                'Suitable for oncology-oriented fluorescence workflows.',
                'Built for institutional and laboratory procurement cycles.',
            ],
            'specifications' => [
                'Format' => 'Probe kit',
                'Pack Size' => '25 tests',
                'Storage' => '2 C to 8 C',
                'Application' => 'FISH analysis',
            ],
        ],
        [
            'id' => 3,
            'slug' => 'ssoadvanced-universal-sybr-green',
            'brand' => 'Bio-Rad',
            'title' => 'SsoAdvanced Universal SYBR Green',
            'sku' => 'BRD-SSO-882-U',
            'list_price' => 420.00,
            'your_price' => 385.20,
            'moq' => '10 Units',
            'discounts' => ['20+ units: 8% off', '50+ units: 15% off'],
            'badge' => 'New Arrival',
            'badge_class' => 'bg-blue-500 text-white',
            'placeholder_top' => 'SYBR Green',
            'placeholder_bottom' => 'Analyzer',
            'placeholder_accent' => '#2563eb',
            'description' => 'Universal SYBR Green chemistry for fast, sensitive real-time PCR analysis across regulated and research lab environments.',
            'overview' => [
                'Optimized for broad assay compatibility and fast cycling.',
                'Low background with high fluorescence stability.',
                'Good fit for genomics and infectious disease panels.',
            ],
            'specifications' => [
                'Format' => 'qPCR reagent',
                'Pack Size' => '500 reactions',
                'Storage' => '-20 C',
                'Application' => 'Real-time PCR',
            ],
        ],
        [
            'id' => 4,
            'slug' => 'rapid-flow-rna-isolation-kit-50-preps',
            'brand' => 'Biogenix Biotools',
            'title' => 'Rapid-Flow RNA Isolation Kit (50 Preps)',
            'sku' => 'BGX-RNA-50P-44',
            'list_price' => 310.00,
            'your_price' => 265.00,
            'moq' => '1 Unit',
            'discounts' => ['5+ units: 5% off', '20+ units: 10% off'],
            'badge' => 'Special Offer',
            'badge_class' => 'bg-sky-500 text-white',
            'placeholder_top' => 'RNA Kit',
            'placeholder_bottom' => 'Prep Tray',
            'placeholder_accent' => '#06b6d4',
            'description' => 'Streamlined RNA purification kit for high-throughput sample prep with clean extraction performance and reduced handling time.',
            'overview' => [
                'Fast isolation workflow optimized for routine prep volume.',
                'Designed for reproducible downstream molecular analysis.',
                'Compact tray-based consumable packaging.',
            ],
            'specifications' => [
                'Format' => 'Isolation kit',
                'Pack Size' => '50 preps',
                'Storage' => 'Room temp / chilled components',
                'Application' => 'RNA extraction',
            ],
        ],
        [
            'id' => 5,
            'slug' => 'applied-biosystems-taqman-gene-expression-assay',
            'brand' => 'Thermo Fisher',
            'title' => 'Applied Biosystems TaqMan Gene Expression Assay',
            'sku' => 'TFA-TMG-99-ASS',
            'list_price' => 560.00,
            'your_price' => 495.00,
            'moq' => '3 Units',
            'discounts' => ['5+ units: 4% off', '10+ units: 9% off'],
            'badge' => 'Institutional Fav',
            'badge_class' => 'bg-indigo-500 text-white',
            'placeholder_top' => 'Gene Assay',
            'placeholder_bottom' => 'Microscope',
            'placeholder_accent' => '#1d4ed8',
            'description' => 'Trusted gene expression assay solution with robust sensitivity for institutional molecular profiling and targeted quantification workflows.',
            'overview' => [
                'Validated assay chemistry for consistent expression analysis.',
                'Supports high-specificity quantification across target panels.',
                'Works well in genomics and translational research labs.',
            ],
            'specifications' => [
                'Format' => 'Assay mix',
                'Pack Size' => '200 reactions',
                'Storage' => '-20 C',
                'Application' => 'Gene expression',
            ],
        ],
        [
            'id' => 6,
            'slug' => 'high-throughput-elisa-plate-sealers',
            'brand' => 'Biogenix Labware',
            'title' => 'High-Throughput ELISA Plate Sealers',
            'sku' => 'BGX-ELS-PLT-S',
            'list_price' => 145.00,
            'your_price' => 128.00,
            'moq' => '25 Units',
            'discounts' => ['50+ units: 15% off', '100+ units: 25% off'],
            'badge' => 'Legacy Support',
            'badge_class' => 'bg-slate-100 text-slate-700',
            'placeholder_top' => 'ELISA Plate',
            'placeholder_bottom' => 'Seal',
            'placeholder_accent' => '#0f766e',
            'description' => 'Labware sealing solution built for stable ELISA plate handling, throughput, and clean sample protection during plate processing.',
            'overview' => [
                'Tight sealing helps reduce contamination risk.',
                'Supports routine lab throughput and plate transport.',
                'Cost-efficient bulk ordering for institutions and diagnostics labs.',
            ],
            'specifications' => [
                'Format' => 'Plate sealer',
                'Pack Size' => '100 seals',
                'Storage' => 'Ambient',
                'Application' => 'ELISA workflow',
            ],
        ],
    ];

    $buildPlaceholder = function (string $top, string $bottom, string $accent): string {
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="720" height="500" viewBox="0 0 720 500">
  <defs>
    <linearGradient id="bg" x1="0%" x2="100%" y1="0%" y2="100%">
      <stop offset="0%" stop-color="#0b1727" />
      <stop offset="55%" stop-color="#10384c" />
      <stop offset="100%" stop-color="#1f2937" />
    </linearGradient>
    <radialGradient id="glow" cx="32%" cy="22%" r="58%">
      <stop offset="0%" stop-color="{$accent}" stop-opacity="0.95" />
      <stop offset="100%" stop-color="{$accent}" stop-opacity="0" />
    </radialGradient>
  </defs>
  <rect width="720" height="500" rx="28" fill="url(#bg)" />
  <rect width="720" height="500" rx="28" fill="url(#glow)" opacity="0.78" />
  <circle cx="538" cy="138" r="88" fill="#ffffff" fill-opacity="0.06" />
  <circle cx="184" cy="356" r="112" fill="#ffffff" fill-opacity="0.05" />
  <rect x="118" y="294" width="182" height="96" rx="18" fill="#ffffff" fill-opacity="0.92" />
  <rect x="340" y="236" width="88" height="156" rx="20" fill="#ffffff" fill-opacity="0.9" />
  <rect x="452" y="212" width="52" height="180" rx="20" fill="#ffffff" fill-opacity="0.82" />
  <rect x="520" y="172" width="36" height="220" rx="18" fill="#ffffff" fill-opacity="0.72" />
  <rect x="150" y="254" width="34" height="40" rx="10" fill="#e2e8f0" />
  <rect x="190" y="254" width="34" height="40" rx="10" fill="#e2e8f0" />
  <rect x="230" y="254" width="34" height="40" rx="10" fill="#e2e8f0" />
  <text x="64" y="96" fill="#e2e8f0" font-family="Inter, Arial, sans-serif" font-size="28" font-weight="700" letter-spacing="4">{$top}</text>
  <text x="64" y="138" fill="#f8fafc" font-family="Inter, Arial, sans-serif" font-size="44" font-weight="800">{$bottom}</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    };

    $selectedProductSlug = trim((string) request('product'));
    $selectedProduct = collect($products)->firstWhere('slug', $selectedProductSlug);
    $catalogUrl = request()->fullUrlWithQuery(['product' => null]);
@endphp

<section class="w-full bg-[#f4f5f7] py-8 md:py-10" style="font-family: Inter, system-ui, sans-serif;">
    <div class="mx-auto max-w-[1380px] px-4 sm:px-6 lg:px-8">
        @if ($selectedProduct)
            <div class="flex flex-wrap items-center gap-2 text-[12px] font-medium text-slate-400">
                <a href="{{ $catalogUrl }}" class="text-inherit no-underline hover:text-slate-700">Catalog</a>
                <span>/</span>
                <span>{{ $selectedProduct['brand'] }}</span>
                <span>/</span>
                <span class="text-slate-700">{{ $selectedProduct['title'] }}</span>
            </div>

            <div class="mt-5">
                <a href="{{ $catalogUrl }}" class="inline-flex h-11 items-center justify-center rounded-[10px] border border-slate-200 bg-white px-4 text-[14px] font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">
                    &larr; Back to Catalog
                </a>
            </div>

            <div class="mt-8 grid gap-8 xl:grid-cols-[minmax(0,0.92fr)_minmax(0,1.08fr)]">
                <div class="space-y-5">
                    <div class="rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm">
                        <img
                            src="{{ $buildPlaceholder($selectedProduct['placeholder_top'], $selectedProduct['placeholder_bottom'], $selectedProduct['placeholder_accent']) }}"
                            alt="{{ $selectedProduct['title'] }}"
                            class="h-[420px] w-full rounded-[18px] object-cover"
                        >
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        @for ($thumb = 1; $thumb <= 4; $thumb++)
                            <div class="rounded-[16px] border border-slate-200 bg-white p-2 shadow-sm">
                                <img
                                    src="{{ $buildPlaceholder($selectedProduct['placeholder_top'], $selectedProduct['placeholder_bottom'], $selectedProduct['placeholder_accent']) }}"
                                    alt="{{ $selectedProduct['title'] }} thumbnail {{ $thumb }}"
                                    class="h-24 w-full rounded-[12px] object-cover"
                                >
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-[#2383eb]">
                            {{ $selectedProduct['brand'] }}
                        </span>
                        <h1 class="max-w-[760px] text-[34px] font-extrabold leading-[1.08] tracking-[-0.04em] text-slate-950">
                            {{ $selectedProduct['title'] }}
                        </h1>
                        <p class="text-[15px] leading-[1.8] text-slate-500">{{ $selectedProduct['description'] }}</p>
                        <div class="flex flex-wrap items-center gap-3 text-[13px] text-slate-500">
                            <span>SKU: {{ $selectedProduct['sku'] }}</span>
                            <span class="text-slate-300">|</span>
                            <span>MOQ: {{ $selectedProduct['moq'] }}</span>
                            <span class="text-slate-300">|</span>
                            <span class="font-semibold text-emerald-600">{{ $selectedProduct['badge'] }}</span>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <p class="text-[12px] font-semibold uppercase tracking-[0.12em] text-slate-400">Market Retail Price</p>
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-[34px] font-extrabold tracking-[-0.04em] text-slate-950">${{ number_format($selectedProduct['your_price'], 2) }}</span>
                                    <span class="text-[16px] font-medium text-slate-400 line-through">${{ number_format($selectedProduct['list_price'], 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-[16px] bg-[#f8fafc] p-4">
                            <p class="text-[12px] font-semibold uppercase tracking-[0.1em] text-[#2383eb]">Bulk Discount Options</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($selectedProduct['discounts'] as $discount)
                                    <span class="rounded-full border border-blue-100 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600">{{ $discount }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <button type="button" class="inline-flex h-12 items-center justify-center rounded-[10px] bg-[#2383eb] px-4 text-[14px] font-semibold text-white shadow-sm transition hover:bg-[#1570c9]">
                                Add to Cart
                            </button>
                            <button type="button" class="inline-flex h-12 items-center justify-center rounded-[10px] border border-slate-200 bg-white px-4 text-[14px] font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Generate Quote
                            </button>
                            <button type="button" class="inline-flex h-12 items-center justify-center rounded-[10px] border border-blue-200 bg-blue-50 px-4 text-[14px] font-semibold text-[#2383eb] transition hover:bg-blue-100">
                                Order Now
                            </button>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-[22px] font-bold tracking-[-0.03em] text-slate-950">Product Overview</h2>
                        <ul class="mt-4 space-y-3 text-[15px] leading-[1.8] text-slate-600">
                            @foreach ($selectedProduct['overview'] as $point)
                                <li class="flex items-start gap-3">
                                    <span class="mt-2 h-2 w-2 rounded-full bg-[#2383eb]"></span>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-[22px] font-bold tracking-[-0.03em] text-slate-950">Technical Specifications</h2>
                        <div class="mt-5 overflow-hidden rounded-[16px] border border-slate-200">
                            @foreach ($selectedProduct['specifications'] as $key => $value)
                                <div class="grid border-b border-slate-200 text-[14px] last:border-b-0 sm:grid-cols-[220px_minmax(0,1fr)]">
                                    <div class="bg-slate-50 px-5 py-4 font-semibold text-slate-700">{{ $key }}</div>
                                    <div class="px-5 py-4 text-slate-600">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
        <div class="flex flex-wrap items-center gap-2 text-[12px] font-medium text-slate-400">
            <span>Home</span>
            <span>/</span>
            <span>Catalog</span>
            <span>/</span>
            <span class="text-slate-700">IVD Kits &amp; Reagents</span>
        </div>

        <div class="mt-5 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-[780px]">
                <h1 class="text-[36px] font-bold tracking-[-0.04em] text-slate-950">Product Catalog</h1>
                <p class="mt-3 max-w-[720px] text-[14px] leading-7 text-slate-500">
                    Explore our comprehensive range of high-performance biotech reagents, IVD kits, and life science research tools engineered for precision.
                </p>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                <span class="text-[13px] font-medium text-slate-400">Sort by:</span>
                <span class="text-[13px] font-semibold text-slate-800">Most Relevant</span>
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </div>
        </div>

        <div class="mt-8 grid gap-8 xl:grid-cols-[220px_minmax(0,1fr)]">
            <aside class="space-y-7 rounded-[28px] border border-white/70 bg-white/80 p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] xl:sticky xl:top-8 xl:self-start">
                <?php foreach ($filters as $section => $options): ?>
                    <section class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-[12px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $section }}</h2>
                            <?php if ($section === 'Category'): ?>
                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            <?php endif; ?>
                        </div>

                        <?php foreach ($options as $option): ?>
                            <label class="flex cursor-pointer items-center justify-between gap-3 text-[14px] text-slate-800">
                                <span class="flex items-center gap-3">
                                    <span class="{{ $option['checked'] ? 'border-[#2383eb] bg-[#2383eb]' : 'border-slate-300 bg-transparent' }} flex h-[15px] w-[15px] items-center justify-center rounded-[4px] border">
                                        <?php if ($option['checked']): ?>
                                            <svg class="h-2.5 w-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <path d="m5 12 4 4L19 6"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </span>
                                    <span>{{ $option['label'] }}</span>
                                </span>
                                <?php if ($option['count']): ?>
                                    <span class="text-[11px] text-slate-400">{{ $option['count'] }}</span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    </section>
                <?php endforeach; ?>

                <section class="space-y-4">
                    <h2 class="text-[12px] font-semibold uppercase tracking-[0.18em] text-slate-400">Price Range</h2>
                    <div class="px-1">
                        <input
                            type="range"
                            min="150"
                            max="2500"
                            value="1550"
                            class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-300 accent-[#2383eb]"
                        >
                        <div class="mt-4 flex items-center justify-between text-[12px] font-semibold text-slate-700">
                            <span>$150</span>
                            <span>$2,500+</span>
                        </div>
                    </div>
                </section>
            </aside>

            <div class="space-y-6">
                <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
                    <?php foreach ($products as $productIndex => $product): ?>
                        @php
                            $detailQuery = array_merge(
                                \Illuminate\Support\Arr::except(request()->query(), ['product', 'id']),
                                ['id' => $product['id']]
                            );
                            $detailUrl = asset('product-details.php') . '?' . http_build_query($detailQuery);
                            $ratingValue = number_format(4.6 + (($productIndex % 3) * 0.1), 1);
                            $reviewTotal = 42 + ($productIndex * 7);
                        @endphp
                        <article class="group overflow-hidden rounded-[22px] border border-white/80 bg-white shadow-[0_18px_42px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_52px_rgba(15,23,42,0.12)]">
                            <div class="relative px-4 pt-4">
                                <div class="overflow-hidden rounded-[16px]">
                                    <img
                                        src="{{ $buildPlaceholder($product['placeholder_top'], $product['placeholder_bottom'], $product['placeholder_accent']) }}"
                                        alt="{{ $product['title'] }}"
                                        class="h-[210px] w-full object-cover transition duration-300 group-hover:scale-[1.04]"
                                    >
                                </div>
                                <span class="{{ $product['badge_class'] }} absolute left-6 top-6 rounded-[6px] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] shadow-sm">
                                    {{ $product['badge'] }}
                                </span>
                            </div>

                            <div class="space-y-4 px-4 pb-4 pt-5">
                                <div class="space-y-1.5">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $product['brand'] }}</p>
                                    <h3 class="text-[15px] font-bold leading-[1.38] text-slate-950">
                                        {{ $product['title'] }}
                                    </h3>
                                    <p class="text-[12px] text-slate-400">SKU: {{ $product['sku'] }}</p>
                                    <div class="flex items-center gap-2 pt-1 text-[13px] font-medium text-slate-500">
                                        <span class="flex items-center gap-1 text-amber-400">
                                            @for ($star = 0; $star < 5; $star++)
                                                <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="m10 1.5 2.54 5.14 5.68.83-4.11 4 1 5.66L10 14.44 4.89 17.13l.98-5.66-4.1-4 5.67-.83L10 1.5Z"></path></svg>
                                            @endfor
                                        </span>
                                        <span>{{ $ratingValue }}</span>
                                        <span class="text-slate-300">|</span>
                                        <span>{{ $reviewTotal }} reviews</span>
                                    </div>
                                </div>

                                <div class="rounded-[14px] bg-[#fbfcfd] px-3 py-3">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span class="uppercase tracking-[0.08em]">List MRP:</span>
                                        <span class="font-medium line-through">${{ number_format($product['list_price'], 2) }}</span>
                                    </div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#2383eb]">Your Price:</span>
                                        <span class="text-[18px] font-extrabold tracking-[-0.03em] text-[#2383eb]">${{ number_format($product['your_price'], 2) }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 text-[12px] text-slate-700">
                                    <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 8h10M7 12h10M7 16h6"></path>
                                        <path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8l-5 0V6a2 2 0 0 1 2-2Z"></path>
                                    </svg>
                                    <span>MOQ: {{ $product['moq'] }}</span>
                                </div>

                                <div class="rounded-[14px] border border-slate-200 bg-[#fbfcfd] px-3 py-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-[#2383eb]">Bulk Discounts Available</p>
                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-500">
                                        <?php foreach ($product['discounts'] as $discount): ?>
                                            <span>{{ $discount }}</span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="grid gap-2 sm:grid-cols-2">
                                    <a href="{{ $detailUrl }}" class="inline-flex h-[44px] items-center justify-center rounded-[10px] border border-slate-200 bg-white text-[14px] font-semibold text-slate-700 no-underline shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#2383eb] hover:text-[#2383eb]">
                                        View Details
                                    </a>
                                    <button type="button" class="inline-flex h-[44px] items-center justify-center rounded-[10px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] text-[14px] font-semibold text-white shadow-[0_14px_26px_rgba(35,131,235,0.20)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_18px_32px_rgba(35,131,235,0.26)]">
                                        <svg class="mr-2 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="18" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.8a1 1 0 0 0 1-.8L21 7H7"></path>
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="flex items-center justify-center gap-2 pt-4">
                    <button type="button" class="flex h-10 w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                        &lt;
                    </button>
                    <button type="button" class="flex h-10 min-w-10 items-center justify-center rounded-[10px] bg-[#2383eb] px-3 text-[14px] font-semibold text-white">1</button>
                    <button type="button" class="flex h-10 min-w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white px-3 text-[14px] font-semibold text-slate-700 hover:bg-slate-50">2</button>
                    <button type="button" class="flex h-10 min-w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white px-3 text-[14px] font-semibold text-slate-700 hover:bg-slate-50">3</button>
                    <span class="px-2 text-slate-400">...</span>
                    <button type="button" class="flex h-10 min-w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white px-3 text-[14px] font-semibold text-slate-700 hover:bg-slate-50">12</button>
                    <button type="button" class="flex h-10 w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                        &gt;
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
