@php
    $productCollection = $products instanceof \Illuminate\Contracts\Pagination\Paginator
        ? collect($products->items())
        : collect($products ?? []);

    $categoryOptions = $productCollection
        ->map(function ($item) {
            $id = $item->category_id ?? ($item->category_name ?? null);
            $name = $item->category_name ?? ($item->category ?? null);

            return ['id' => $id, 'name' => $name];
        })
        ->filter(fn ($item) => filled($item['id']) && filled($item['name']))
        ->unique('id')
        ->values();

    $applications = $productCollection->pluck('application')->filter()->unique()->values();
    $brands = $productCollection->pluck('brand')->filter()->unique()->values();
@endphp

<div class="page-shell catalog-page">
    <section class="section-stack catalog-toolbar">
        <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span>/</span>
            <span class="font-semibold text-slate-700">Products &amp; Solutions</span>
        </nav>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 p-5 text-white shadow-xl md:p-7">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <x-badge variant="info" class="!border-white/30 !bg-white/10 !text-blue-100">Catalog</x-badge>
                    <h1 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Products &amp; Solutions</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-100 md:text-base">Browse product categories, compare MRP values, and move to details or quotation in one streamlined flow.</p>
                </div>
                <div class="lg:col-span-4">
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-100">Smart Filtering</p>
                        <p class="mt-2 text-sm text-slate-100">Use category, application, and brand filters together for faster product discovery.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="saas-card catalog-filter-card space-y-4">
            <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">
                <div class="xl:col-span-2">
                    <label for="search_text" class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                    <input id="search_text" name="search_text" class="form-control" value="{{ request('search_text', request('search')) }}" placeholder="Search by product name or SKU">
                </div>

                <div>
                    <label for="category_id" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">All categories</option>
                        @foreach ($categoryOptions as $category)
                            <option value="{{ $category['id'] }}" @selected((string) request('category_id') === (string) $category['id'])>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="application" class="mb-1 block text-sm font-medium text-slate-700">Application</label>
                    <select id="application" name="application" class="form-control">
                        <option value="">All applications</option>
                        @foreach ($applications as $application)
                            <option value="{{ $application }}" @selected(request('application') === $application)>{{ $application }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="brand" class="mb-1 block text-sm font-medium text-slate-700">Brand</label>
                    <select id="brand" name="brand" class="form-control">
                        <option value="">All brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="xl:col-span-5 flex flex-wrap items-center gap-2">
                    <button class="btn btn-primary" type="submit">Apply Filters</button>
                    <a href="{{ route('products.index') }}" class="btn secondary">Reset</a>
                </div>
            </form>

            <div class="flex flex-wrap gap-2">
                @foreach (['IVD Kits', 'Reagents', 'Instruments', 'Consumables'] as $chip)
                    <span class="chip-filter">{{ $chip }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-stack catalog-results">
        <x-ui.section-heading title="Catalog" subtitle="Guest view shows MRP only. Login for account-specific pricing." />
        @guest
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <p class="text-sm text-gray-700">Login to access personalized pricing and ordering features.</p>
                <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login Now</a>
            </div>
        @endguest

        @if ($productCollection->isEmpty())
            <div class="saas-card space-y-3 text-sm text-slate-600">
                <p>No products are currently visible for this context.</p>
                @if (request()->filled('category'))
                    <p class="text-xs text-slate-500">The old category link format is unsupported. Use the updated catalog filter flow below.</p>
                    <a href="{{ route('products.index') }}" class="btn secondary">View All Products</a>
                @endif
            </div>
        @else
            <div id="catalogGrid" class="catalog-grid">
                @foreach ($productCollection as $product)
                    @php
                        $rawImage = $product->image_path ?? $product->image ?? null;
                        $imageUrl = filled($rawImage)
                            ? (\Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://', '/'])
                                ? $rawImage
                                : (\Illuminate\Support\Str::startsWith($rawImage, 'images/')
                                    ? asset($rawImage)
                                    : asset('storage/' . ltrim($rawImage, '/'))))
                            : asset('images/logo.jpg');
                        $quoteUrl = route('proforma.create', ['product_id' => $product->id]);
                        $orderUrl = auth()->check() ? $quoteUrl : route('login');
                    @endphp

                    <article class="catalog-card group" data-catalog-card data-category="{{ strtolower($product->category_name ?? 'general') }}">
                        <div class="relative">
                            <div class="catalog-card-media">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                            </div>
                            <div class="pointer-events-none absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-900/70 to-transparent p-3 opacity-0 transition group-hover:opacity-100">
                                <div class="pointer-events-auto flex gap-2">
                                    <x-ui.action-link :href="route('products.productDetails', $product->id)" variant="secondary" class="!border-white/70 !bg-white !text-slate-900">View Details</x-ui.action-link>
                                    <x-ui.action-link :href="$quoteUrl">Generate Quote</x-ui.action-link>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 p-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $product->category_name ?? 'General' }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900">{{ $product->name }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $product->description ?: 'Product details available on the detail page.' }}</p>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-blue-700">
                                    MRP:
                                    @if ($product->visible_price !== null)
                                        {{ $product->visible_currency ?? 'INR' }} {{ number_format((float) $product->visible_price, 2) }}
                                    @else
                                        Not available
                                    @endif
                                </p>
                                <x-ui.action-link :href="route('products.productDetails', $product->id)" variant="secondary">View</x-ui.action-link>
                            </div>
                            @guest
                                <p class="text-xs text-slate-500">Login to view customer pricing and discounts.</p>
                            @endguest

                            <div class="flex flex-wrap items-center gap-2">
                                <x-ui.action-link :href="$quoteUrl" variant="secondary">Generate Quote</x-ui.action-link>
                                <x-ui.action-link :href="$orderUrl">Order Now</x-ui.action-link>
                            </div>

                            <label class="flex items-center gap-2 text-xs text-slate-600">
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 compare-toggle"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-category="{{ $product->category_name ?? 'General' }}"
                                    data-product-mrp="{{ $product->visible_currency ?? 'INR' }} {{ number_format((float) ($product->visible_price ?? 0), 2) }}"
                                    data-product-description="{{ $product->description ?: 'Product details available on the detail page.' }}"
                                >
                                Add to compare
                            </label>
                        </div>
                    </article>
                @endforeach
            </div>
            <p id="clientCatalogEmpty" class="mt-4 hidden rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                No products matched the selected category view.
            </p>
        @endif

        @if ($products instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="pagination-wrap">{{ $products->links() }}</div>
        @endif
    </section>

    <section id="compareTray" class="fixed inset-x-0 bottom-3 z-40 hidden px-3 sm:bottom-4 sm:px-4">
        <div class="mx-auto flex w-full max-w-4xl flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-xl backdrop-blur">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Compare Products</p>
                <p id="compareSummary" class="max-w-[18rem] truncate text-xs text-slate-600 sm:max-w-xl">Select up to 3 products.</p>
            </div>
            <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                <button id="clearCompareBtn" type="button" class="btn secondary btn-sm w-full sm:w-auto">Clear</button>
                <button id="openCompareBtn" type="button" class="btn btn-primary btn-sm w-full sm:w-auto">Compare Now</button>
            </div>
        </div>
    </section>

    <x-modal id="compareModal" title="Product Comparison">
        <div id="compareTableWrap" class="table-container">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Product 1</th>
                        <th>Product 2</th>
                        <th>Product 3</th>
                    </tr>
                </thead>
                <tbody id="compareTableBody"></tbody>
            </table>
        </div>
    </x-modal>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const currentUrl = new URL(window.location.href);
        const categoryParam = currentUrl.searchParams.get('category');
        if (categoryParam && !currentUrl.searchParams.get('category_name') && !currentUrl.searchParams.get('category_id')) {
            currentUrl.searchParams.delete('category');
            currentUrl.searchParams.set('category_name', categoryParam);
            window.location.replace(currentUrl.toString());
            return;
        }

        const categoryNameFilter = (currentUrl.searchParams.get('category_name') || '').trim().toLowerCase();
        const catalogCards = Array.from(document.querySelectorAll('[data-catalog-card]'));
        const clientCatalogEmpty = document.getElementById('clientCatalogEmpty');

        if (categoryNameFilter && catalogCards.length) {
            let visibleCount = 0;
            catalogCards.forEach(function (card) {
                const cardCategory = (card.getAttribute('data-category') || '').toLowerCase();
                const visible = cardCategory.includes(categoryNameFilter);
                card.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            // Fallback: if URL category_name does not map to visible card categories,
            // keep catalog visible instead of showing an empty state.
            if (visibleCount === 0) {
                catalogCards.forEach(function (card) {
                    card.classList.remove('hidden');
                });
            }

            if (clientCatalogEmpty) {
                clientCatalogEmpty.classList.add('hidden');
            }
        }

        const toggles = Array.from(document.querySelectorAll('.compare-toggle'));
        const compareTray = document.getElementById('compareTray');
        const compareSummary = document.getElementById('compareSummary');
        const openCompareBtn = document.getElementById('openCompareBtn');
        const clearCompareBtn = document.getElementById('clearCompareBtn');
        const compareModal = document.getElementById('compareModal');
        const compareTableBody = document.getElementById('compareTableBody');
        const maxItems = 3;

        function getSelected() {
            return toggles.filter(function (toggle) {
                return toggle.checked;
            });
        }

        function paintSummary() {
            const selected = getSelected();
            const names = selected.map(function (item) {
                return item.dataset.productName;
            });

            if (!selected.length) {
                compareTray.classList.add('hidden');
                compareSummary.textContent = 'Select up to 3 products.';
                return;
            }

            compareTray.classList.remove('hidden');
            compareSummary.textContent = names.join(' | ');
        }

        function enforceLimit(changedToggle) {
            const selected = getSelected();
            if (selected.length <= maxItems) return true;

            changedToggle.checked = false;
            return false;
        }

        function toCell(value) {
            return '<td class="break-words align-top">' + (value || '-') + '</td>';
        }

        function buildComparisonTable() {
            const selected = getSelected().map(function (item) {
                return {
                    name: item.dataset.productName,
                    category: item.dataset.productCategory,
                    mrp: item.dataset.productMrp,
                    description: item.dataset.productDescription
                };
            });

            const rows = [
                ['Product Name', 'name'],
                ['Category', 'category'],
                ['MRP', 'mrp'],
                ['Description', 'description']
            ];

            const html = rows.map(function (row) {
                const label = row[0];
                const key = row[1];
                return '<tr><th>' + label + '</th>' +
                    toCell(selected[0] ? selected[0][key] : '-') +
                    toCell(selected[1] ? selected[1][key] : '-') +
                    toCell(selected[2] ? selected[2][key] : '-') +
                    '</tr>';
            }).join('');

            compareTableBody.innerHTML = html;
        }

        toggles.forEach(function (toggle) {
            toggle.addEventListener('change', function () {
                if (!enforceLimit(toggle)) {
                    alert('You can compare up to 3 products only.');
                    return;
                }
                paintSummary();
            });
        });

        if (clearCompareBtn) {
            clearCompareBtn.addEventListener('click', function () {
                toggles.forEach(function (toggle) {
                    toggle.checked = false;
                });
                paintSummary();
            });
        }

        if (openCompareBtn && compareModal) {
            openCompareBtn.addEventListener('click', function () {
                const selected = getSelected();
                if (!selected.length) return;

                buildComparisonTable();
                compareModal.classList.remove('hidden');
            });
        }

        paintSummary();
    });
</script>
@endpush
