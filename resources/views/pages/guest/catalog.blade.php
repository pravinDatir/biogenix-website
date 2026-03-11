@php
    use Illuminate\Support\Str;

    $productCollection = collect($products->items());
    $categoryOptions = $catalogOptions['categoryOptions'] ?? collect();
    $applicationOptions = $catalogOptions['applicationOptions'] ?? collect();
    $brandOptions = $catalogOptions['brandOptions'] ?? collect();
    $minPrice = (int) ($catalogOptions['minPrice'] ?? 150);
    $maxPrice = (int) ($catalogOptions['maxPrice'] ?? 2500);

    $selectedCategories = collect((array) request()->input('category_name', []))
        ->map(fn ($value) => trim((string) $value))
        ->filter()
        ->values();

    $selectedApplications = collect((array) request()->input('application_name', request()->input('subcategory_name', [])))
        ->map(fn ($value) => trim((string) $value))
        ->filter()
        ->values();

    $selectedBrands = collect((array) request()->input('brand_name', []))
        ->map(fn ($value) => trim((string) $value))
        ->filter()
        ->values();

    $promotedApplications = $selectedCategories
        ->filter(fn ($value) => ! $categoryOptions->has($value) && $applicationOptions->has($value))
        ->values();

    if ($promotedApplications->isNotEmpty()) {
        $selectedApplications = $selectedApplications
            ->merge($promotedApplications)
            ->unique()
            ->values();

        $selectedCategories = $selectedCategories
            ->reject(fn ($value) => $promotedApplications->contains($value))
            ->values();
    }

    $search = trim((string) request('search', request('search_text', request('search_value', ''))));
    $sort = trim((string) request('sort', 'relevant'));
    $selectedMaxPrice = request()->filled('max_price') ? min($maxPrice, (float) request('max_price')) : $maxPrice;
    $activeFilterCount = $selectedCategories->count()
        + $selectedApplications->count()
        + $selectedBrands->count()
        + ($search !== '' ? 1 : 0)
        + ($selectedMaxPrice < $maxPrice ? 1 : 0);

    $currentQuery = request()->query();

    $normalizeQueryArray = function ($value): array {
        $values = is_array($value) ? $value : [$value];

        return collect($values)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    };

    $dropQueryKey = function (string $key) use ($currentQuery): string {
        $query = $currentQuery;
        unset($query[$key], $query['page']);

        return route('products.index', $query);
    };

    $dropQueryKeys = function (array $keys) use ($currentQuery): string {
        $query = $currentQuery;

        foreach ($keys as $key) {
            unset($query[$key]);
        }

        unset($query['page']);

        return route('products.index', $query);
    };

    $removeQueryArrayValue = function (string $key, string $value) use ($currentQuery, $normalizeQueryArray): string {
        $query = $currentQuery;
        $values = $normalizeQueryArray($query[$key] ?? []);
        $values = array_values(array_filter($values, fn ($item) => $item !== $value));

        if (count($values)) {
            $query[$key] = $values;
        } else {
            unset($query[$key]);
        }

        unset($query['page']);

        return route('products.index', $query);
    };

    $removeQueryArrayValueFromKeys = function (array $keys, string $value) use ($currentQuery, $normalizeQueryArray): string {
        $query = $currentQuery;

        foreach ($keys as $key) {
            if (! array_key_exists($key, $query)) {
                continue;
            }

            $values = $normalizeQueryArray($query[$key] ?? []);
            $values = array_values(array_filter($values, fn ($item) => $item !== $value));

            if (count($values)) {
                $query[$key] = $values;
            } else {
                unset($query[$key]);
            }
        }

        unset($query['page']);

        return route('products.index', $query);
    };

    $formatInr = function (float|int|null $amount, int $decimals = 2): string {
        if ($amount === null) {
            return 'Request Pricing';
        }

        $negative = $amount < 0;
        $amount = abs((float) $amount);
        $formatted = number_format($amount, $decimals, '.', '');
        [$integerPart, $fractionPart] = array_pad(explode('.', $formatted), 2, '00');

        if (strlen($integerPart) > 3) {
            $lastThree = substr($integerPart, -3);
            $remaining = substr($integerPart, 0, -3);
            $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
            $integerPart = $remaining . ',' . $lastThree;
        }

        return ($negative ? '-' : '') . 'Rs. ' . $integerPart . ($decimals > 0 ? '.' . $fractionPart : '');
    };

    $resolveImageUrl = function ($product): ?string {
        $rawImage = $product->image_path ?? $product->image ?? null;

        if (! filled($rawImage)) {
            return null;
        }

        if (Str::startsWith($rawImage, ['http://', 'https://', '/'])) {
            return $rawImage;
        }

        if (Str::startsWith($rawImage, 'images/')) {
            return asset($rawImage);
        }

        return asset('storage/' . ltrim($rawImage, '/'));
    };

    $resolveVisualVariant = function ($product, int $index): string {
        $context = Str::lower(implode(' ', array_filter([
            $product->name ?? null,
            $product->category_name ?? null,
            $product->subcategory_name ?? null,
            $product->brand ?? null,
        ])));

        return match (true) {
            Str::contains($context, ['ecg', 'monitor', 'analyzer', 'hematology']) => 'machine',
            Str::contains($context, ['glove', 'plate']) => 'tray',
            Str::contains($context, ['syringe', 'needle', 'fish']) => 'tubes',
            Str::contains($context, ['centrifuge']) => 'centrifuge',
            Str::contains($context, ['microscope', 'gene']) => 'microscope',
            Str::contains($context, ['culture', 'petri']) => 'petri',
            default => ['vials', 'tubes', 'machine', 'tray', 'microscope', 'petri'][$index % 6],
        };
    };

    $badgeSets = [
        ['In Stock', 'Verified'],
        ['Clinical Ready', null],
        ['New Arrival', null],
        ['Best Seller', null],
        ['Institutional Fav', null],
        ['Legacy Support', null],
    ];
    $badgeStyles = [
        ['bg-[#215eaa] text-white', 'bg-[#177a6b] text-white'],
        ['bg-[#2f8fff] text-white', null],
        ['bg-[#4f86dc] text-white', null],
        ['bg-slate-900 text-white', null],
        ['bg-indigo-500 text-white', null],
        ['bg-white/90 text-slate-700', null],
    ];
