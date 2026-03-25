@push('styles')
<style>
    .home-page {
        --home-orange: var(--color-primary-600);
        --home-cyan: #38bdf8;
        --home-navy: #07111f;
        --home-panel: rgba(255, 255, 255, 0.74);
        background: transparent;
    }

    .home-page .home-card,
    .home-page .home-panel {
        border-color: rgba(148, 163, 184, 0.18);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.78));
        box-shadow: 0 24px 55px rgba(15, 23, 42, 0.07);
        backdrop-filter: blur(18px);
        padding: 1.5rem;
    }

    @media (min-width: 640px) {
        .home-page .home-panel {
            padding: 2rem;
        }
    }

    @media (min-width: 1024px) {
        .home-page .home-panel {
            padding: 2.5rem;
        }
    }

    .hero-gradient-overlay {
        background: linear-gradient(135deg, rgba(7, 17, 31, 0.92) 0%, rgba(13, 30, 56, 0.88) 50%, rgba(7, 17, 31, 0.95) 100%);
    }

    /* ─── Premium Testimonial Card ─── */
    .testimonial-card {
        position: relative;
        isolation: isolate;
        overflow: hidden;
    }

    .testimonial-card::before {
        content: '“';
        position: absolute;
        top: -1rem;
        right: 1.5rem;
        font-family: 'Sora', serif;
        font-size: 8rem;
        font-weight: 800;
        color: rgba(15, 23, 42, 0.04);
        pointer-events: none;
        z-index: -1;
        line-height: 1;
    }

    .testimonial-author-badge {
        position: relative;
        display: inline-flex;
        height: 3rem;
        width: 3rem;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.82);
    }

    /* ─── Premium Insight Card ─── */
    .insight-card .insight-icon-shell {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s ease;
    }

    .home-panel:hover .insight-icon-shell {
        transform: scale(1.1) translateY(-2px);
    }

    .home-page .home-card {
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .home-page .home-card:hover,
    .home-page .home-panel:hover {
        border-color: rgba(255, 106, 0, 0.2);
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.11);
    }

    .home-page .home-input {
        border-radius: 1rem;
        border-color: rgba(148, 163, 184, 0.28);
        background: rgba(255, 255, 255, 0.95);
    }

    .home-page .home-primary-button {
        border-radius: 1rem;
        background: linear-gradient(135deg, var(--color-primary-600), var(--color-primary-500));
        box-shadow: 0 18px 30px rgba(26, 77, 46, 0.28);
    }

    .home-page .home-primary-button:hover {
        background: linear-gradient(135deg, #ed6200, #ff7b21);
    }

    .home-hero::before,
    .home-hero::after {
        content: '';
        position: absolute;
        border-radius: 9999px;
        pointer-events: none;
        filter: blur(12px);
    }

    .home-hero::before {
        top: 4rem;
        left: -6rem;
        height: 18rem;
        width: 18rem;
        background: radial-gradient(circle, rgba(255, 106, 0, 0.34), transparent 70%);
    }

    .home-hero::after {
        right: -5rem;
        top: -2rem;
        height: 20rem;
        width: 20rem;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.28), transparent 72%);
    }

    .home-hero-track-overlay {
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 42px 42px;
        mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.85), transparent);
    }

    .home-hero-copy-shell {
        position: relative;
        max-width: 64rem;
        border-radius: var(--ui-radius-card);
        background: linear-gradient(180deg, rgba(7, 17, 31, 0.16), rgba(7, 17, 31, 0.04));
        padding: 0.35rem 1.2rem 1.25rem 0;
        backdrop-filter: blur(4px);
    }

    .home-hero-title,
    .home-hero-copy,
    .home-hero-chip-text {
        text-shadow: 0 8px 28px rgba(0, 0, 0, 0.45);
    }

    .home-hero-focus-card {
        width: 100%;
        border-radius: var(--ui-radius-card);
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: linear-gradient(180deg, rgba(7, 17, 31, 0.56), rgba(7, 17, 31, 0.78));
        box-shadow: 0 22px 55px rgba(0, 0, 0, 0.22);
        padding: 1.25rem;
        backdrop-filter: blur(18px);
    }

    .home-hero-focus-title,
    .home-hero-focus-copy,
    .home-hero-focus-meta {
        text-shadow: 0 10px 26px rgba(0, 0, 0, 0.42);
    }

    .home-route-visual {
        position: relative;
        overflow: hidden;
        border-radius: var(--ui-radius-card);
        min-height: 11rem;
    }

    .home-route-visual::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(7, 17, 31, 0.14), rgba(7, 17, 31, 0.76));
    }

    .home-route-visual img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .home-panel:hover .home-route-visual img {
        transform: scale(1.05);
    }

    .home-route-copy {
        position: absolute;
        inset: auto 1rem 1rem 1rem;
        z-index: 1;
    }

    .home-route-card {
        position: relative;
        overflow: hidden;
        min-height: 25rem;
        border-radius: var(--ui-radius-card);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 26px 64px rgba(15, 23, 42, 0.14);
    }

    .home-route-card img {
        position: absolute;
        inset: 0;
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .home-route-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(7, 17, 31, 0.08) 0%, rgba(7, 17, 31, 0.22) 32%, rgba(7, 17, 31, 0.84) 100%);
        z-index: 0;
    }

    .home-route-card::after {
        content: '';
        position: absolute;
        inset: 1rem;
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
        pointer-events: none;
        z-index: 1;
    }

    .home-route-card:hover img {
        transform: scale(1.05);
    }

    .home-route-card__content {
        position: relative;
        z-index: 2;
        display: flex;
        min-height: 25rem;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
    }

    .home-route-card__chip {
        position: absolute;
        left: 1.5rem;
        top: 1.5rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.12);
        padding: 0.55rem 0.9rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(12px);
    }

    .home-route-card__panel {
        border-radius: 1.6rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: linear-gradient(180deg, rgba(7, 17, 31, 0.34), rgba(7, 17, 31, 0.52));
        padding: 1.25rem;
        backdrop-filter: blur(10px);
    }

    .home-delivery-card {
        position: relative;
        overflow: hidden;
    }

    .home-delivery-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(135deg, rgba(255, 106, 0, 0.96), rgba(255, 147, 71, 0.84)),
            url('{{ asset('upload/corousel/lucknow-map.svg') }}');
        background-position: center, center;
        background-repeat: no-repeat, no-repeat;
        background-size: cover, cover;
        opacity: 1;
    }

    .home-delivery-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 26%),
            linear-gradient(90deg, rgba(122, 38, 0, 0.14), transparent 52%);
    }

    .home-delivery-content {
        position: relative;
        z-index: 1;
    }

    .home-stats {
        margin-top: 1.5rem;
        z-index: 20;
    }

    .home-stats-shell {
        border: 1px solid rgba(255, 255, 255, 0.6);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.72));
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.1);
        backdrop-filter: blur(24px);
    }

    .home-category-carousel-wrapper {
        position: relative;
        margin-top: 1.5rem;
        padding-bottom: 0.5rem;
    }

    .home-category-grid {
        display: flex;
        gap: 0.9rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
    }

    .home-category-grid::-webkit-scrollbar {
        display: none;
    }

    .home-category-tile {
        position: relative;
        overflow: hidden;
        min-height: 0;
        display: flex;
        flex-direction: column;
        border-radius: var(--ui-radius-card);
        border: 1px solid rgba(255, 255, 255, 0.72);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.9));
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
        isolation: isolate;
        flex: 0 0 100%;
        scroll-snap-align: start;
    }

    .home-category-tile__media {
        position: relative;
        height: 14.25rem;
        flex: 0 0 14.25rem;
        overflow: hidden;
    }

    .home-category-tile__image {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .home-category-tile__media::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(7, 17, 31, 0.04) 0%, rgba(7, 17, 31, 0.08) 55%, rgba(7, 17, 31, 0.26) 100%);
        z-index: 0;
    }

    .home-category-tile:hover .home-category-tile__image {
        transform: scale(1.06);
    }

    .home-category-tile__content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        flex: 1;
        padding: 0.85rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.96));
    }

    .home-category-tile--featured,
    .home-category-tile--featured .home-category-tile__content {
        min-height: 0;
    }

    .home-category-tile--featured .home-category-tile__media {
        height: 14.25rem;
        flex-basis: 14.25rem;
    }

    .home-category-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.75);
        padding: 0.35rem 0.7rem;
        font-size: 0.64rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #0f172a;
        backdrop-filter: blur(12px);
    }

    .home-category-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 9999px;
        border: 1px solid rgba(255, 106, 0, 0.16);
        background: rgba(255, 243, 236, 0.95);
        padding: 0.35rem 0.65rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: #9a3412;
        backdrop-filter: blur(10px);
    }

    .home-category-copy {
        max-width: 28rem;
        color: #64748b;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        overflow: hidden;
    }

    .home-category-action {
        display: inline-flex;
        min-height: 2.5rem;
        align-items: center;
        justify-content: center;
        border-radius: 0.9rem;
        background: linear-gradient(135deg, var(--color-primary-600), var(--color-primary-500));
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #ffffff;
        box-shadow: 0 14px 24px rgba(26, 77, 46, 0.22);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .home-category-action:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, var(--color-primary-700), var(--color-primary-600));
        box-shadow: 0 16px 28px rgba(26, 77, 46, 0.28);
    }

    @media (min-width: 640px) {
        .home-category-tile {
            flex: 0 0 calc(50% - 0.45rem);
        }
    }

    @media (min-width: 768px) {
        .home-category-tile {
            flex: 0 0 calc(33.333% - 0.6rem);
        }
    }

    @media (min-width: 1024px) {
        .home-category-tile {
            flex: 0 0 calc(25% - 0.675rem);
        }
    }

    @media (min-width: 1280px) {
        .home-category-tile {
            flex: 0 0 calc(25% - 0.675rem);
        }
    }

    .home-solutions {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
    }

    .home-trust {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.52), rgba(255, 255, 255, 0.82));
        backdrop-filter: blur(14px);
    }

    .home-cta {
        background:
            radial-gradient(circle at top right, rgba(26, 77, 46, 0.18), transparent 28%),
            linear-gradient(135deg, #07111f 0%, #102243 52%, #112d60 100%);
    }

    .home-reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.8s ease, transform 0.8s ease;
        will-change: opacity, transform;
    }

    .home-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .home-page .home-card,
        .home-page .home-panel,
        .home-reveal {
            transition: none !important;
            transform: none !important;
            opacity: 1 !important;
        }
    }

    @media (max-width: 768px) {
        .home-stats {
            margin-top: 1rem;
        }
    }
