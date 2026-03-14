@php
    $heroSlides = [
        [
            'tag' => 'Diagnostic Innovation',
            'title' => 'A One-Stop Diagnostic Powerhouse for India.',
            'copy' => 'From rapid IVD kits to intelligent instruments, Biogenix helps labs and hospitals scale with speed, quality, and dependable support.',
            'image' => asset('images/image1.jpg'),
        ],
        [
            'tag' => 'Clinical Workflow',
            'title' => 'Precision technologies for modern care delivery.',
            'copy' => 'Integrated catalog, quote, and fulfillment workflows built for high-performance diagnostics teams.',
            'image' => asset('images/image3.jpg'),
        ],
        [
            'tag' => 'Lucknow Operations',
            'title' => 'Fast logistics support with trusted service execution.',
            'copy' => 'Same-day assistance for priority requirements with transparent communication and support.',
            'image' => asset('images/hema1.jpg'),
        ],
    ];

    $cardClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md';
    $darkCardClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm';
    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
@endphp

<div>
    <section class="relative min-h-[calc(100vh-88px)] overflow-hidden bg-slate-900 text-white">
        <div class="absolute inset-0 overflow-hidden" id="heroCarousel">
            <div id="heroTrack" class="flex h-full w-full transition-transform duration-700 ease-out">
                @foreach ($heroSlides as $slide)
                    <article class="relative h-full w-full shrink-0">
                        <img
                            src="{{ $slide['image'] }}"
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

        <div class="pointer-events-none absolute bottom-5 left-0 right-0 z-20 sm:bottom-6">
            <div class="container flex items-center justify-between gap-3">
                <div id="heroDots" class="pointer-events-auto flex items-center gap-2">
                    @foreach ($heroSlides as $slide)
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
                    <button id="heroPrev" type="button" class="inline-flex h-9 min-w-[3.75rem] items-center justify-center rounded-full border border-white/50 bg-white/10 px-3 text-xs font-semibold text-white transition hover:bg-white/20">Prev</button>
                    <button id="heroNext" type="button" class="inline-flex h-9 min-w-[3.75rem] items-center justify-center rounded-full border border-white/50 bg-white/10 px-3 text-xs font-semibold text-white transition hover:bg-white/20">Next</button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Core Product Categories" subtitle="Designed for modern diagnostics workflows and scalable healthcare operations." />
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['name' => 'IVD Kits', 'copy' => 'Rapid, reliable kits for daily diagnostics.', 'image' => asset('images/home1.jpg')],
                    ['name' => 'Reagents', 'copy' => 'Validated chemistry and molecular reagents.', 'image' => asset('images/hema2.jpg')],
                    ['name' => 'Instruments', 'copy' => 'High-throughput systems for clinical teams.', 'image' => asset('images/image2.jpg')],
                    ['name' => 'Consumables', 'copy' => 'Lab essentials engineered for consistency.', 'image' => asset('images/home3.jpg')],
                ] as $item)
                    <article class="{{ $cardClass }}">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-40 w-full rounded-2xl object-cover" loading="lazy" decoding="async">
                        <div class="space-y-2 pt-4">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $item['name'] }}</h3>
                            <p class="text-sm text-slate-600">{{ $item['copy'] }}</p>
                            <x-ui.action-link :href="route('products.index')" variant="secondary">Explore</x-ui.action-link>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Clinical & Business Solutions" subtitle="Purpose-built pathways for B2B institutions and B2C healthcare buyers." />
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <article class="{{ $darkCardClass }}">
                    <x-badge variant="info">B2B Operations</x-badge>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">Distributor, Lab, and Hospital Enablement</h3>
                    <p class="mt-2 text-sm text-slate-600">Account-based ordering, product discovery, quotation approval flow, and coordinated support for healthcare institutions.</p>
                    <div class="mt-5">
                        <x-ui.action-link :href="route('login', ['user_type' => 'b2b'])">B2B Login</x-ui.action-link>
                    </div>
                </article>
                <article class="{{ $darkCardClass }}">
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

            <article class="{{ $darkCardClass }} xl:col-span-5">
                <h3 class="text-xl font-semibold text-slate-900">Newsletter</h3>
                <p class="mt-2 text-sm text-slate-600">Get product updates, launch announcements, and support advisories.</p>
                <form id="newsletterForm" class="mt-4 space-y-3" novalidate>
                    <div>
                        <label for="newsletterEmail" class="mb-2 block text-sm font-semibold text-slate-700">Work Email</label>
                        <input id="newsletterEmail" type="email" class="{{ $inputClass }}" placeholder="you@organization.com" required>
                    </div>
                    <button type="submit" id="newsletterSubmitBtn" class="{{ $primaryButtonClass }} w-full">Subscribe</button>
                    <p id="newsletterStatus" class="min-h-[1.25rem] text-sm font-medium text-slate-600"></p>
                </form>
            </article>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-6 lg:grid-cols-12">
            <article class="min-h-[18rem] overflow-hidden rounded-3xl border border-slate-200 bg-slate-900 text-white shadow-xl md:min-h-[22rem] lg:col-span-6">
                <img src="{{ asset('images/image4.jpg') }}" alt="Biogenix diagnostics support" class="h-full w-full object-cover opacity-80" loading="lazy" decoding="async">
            </article>

            <article class="lg:col-span-6">
                <x-ui.section-heading title="Why Leading Teams Choose Biogenix" subtitle="A modern diagnostics partner model inspired by enterprise healthcare standards." />
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['title' => 'Product Depth', 'copy' => 'Broad portfolio across IVD, reagents, instruments, and consumables.'],
                        ['title' => 'Operational Reliability', 'copy' => 'Structured order workflows and responsive escalation support.'],
                        ['title' => 'Compliance-Ready', 'copy' => 'Quality-first processes aligned to regulated healthcare operations.'],
                        ['title' => 'Consultative Service', 'copy' => 'Pre-sale and post-sale support for clinical and procurement teams.'],
                    ] as $value)
                        <article class="{{ $darkCardClass }}">
                            <h3 class="text-base font-semibold text-slate-900">{{ $value['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $value['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Insights & Updates" subtitle="Explore diagnostics trends, product updates, and operational best practices." />
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['title' => 'Scaling Diagnostic Labs in Tier-2 Cities', 'tag' => 'Operations', 'copy' => 'How regional labs can improve turnaround time with better procurement and support.'],
                    ['title' => 'Choosing the Right Reagent Mix', 'tag' => 'Product Guide', 'copy' => 'A practical framework for balancing consistency, throughput, and budget.'],
                    ['title' => 'Checklist for New Instrument Rollouts', 'tag' => 'Implementation', 'copy' => 'Deployment, training, and support essentials for successful onboarding.'],
                ] as $insight)
                    <article class="{{ $darkCardClass }}">
                        <x-badge variant="default">{{ $insight['tag'] }}</x-badge>
                        <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $insight['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $insight['copy'] }}</p>
                        <x-ui.action-link href="#" variant="secondary" class="mt-4">Read More</x-ui.action-link>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

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
        const track = document.getElementById('heroTrack');
        const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
        const nextBtn = document.getElementById('heroNext');
        const prevBtn = document.getElementById('heroPrev');
        const carousel = document.getElementById('heroCarousel');
        let index = 0;
        let intervalId = null;
        const total = dots.length;

        function paintDots() {
            dots.forEach(function (dot, dotIndex) {
                const active = dotIndex === index;
                dot.classList.toggle('bg-white', active);
                dot.classList.toggle('w-10', active);
                dot.classList.toggle('bg-white/40', !active);
            });
        }

        function moveTo(target) {
            index = (target + total) % total;
            if (track) {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
            }
            paintDots();
        }

        function startAuto() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(function () {
                moveTo(index + 1);
            }, 5000);
        }

        function stopAuto() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const target = Number(dot.getAttribute('data-slide-index') || 0);
                moveTo(target);
                startAuto();
            });
        });

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                moveTo(index + 1);
                startAuto();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                moveTo(index - 1);
                startAuto();
            });
        }

        if (carousel) {
            carousel.addEventListener('mouseenter', stopAuto);
            carousel.addEventListener('mouseleave', startAuto);
        }

        moveTo(0);
        startAuto();

        const newsletterForm = document.getElementById('newsletterForm');
        const newsletterBtn = document.getElementById('newsletterSubmitBtn');
        const newsletterStatus = document.getElementById('newsletterStatus');
        const newsletterEmail = document.getElementById('newsletterEmail');

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
