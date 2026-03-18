@push('styles')
<style>
    .home-page {
        --home-orange: #ff6a00;
        --home-cyan: #38bdf8;
        --home-navy: #07111f;
        --home-panel: rgba(255, 255, 255, 0.74);
        background:
            radial-gradient(circle at top left, rgba(255, 106, 0, 0.12), transparent 23%),
            radial-gradient(circle at 88% 14%, rgba(56, 189, 248, 0.12), transparent 20%),
            linear-gradient(180deg, #f5f9ff 0%, #eef4ff 50%, #f9fbff 100%);
    }

    .home-page .home-card,
    .home-page .home-panel {
        border-color: rgba(148, 163, 184, 0.18);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.78));
        box-shadow: 0 24px 55px rgba(15, 23, 42, 0.07);
        backdrop-filter: blur(18px);
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
        background: linear-gradient(135deg, #ff6a00, #ff8f3f);
        box-shadow: 0 18px 30px rgba(255, 106, 0, 0.28);
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
        border-radius: 2rem;
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
        border-radius: 1.75rem;
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
        border-radius: 1.5rem;
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
        border-radius: 2rem;
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
        margin-top: -3.8rem;
        z-index: 20;
    }

    .home-stats-shell {
        border: 1px solid rgba(255, 255, 255, 0.6);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.72));
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.1);
        backdrop-filter: blur(24px);
    }

    .home-category-grid {
        display: grid;
        gap: 0.9rem;
    }

    .home-category-tile {
        position: relative;
        overflow: hidden;
        min-height: 0;
        display: flex;
        flex-direction: column;
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.72);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.9));
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
        isolation: isolate;
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
        background: linear-gradient(135deg, #ff6a00, #ff8f3f);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #ffffff;
        box-shadow: 0 14px 24px rgba(255, 106, 0, 0.22);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .home-category-action:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #ed6200, #ff7b21);
        box-shadow: 0 16px 28px rgba(255, 106, 0, 0.28);
    }

    @media (min-width: 640px) {
        .home-category-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 768px) {
        .home-category-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .home-category-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1280px) {
        .home-category-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }

    .home-solutions {
        background:
            radial-gradient(circle at 15% 20%, rgba(255, 106, 0, 0.11), transparent 24%),
            radial-gradient(circle at 88% 10%, rgba(56, 189, 248, 0.12), transparent 18%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .home-trust {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.52), rgba(255, 255, 255, 0.82));
        backdrop-filter: blur(14px);
    }

    .home-cta {
        background:
            radial-gradient(circle at top right, rgba(255, 106, 0, 0.18), transparent 28%),
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
            margin-top: -2.2rem;
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
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_22%_28%,rgba(7,17,31,0.28),transparent_22%),linear-gradient(105deg,rgba(7,17,31,0.98)_10%,rgba(7,17,31,0.84)_44%,rgba(7,17,31,0.62)_62%,rgba(7,17,31,0.9)_100%)]"></div>

                        <div class="relative z-10 mx-auto grid min-h-[calc(100vh-88px)] w-full max-w-none grid-cols-1 gap-8 px-4 py-10 pb-28 sm:px-6 md:py-14 lg:grid-cols-12 lg:px-8 xl:px-10">
                            <div class="home-reveal flex flex-col justify-center lg:col-span-7">
                                <div class="home-hero-copy-shell">
                                    <x-badge variant="inverse" class="w-fit">{{ $slide['tag'] }}</x-badge>
                                    <h1 class="home-hero-title mt-5 max-w-4xl font-['Sora'] text-5xl font-semibold tracking-tight text-white md:text-6xl lg:text-7xl">{{ $slide['title'] }}</h1>
                                    <p class="home-hero-copy mt-6 max-w-2xl text-base leading-8 text-slate-100 md:text-xl">{{ $slide['copy'] }}</p>

                                    <div class="mt-8 flex flex-wrap items-center gap-3">
                                        <x-ui.action-link :href="route('products.index')" variant="secondary" class="min-h-11 px-5">Browse Catalog</x-ui.action-link>
                                        <x-ui.action-link :href="route('quotation.create')" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                                        <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5">Book Meeting</x-ui.action-link>
                                    </div>

                                    <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-200">
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Healthcare-first sourcing</span>
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Fast quotation workflow</span>
                                        <span class="home-hero-chip-text rounded-full border border-white/12 bg-white/8 px-4 py-2 backdrop-blur">Structured support handoff</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-reveal flex items-end lg:col-span-5">
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
    {{-- SEARCH BAR STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="relative z-30 -mt-10 mb-4 px-4 sm:px-6 lg:px-8 xl:px-10">
        <div class="mx-auto w-full max-w-4xl rounded-3xl bg-white p-3 shadow-xl ring-1 ring-slate-900/5 sm:p-4">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197M15.803 15.803A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input
                        type="search"
                        name="search"
                        id="homeProductSearch"
                        class="block w-full rounded-2xl border-0 bg-slate-50 py-4 pl-12 pr-4 text-base text-slate-900 ring-1 ring-inset ring-slate-200 transition placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-primary-600 focus:outline-none sm:text-lg"
                        placeholder="Search products, categories, or keywords..."
                        aria-label="Search catalog"
                        required
                    >
                </div>
                <button type="submit" class="inline-flex min-h-[3.5rem] items-center justify-center gap-2 rounded-2xl bg-primary-600 px-8 text-base font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/30">
                    Search Catalog
                </button>
            </form>
            <div class="mt-3 hidden items-center justify-center gap-2 px-2 sm:flex">
                <p class="text-xs font-medium text-slate-500">Popular searches:</p>
                <div class="flex gap-2">
                    <a href="{{ route('products.index', ['search' => 'hematology']) }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">Hematology</a>
                    <a href="{{ route('products.index', ['search' => 'reagents']) }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">Reagents</a>
                    <a href="{{ route('products.index', ['search' => 'consumables']) }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">Consumables</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STATS / SOCIAL PROOF STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-stats border-b border-transparent bg-transparent py-8">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <dl class="home-stats-shell grid grid-cols-2 gap-6 rounded-[2rem] px-5 py-6 sm:grid-cols-4 sm:px-6">
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
    <section class="home-categories bg-transparent py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="Core Product Categories" subtitle="Designed for modern diagnostics workflows and scalable healthcare operations." />
            </div>
            <div class="home-category-grid mt-6">
                @forelse (($productCategories ?? collect()) as $category)
                    @php
                        $tileClass = 'home-category-tile--standard';
                        $imagePath = $category->default_image_path ?: 'upload/categories/image1.jpg';
                        $categoryCopy = \Illuminate\Support\Str::limit($category->description ?: $category->application ?: 'Explore products from this category.', 60);
                    @endphp
                    <article class="home-category-tile home-reveal group {{ $tileClass }}">
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
                                <h3 class="font-['Sora'] text-lg font-semibold tracking-tight text-slate-950">{{ $category->name }}</h3>
                                <p class="home-category-copy mt-1.5 text-[13px] leading-5.5">
                                    {{ $categoryCopy }}
                                </p>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('products.index') }}" class="home-category-action">Explore</a>
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
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CLINICAL & BUSINESS SOLUTIONS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-solutions py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="Clinical &amp; Business Solutions" subtitle="Purpose-built pathways for B2B institutions and B2C healthcare buyers." />
            </div>
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <article class="home-route-card home-reveal">
                    <img src="{{ asset('upload/corousel/image2.jpg') }}" alt="B2B healthcare procurement" loading="lazy" decoding="async">
                    <span class="home-route-card__chip">B2B Operations</span>
                    <div class="home-route-card__content">
                        <div class="home-route-card__panel">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For institutions and channel partners</p>
                            <h3 class="mt-3 text-2xl font-semibold text-white">Distributor, lab, and hospital buying with structured support.</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-200">Account-based ordering, product discovery, quotation approval flow, and coordinated support for healthcare institutions.</p>
                            <div class="mt-5">
                                <x-ui.action-link :href="route('login', ['user_type' => 'b2b'])" variant="inverse">B2B Login</x-ui.action-link>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="home-route-card home-reveal">
                    <img src="{{ asset('upload/corousel/image5.jpg') }}" alt="B2C healthcare retail buying" loading="lazy" decoding="async">
                    <span class="home-route-card__chip">B2C Access</span>
                    <div class="home-route-card__content">
                        <div class="home-route-card__panel">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/65">For direct and retail buyers</p>
                            <h3 class="mt-3 text-2xl font-semibold text-white">A cleaner buying path for faster product access.</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-200">Simple MRP-visible catalog flow with quick quotation generation and immediate assistance through support channels.</p>
                            <div class="mt-5">
                                <x-ui.action-link :href="route('login', ['user_type' => 'b2c'])" variant="inverse">B2C Login</x-ui.action-link>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PARTNER / BRAND TRUST BAR --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-trust border-y border-slate-100 py-8">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
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
    <section class="home-newsletter bg-transparent py-12 md:py-16">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-5 px-4 sm:px-6 xl:grid-cols-12 lg:px-8 xl:px-10">
            <article class="home-delivery-card home-reveal rounded-3xl p-6 text-white shadow-[0_24px_55px_rgba(255,106,0,0.25)] xl:col-span-7 md:p-8">
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

            <article class="home-panel home-reveal xl:col-span-5">
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
    {{-- WHY BIOGENIX --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-why bg-transparent py-12 md:py-16">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-6 px-4 sm:px-6 lg:grid-cols-12 lg:px-8 xl:px-10">
            <article class="home-reveal min-h-[18rem] overflow-hidden rounded-3xl border border-slate-200 bg-slate-900 text-white shadow-xl md:min-h-[22rem] lg:col-span-6">
                <img src="{{ asset('upload/corousel/image4.jpg') }}" alt="Biogenix diagnostics support" class="h-full w-full object-cover opacity-80" loading="lazy" decoding="async">
            </article>

            <article class="home-reveal lg:col-span-6">
                <x-ui.section-heading title="Why Leading Teams Choose Biogenix" subtitle="A modern diagnostics partner model inspired by enterprise healthcare standards." />
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['title' => 'Product Depth', 'copy' => 'Broad portfolio across IVD, reagents, instruments, and consumables.', 'icon' => 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2'],
                        ['title' => 'Operational Reliability', 'copy' => 'Structured order workflows and responsive escalation support.', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Compliance-Ready', 'copy' => 'Quality-first processes aligned to regulated healthcare operations.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Consultative Service', 'copy' => 'Pre-sale and post-sale support for clinical and procurement teams.', 'icon' => 'M8 10h8M8 14h5M12 3c4.97 0 9 3.58 9 8 0 1.95-.78 3.74-2.07 5.16L20 21l-5.04-1.68A10.5 10.5 0 0 1 12 20c-4.97 0-9-3.58-9-8s4.03-9 9-9Z'],
                    ] as $value)
                        <article class="group flex gap-3.5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
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
    <section class="home-testimonials bg-transparent py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="What Our Clients Say" subtitle="Trusted by procurement heads, lab managers, and healthcare institutions across India." />
            </div>
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
                    <article class="home-panel home-reveal flex flex-col gap-4">
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
    <section class="home-insights bg-transparent py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <x-ui.section-heading title="Insights &amp; Updates" subtitle="Explore diagnostics trends, product updates, and operational best practices." />
            </div>
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
                    <article class="home-panel home-reveal group flex flex-col gap-4">
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
    {{-- FREQUENTLY ASKED QUESTIONS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="mx-auto w-full max-w-3xl px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Frequently Asked Questions</h2>
                <p class="mt-4 text-base text-slate-600">Got questions? We have answers. If you can't find what you're looking for, please <a href="{{ route('contact') }}" class="font-semibold text-primary-600 hover:text-primary-500">contact our support team</a>.</p>
            </div>
            <div class="mt-10 space-y-4">
                @foreach ([
                    ['q' => 'Do you ship to all locations in India?', 'a' => 'Yes, we provide pan-India delivery. We also offer same-day or next-day delivery in select regions, including Lucknow and surrounding areas, depending on product availability and current stock levels.'],
                    ['q' => 'How can I request a bulk or institutional quotation?', 'a' => 'You can use the "Generate Quote" button available on most pages to instantly build an MRP quotation. For negotiated or institutional pricing, we recommend creating a B2B account or booking a meeting with our sales team.'],
                    ['q' => 'Are your medical supplies and instruments certified?', 'a' => 'Absolutely. We only partner with leading brands and manufacturers. All our products comply with standard regulations such as ISO, CE Marking, and FDA where applicable.'],
                    ['q' => 'What is your return or replacement policy?', 'a' => 'We offer replacements for items that arrive damaged, defective, or do not match the order description. Due to the sensitive nature of diagnostic supplies, returns for correctly fulfilled products are handled on a case-by-case basis. Please review our full return policy for details.'],
                    ['q' => 'Do you provide post-installation support for instruments?', 'a' => 'Yes. For medium to large diagnostic instruments, we coordinate with manufacturer technicians to ensure proper installation, basic operational training, and support.'],
                ] as $index => $faq)
                    <details class="group rounded-3xl border border-slate-200 bg-white shadow-sm [&_summary::-webkit-details-marker]:hidden" {{ $index === 0 ? 'open' : '' }}>
                        <summary class="flex cursor-pointer items-center justify-between gap-4 p-6 text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-600/30 rounded-3xl group-open:rounded-b-none">
                            <h3 class="text-lg font-semibold">{{ $faq['q'] }}</h3>
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-500 transition-transform duration-300 group-open:-rotate-180 group-open:bg-primary-50 group-open:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 pt-0 text-slate-600">
                            <p class="text-base leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- FINAL CTA STRIP --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="home-cta py-12 text-white md:py-14">
        <div class="mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <div class="home-reveal">
                <h2 class="font-['Sora'] text-2xl font-semibold text-white md:text-4xl">Need a faster procurement decision?</h2>
                <p class="mx-auto mt-3 max-w-3xl text-sm text-slate-200 md:text-base">
                    Generate a compliant MRP-only quote instantly, or schedule a meeting with our team for institutional onboarding and product consultation.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <x-ui.action-link :href="route('quotation.create')" class="min-h-11 px-5">Generate Quote</x-ui.action-link>
                    <x-ui.action-link :href="route('book-meeting')" variant="inverse" class="min-h-11 px-5">Book a Meeting</x-ui.action-link>
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