@endphp

<div class="full-bleed -mt-6 bg-[#f4f6f8] [font-family:Inter,system-ui,sans-serif] md:-mt-8">
    <div class="catalog-premium-stage">
        <div id="catalogMobileBackdrop" class="catalog-mobile-backdrop" aria-hidden="true"></div>
        <div id="catalogLoadingOverlay" class="catalog-loading-overlay" aria-hidden="true">
            <div class="catalog-loading-card">
                <div class="catalog-spinner-row">
                    <span class="catalog-spinner" aria-hidden="true"></span>
                    <p class="text-[14px] font-semibold text-slate-900">Updating results</p>
                </div>
                <p class="mt-3 text-[13px] leading-6 text-slate-500">Applying filters and refreshing product availability. This takes a moment.</p>
            </div>
        </div>
        <div id="uiToastHost" class="ui-toast-host" aria-live="polite" aria-atomic="true"></div>
        <form id="catalogFiltersForm" method="GET" action="{{ route('products.index') }}" class="space-y-5 md:space-y-6">
            <section class="catalog-premium-section pt-2 md:pt-3">
                <div class="catalog-premium-hero">
                    <div class="flex flex-wrap items-center gap-2 text-[12px] font-medium text-slate-400">
                        <a href="{{ route('home') }}" class="text-inherit no-underline hover:text-slate-700">Home</a>
                        <span>/</span>
                        <span>Catalog</span>
                        <span>/</span>
                        <span class="text-slate-700">{{ $selectedCategories->first() ?: ($selectedApplications->first() ?: 'IVD Kits & Reagents') }}</span>
                    </div>

                    <div class="catalog-premium-toolbar mt-4">
                        <div class="catalog-premium-heading">
                            <h1 class="text-[36px] font-bold tracking-[-0.04em] text-slate-950">Product Catalog</h1>
                            <p class="mt-2 max-w-[760px] text-[14px] leading-7 text-slate-500">
                                Explore our comprehensive range of high-performance biotech reagents, IVD kits, and life science research tools engineered for precision.
                            </p>
                        </div>

                        <div class="catalog-premium-controls">
                            <label class="catalog-premium-search">
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                </svg>
                                <input
                                    type="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search by SKU, product name, application, or brand..."
                                    aria-label="Search products"
                                >
                                <button type="submit">Search</button>
                            </label>

                            <div class="catalog-premium-sort">
                                <span class="text-[13px] font-medium text-slate-400">Sort by:</span>
                                <select id="catalogSort" name="sort" class="border-0 bg-transparent pr-6 text-[13px] font-semibold text-slate-800 outline-none">
                                    <option value="relevant" @selected($sort === 'relevant')>Most Relevant</option>
                                    <option value="name_az" @selected($sort === 'name_az')>Name A-Z</option>
                                    <option value="price_low" @selected($sort === 'price_low')>Price Low to High</option>
                                    <option value="price_high" @selected($sort === 'price_high')>Price High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="catalog-premium-layout pb-5 md:pb-7">
                <aside class="catalog-premium-sidebar space-y-7 rounded-[28px] border border-white/70 bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between gap-3 xl:hidden">
                        <div>
                            <p class="text-[14px] font-semibold text-slate-900">Filters</p>
                            <p class="mt-1 text-[12px] font-medium text-slate-500">Select multiple filters, then apply.</p>
                        </div>
                        <button type="button" id="catalogFiltersClose" class="catalog-toolbar-btn" aria-label="Close filters">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                            Close
                        </button>
                    </div>

                    @if ($activeFilterCount > 0)
                        <div class="flex items-center justify-between rounded-2xl bg-blue-50 px-4 py-3">
                            <span class="text-[13px] font-medium text-blue-700">{{ $activeFilterCount }} filters applied</span>
                            <a href="{{ route('products.index') }}" class="text-[13px] font-semibold text-[#2383eb] no-underline hover:text-[#1570c9]">Clear</a>
                        </div>
                    @endif

                    <details class="catalog-filter-group" open>
                        <summary class="catalog-filter-summary">
                            <span class="catalog-filter-summary-title">Category</span>
                            <span class="catalog-filter-summary-actions">
                                @if ($selectedCategories->isNotEmpty())
                                    <a href="{{ $dropQueryKey('category_name') }}" class="catalog-filter-clear">Clear</a>
                                @endif
                                <svg class="catalog-filter-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="mt-4 space-y-3">
                            @foreach ($categoryOptions as $label => $count)
                                <label class="flex items-center justify-between gap-3 text-[14px] text-slate-800">
                                    <span class="flex items-center gap-3">
                                        <input class="catalog-auto h-4 w-4 rounded border-slate-300 text-[#2383eb]" type="checkbox" name="category_name[]" value="{{ $label }}" @checked($selectedCategories->contains($label))>
                                        <span>{{ $label }}</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400">{{ $count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    <details class="catalog-filter-group" open>
                        <summary class="catalog-filter-summary">
                            <span class="catalog-filter-summary-title">Application</span>
                            <span class="catalog-filter-summary-actions">
                                @if ($selectedApplications->isNotEmpty())
                                    <a href="{{ $dropQueryKeys(['application_name', 'subcategory_name']) }}" class="catalog-filter-clear">Clear</a>
                                @endif
                                <svg class="catalog-filter-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="mt-4 space-y-3">
                            @foreach ($applicationOptions as $label => $count)
                                <label class="flex items-center justify-between gap-3 text-[14px] text-slate-800">
                                    <span class="flex items-center gap-3">
                                        <input class="catalog-auto h-4 w-4 rounded border-slate-300 text-[#2383eb]" type="checkbox" name="application_name[]" value="{{ $label }}" @checked($selectedApplications->contains($label))>
                                        <span>{{ $label }}</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400">{{ $count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    <details class="catalog-filter-group" open>
                        <summary class="catalog-filter-summary">
                            <span class="catalog-filter-summary-title">Brand</span>
                            <span class="catalog-filter-summary-actions">
                                @if ($selectedBrands->isNotEmpty())
                                    <a href="{{ $dropQueryKey('brand_name') }}" class="catalog-filter-clear">Clear</a>
                                @endif
                                <svg class="catalog-filter-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="mt-4 space-y-3">
                            @foreach ($brandOptions as $label => $count)
                                <label class="flex items-center justify-between gap-3 text-[14px] text-slate-800">
                                    <span class="flex items-center gap-3">
                                        <input class="catalog-auto h-4 w-4 rounded border-slate-300 text-[#2383eb]" type="checkbox" name="brand_name[]" value="{{ $label }}" @checked($selectedBrands->contains($label))>
                                        <span>{{ $label }}</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400">{{ $count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </details>

                    <details class="catalog-filter-group" open>
                        <summary class="catalog-filter-summary">
                            <span class="catalog-filter-summary-title">Price Range</span>
                            <span class="catalog-filter-summary-actions">
                                <span id="catalogPriceLabel" class="text-[12px] font-semibold text-[#2383eb]">{{ $formatInr($selectedMaxPrice, 0) }}</span>
                                @if ($selectedMaxPrice < $maxPrice)
                                    <a href="{{ $dropQueryKey('max_price') }}" class="catalog-filter-clear">Clear</a>
                                @endif
                                <svg class="catalog-filter-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="mt-4 px-1">
                            <input
                                id="catalogPriceRange"
                                type="range"
                                name="max_price"
                                min="{{ $minPrice }}"
                                max="{{ $maxPrice }}"
                                step="1"
                                value="{{ $selectedMaxPrice }}"
                                class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-300 accent-[#2383eb]"
                            >
                            <div class="mt-4 flex items-center justify-between text-[12px] font-semibold text-slate-700">
                                <span>{{ $formatInr($minPrice, 0) }}</span>
                                <span>{{ $formatInr($maxPrice, 0) }}</span>
                            </div>
                        </div>
                    </details>

                    <div class="catalog-mobile-apply xl:hidden">
                        <button type="submit">Apply Filters</button>
                        <a href="{{ route('products.index') }}">Clear All</a>
                    </div>
                </aside>

                <div class="catalog-premium-results space-y-6">
                    <div class="catalog-premium-results-header">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-sm">
                                <p class="text-[13px] font-medium text-slate-500">{{ $products->total() }} products available</p>
                            </div>
                            @if ($search !== '')
                                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 shadow-sm">
                                    <p class="text-[13px] font-medium text-blue-700">Search: <span class="font-semibold">{{ $search }}</span></p>
                                </div>
                            @endif
                            @if ($activeFilterCount > 0)
                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                    <p class="text-[13px] font-medium text-slate-500">{{ $activeFilterCount }} active filters</p>
                                </div>
                            @endif
                        </div>

                        <div class="catalog-toolbar-actions">
                            <button type="button" id="catalogFiltersOpen" class="catalog-toolbar-btn xl:hidden" aria-label="Open filters">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 6h16"></path>
                                    <path d="M7 12h10"></path>
                                    <path d="M10 18h4"></path>
                                </svg>
                                Filters
                            </button>

                            @if ($search !== '' || $activeFilterCount > 0)
                                <a href="{{ route('products.index') }}" class="inline-flex h-11 items-center justify-center rounded-[14px] border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 no-underline shadow-sm transition hover:border-[#2383eb] hover:text-[#2383eb]">
                                    Reset Catalog
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($search !== '' || $selectedMaxPrice < $maxPrice || $selectedCategories->isNotEmpty() || $selectedApplications->isNotEmpty() || $selectedBrands->isNotEmpty())
                        <div class="catalog-active-chips">
                            @if ($search !== '')
                                <a href="{{ $dropQueryKeys(['search', 'search_text', 'search_value']) }}" class="catalog-chip" title="Remove search filter">
                                    <strong>Search</strong>: {{ Str::limit($search, 26) }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                                </a>
                            @endif

                            @if ($selectedMaxPrice < $maxPrice)
                                <a href="{{ $dropQueryKey('max_price') }}" class="catalog-chip" title="Remove price filter">
                                    <strong>Up to</strong> {{ $formatInr($selectedMaxPrice, 0) }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                                </a>
                            @endif

                            @foreach ($selectedCategories as $label)
                                <a href="{{ $removeQueryArrayValue('category_name', $label) }}" class="catalog-chip" title="Remove category filter">
                                    <strong>Category</strong>: {{ $label }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                                </a>
                            @endforeach

                            @foreach ($selectedApplications as $label)
                                <a href="{{ $removeQueryArrayValueFromKeys(['application_name', 'subcategory_name'], $label) }}" class="catalog-chip" title="Remove application filter">
                                    <strong>Application</strong>: {{ $label }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                                </a>
                            @endforeach

                            @foreach ($selectedBrands as $label)
                                <a href="{{ $removeQueryArrayValue('brand_name', $label) }}" class="catalog-chip" title="Remove brand filter">
                                    <strong>Brand</strong>: {{ $label }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                                </a>
                            @endforeach

                            <a href="{{ route('products.index') }}" class="catalog-chip catalog-chip--clear" title="Clear all filters">
                                Clear all
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                            </a>
                        </div>
                    @endif

                    @if ($productCollection->isEmpty())
                        <div class="rounded-[28px] border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
                            <h2 class="text-[26px] font-bold tracking-[-0.03em] text-slate-950">No products matched these filters</h2>
                            <p class="mt-3 text-[15px] leading-7 text-slate-500">Remove one or more filters to see more catalog items.</p>
                            <a href="{{ route('products.index') }}" class="mt-8 inline-flex h-12 items-center justify-center rounded-[14px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] px-6 text-[14px] font-semibold text-white no-underline shadow-[0_16px_30px_rgba(35,131,235,0.22)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_36px_rgba(35,131,235,0.28)]">
                                Clear Filters
                            </a>
                        </div>
                    @else
                        <div class="catalog-premium-grid">
                            @foreach ($productCollection as $product)
                                @php
                                    $badgeRow = $badgeSets[$loop->index % count($badgeSets)];
                                    $badgeStyleRow = $badgeStyles[$loop->index % count($badgeStyles)];
                                    $price = $product->visible_price !== null ? (float) $product->visible_price : null;
                                    $listPrice = $price !== null ? round($price * 1.16, 2) : null;
                                $detailUrl = route('products.productDetails', $product->id);
                                    $variantId = $product->visible_variant_id ?? null;
                                    $imageUrl = $resolveImageUrl($product);
                                    $visualVariant = $resolveVisualVariant($product, $loop->index);
                                    $ratingValue = number_format(4.6 + (($loop->index % 3) * 0.1), 1);
                                    $reviewTotal = 42 + ($loop->index * 7);
                                    $inStock = (bool) ($product->is_active ?? true);
                                    $stockText = $inStock ? 'In Stock' : 'Limited Availability';
                                @endphp
                                <article class="group flex h-full flex-col overflow-hidden rounded-[22px] border border-white/80 bg-white shadow-[0_18px_42px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_52px_rgba(15,23,42,0.12)]">
                                    <div class="relative px-4 pt-4">
                                        <div class="overflow-hidden rounded-[16px]">
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-[220px] w-full object-cover transition duration-300 group-hover:scale-[1.04]" loading="lazy" decoding="async">
                                            @else
                                                @include('customer.partials.product-visual', ['variant' => $visualVariant, 'class' => 'h-[220px] rounded-[16px] transition duration-300 group-hover:scale-[1.04]'])
                                            @endif
                                        </div>
                                        <div class="absolute left-6 top-6 flex flex-col gap-2">
                                            @foreach ($badgeRow as $badgeIndex => $badgeLabel)
                                                @if ($badgeLabel)
                                                    <span class="{{ $badgeStyleRow[$badgeIndex] }} rounded-[6px] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] shadow-sm">
                                                        {{ $badgeLabel }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex flex-1 flex-col space-y-4 px-4 pb-4 pt-5">
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $product->brand ?? 'Biogenix' }}</p>
                                            <h3 class="catalog-card-title text-[16px] font-semibold leading-[1.38] text-slate-950">{{ Str::limit((string) $product->name, 58) }}</h3>
                                            <p class="text-[12px] text-slate-400">SKU: {{ $product->visible_variant_sku ?? $product->sku ?? 'N/A' }}</p>
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

                                        <div class="catalog-card-price rounded-[14px] bg-[#fbfcfd] px-3 py-3">
                                            <div class="flex items-center justify-between text-[11px] text-slate-400">
                                                <span class="uppercase tracking-[0.08em]">List MRP:</span>
                                                <span class="font-medium line-through">{{ $listPrice !== null ? $formatInr($listPrice) : 'N/A' }}</span>
                                            </div>
                                            <div class="mt-2 flex items-end justify-between gap-3">
                                                <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#2383eb]">Your Price:</span>
                                                <span class="text-[18px] font-extrabold tracking-[-0.03em] text-[#2383eb]">{{ $formatInr($price) }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 text-[12px] text-slate-700">
                                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M7 8h10M7 12h10M7 16h6"></path>
                                                <path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8l-5 0V6a2 2 0 0 1 2-2Z"></path>
                                            </svg>
                                            <span>MOQ: {{ $product->visible_variant_name ?? ($product->brand ? '1 Unit' : '5 Units') }}</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 text-[12px] font-medium text-slate-600">
                                            <span class="catalog-stock {{ $inStock ? 'catalog-stock--in' : 'catalog-stock--limited' }}">
                                                <span class="catalog-stock-dot {{ $inStock ? 'catalog-stock-dot--in' : 'catalog-stock-dot--limited' }}"></span>
                                                {{ $stockText }}
                                            </span>
                                            <span class="catalog-ships">
                                                Ships 24-48h
                                            </span>
                                        </div>

                                        <div class="catalog-card-discount rounded-[14px] border border-slate-200 bg-[#fbfcfd] px-3 py-3">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-[#2383eb]">Bulk Discounts Available</p>
                                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-500">
                                                <span>5+ units: 5% off</span>
                                                <span>10+ units: 12% off</span>
                                            </div>
                                        </div>

                                        <div class="catalog-card-actions">
                                            <a href="{{ $detailUrl }}" class="catalog-card-action catalog-card-action--secondary">
                                                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>View Details</span>
                                            </a>
                                            @guest
                                                <a href="{{ route('login') }}" class="catalog-card-action catalog-card-action--primary js-add-to-cart" data-product-id="{{ $product->id }}" data-variant-id="{{ $variantId ?? '' }}" data-quantity="1" data-product-name="{{ e((string) ($product->name ?? '')) }}" data-unit-price="{{ $price }}" data-model="{{ $product->visible_variant_sku ?? $product->sku ?? 'N/A' }}" data-image="{{ $imageUrl }}">
                                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="9" cy="20" r="1"></circle>
                                                        <circle cx="18" cy="20" r="1"></circle>
                                                        <path d="M3 4h2l2.4 10.5a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L21 7H7"></path>
                                                    </svg>
                                                    <span>Add to Cart</span>
                                                </a>
                                            @else
                                                <button type="button" class="catalog-card-action catalog-card-action--primary js-add-to-cart" data-product-id="{{ $product->id }}" data-variant-id="{{ $variantId ?? '' }}" data-quantity="1" data-product-name="{{ e((string) ($product->name ?? '')) }}" data-unit-price="{{ $price }}" data-model="{{ $product->visible_variant_sku ?? $product->sku ?? 'N/A' }}" data-image="{{ $imageUrl }}">
                                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="9" cy="20" r="1"></circle>
                                                        <circle cx="18" cy="20" r="1"></circle>
                                                        <path d="M3 4h2l2.4 10.5a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L21 7H7"></path>
                                                    </svg>
                                                    <span>Add to Cart</span>
                                                </button>
                                            @endguest
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    @if ($products->hasPages())
                        <div class="flex items-center justify-center gap-2 pt-4">
                            <a href="{{ $products->previousPageUrl() ?: '#' }}" class="flex h-10 w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white text-slate-600 no-underline {{ $products->onFirstPage() ? 'pointer-events-none opacity-40' : 'hover:bg-slate-50' }}">&lt;</a>
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $pageNumber => $pageUrl)
                                <a href="{{ $pageUrl }}" class="flex h-10 min-w-10 items-center justify-center rounded-[10px] px-3 text-[14px] font-semibold no-underline {{ $pageNumber === $products->currentPage() ? 'bg-[#2383eb] text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                                    {{ $pageNumber }}
                                </a>
                            @endforeach
                            <a href="{{ $products->nextPageUrl() ?: '#' }}" class="flex h-10 w-10 items-center justify-center rounded-[10px] border border-slate-200 bg-white text-slate-600 no-underline {{ $products->hasMorePages() ? 'hover:bg-slate-50' : 'pointer-events-none opacity-40' }}">&gt;</a>
                        </div>
                    @endif
                </div>
            </section>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.documentElement;
            const form = document.getElementById('catalogFiltersForm');
            const range = document.getElementById('catalogPriceRange');
            const label = document.getElementById('catalogPriceLabel');
            const sort = document.getElementById('catalogSort');
            const openFilters = document.getElementById('catalogFiltersOpen');
            const closeFilters = document.getElementById('catalogFiltersClose');
            const backdrop = document.getElementById('catalogMobileBackdrop');

            const isDesktop = function () {
                return window.matchMedia && window.matchMedia('(min-width: 1280px)').matches;
            };

            const setMobileFiltersOpen = function (open) {
                root.classList.toggle('catalog-mobile-open', Boolean(open));
                document.body.style.overflow = open ? 'hidden' : '';
            };

            if (openFilters) {
                openFilters.addEventListener('click', function () {
                    setMobileFiltersOpen(true);
                });
            }

            if (closeFilters) {
                closeFilters.addEventListener('click', function () {
                    setMobileFiltersOpen(false);
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    setMobileFiltersOpen(false);
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setMobileFiltersOpen(false);
                }
            });

            const formatInr = function (value) {
                const stringValue = String(Math.round(Number(value)));
                if (stringValue.length <= 3) {
                    return 'Rs. ' + stringValue;
                }

                const lastThree = stringValue.slice(-3);
                const rest = stringValue.slice(0, -3).replace(/\B(?=(\d{2})+(?!\d))/g, ',');
                return 'Rs. ' + rest + ',' + lastThree;
            };

            if (range && label) {
                range.addEventListener('input', function () {
                    label.textContent = formatInr(range.value);
                });

                range.addEventListener('change', function () {
                    if (form && isDesktop()) {
                        form.submit();
                    }
                });
            }

            if (sort) {
                sort.addEventListener('change', function () {
                    if (form) {
                        form.submit();
                    }
                });
            }

            document.querySelectorAll('.catalog-auto').forEach(function (input) {
                input.addEventListener('change', function () {
                    if (form && isDesktop()) {
                        form.submit();
                    }
                });
            });

            if (form) {
                form.addEventListener('submit', function () {
                    setMobileFiltersOpen(false);
                    root.classList.add('catalog-loading');
                });
            }

            const toastHost = document.getElementById('uiToastHost');
            const cartItemsUrl = @json(route('cart.items.store'));
            const loginUrl = @json(route('login'));
            const cartPageUrl = @json(route('cart.page'));
            const csrfToken = @json(csrf_token());
            const isAuthenticated = @json(auth()->check());

            const showToast = function (options) {
                if (!toastHost) {
                    return;
                }

                const title = String(options && options.title ? options.title : 'Update');
                const message = String(options && options.message ? options.message : '');
                const variant = String(options && options.variant ? options.variant : 'info');
                const primaryAction = options && options.primary ? options.primary : null;

                const toast = document.createElement('div');
                toast.className = 'ui-toast';

                const icon = document.createElement('div');
                icon.className = variant === 'warn' ? 'ui-toast__icon ui-toast__icon--warn' : 'ui-toast__icon';
                icon.innerHTML = variant === 'warn'
                    ? '<svg width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M12 9v4\"></path><path d=\"M12 17h.01\"></path><path d=\"M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z\"></path></svg>'
                    : '<svg width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M20 6 9 17l-5-5\"></path></svg>';

                const body = document.createElement('div');
                body.className = 'ui-toast__body';

                const titleEl = document.createElement('div');
                titleEl.className = 'ui-toast__title';
                titleEl.textContent = title;

                const messageEl = document.createElement('div');
                messageEl.className = 'ui-toast__message';
                messageEl.textContent = message;

                body.appendChild(titleEl);
                if (message) {
                    body.appendChild(messageEl);
                }

                const actions = document.createElement('div');
                actions.className = 'ui-toast__actions';

                if (primaryAction && primaryAction.href && primaryAction.label) {
                    const primary = document.createElement('a');
                    primary.className = 'ui-toast__btn ui-toast__btn--primary';
                    primary.href = String(primaryAction.href);
                    primary.textContent = String(primaryAction.label);
                    actions.appendChild(primary);
                }

                const dismiss = document.createElement('button');
                dismiss.type = 'button';
                dismiss.className = 'ui-toast__btn';
                dismiss.textContent = 'Dismiss';
                dismiss.addEventListener('click', function () {
                    toast.remove();
                });
                actions.appendChild(dismiss);

                toast.appendChild(icon);
                toast.appendChild(body);
                toast.appendChild(actions);
                toastHost.appendChild(toast);

                window.setTimeout(function () {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px) scale(0.98)';
                    window.setTimeout(function () {
                        toast.remove();
                    }, 220);
                }, Number(options && options.duration ? options.duration : 4200));
            };

            const addToCart = async function (payload) {
                const response = await fetch(cartItemsUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const contentType = String(response.headers.get('content-type') || '');
                if (response.redirected || !contentType.includes('application/json')) {
                    throw { type: 'auth' };
                }

                const data = await response.json().catch(function () {
                    return null;
                });

                if (!response.ok) {
                    throw { type: 'error', message: data && data.message ? data.message : 'Unable to add product to cart.' };
                }

                return data;
            };

            const syncLocalCart = function (target, quantity) {
                if (!window.CartStore) {
                    return;
                }

                const productId = Number(target.dataset.productId || 0);
                const variantIdRaw = String(target.dataset.variantId || '').trim();
                const variantId = variantIdRaw ? Number(variantIdRaw) : null;
                const productName = String(target.dataset.productName || 'Product');

                window.CartStore.addItem({
                    productId: productId,
                    variantId: variantId || null,
                    quantity: quantity,
                    unitPrice: Number(target.dataset.unitPrice || 0),
                    name: productName,
                    model: target.dataset.model || '',
                    image: target.dataset.image || '',
                });
            };

            document.querySelectorAll('.js-add-to-cart').forEach(function (button) {
                button.addEventListener('click', async function (event) {
                    const target = event.currentTarget;
                    const quantity = Math.max(1, Number(target.dataset.quantity || 1));
                    const productName = String(target.dataset.productName || 'Product');

                    event.preventDefault();

                    if (!isAuthenticated) {
                        syncLocalCart(target, quantity);
                        showToast({
                            title: 'Added to cart',
                            message: productName + ' was added to your cart. Login will be required only during checkout.',
                            variant: 'info',
                            primary: { label: 'View Cart', href: cartPageUrl },
                        });
                        return;
                    }

                    if (target.dataset.loading === '1') {
                        return;
                    }

                    const productId = Number(target.dataset.productId || 0);
                    const variantIdRaw = String(target.dataset.variantId || '').trim();
                    const variantId = variantIdRaw ? Number(variantIdRaw) : null;

                    target.dataset.loading = '1';
                    target.setAttribute('aria-busy', 'true');
                    target.classList.add('opacity-80');

                    try {
                        await addToCart({
                            product_id: productId,
                            product_variant_id: variantId,
                            quantity: quantity,
                        });

                        syncLocalCart(target, quantity);

                        showToast({
                            title: 'Added to cart',
                            message: productName + ' was added to your cart.',
                            variant: 'info',
                            primary: { label: 'View Cart', href: cartPageUrl },
                        });
                    } catch (error) {
                        const isAuthError = error && error.type === 'auth';
                        showToast({
                            title: isAuthError ? 'Login required' : 'Could not add to cart',
                            message: isAuthError ? 'Please login again to continue.' : String(error && error.message ? error.message : 'Please try again.'),
                            variant: 'warn',
                            primary: isAuthError ? { label: 'Login', href: loginUrl } : null,
                        });
                    } finally {
                        delete target.dataset.loading;
                        target.removeAttribute('aria-busy');
                        target.classList.remove('opacity-80');
                    }
                });
            });
        });
    </script>
@endpush

