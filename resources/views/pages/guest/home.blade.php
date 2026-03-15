<div>
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HERO CAROUSEL --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="relative min-h-[calc(100vh-88px)] overflow-hidden bg-slate-900 text-white">
        <div class="absolute inset-0 overflow-hidden" id="heroCarousel">
            <div id="heroTrack" class="flex h-full w-full transition-transform duration-700 ease-out">
                @foreach ($heroSlides ?? [] as $slide)
                    <article class="relative h-full w-full shrink-0">
                        <img
                            src="{{ asset($slide['image']) }}"
                            alt="{{ $slide['title'] }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/60 to-slate-900/30"></div>

                        <div class="container relative z-10 grid min-h-[calc(100vh-88px)] grid-cols-1 gap-8 py-10 pb-24 md:py-14 lg:grid-cols-12">
                            <div class="flex flex-col justify-center lg:col-span-7">
                                <x-badge variant="inverse" class="w-fit">{{ $slide['tag'] }}</x-badge>
                                <h1 class="mt-5 max-w-3xl text-5xl font-bold tracking-tight text-white md:text-6xl lg:text-7xl">{{ $slide['title'] }}</h1>
                                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-100 md:text-xl">{{ $slide['copy'] }}</p>

                                <div class="mt-8 flex flex-wrap items-center gap-3">
                                    <x-ui.action-link :href="route('proforma.create')" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                                    <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5">Book Meeting</x-ui.action-link>
                                </div>
                            </div>

                            <div class="flex items-end lg:col-span-5">
                                <div class="w-full rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-50">Featured Focus</p>
                                    <h2 class="mt-2 text-xl font-semibold text-white md:text-2xl">Trusted diagnostics for labs, hospitals, and care networks.</h2>
                                    <p class="mt-3 text-sm text-slate-100">Biogenix combines category expertise with enterprise-ready support to improve continuity across procurement and delivery operations.</p>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Dots + Icon Arrows --}}
        <div class="pointer-events-none absolute bottom-5 left-0 right-0 z-20 sm:bottom-6">
            <div class="container flex items-center justify-between gap-3">
                <div id="heroDots" class="pointer-events-auto flex items-center gap-2">
                    @foreach ($heroSlides ?? [] as $slide)
                        <button
                            type="button"
                            class="h-2.5 w-8 rounded-full bg-white/40 transition hover:bg-white/80"
                            data-hero-dot
                            data-slide-index="{{ $loop->index }}"
                            aria-label="Go to slide {{ $loop->iteration }}"
                        ></button>
                    @endforeach
                </div>
                <div class="pointer-events-auto flex gap-2">
                    {{-- Icon-only Prev button --}}
                    <button id="heroPrev" type="button" aria-label="Previous slide"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 bg-white/10 text-white transition hover:bg-white/25 hover:scale-105">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    {{-- Icon-only Next button --}}
                    <button id="heroNext" type="button" aria-label="Next slide"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 bg-white/10 text-white transition hover:bg-white/25 hover:scale-105">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STATS / SOCIAL PROOF STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="border-b border-slate-100 bg-white py-8">
        <div class="container">
            <dl class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                @foreach ([
                    ['value' => '5,000+', 'label' => 'Products Listed', 'icon' => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2'],
                    ['value' => '200+',   'label' => 'Lab & Hospital Clients', 'icon' => 'M3 21h18M9 21V9m6 12V9M3 9l9-7 9 7'],
                    ['value' => '24h',    'label' => 'Avg. Dispatch Time', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['value' => '98%',    'label' => 'Order Satisfaction', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $stat)
                    <div class="flex flex-col items-center gap-3 text-center sm:flex-row sm:items-center sm:text-left sm:gap-4">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                            </svg>
                        </span>
                        <div>
                            <dt class="text-2xl font-extrabold tracking-tight text-slate-950">{{ $stat['value'] }}</dt>
                            <dd class="mt-0.5 text-sm font-medium text-slate-500">{{ $stat['label'] }}</dd>
                        </div>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CORE PRODUCT CATEGORIES (with hover overlay + count badge) --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Core Product Categories" subtitle="Designed for modern diagnostics workflows and scalable healthcare operations." />
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">
                @forelse (($productCategories ?? collect()) as $category)
                    <article class="home-card group">
                        {{-- Image with gradient overlay on hover --}}
                        <div class="relative overflow-hidden rounded-2xl">
                            <img
                                src="{{ asset($category->default_image_path ?: 'storage/categories/image1.jpg') }}"
                                alt="{{ $category->name }}"
                                class="h-40 w-full object-cover transition duration-500 group-hover:scale-[1.06]"
                                loading="lazy"
                                decoding="async"
                            >
                            {{-- Dark overlay on hover --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            {{-- Hover CTA --}}
                            <div class="absolute inset-0 flex items-end justify-center pb-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-4 py-1.5 text-xs font-semibold text-slate-900 shadow-lg">
                                    <svg class="h-3.5 w-3.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" /><circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Explore
                                </span>
                            </div>
                            {{-- Product count badge --}}
                            @if (isset($category->products_count) && $category->products_count > 0)
                                <span class="absolute right-3 top-3 inline-flex items-center rounded-full bg-primary-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm">
                                    {{ $category->products_count }}
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 pt-4">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $category->name }}</h3>
                            <p class="text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit($category->description ?: $category->application ?: 'Explore products from this category.', 110) }}
                            </p>
                            <x-ui.action-link :href="route('products.index')" variant="secondary">Explore</x-ui.action-link>
                        </div>
                    </article>
                @empty
                    <article class="home-panel sm:col-span-2 xl:col-span-5">
                        <h3 class="text-lg font-semibold text-slate-900">Categories will appear here</h3>
                        <p class="mt-2 text-sm text-slate-600">No home page categories are available right now.</p>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CLINICAL & BUSINESS SOLUTIONS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Clinical &amp; Business Solutions" subtitle="Purpose-built pathways for B2B institutions and B2C healthcare buyers." />
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <article class="home-panel">
                    <x-badge variant="info">B2B Operations</x-badge>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">Distributor, Lab, and Hospital Enablement</h3>
                    <p class="mt-2 text-sm text-slate-600">Account-based ordering, product discovery, quotation approval flow, and coordinated support for healthcare institutions.</p>
                    <div class="mt-5">
                        <x-ui.action-link :href="route('login', ['user_type' => 'b2b'])">B2B Login</x-ui.action-link>
                    </div>
                </article>
                <article class="home-panel">
                    <x-badge variant="success">B2C Access</x-badge>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">Retail and Independent Care Buyers</h3>
                    <p class="mt-2 text-sm text-slate-600">Simple MRP-visible catalog flow with quick quotation generation and immediate assistance through support channels.</p>
                    <div class="mt-5">
                        <x-ui.action-link :href="route('login', ['user_type' => 'b2c'])">B2C Login</x-ui.action-link>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PARTNER / BRAND TRUST BAR --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="border-y border-slate-100 bg-slate-50 py-8">
        <div class="container">
            <p class="mb-6 text-center text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Trusted by leading healthcare institutions &amp; diagnostic brands</p>
            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-10">
                @foreach ([
                    ['name' => 'ISO 9001', 'sub' => 'Quality Certified'],
                    ['name' => 'CE Mark',  'sub' => 'EU Compliance'],
                    ['name' => 'ICMR',     'sub' => 'Registered Supplier'],
                    ['name' => 'WHO GMP',  'sub' => 'Manufacturing Standard'],
                    ['name' => 'FDA',      'sub' => 'Approved Distributor'],
                ] as $badge)
                    <div class="flex items-center gap-2.5 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-50 text-primary-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 12 1.8 1.8 3.2-3.6" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $badge['name'] }}</p>
                            <p class="text-[11px] font-medium text-slate-500">{{ $badge['sub'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SAME-DAY DELIVERY + NEWSLETTER --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-5 xl:grid-cols-12">
            <article class="rounded-3xl bg-gradient-to-r from-primary-600 to-primary-700 p-6 text-white shadow-sm xl:col-span-7 md:p-8">
                <h2 class="text-2xl font-semibold text-white md:text-3xl">Same-Day Delivery Support in Lucknow</h2>
                <p class="mt-3 max-w-2xl text-sm text-primary-50 md:text-base">For select products and serviceable pincodes, our local operations network enables faster diagnostics fulfillment.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <x-ui.action-link :href="route('proforma.create')" variant="contrast" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                    <x-ui.action-link :href="route('contact')" variant="inverse" class="min-h-11 px-5">Talk to Support</x-ui.action-link>
                </div>
            </article>

            <article class="home-panel xl:col-span-5">
                <h3 class="text-xl font-semibold text-slate-900">Newsletter</h3>
                <p class="mt-2 text-sm text-slate-600">Get product updates, launch announcements, and support advisories.</p>
                <form id="newsletterForm" class="mt-4 space-y-3" novalidate>
                    <div>
                        <label for="newsletterEmail" class="mb-2 block text-sm font-semibold text-slate-700">Work Email</label>
                        <input id="newsletterEmail" type="email" class="home-input" placeholder="you@organization.com" required>
                    </div>
                    <button type="submit" id="newsletterSubmitBtn" class="home-primary-button w-full">Subscribe</button>
                    <p id="newsletterStatus" class="min-h-[1.25rem] text-sm font-medium text-slate-600"></p>
                </form>
            </article>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- WHY BIOGENIX --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-white py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-6 lg:grid-cols-12">
            <article class="min-h-[18rem] overflow-hidden rounded-3xl border border-slate-200 bg-slate-900 text-white shadow-xl md:min-h-[22rem] lg:col-span-6">
                <img src="{{ asset('storage/slides/image4.jpg') }}" alt="Biogenix diagnostics support" class="h-full w-full object-cover opacity-80" loading="lazy" decoding="async">
            </article>

            <article class="lg:col-span-6">
                <x-ui.section-heading title="Why Leading Teams Choose Biogenix" subtitle="A modern diagnostics partner model inspired by enterprise healthcare standards." />
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['title' => 'Product Depth', 'copy' => 'Broad portfolio across IVD, reagents, instruments, and consumables.', 'icon' => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2'],
                        ['title' => 'Operational Reliability', 'copy' => 'Structured order workflows and responsive escalation support.', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Compliance-Ready', 'copy' => 'Quality-first processes aligned to regulated healthcare operations.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Consultative Service', 'copy' => 'Pre-sale and post-sale support for clinical and procurement teams.', 'icon' => 'M8 10h8M8 14h5M12 3c4.97 0 9 3.58 9 8 0 1.95-.78 3.74-2.07 5.16L20 21l-5.04-1.68A10.5 10.5 0 0 1 12 20c-4.97 0-9-3.58-9-8s4.03-9 9-9Z'],
                    ] as $value)
                        <article class="home-panel group flex gap-3.5">
                            <span class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 transition group-hover:bg-primary-600 group-hover:text-white">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $value['icon'] }}" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $value['title'] }}</h3>
                                <p class="mt-1.5 text-sm text-slate-600">{{ $value['copy'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- TESTIMONIALS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="What Our Clients Say" subtitle="Trusted by procurement heads, lab managers, and healthcare institutions across India." />
            <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach ([
                    [
                        'quote'   => 'Biogenix has transformed our reagent procurement cycle. Same-day availability and documentation packs save us hours every week.',
                        'name'    => 'Dr. Priya Mehta',
                        'role'    => 'Lab Director, Apollo Diagnostics',
                        'rating'  => 5,
                        'initials'=> 'PM',
                        'color'   => 'bg-primary-600',
                    ],
                    [
                        'quote'   => 'The B2B portal is clean, fast, and the quotation workflow makes budget approvals painless. Highly recommended for hospital procurement.',
                        'name'    => 'Rajesh Kumar',
                        'role'    => 'Purchase Manager, Fortis Healthcare',
                        'rating'  => 5,
                        'initials'=> 'RK',
                        'color'   => 'bg-emerald-600',
                    ],
                    [
                        'quote'   => 'Excellent product range and responsive support. We\'ve consolidated three vendors into one with Biogenix as our primary diagnostics partner.',
                        'name'    => 'Sunita Agarwal',
                        'role'    => 'Operations Head, PathLab India',
                        'rating'  => 5,
                        'initials'=> 'SA',
                        'color'   => 'bg-violet-600',
                    ],
                ] as $testimonial)
                    <article class="home-panel flex flex-col gap-4">
                        {{-- Stars --}}
                        <div class="flex items-center gap-0.5">
                            @for ($s = 0; $s < $testimonial['rating']; $s++)
                                <svg class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 20 20">
                                    <path d="m10 1.5 2.5 5.1 5.7.8-4.1 4 1 5.7L10 14.4 4.9 17l1-5.7-4.1-4 5.7-.8L10 1.5Z" />
                                </svg>
                            @endfor
                        </div>
                        {{-- Quote --}}
                        <blockquote class="flex-1 text-sm leading-7 text-slate-600">
                            "{{ $testimonial['quote'] }}"
                        </blockquote>
                        {{-- Author --}}
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full {{ $testimonial['color'] }} text-sm font-bold text-white">
                                {{ $testimonial['initials'] }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $testimonial['name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- INSIGHTS & UPDATES (with icon thumbnails) --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Insights &amp; Updates" subtitle="Explore diagnostics trends, product updates, and operational best practices." />
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    [
                        'title'  => 'Scaling Diagnostic Labs in Tier-2 Cities',
                        'tag'    => 'Operations',
                        'copy'   => 'How regional labs can improve turnaround time with better procurement and support.',
                        'href'   => route('about'),
                        'action' => 'Explore Story',
                        'icon'   => 'M3 21h18M9 21V9m6 12V9M3 9l9-7 9 7',
                        'color'  => 'bg-blue-50 text-blue-600',
                    ],
                    [
                        'title'  => 'Choosing the Right Reagent Mix',
                        'tag'    => 'Product Guide',
                        'copy'   => 'A practical framework for balancing consistency, throughput, and budget.',
                        'href'   => route('products.index'),
                        'action' => 'Browse Products',
                        'icon'   => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2M9 12h6M9 16h4',
                        'color'  => 'bg-emerald-50 text-emerald-600',
                    ],
                    [
                        'title'  => 'Checklist for New Instrument Rollouts',
                        'tag'    => 'Implementation',
                        'copy'   => 'Deployment, training, and support essentials for successful onboarding.',
                        'href'   => route('contact'),
                        'action' => 'Talk to Team',
                        'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'color'  => 'bg-violet-50 text-violet-600',
                    ],
                ] as $insight)
                    <article class="home-panel group flex flex-col gap-4">
                        {{-- Icon thumbnail --}}
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $insight['color'] }} transition group-hover:scale-110">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $insight['icon'] }}" />
                                </svg>
                            </span>
                            <x-badge variant="default">{{ $insight['tag'] }}</x-badge>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $insight['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $insight['copy'] }}</p>
                        </div>
                        <x-ui.action-link :href="$insight['href']" variant="secondary" class="mt-auto w-fit">
                            {{ $insight['action'] }}
                        </x-ui.action-link>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- FINAL CTA STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-950 py-12 text-white md:py-14">
        <div class="container text-center">
            <h2 class="text-2xl font-semibold text-white md:text-3xl">Need a faster procurement decision?</h2>
            <p class="mx-auto mt-3 max-w-3xl text-sm text-slate-200 md:text-base">
                Generate a compliant MRP-only quote instantly, or schedule a meeting with our team for institutional onboarding and product consultation.
            </p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <x-ui.action-link :href="route('proforma.create')" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5">Book a Meeting</x-ui.action-link>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        /* ─── Hero Carousel ─── */
        const track    = document.getElementById('heroTrack');
        const dots     = Array.from(document.querySelectorAll('[data-hero-dot]'));
        const nextBtn  = document.getElementById('heroNext');
        const prevBtn  = document.getElementById('heroPrev');
        const carousel = document.getElementById('heroCarousel');
        let index      = 0;
        let intervalId = null;
        const total    = dots.length;

        function paintDots() {
            dots.forEach(function (dot, dotIndex) {
                const active = dotIndex === index;
                dot.classList.toggle('bg-white',    active);
                dot.classList.toggle('w-10',        active);
                dot.classList.toggle('bg-white/40', !active);
            });
        }

        function moveTo(target) {
            index = (target + total) % total;
            if (track) track.style.transform = 'translateX(-' + (index * 100) + '%)';
            paintDots();
        }

        function startAuto() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(function () { moveTo(index + 1); }, 5000);
        }

        function stopAuto() {
            if (intervalId) { clearInterval(intervalId); intervalId = null; }
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                moveTo(Number(dot.getAttribute('data-slide-index') || 0));
                startAuto();
            });
        });

        if (nextBtn) nextBtn.addEventListener('click', function () { moveTo(index + 1); startAuto(); });
        if (prevBtn) prevBtn.addEventListener('click', function () { moveTo(index - 1); startAuto(); });

        /* ── Touch / Swipe support ── */
        if (carousel) {
            let touchStartX = 0;
            let touchEndX   = 0;

            carousel.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
                stopAuto();
            }, { passive: true });

            carousel.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 40) {
                    moveTo(diff > 0 ? index + 1 : index - 1);
                }
                startAuto();
            }, { passive: true });

            carousel.addEventListener('mouseenter', stopAuto);
            carousel.addEventListener('mouseleave', startAuto);
        }

        moveTo(0);
        startAuto();

        /* ─── Newsletter ─── */
        const newsletterForm   = document.getElementById('newsletterForm');
        const newsletterBtn    = document.getElementById('newsletterSubmitBtn');
        const newsletterStatus = document.getElementById('newsletterStatus');
        const newsletterEmail  = document.getElementById('newsletterEmail');

        if (newsletterForm && newsletterBtn && newsletterStatus && newsletterEmail) {
            newsletterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const email = newsletterEmail.value.trim();
                newsletterEmail.classList.remove('border-rose-400', 'ring-4', 'ring-rose-500/10');

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    newsletterEmail.classList.add('border-rose-400', 'ring-4', 'ring-rose-500/10');
                    newsletterStatus.textContent = 'Please enter a valid email.';
                    newsletterStatus.classList.add('text-rose-600');
                    newsletterStatus.classList.remove('text-emerald-600');
                    return;
                }

                newsletterBtn.disabled = true;
                newsletterBtn.classList.add('cursor-not-allowed', 'opacity-70');
                newsletterBtn.setAttribute('aria-disabled', 'true');
                newsletterStatus.textContent = 'You are subscribed. Thank you.';
                newsletterStatus.classList.remove('text-rose-600');
                newsletterStatus.classList.add('text-emerald-600');
                newsletterForm.reset();

                setTimeout(function () {
                    newsletterBtn.disabled = false;
                    newsletterBtn.classList.remove('cursor-not-allowed', 'opacity-70');
                    newsletterBtn.setAttribute('aria-disabled', 'false');
                }, 500);
            });
        }
    });
</script>
@endpush
