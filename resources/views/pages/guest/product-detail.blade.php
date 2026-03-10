@php
    $rawImage = $product->image_path ?? $product->image ?? null;
    $imageUrl = filled($rawImage)
        ? (\Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://', '/'])
            ? $rawImage
            : (\Illuminate\Support\Str::startsWith($rawImage, 'images/')
                ? asset($rawImage)
                : asset('storage/' . ltrim($rawImage, '/'))))
        : asset('images/logo.jpg');

    $mrpAmount = $product->visible_price;
    $mrpCurrency = $product->visible_currency ?? 'INR';

    $brochure = $product->brochure_path ?? $product->brochure_url ?? null;
    $brochureUrl = filled($brochure)
        ? (\Illuminate\Support\Str::startsWith($brochure, ['http://', 'https://', '/']) ? $brochure : asset('storage/' . ltrim($brochure, '/')))
        : null;
@endphp

<div class="page-shell">
    <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:underline">Products</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Product Details</span>
        <span>/</span>
        @auth
            <a href="{{ route('proforma.create', ['product_id' => $product->id]) }}" class="hover:underline">Order</a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="hover:underline">Order</a>
        @endguest
    </nav>

    <div>
        <a href="{{ route('products.index') }}" class="btn secondary">&larr; Back to Products</a>
    </div>

    <section class="detail-grid">
        <div class="detail-media space-y-4">
            <div class="saas-card">
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-72 w-full rounded-xl object-cover md:h-96" loading="lazy" decoding="async">
            </div>
            <div class="grid grid-cols-3 gap-3">
                @for ($i = 0; $i < 3; $i++)
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }} thumbnail {{ $i + 1 }}" class="h-20 w-full rounded-xl border border-slate-200 object-cover" loading="lazy" decoding="async">
                @endfor
            </div>
        </div>

        <div class="detail-main">
            <x-ui.surface-card>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ $product->category_name ?? 'Diagnostics Product' }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900 md:text-3xl">{{ $product->name ?? 'Product Details' }}</h1>
                <p class="mt-3 text-sm text-slate-600">{{ $product->description ?: 'Detailed product description will be available soon.' }}</p>

                <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 p-4">
                    <p class="text-sm font-semibold text-blue-900">
                        MRP:
                        @if ($mrpAmount !== null)
                            {{ $mrpCurrency }} {{ number_format((float) $mrpAmount, 2) }}
                        @else
                            Not available
                        @endif
                    </p>
                    @guest
                        <p class="mt-1 text-xs text-blue-700">Login to view customer pricing and discounts.</p>
                    @endguest
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    @guest
                        <x-ui.action-link :href="route('login')">Login to see pricing</x-ui.action-link>
                        <x-ui.action-link :href="route('proforma.create', ['product_id' => $product->id])" variant="secondary">Generate Quote</x-ui.action-link>
                        <x-ui.action-link :href="route('login')">Order Now</x-ui.action-link>
                    @endguest
                    @auth
                        <x-ui.action-link :href="route('proforma.create', ['product_id' => $product->id])" variant="secondary">Generate Quote</x-ui.action-link>
                        <x-ui.action-link :href="route('proforma.create', ['product_id' => $product->id])">Order Now</x-ui.action-link>
                    @endauth
                    @if ($brochureUrl)
                        <x-ui.action-link :href="$brochureUrl" variant="secondary" target="_blank" rel="noopener">Download Brochure</x-ui.action-link>
                    @endif
                </div>
                @guest
                    <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <p class="text-sm text-gray-700">Login to access this feature.</p>
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login</a>
                    </div>
                @endguest
            </x-ui.surface-card>

            <x-ui.surface-card title="Technical Specifications" subtitle="Core technical and commercial identifiers.">
                <div class="table-container spec-table">
                    <div class="spec-table-row">
                        <div class="spec-table-key">SKU</div>
                        <div class="spec-table-value">{{ $product->visible_variant_sku ?? $product->sku ?? 'N/A' }}</div>
                    </div>
                    <div class="spec-table-row">
                        <div class="spec-table-key">Variant</div>
                        <div class="spec-table-value">{{ $product->visible_variant_name ?? 'Default Variant' }}</div>
                    </div>
                    <div class="spec-table-row">
                        <div class="spec-table-key">Price Type</div>
                        <div class="spec-table-value">{{ strtoupper($product->visible_price_type ?? 'MRP') }}</div>
                    </div>
                    <div class="spec-table-row">
                        <div class="spec-table-key">GST Rate</div>
                        <div class="spec-table-value">{{ number_format((float) ($gst_rate ?? 0), 2) }}%</div>
                    </div>
                </div>
            </x-ui.surface-card>

            <x-ui.surface-card title="Short Video" subtitle="Quick walkthrough and product overview.">
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <iframe class="h-60 w-full md:h-72" src="https://www.youtube.com/embed/M7lc1UVf-VE" title="Product Overview Video" allowfullscreen></iframe>
                </div>
            </x-ui.surface-card>
        </div>
    </section>
</div>