</style>
@endpush

<div class="home-page">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HERO CAROUSEL --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-hero relative min-h-[calc(100vh-88px)] overflow-hidden bg-slate-900 text-white">
        <div class="home-hero-track-overlay absolute inset-0 opacity-30"></div>
        <div class="absolute inset-0 overflow-hidden" id="heroCarousel">
            <div id="heroTrack" class="flex h-full w-full translate-x-0 transition-transform duration-700 ease-out">
                @foreach ($heroSlides ?? [] as $slide)
                    <article class="relative h-full w-full shrink-0">
                        <img
                            src="{{ asset($slide['image']) }}"
                            alt="{{ $slide['title'] }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                            decoding="async"
                        >
                        <div class="hero-gradient-overlay absolute inset-0 z-0 opacity-90"></div>

                        <div class="relative z-10 mx-auto grid min-h-[calc(100vh-88px)] w-full max-w-none grid-cols-1 gap-8 px-4 py-8 pb-12 sm:px-6 md:py-10 lg:grid-cols-12 lg:px-8 xl:px-10">
                            <div class="home-reveal flex flex-col justify-start lg:col-span-7">
                                <div class="home-hero-copy-shell">
                                    <div class="mb-4 inline-flex items-center gap-2.5 rounded-full border border-primary-500/20 bg-primary-500/10 px-4 py-1.5 backdrop-blur-md">
                                        <span class="relative flex h-2 w-2">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary-500 opacity-75"></span>
                                            <span class="relative inline-flex h-2 w-2 rounded-full bg-primary-600"></span>
                                        </span>
                                        <span class="text-xs font-bold tracking-widest text-primary-600 uppercase">Trusted Diagnostics Partner</span>
                                    </div>
                                    <h1 class="font-display mb-6 text-4xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                                        Next-Gen <span class="bg-gradient-to-r from-primary-400 via-emerald-400 to-primary-500 bg-clip-text text-transparent">Healthcare</span> Solutions
                                    </h1>
                                    <p class="home-hero-copy mt-6 max-w-2xl text-base leading-8 text-slate-100 md:text-xl">{{ $slide['copy'] }}</p>

                                    <div class="mt-8 flex flex-wrap items-center gap-3">
                                        <x-ui.action-link :href="route('products.index')" variant="secondary" class="min-h-11 px-5 hover-lift">Browse Catalog</x-ui.action-link>
                                        <x-ui.action-link :href="route('quotation.create')" class="min-h-11 px-5 hover-lift glow-orange">Generate Quote</x-ui.action-link>
                                        <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5">Book Meeting</x-ui.action-link>
                                    </div>

                                    <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-200">
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Healthcare-first sourcing</span>
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Fast quotation workflow</span>
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Structured support handoff</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-reveal flex items-start lg:col-span-5">
                                <div class="home-hero-focus-card">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-50">Featured Focus</p>
                                    <h2 class="home-hero-focus-title mt-2 text-xl font-semibold text-white md:text-2xl">Trusted diagnostics for labs, hospitals, and care networks.</h2>
                                    <p class="home-hero-focus-copy mt-3 text-sm text-slate-100">Biogenix combines category expertise with enterprise-ready support to improve continuity across procurement and delivery operations.</p>
                                    <div class="mt-5 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-3">
                                            <p class="text-xl font-semibold text-white">24h</p>
                                            <p class="home-hero-focus-meta text-xs uppercase tracking-[0.18em] text-white/70">Dispatch promise</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-3">
                                            <p class="text-xl font-semibold text-white">200+</p>
                                            <p class="home-hero-focus-meta text-xs uppercase tracking-[0.18em] text-white/70">Institutional clients</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-3">
                                            <p class="text-xl font-semibold text-white">98%</p>
                                            <p class="home-hero-focus-meta text-xs uppercase tracking-[0.18em] text-white/70">Satisfaction score</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Dots + Icon Arrows --}}
        <div class="pointer-events-none absolute bottom-5 left-0 right-0 z-20 sm:bottom-6">
            <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 flex items-center justify-between gap-3">
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
    <!-- ... -->
        </div>
    </section>

 
   

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CORE PRODUCT CATEGORIES (with hover overlay + count badge) --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-categories bg-transparent py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="Core Product Categories" subtitle="Designed for modern diagnostics workflows and scalable healthcare operations." />
            </div>
            <div class="home-category-carousel-wrapper home-reveal group">
                {{-- Carousel Navigation (Prev) --}}
                <button id="catPrev" type="button" aria-label="Previous category"
                    class="absolute -left-2 sm:-left-4 top-1/2 z-10 -translate-y-1/2 inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-400/50 bg-slate-900/5 text-slate-600 backdrop-blur-sm transition hover:scale-110 hover:bg-slate-900/10 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                {{-- Carousel Navigation (Next) --}}
                <button id="catNext" type="button" aria-label="Next category"
                    class="absolute -right-2 sm:-right-4 top-1/2 z-10 -translate-y-1/2 inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-400/50 bg-slate-900/5 text-slate-600 backdrop-blur-sm transition hover:scale-110 hover:bg-slate-900/10 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                    </svg>
                </button>

                <div class="home-category-grid" id="categoryCarousel">
                @forelse (($productCategories ?? collect()) as $category)
                    @php
                        $tileClass = 'home-category-tile--standard';
                        $imagePath = $category->default_image_path ?: 'upload/categories/image1.jpg';
                        $categoryCopy = \Illuminate\Support\Str::limit($category->description ?: $category->application ?: 'Explore products from this category.', 60);
                    @endphp
                    <article class="glass-card hover-lift home-reveal group {{ $tileClass }} rounded-[var(--ui-radius-card)]">
                        <div class="home-category-tile__media">
                            <img
                                src="{{ asset($imagePath) }}"
                                alt="{{ $category->name }}"
                                class="home-category-tile__image"
                                loading="lazy"
                                decoding="async"
                            >
                            <div class="relative z-[1] flex items-start justify-between gap-3 p-4">
                                <span class="home-category-pill">{{ $loop->first ? 'Featured category' : 'Category' }}</span>
                                @if (isset($category->products_count) && $category->products_count > 0)
                                    <span class="home-category-meta">{{ $category->products_count }} products</span>
                                @endif
                            </div>
                        </div>
                        <div class="home-category-tile__content">
                            <div>
                                <h3 class="font-display text-lg font-semibold tracking-tight text-slate-950">{{ $category->name }}</h3>
                                <p class="home-category-copy mt-1.5 text-[13px] leading-5.5">
                                    {{ $categoryCopy }}
                                </p>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('products.index') }}" class="home-category-action hover-lift glow-orange">Explore</a>
                            </div>
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
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CLINICAL & BUSINESS SOLUTIONS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-solutions py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="Clinical Business Solutions" subtitle="Purpose-built pathways for B2B institutions and B2C healthcare buyers." />
            </div>
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <article class="home-route-card home-reveal">
                    <img src="{{ asset('upload/corousel/b2b-enterprise-bg.png') }}" alt="B2B Enterprise Solutions" loading="lazy" decoding="async">
                    <span class="home-route-card__chip">B2B Operations</span>
                    <div class="home-route-card__content">
                        <div class="home-route-card__panel">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For institutions and channel partners</p>
                            <h3 class="mt-3 text-2xl font-semibold text-white">Distributor, lab, and hospital buying with structured support.</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-200">Account-based ordering, product discovery, quotation approval flow, and coordinated support for healthcare institutions.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <x-ui.action-link :href="route('login', ['user_type' => 'b2b'])" variant="inverse">B2B Login</x-ui.action-link>
                                <x-ui.action-link href="{{ url('/b2b-signup') }}" class="bg-black/30 hover:bg-black/50 border border-white/10 text-white backdrop-blur-md transition-colors">B2B Sign Up</x-ui.action-link>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="home-route-card home-reveal">
                    <img src="{{ asset('upload/corousel/b2c-consumer-bg.png') }}" alt="B2C Direct-to-Consumer" loading="lazy" decoding="async">
                    <span class="home-route-card__chip">B2C Access</span>
                    <div class="home-route-card__content">
                        <div class="home-route-card__panel">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For direct and retail buyers</p>
                            <h3 class="mt-3 text-2xl font-semibold text-white">A cleaner buying path for faster product access.</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-200">Simple MRP-visible catalog flow with quick quotation generation and immediate assistance through support channels.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <x-ui.action-link :href="route('login', ['user_type' => 'b2c'])" variant="inverse">B2C Login</x-ui.action-link>
                                <x-ui.action-link href="{{ url('/signup') }}" class="bg-black/30 hover:bg-black/50 border border-white/10 text-white backdrop-blur-md transition-colors">B2C Sign Up</x-ui.action-link>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SAME-DAY DELIVERY + NEWSLETTER --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-newsletter bg-transparent py-12 md:py-16">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-5 px-4 sm:px-6 xl:grid-cols-12 lg:px-8 xl:px-10">
            <article class="home-delivery-card home-reveal rounded-[var(--ui-radius-card)] p-6 text-white shadow-[0_24px_55px_rgba(255,106,0,0.25)] xl:col-span-7 md:p-8">
                <div class="home-delivery-content">
                    <span class="inline-flex rounded-full border border-white/18 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/80 backdrop-blur">Lucknow local support</span>
                    <h2 class="mt-4 text-2xl font-semibold text-white md:text-3xl">Same-Day Delivery Support in Lucknow</h2>
                    <p class="mt-3 max-w-2xl text-sm text-primary-50 md:text-base">For select products and serviceable pincodes, our local operations network enables faster diagnostics fulfillment.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <x-ui.action-link :href="route('quotation.create')" variant="contrast" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                        <x-ui.action-link :href="route('contact')" variant="inverse" class="min-h-11 px-5">Talk to Support</x-ui.action-link>
                    </div>
                </div>
            </article>

            <article class="home-panel home-reveal rounded-3xl xl:col-span-5">
                <h3 class="text-xl font-semibold text-slate-900">Newsletter</h3>
                <p class="mt-2 text-sm text-slate-600">Get product updates, launch announcements, and support advisories.</p>
                <form id="newsletterForm" class="mt-4 space-y-3" novalidate>
                    <div>
                        <label for="newsletterEmail" class="mb-2 block text-sm font-semibold text-slate-700">Work Email</label>
                        <input id="newsletterEmail" type="email" class="block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10" placeholder="you@organization.com" required>
                    </div>
                    <button type="submit" id="newsletterSubmitBtn" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20">Subscribe</button>
                    <p id="newsletterStatus" class="min-h-[1.25rem] text-sm font-medium text-slate-600"></p>
                </form>
            </article>
        </div>
    </section>

   

    

   

   

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STRATEGIC PRESENCE: NORTHERN HUB --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-northern-hub bg-transparent py-12 md:py-16">
            <div class="home-reveal relative overflow-hidden rounded-[var(--ui-radius-card)] border border-slate-200/80 shadow-[var(--ui-shadow-card)] glass-card">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10 xl:p-12">
                        <h2 class="font-display text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl lg:text-[2rem]">Strategic Presence: Northern Hub</h2>
                        <p class="mt-4 max-w-lg text-sm leading-6 text-slate-600 md:text-[15px] md:leading-7">
                            Our state-of-the-art reference laboratory in Lucknow serves as the nerve center for North India, ensuring specialized pathology samples reach analysis within 6 hours of collection.
                        </p>

                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:#1A4D2E">
                                    <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-800">24/7 Operational Diagnostic Facility</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full" style="background:#1A4D2E">
                                    <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-800">Dedicated Cold-Chain Logistics Hub</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-600">
                                    <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-800">AI-Integrated Result Validation</span>
                            </li>
                        </ul>

                        <div class="mt-8">
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:scale-105 hover:bg-primary-700">
                                Find a Collection Center
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Right: Image with location badge --}}
                    <div class="relative min-h-[280px] lg:min-h-[420px]">
                        <img
                            src="{{ asset('upload/corousel/image4.jpg') }}"
                            alt="Biogenix Northern Hub Laboratory in Lucknow"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >
                        {{-- Location badge --}}
                        <div class="absolute bottom-4 right-4 z-10 glass-card rounded-2xl px-5 py-3 sm:bottom-6 sm:right-6">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Center of Excellence</p>
                            <p class="font-display mt-1 text-lg font-bold tracking-tight text-slate-950">Lucknow, UP</p>
                            <p class="text-xs font-medium text-slate-500">Regional Reference Lab #01</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- TEST YOUR DIAGNOSTIC PRECISION --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-quiz-cta bg-transparent py-6 md:py-8">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal overflow-hidden rounded-3xl bg-primary-600 px-6 py-10 shadow-[0_24px_55px_rgba(26,77,46,0.25)] sm:px-10 md:flex md:items-center md:justify-between md:px-12 md:py-12 lg:px-16">
                <div class="max-w-xl">
                    <h2 class="font-display text-2xl font-semibold tracking-tight text-white md:text-3xl lg:text-[2rem]">Test Your Diagnostic<br>Precision</h2>
                    <p class="mt-3 text-sm leading-6 text-white/80 md:text-[15px] md:leading-7">
                        Take our 4-minute kit assessment and unlock a 15% discount code on your first clinical order.
                    </p>
                </div>
                <div class="mt-6 md:mt-0 md:ml-8 md:shrink-0">
                    <a href="{{ route('diagnostic-quiz') }}" class="inline-flex items-center gap-2.5 rounded-xl border border-secondary-700/20 bg-secondary-600 px-7 py-3.5 text-sm font-semibold text-primary-800 shadow-sm transition hover:scale-105 hover:bg-secondary-500 glow-orange">
                        Start Quiz
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- FINAL CTA STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-cta py-12 text-white md:py-14">
        <div class="mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <h2 class="font-display text-2xl font-semibold text-white md:text-4xl">Need a faster procurement decision?</h2>
                <p class="mx-auto mt-3 max-w-3xl text-sm text-slate-200 md:text-base">
                    Generate a compliant MRP-only quote instantly, or schedule a meeting with our team for institutional onboarding and product consultation.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <x-ui.action-link :href="route('quotation.create')" class="min-h-11 px-5 hover-lift glow-orange">Generate Quote</x-ui.action-link>
                    <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5 hover-lift">Book a Meeting</x-ui.action-link>
                </div>
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
            if (!track || total === 0) return;
            index = (target + total) % total;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            paintDots();
        }

        function startAuto() {
            if (total <= 1) return;
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

        if (total > 0) {
            moveTo(0);
            startAuto();
        }

        const revealItems = Array.from(document.querySelectorAll('.home-reveal'));
        let revealFrameId = null;

        // This keeps section animation active while the user scrolls through the page.
        function refreshRevealItems() {
            revealFrameId = null;

            if (!revealItems.length) {
                return;
            }

            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            const visibleTopLimit = viewportHeight * 0.9;
            const visibleBottomLimit = viewportHeight * 0.08;

            revealItems.forEach(function (item) {
                const rect = item.getBoundingClientRect();
                const isVisible = rect.top <= visibleTopLimit && rect.bottom >= visibleBottomLimit;

                item.classList.toggle('is-visible', isVisible);
            });
        }

        // This avoids repeated layout work when scroll fires many times quickly.
        function queueRevealRefresh() {
            if (revealFrameId !== null) {
                return;
            }

            revealFrameId = window.requestAnimationFrame(refreshRevealItems);
        }

        queueRevealRefresh();
        window.addEventListener('load', queueRevealRefresh);
        window.addEventListener('resize', queueRevealRefresh);
        window.addEventListener('scroll', queueRevealRefresh, { passive: true });

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
                    newsletterStatus.classList.remove('text-primary-600');
                    return;
                }

                newsletterBtn.disabled = true;
                newsletterBtn.classList.add('cursor-not-allowed', 'opacity-70');
                newsletterBtn.setAttribute('aria-disabled', 'true');
                newsletterStatus.textContent = 'You are subscribed. Thank you.';
                newsletterStatus.classList.remove('text-rose-600');
                newsletterStatus.classList.add('text-primary-600');
                newsletterForm.reset();

                setTimeout(function () {
                    newsletterBtn.disabled = false;
                    newsletterBtn.classList.remove('cursor-not-allowed', 'opacity-70');
                    newsletterBtn.setAttribute('aria-disabled', 'false');
                }, 500);
            });
        }

        /* ─── Category Carousel Auto Scroll ─── */
        const catCarousel = document.getElementById('categoryCarousel');
        let catScrollInterval;
        
        function startCatScroll() {
            if (!catCarousel) return;
            if (catScrollInterval) clearInterval(catScrollInterval);
            
            catScrollInterval = setInterval(() => {
                const maxScrollLeft = catCarousel.scrollWidth - catCarousel.clientWidth;
                if (catCarousel.scrollLeft >= maxScrollLeft - 1) {
                    catCarousel.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    const itemWidth = catCarousel.children[0]?.clientWidth || 300;
                    const gap = 14; // ≈ 0.9rem
                    catCarousel.scrollBy({ left: itemWidth + gap, behavior: 'smooth' });
                }
            }, 3000);
        }
        
        function stopCatScroll() {
            if (catScrollInterval) clearInterval(catScrollInterval);
        }
        
        if (catCarousel) {
            startCatScroll();
            catCarousel.addEventListener('mouseenter', stopCatScroll);
            catCarousel.addEventListener('mouseleave', startCatScroll);
            catCarousel.addEventListener('touchstart', stopCatScroll, { passive: true });
            catCarousel.addEventListener('touchend', startCatScroll, { passive: true });

            const catPrev = document.getElementById('catPrev');
            const catNext = document.getElementById('catNext');

            if (catPrev) {
                catPrev.addEventListener('mouseenter', stopCatScroll);
                catPrev.addEventListener('mouseleave', startCatScroll);
                catPrev.addEventListener('click', () => {
                    const itemWidth = catCarousel.children[0]?.clientWidth || 300;
                    catCarousel.scrollBy({ left: -(itemWidth + 14), behavior: 'smooth' });
                });
            }

            if (catNext) {
                catNext.addEventListener('mouseenter', stopCatScroll);
                catNext.addEventListener('mouseleave', startCatScroll);
                catNext.addEventListener('click', () => {
                    const itemWidth = catCarousel.children[0]?.clientWidth || 300;
                    catCarousel.scrollBy({ left: itemWidth + 14, behavior: 'smooth' });
                });
            }
        }
    });
</script>

@endpush
