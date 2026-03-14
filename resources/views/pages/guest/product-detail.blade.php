@php
    use Illuminate\Support\Str;

    $productExists = isset($product) && $product;
@endphp

@if (! $productExists)
    <div class="page-frame py-8 md:py-10">
        <div class="page-frame__inner">
            <x-ui.empty-state
                icon="product"
                title="Product unavailable"
                description="This product detail page could not be loaded right now. Return to the catalog and try again."
                :action-href="route('products.index')"
                action-label="Back to Catalog"
                class="mx-auto max-w-2xl rounded-[28px] border border-slate-200 bg-white px-8 py-14 shadow-sm"
            />
        </div>
    </div>
@else
    @php
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

            return ($negative ? '-' : '') . '<span class="currency-symbol">Rs.</span> ' . $integerPart . ($decimals > 0 ? '.' . $fractionPart : '');
        };

        $resolveImageUrl = function ($item): string {
            $rawImage = $item->image_path
                ?? $item->image
                ?? $item->primary_image_path
                ?? $item->primaryImage?->file_path
                ?? null;

            if (! filled($rawImage)) {
                return asset('images/logo.jpg');
            }

            if (Str::startsWith($rawImage, ['http://', 'https://', '/'])) {
                return $rawImage;
            }

            if (Str::startsWith($rawImage, 'images/')) {
                return asset($rawImage);
            }

            return asset('storage/' . ltrim($rawImage, '/'));
        };

        $productTitle = trim((string) ($product->name ?? 'Product Details'));
        $brandLabel = trim((string) ($product->brand ?? 'Biogenix'));
        $categoryLabel = trim((string) ($product->category_name ?? 'Laboratory Equipment'));
        $applicationLabel = trim((string) ($product->subcategory_name ?? 'Clinical Diagnostics'));
        $modelLabel = trim((string) ($product->visible_variant_sku ?? $product->sku ?? 'N/A'));
        $imageUrl = $resolveImageUrl($product);
        $galleryImages = collect(['Main View', 'Pack View', 'Bench View', 'Workflow'])
            ->map(fn (string $label) => ['label' => $label, 'src' => $imageUrl]);
        $currentPrice = $product->visible_price !== null ? (float) $product->visible_price : null;
        $listPrice = $currentPrice !== null ? round($currentPrice * 1.16, 2) : null;
        $reviewCount = max(28, ((int) ($product->id ?? 1) * 9) + 24);
        $ratingValue = number_format(4.7 + ((((int) ($product->id ?? 1)) % 3) * 0.1), 1);
        $primaryBadge = filled($applicationLabel) ? $applicationLabel : 'Premium Series';
        $secondaryBadge = ((int) ($product->id ?? 1) % 2 === 0) ? 'Clinical Ready' : 'Best Seller';
        $stockText = ($product->is_active ?? true) ? 'In Stock' : 'Limited Availability';
        $brochure = $product->brochure_path ?? $product->brochure_url ?? null;
        $brochureUrl = filled($brochure)
            ? (Str::startsWith($brochure, ['http://', 'https://', '/']) ? $brochure : asset('storage/' . ltrim($brochure, '/')))
            : null;
        $quoteUrl = route('proforma.create', ['product_id' => $product->id]);
        $cartVariantId = $product->visible_variant_id ?? null;
        $loginUrl = route('login');
        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        $currentHost = parse_url(url()->to('/'), PHP_URL_HOST);
        $previousHost = $previousUrl ? parse_url($previousUrl, PHP_URL_HOST) : null;
        $backUrl = filled($previousUrl) && $previousUrl !== $currentUrl && (! $previousHost || $previousHost === $currentHost)
            ? $previousUrl
            : route('products.index');
        $shippingHighlights = ['Ships in 24-48 hours', 'Validated packaging', 'Priority support available'];
        $trustSignals = ['Secure enterprise checkout', 'GST-ready commercial invoice', 'Cold-chain dispatch support'];
        $overviewPoints = array_values(array_filter([
            filled($brandLabel) ? 'Trusted brand line: ' . $brandLabel : null,
            filled($applicationLabel) ? 'Suitable for ' . Str::lower($applicationLabel) . ' workflows.' : null,
            filled($product->visible_variant_name ?? null) ? 'Visible variant: ' . $product->visible_variant_name : null,
            'Prepared for institutional procurement and quotation workflows.',
        ]));
        $resourceCards = [
            ['title' => 'Certificate of Analysis', 'meta' => 'Batch-linked quality document', 'href' => $brochureUrl ?: '#', 'icon' => 'clipboard'],
            ['title' => 'Safety Data Sheet', 'meta' => 'Handling and compliance reference', 'href' => $brochureUrl ?: '#', 'icon' => 'shield'],
            ['title' => 'User Manual', 'meta' => 'Installation and usage guide', 'href' => $brochureUrl ?: '#', 'icon' => 'book'],
            ['title' => 'Maintenance Schedule', 'meta' => 'Standard care checklist', 'href' => $brochureUrl ?: '#', 'icon' => 'calendar'],
        ];
        $specRows = [
            ['Max RPM', number_format(17500 + ((int) ($product->id ?? 1) * 120)) . ' RPM'],
            ['Max RCF', number_format(30130 + ((int) ($product->id ?? 1) * 180)) . ' x g'],
            ['Capacity', (2 + ((int) ($product->id ?? 1) % 3)) . ' x 250 mL'],
            ['Temperature Range', '-20 to +40 deg C'],
            ['Run Time', '10s to 99h 59min'],
            ['Noise Level', '< ' . max(46, 56 - (int) ($product->id ?? 1)) . ' dB(A)'],
            ['Dimensions', (440 + ((int) ($product->id ?? 1) * 5)) . ' x 550 x 370 mm'],
        ];
        $bulkTierRows = [
            ['label' => '1 - 2 Units', 'discount' => 'None', 'price' => $currentPrice, 'min' => 1, 'max' => 2, 'discount_value' => 0],
            ['label' => '3 - 5 Units', 'discount' => '5% Off', 'price' => $currentPrice !== null ? round($currentPrice * 0.95, 2) : null, 'min' => 3, 'max' => 5, 'discount_value' => 5],
            ['label' => '6+ Units', 'discount' => '12% Off', 'price' => $currentPrice !== null ? round($currentPrice * 0.88, 2) : null, 'min' => 6, 'max' => null, 'discount_value' => 12],
        ];
        $relatedProducts = collect($related_products ?? [])->filter();
        $compactCardClass = 'rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm md:p-5';
        $sectionCardClass = 'rounded-[32px] border border-slate-200 bg-white p-5 shadow-sm md:p-6';
        $purchaseCardClass = 'rounded-[32px] border border-slate-200 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.1),transparent_36%),linear-gradient(180deg,rgba(255,255,255,1)_0%,rgba(248,250,252,0.96)_100%)] p-5 shadow-sm md:p-6 xl:sticky xl:top-6';
        $featurePanelClass = 'mt-3 rounded-3xl bg-[linear-gradient(135deg,rgba(248,251,255,1)_0%,rgba(238,244,255,1)_100%)] p-4 md:p-5';
        $iconTilePrimaryClass = 'inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-50 text-primary-700';
        $primaryButtonClass = 'inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700';
        $secondaryButtonClass = 'inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50';
        $qtyPickerClass = 'inline-flex h-12 w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-1';
        $qtyButtonClass = 'inline-flex h-8 w-8 items-center justify-center rounded-full text-lg font-semibold text-slate-700 transition hover:bg-white hover:text-primary-700';
        $estimateClass = 'rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3';
        $actionsClass = 'grid gap-3 sm:grid-cols-2';
        $inlineChipClass = 'inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600';
        $sectionHeadingClass = 'text-xl font-semibold text-slate-950';
    @endphp

    <div class="page-frame product-detail-shell -mt-6 py-4 md:-mt-8 md:py-6">
        <div id="uiToastHost" class="ui-toast-host" aria-live="polite" aria-atomic="true"></div>
        <div class="page-frame__inner">
            <a href="{{ $backUrl }}" class="page-back-link mb-4">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                <span>Back</span>
            </a>

            <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-400">
                <a href="{{ route('home') }}" class="text-inherit no-underline hover:text-slate-700">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="text-inherit no-underline hover:text-slate-700">Catalog</a>
                <span>/</span>
                <span>{{ $categoryLabel }}</span>
                <span>/</span>
                <span>{{ $applicationLabel }}</span>
                <span>/</span>
                <span class="text-slate-700">{{ $productTitle }}</span>
            </div>

            <section class="product-detail-stage">
                <div class="product-detail-media-column">
                    <div class="{{ $compactCardClass }}">
                        <div class="product-gallery-panel group">
                            <img id="catalogProductMainImage" src="{{ $galleryImages->first()['src'] }}" alt="{{ $productTitle }}" class="product-visual-stage-lg w-full cursor-zoom-in object-cover transition duration-500 group-hover:scale-[1.04]" loading="lazy" decoding="async">
                            <button id="productImageZoomBtn" type="button" class="absolute right-4 top-4 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/80 bg-white/92 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:text-primary-700" aria-label="Zoom image">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                    <path d="M11 8v6"></path>
                                    <path d="M8 11h6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($galleryImages as $galleryImage)
                            <button type="button" class="catalog-gallery-thumb {{ $loop->first ? 'border-primary-600 bg-white ring-2 ring-primary-600/20 shadow-lg' : 'border-slate-200 bg-white' }} rounded-2xl border p-2 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-primary-600 hover:shadow-md" data-image="{{ $galleryImage['src'] }}" data-alt="{{ $productTitle . ' ' . $galleryImage['label'] }}">
                                <img src="{{ $galleryImage['src'] }}" alt="{{ $galleryImage['label'] }}" class="h-20 w-full rounded-xl object-cover sm:h-24" loading="lazy" decoding="async">
                                <span class="mt-2 block px-1 text-left text-xs font-medium text-slate-400">{{ $galleryImage['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="product-detail-content">
                    <div class="space-y-3.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.status-badge type="product" :value="$primaryBadge" :label="$primaryBadge" />
                            <x-ui.status-badge type="product" :value="$secondaryBadge" :label="$secondaryBadge" />
                        </div>

                        <div class="space-y-3">
                            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">{{ $productTitle }}</h1>
                            <div class="space-y-1 text-sm font-medium text-slate-500">
                                <p>Model No: {{ $modelLabel }} | Professional Grade Biotech System</p>
                            </div>
                            <p class="text-sm leading-7 text-slate-600 md:text-base">{{ $product->description ?: 'Professional scientific product engineered for institutional procurement and precision workflows.' }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-slate-500">
                            <div class="flex items-center gap-1 text-amber-400">
                                @for ($star = 0; $star < 5; $star++)
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="m10 1.5 2.5 5.1 5.7.8-4.1 4 1 5.7L10 14.4 4.9 17l1-5.7-4.1-4 5.7-.8L10 1.5Z"></path></svg>
                                @endfor
                            </div>
                            <span>{{ $ratingValue }}</span>
                            <span class="text-slate-300">|</span>
                            <span>{{ $reviewCount }} Customer Reviews</span>
                            <x-ui.status-badge type="product" :value="$stockText" :label="$stockText" dot />
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($shippingHighlights as $highlight)
                                <x-ui.status-badge type="cart" :value="$highlight" :label="$highlight" dot />
                            @endforeach
                        </div>
                    </div>

                    <div class="{{ $purchaseCardClass }}">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Market Retail Price (MRP)</p>
                                <div class="mt-3 flex flex-wrap items-baseline gap-3">
                                    <span class="text-xl font-extrabold tracking-tight text-primary-700">{!! $formatInr($currentPrice) !!}</span>
                                    @if ($listPrice !== null)
                                        <span class="text-sm font-medium text-slate-400 line-through">{!! $formatInr($listPrice) !!}</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm font-medium text-slate-500">Inclusive of enterprise-grade packaging and compliance-ready dispatch.</p>
                            </div>

                            <div class="rounded-2xl border border-primary-100 bg-primary-50 px-4 py-3">
                                <p class="text-xs font-medium text-primary-700">Secure checkout</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">SSL protected ordering</p>
                            </div>
                        </div>

                        <div class="{{ $featurePanelClass }}">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-start gap-3">
                                    <span class="{{ $iconTilePrimaryClass }} mt-0.5">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="6" y="10" width="12" height="10" rx="2"></rect>
                                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                        </svg>
                                    </span>
                                    <div class="space-y-1">
                                        <p class="text-base font-medium text-slate-900">{{ auth()->check() ? 'Account-aware pricing controls are active' : 'Unlock wholesale pricing and bulk contract rates' }}</p>
                                        <p class="text-sm leading-6 text-slate-500">{{ auth()->check() ? 'This product follows your current account visibility and quotation rules.' : 'Login reveals B2B price ladders, contract terms, and customer-specific discounts.' }}</p>
                                    </div>
                                </div>
                                
                                @guest
                                    <a href="{{ route('login') }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">
                                        Login to See B2B Price
                                    </a>
                                @endguest
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="max-w-48 space-y-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Quantity</p>
                                <div class="{{ $qtyPickerClass }}">
                                    <button type="button" class="catalog-qty-btn {{ $qtyButtonClass }}" data-direction="-1">-</button>
                                    <span id="catalogQuantityValue" class="text-base font-medium text-slate-900 transition duration-150">1</span>
                                    <button type="button" class="catalog-qty-btn {{ $qtyButtonClass }}" data-direction="1">+</button>
                                </div>
                            </div>

                            <div class="{{ $estimateClass }}">
                                <div class="flex items-center justify-between gap-3 text-sm font-medium text-slate-600">
                                    <span>Estimated total</span>
                                    <span id="detailEstimatedTotal" class="font-semibold text-slate-900">{!! $formatInr($currentPrice) !!}</span>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-sm font-medium text-slate-500">
                                    <span id="detailTierLabel">Tier: {{ $bulkTierRows[0]['label'] }}</span>
                                    <span id="detailTierDiscount">{{ $bulkTierRows[0]['discount'] }}</span>
                                </div>
                            </div>

                            <div class="{{ $actionsClass }}">
                                @guest
                                    <a href="{{ $loginUrl }}" class="{{ $primaryButtonClass }} js-add-to-cart" data-product-id="{{ $product->id }}" data-variant-id="{{ $cartVariantId }}" data-product-name="{{ e($productTitle) }}">
                                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="18" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.5a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L21 7H7"></path>
                                        </svg>
                                        <span>Add to Cart</span>
                                    </a>
                                @else
                                    <button type="button" class="{{ $primaryButtonClass }} js-add-to-cart" data-product-id="{{ $product->id }}" data-variant-id="{{ $cartVariantId }}" data-product-name="{{ e($productTitle) }}">
                                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="9" cy="20" r="1"></circle>
                                            <circle cx="18" cy="20" r="1"></circle>
                                            <path d="M3 4h2l2.4 10.5a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L21 7H7"></path>
                                        </svg>
                                        <span>Add to Cart</span>
                                    </button>
                                @endguest
                                <a href="{{ $quoteUrl }}" class="{{ $secondaryButtonClass }}">
                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 5h18"></path>
                                        <path d="M7 3v4"></path>
                                        <path d="M17 3v4"></path>
                                        <rect x="4" y="7" width="16" height="13" rx="2"></rect>
                                        <path d="M8 11h8"></path>
                                    </svg>
                                    <span>Add to Quote</span>
                                </a>
                            </div>
                        </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($trustSignals as $signal)
                                    <span class="{{ $inlineChipClass }}"><span class="h-1.5 w-1.5 rounded-full bg-primary-600"></span>{{ $signal }}</span>
                                @endforeach
                            </div>

                        <a href="{{ $brochureUrl ?: '#' }}" @if ($brochureUrl) target="_blank" rel="noopener" @endif data-download-label="Full Brochure" class="{{ $secondaryButtonClass }} js-download-resource mt-4 w-full">
                            Download Full Brochure
                        </a>
                    </div>

                </div>
            </section>

            <section class="mt-5">
                <div class="{{ $sectionCardClass }}">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="{{ $sectionHeadingClass }}">Bulk Tier Pricing</h2>
                        <x-ui.status-badge id="bulkTierHint" type="cart" value="best_value_on_6_units" label="Best value on 6+ units" />
                    </div>
                    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200">
                        <div class="grid grid-cols-3 bg-slate-50 px-5 py-3 table-label">
                            <span>Quantity</span>
                            <span>Discount</span>
                            <span class="text-right">Price/Unit</span>
                        </div>
                        @foreach ($bulkTierRows as $tier)
                            <div
                                class="{{ $loop->last ? 'bg-primary-50/70' : ($loop->odd ? 'bg-white' : 'bg-slate-50/60') }} grid cursor-pointer grid-cols-3 border-t border-slate-200 px-5 py-4 text-sm text-slate-700 transition"
                                data-bulk-tier-row
                                data-min="{{ (int) ($tier['min'] ?? 1) }}"
                                @if ($tier['max'] !== null) data-max="{{ (int) $tier['max'] }}" @endif
                                data-price="{{ $tier['price'] !== null ? (float) $tier['price'] : '' }}"
                                data-label="{{ e((string) $tier['label']) }}"
                                data-discount="{{ e((string) $tier['discount']) }}"
                            >
                                <span class="{{ $loop->last ? 'font-semibold text-slate-900' : '' }}">{{ $tier['label'] }}</span>
                                <span class="{{ $loop->last ? 'font-semibold text-emerald-700' : 'text-slate-600' }}">{{ $tier['discount'] }}</span>
                                <span class="text-right font-semibold text-slate-900">{!! $formatInr($tier['price']) !!}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="mt-5 grid gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">
                <div class="{{ $sectionCardClass }}">
                    <h2 class="{{ $sectionHeadingClass }}">Product Overview</h2>
                    <div class="mt-5 space-y-5 text-sm leading-7 text-slate-600 md:text-base">
                        <p>{{ $product->description ?: 'This product is presented for scientific buyers who need reliable performance, clear documentation, and a polished procurement experience.' }}</p>
                        <p>The detail flow keeps pricing, technical references, and quotation actions together so users can move from evaluation to purchase without losing context.</p>
                        <ul class="space-y-3">
                            @foreach ($overviewPoints as $point)
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-primary-50 text-primary-700">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.5 7.563a1 1 0 0 1-1.421 0l-3-3.025a1 1 0 1 1 1.42-1.408l2.29 2.31 6.79-6.854a1 1 0 0 1 1.415 0Z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-5">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <p class="text-base font-medium text-slate-900">Customer reviews snapshot</p>
                                <span class="text-sm font-semibold text-slate-600">{{ $ratingValue }} / 5 • {{ $reviewCount }} reviews</span>
                            </div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                                    <p class="text-sm font-medium leading-6 text-slate-900">Fast dispatch and excellent documentation pack for procurement approvals.</p>
                                    <p class="mt-2 text-sm font-medium text-slate-500">Institutional buyer • Verified order</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                                    <p class="text-sm font-medium leading-6 text-slate-900">Stable performance in routine workflows with clear setup guidance.</p>
                                    <p class="mt-2 text-sm font-medium text-slate-500">Lab manager • Repeat purchase</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{ $sectionCardClass }}">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="{{ $sectionHeadingClass }}">Technical Resources</h3>
                        <x-ui.status-badge type="product" value="resource_count" :label="count($resourceCards) . ' files'" />
                    </div>
                    <div class="mt-5 space-y-3">
                        @foreach ($resourceCards as $resource)
                            <a href="{{ $resource['href'] }}" @if ($resource['href'] !== '#') target="_blank" rel="noopener" @endif data-download-label="{{ e((string) $resource['title']) }}" class="js-download-resource group flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 no-underline transition duration-200 hover:-translate-y-0.5 hover:border-primary-100 hover:bg-white hover:shadow-md">
                                <div class="flex items-start gap-3">
                                    <span class="{{ $iconTilePrimaryClass }} mt-0.5 bg-white">
                                        @if ($resource['icon'] === 'clipboard')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="3" width="6" height="4" rx="1"></rect><path d="M9 5H7a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path><path d="M9 12h6"></path><path d="M9 16h4"></path></svg>
                                        @elseif ($resource['icon'] === 'shield')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3 5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3Z"></path><path d="m9.5 12 1.8 1.8 3.2-3.6"></path></svg>
                                        @elseif ($resource['icon'] === 'book')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path></svg>
                                        @else
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="6" width="18" height="15" rx="2"></rect><path d="M3 10h18"></path></svg>
                                        @endif
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $resource['title'] }}</p>
                                        <p class="mt-1 text-sm font-medium text-slate-500">{{ $resource['meta'] }}</p>
                                    </div>
                                </div>
                                <svg class="h-4 w-4 text-slate-400 transition group-hover:text-primary-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="{{ $sectionCardClass }}">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="{{ $sectionHeadingClass }}">Technical Specifications</h2>
                        <x-ui.status-badge type="product" value="validated_configuration" label="Validated configuration" />
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($specRows as $row)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $row[0] }}</p>
                                <p class="mt-3 text-base font-medium leading-7 text-slate-900">{{ $row[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-primary-100 bg-primary-50 p-5 shadow-sm md:p-6">
                    <h3 class="{{ $sectionHeadingClass }}">Need a Custom Setup?</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">Our specialists can help configure this product for your workflow, budget, and institutional procurement needs.</p>
                    <p class="mt-5 text-sm font-medium text-slate-500">Suggested with installation guidance, documentation packs, and compliance support.</p>
                    <a href="{{ route('contact') }}" class="{{ $primaryButtonClass }} mt-6 w-full">
                        Consult an Expert
                    </a>
                </div>
            </section>

            <section class="mt-6 pb-2">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="{{ $sectionHeadingClass }}">Frequently Bought Together</h2>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-primary-700 transition hover:text-primary-800">View All Related Products</a>
                </div>

                @if ($relatedProducts->isNotEmpty())
                    <div class="product-detail-related-grid">
                        @foreach ($relatedProducts as $relatedProduct)
                            @php
                                $relatedImage = $resolveImageUrl($relatedProduct);
                                $relatedPrice = $relatedProduct->visible_price !== null ? (float) $relatedProduct->visible_price : null;
                                $relatedReviews = 38 + (((int) ($relatedProduct->id ?? 1)) * 3);
                            @endphp
                            <article class="product-detail-related-card group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl">
                                <div class="overflow-hidden">
                                    <img src="{{ $relatedImage }}" alt="{{ $relatedProduct->name }}" class="h-[220px] w-full object-cover transition duration-300 group-hover:scale-[1.04]" loading="lazy" decoding="async">
                                </div>
                                <div class="space-y-3 px-4 pb-5 pt-4">
                                    <div class="flex items-center gap-1 text-amber-400">
                                        @for ($star = 0; $star < 5; $star++)
                                            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 20 20"><path d="m10 1.5 2.5 5.1 5.7.8-4.1 4 1 5.7L10 14.4 4.9 17l1-5.7-4.1-4 5.7-.8L10 1.5Z"></path></svg>
                                        @endfor
                                        <span class="ml-1 text-xs font-medium text-slate-500">{{ $relatedReviews }} reviews</span>
                                    </div>
                                    <h3 class="text-base font-semibold leading-6 text-slate-950">{{ Str::limit((string) ($relatedProduct->name ?? 'Related Product'), 52) }}</h3>
                                    <p class="text-sm leading-6 text-slate-500">{{ $relatedProduct->brand ?? 'Biogenix' }}</p>
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-xl font-extrabold tracking-tight text-primary-700">{!! $formatInr($relatedPrice) !!}</p>
                                        <a href="{{ route('products.productDetails', $relatedProduct->id) }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Quick Add</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
                        <p class="text-base font-medium text-slate-700">Related products will appear here as order history and category matching data becomes available.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div id="productImageLightbox" class="product-lightbox" aria-hidden="true">
        <button type="button" class="product-lightbox__backdrop" data-lightbox-close aria-label="Close image preview"></button>
        <div class="product-lightbox__panel" role="dialog" aria-modal="true" aria-label="Product image preview">
            <div class="product-lightbox__bar">
                <p id="productImageLightboxTitle" class="product-lightbox__title">{{ $productTitle }}</p>
                <button type="button" class="product-lightbox__close" data-lightbox-close>Close</button>
            </div>
            <img id="productImageLightboxImage" src="" alt="" class="product-lightbox__image" loading="lazy" decoding="async">
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mainImage = document.getElementById('catalogProductMainImage');
                const thumbs = Array.from(document.querySelectorAll('.catalog-gallery-thumb'));
                const quantityValue = document.getElementById('catalogQuantityValue');
                const toastHost = document.getElementById('uiToastHost');
                const lightbox = document.getElementById('productImageLightbox');
                const lightboxImage = document.getElementById('productImageLightboxImage');
                const zoomButton = document.getElementById('productImageZoomBtn');
                const estimatedTotal = document.getElementById('detailEstimatedTotal');
                const tierLabel = document.getElementById('detailTierLabel');
                const tierDiscount = document.getElementById('detailTierDiscount');
                const bulkTierRows = Array.from(document.querySelectorAll('[data-bulk-tier-row]'));
                const bulkTierHint = document.getElementById('bulkTierHint');
                let quantity = 1;

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

                const syncLocalCart = function (target, selectedQuantity) {
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
                        quantity: selectedQuantity,
                        unitPrice: Number(target.dataset.unitPrice || 0) || (Number('{{ $currentPrice ?? 0 }}') || 0),
                        name: productName,
                        model: target.dataset.model || '{{ $modelLabel }}',
                        image: lightboxImage ? lightboxImage.src || mainImage.src : (mainImage ? mainImage.src : ''),
                    });
                };

                const formatInr = function (amount) {
                    const numeric = Number(amount);
                    if (!Number.isFinite(numeric)) {
                        return 'Request Pricing';
                    }

                    const fixed = (Math.round(numeric * 100) / 100).toFixed(2);
                    const parts = fixed.split('.');
                    let integerPart = parts[0];
                    const fractionPart = parts[1] || '00';

                    if (integerPart.length > 3) {
                        const lastThree = integerPart.slice(-3);
                        const rest = integerPart.slice(0, -3).replace(/\B(?=(\d{2})+(?!\d))/g, ',');
                        integerPart = rest + ',' + lastThree;
                    }

                    return 'Rs. ' + integerPart + '.' + fractionPart;
                };

                const resolveActiveTierRow = function (qty) {
                    const desired = Math.max(1, Number(qty) || 1);

                    return bulkTierRows.find(function (row) {
                        const min = Math.max(1, Number(row.dataset.min || 1));
                        const maxRaw = row.dataset.max;
                        const max = maxRaw === undefined ? null : Number(maxRaw);
                        if (!Number.isFinite(max)) {
                            return desired >= min;
                        }
                        return desired >= min && desired <= max;
                    }) || (bulkTierRows.length ? bulkTierRows[0] : null);
                };

                const syncTierPricing = function () {
                    const activeRow = resolveActiveTierRow(quantity);

                    bulkTierRows.forEach(function (row) {
                        if (row === activeRow) {
                            row.classList.add('bg-primary-50/80', 'ring-2', 'ring-inset', 'ring-primary-600/20');
                        } else {
                            row.classList.remove('bg-primary-50/80', 'ring-2', 'ring-inset', 'ring-primary-600/20');
                        }
                    });

                    if (!activeRow) {
                        return;
                    }

                    const unitPrice = String(activeRow.dataset.price || '').trim();
                    const labelText = String(activeRow.dataset.label || '').trim();
                    const discountText = String(activeRow.dataset.discount || '').trim();

                    if (tierLabel) {
                        tierLabel.textContent = labelText ? ('Tier: ' + labelText) : 'Tier';
                    }

                    if (tierDiscount) {
                        tierDiscount.textContent = discountText || '';
                    }

                    if (bulkTierHint) {
                        if (discountText && discountText !== 'None') {
                            bulkTierHint.textContent = labelText && labelText.includes('6') ? ('Best value applied: ' + discountText) : ('Discount active: ' + discountText);
                        } else {
                            bulkTierHint.textContent = 'Best value on 6+ units';
                        }
                    }

                    if (estimatedTotal) {
                        if (!unitPrice) {
                            estimatedTotal.textContent = 'Request Pricing';
                        } else {
                            estimatedTotal.textContent = formatInr(Number(unitPrice) * Math.max(1, quantity));
                        }
                    }
                };

                syncTierPricing();

                bulkTierRows.forEach(function (row) {
                    row.addEventListener('click', function () {
                        const min = Math.max(1, Number(row.dataset.min || 1));
                        quantity = min;

                        if (quantityValue) {
                            quantityValue.textContent = String(quantity);
                            quantityValue.classList.add('scale-110');
                            window.setTimeout(function () {
                                quantityValue.classList.remove('scale-110');
                            }, 120);
                        }

                        syncTierPricing();
                    });
                });

                const openLightbox = function () {
                    if (!lightbox || !lightboxImage || !mainImage) {
                        return;
                    }

                    lightboxImage.src = mainImage.src;
                    lightboxImage.alt = mainImage.alt || 'Product image';
                    lightbox.classList.add('is-open');
                    lightbox.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                };

                const closeLightbox = function () {
                    if (!lightbox) {
                        return;
                    }

                    lightbox.classList.remove('is-open');
                    lightbox.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';

                    if (lightboxImage) {
                        lightboxImage.src = '';
                        lightboxImage.alt = '';
                    }
                };

                if (zoomButton) {
                    zoomButton.addEventListener('click', openLightbox);
                }

                if (mainImage) {
                    mainImage.addEventListener('click', openLightbox);
                }

                if (lightbox) {
                    lightbox.querySelectorAll('[data-lightbox-close]').forEach(function (node) {
                        node.addEventListener('click', closeLightbox);
                    });

                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape' && lightbox.classList.contains('is-open')) {
                            closeLightbox();
                        }
                    });
                }

                thumbs.forEach(function (thumb) {
                    thumb.addEventListener('click', function () {
                        if (!mainImage) {
                            return;
                        }

                        mainImage.classList.add('opacity-60');
                        mainImage.src = thumb.dataset.image;
                        mainImage.alt = thumb.dataset.alt;

                        window.setTimeout(function () {
                            mainImage.classList.remove('opacity-60');
                        }, 140);

                        thumbs.forEach(function (item) {
                            item.classList.remove('border-primary-600', 'ring-2', 'ring-primary-600/20', 'shadow-lg');
                            item.classList.add('border-slate-200');
                        });

                        thumb.classList.remove('border-slate-200');
                        thumb.classList.add('border-primary-600', 'ring-2', 'ring-primary-600/20', 'shadow-lg');
                    });
                });

                document.querySelectorAll('.catalog-qty-btn').forEach(function (button) {
                    button.addEventListener('click', function () {
                        quantity = Math.max(1, quantity + Number(button.dataset.direction || 0));

                        if (!quantityValue) {
                            return;
                        }

                        quantityValue.textContent = String(quantity);
                        quantityValue.classList.add('scale-110');
                        syncTierPricing();

                        window.setTimeout(function () {
                            quantityValue.classList.remove('scale-110');
                        }, 120);
                    });
                });

                const addToCartButtons = Array.from(document.querySelectorAll('.js-add-to-cart'));
                addToCartButtons.forEach(function (button) {
                    button.addEventListener('click', async function (event) {
                        const target = event.currentTarget;
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

                document.querySelectorAll('.js-download-resource').forEach(function (link) {
                    link.addEventListener('click', function (event) {
                        const href = String(link.getAttribute('href') || '').trim();
                        const labelText = String(link.dataset.downloadLabel || 'File');

                        if (!href || href === '#') {
                            event.preventDefault();
                            showToast({
                                title: 'File unavailable',
                                message: labelText + ' is not available for this item yet.',
                                variant: 'warn',
                            });
                            return;
                        }

                        showToast({
                            title: 'Download started',
                            message: labelText + ' is opening in a new tab.',
                            variant: 'info',
                            duration: 2600,
                        });
                    });
                });
            });
        </script>
    @endpush
@endif
