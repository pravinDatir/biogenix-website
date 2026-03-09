<div class="full-bleed">
    <section class="relative overflow-hidden bg-slate-950 py-16 text-white md:py-24">
        <img src="{{ asset('images/image4.jpg') }}" alt="Biogenix company profile" class="absolute inset-0 h-full w-full object-cover opacity-30" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-slate-900/40"></div>
        <div class="container relative z-10">
            <x-badge variant="info" class="!border-white/30 !bg-white/10 !text-blue-100">Company Profile</x-badge>
            <h1 class="mt-4 max-w-4xl text-3xl font-semibold leading-tight text-white sm:text-4xl md:text-6xl">Driving trustworthy diagnostics access across India.</h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-slate-100 md:text-lg">
                Biogenix blends product quality, responsive service, and healthcare domain expertise to support institutions, labs, and care providers.
            </p>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Founded', 'value' => '2017'],
                ['label' => 'Product Categories', 'value' => '4+'],
                ['label' => 'Operational Hub', 'value' => 'Lucknow'],
                ['label' => 'Service Coverage', 'value' => 'PAN India'],
            ] as $stat)
                <article class="saas-card text-center">
                    <p class="text-3xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $stat['label'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Our Journey" subtitle="A focused path from regional diagnostic support to scalable healthcare operations." />
            <div class="mt-6 timeline">
                <div class="timeline-item">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">2017</p>
                    <h3 class="mt-1 text-base font-semibold text-slate-900">Foundation</h3>
                    <p class="mt-1 text-sm text-slate-600">Biogenix launched with a mission to improve access to reliable diagnostic products in North India.</p>
                </div>
                <div class="timeline-item">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">2020</p>
                    <h3 class="mt-1 text-base font-semibold text-slate-900">Portfolio Expansion</h3>
                    <p class="mt-1 text-sm text-slate-600">Added advanced instruments, reagents, and consumables for wider clinical workflows.</p>
                </div>
                <div class="timeline-item">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">2023</p>
                    <h3 class="mt-1 text-base font-semibold text-slate-900">Digital Enablement</h3>
                    <p class="mt-1 text-sm text-slate-600">Introduced online catalog and quotation workflows for B2B/B2C customer journeys.</p>
                </div>
                <div class="timeline-item">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">2026</p>
                    <h3 class="mt-1 text-base font-semibold text-slate-900">Scale & Compliance</h3>
                    <p class="mt-1 text-sm text-slate-600">Strengthened quality controls, partner onboarding, and logistics-led fulfillment execution.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Vision, Mission, and Values" subtitle="Our operational philosophy for long-term healthcare impact." />
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
                <article class="saas-card">
                    <h3 class="text-xl font-semibold text-slate-900">Vision</h3>
                    <p class="mt-3 text-sm text-slate-600">To become the most trusted diagnostics partner for institutions and communities across India.</p>
                </article>
                <article class="saas-card">
                    <h3 class="text-xl font-semibold text-slate-900">Mission</h3>
                    <p class="mt-3 text-sm text-slate-600">Deliver high-quality diagnostics products with dependable support and transparent fulfillment workflows.</p>
                </article>
                <article class="saas-card">
                    <h3 class="text-xl font-semibold text-slate-900">Core Values</h3>
                    <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-slate-600">
                        <li>Clinical reliability</li>
                        <li>Customer commitment</li>
                        <li>Execution excellence</li>
                        <li>Continuous improvement</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-5 lg:grid-cols-2">
            <article class="saas-card">
                <h2 class="ui-section-title">Leadership & Teams</h2>
                <p class="mt-2 text-sm text-slate-600">Our management, operations, and support teams collaborate to deliver end-to-end diagnostics service reliability.</p>
                <div class="mt-4 space-y-2">
                    <div class="rounded-lg border border-slate-200 p-3 text-sm text-slate-700"><strong>Management:</strong> Strategy, governance, and growth planning</div>
                    <div class="rounded-lg border border-slate-200 p-3 text-sm text-slate-700"><strong>Operations:</strong> Inventory, dispatch, logistics orchestration</div>
                    <div class="rounded-lg border border-slate-200 p-3 text-sm text-slate-700"><strong>Support:</strong> Product guidance, issue resolution, escalation</div>
                </div>
            </article>

            <article class="saas-card">
                <h2 class="ui-section-title">Quality & Compliance</h2>
                <p class="mt-2 text-sm text-slate-600">Biogenix follows robust quality and compliance practices to ensure safe and consistent product delivery.</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    @foreach (['ISO 13485', 'GMP Aligned', 'QA Audited', 'CDSCO Ready'] as $badge)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-center text-sm font-semibold text-slate-800">{{ $badge }}</div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Leadership Team" subtitle="Cross-functional leaders driving product quality, customer outcomes, and operational excellence." />
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['name' => 'Arjun Mehta', 'role' => 'Managing Director', 'copy' => 'Leads strategic growth, governance, and partnerships.'],
                    ['name' => 'Neha Srivastava', 'role' => 'Head - Clinical Solutions', 'copy' => 'Guides product fit and technical diagnostics enablement.'],
                    ['name' => 'Rahul Verma', 'role' => 'Head - Operations', 'copy' => 'Owns inventory planning, logistics, and service continuity.'],
                ] as $leader)
                    <article class="saas-card">
                        <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold text-blue-700">
                            {{ strtoupper(substr($leader['name'], 0, 1)) }}
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $leader['name'] }}</h3>
                        <p class="mt-1 text-sm font-medium text-blue-700">{{ $leader['role'] }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $leader['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Team Tree View" subtitle="A simple view of how Biogenix teams collaborate from strategy to delivery." />
            <div class="mt-8 space-y-5">
                <div class="flex justify-center">
                    <div class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-900 shadow-sm">
                        Executive Leadership
                    </div>
                </div>
                <div class="h-6 w-px mx-auto bg-slate-300"></div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Clinical & Product</p>
                        <p class="mt-1 text-xs text-slate-600">Category specialists and technical advisors</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Operations & Logistics</p>
                        <p class="mt-1 text-xs text-slate-600">Inventory, dispatch, and fulfillment teams</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Customer Success</p>
                        <p class="mt-1 text-xs text-slate-600">Support, onboarding, and escalation desk</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Global / India Presence" subtitle="A scalable service footprint with Lucknow as a strong operational center." />
            <div class="mt-6 map-box">
                <iframe
                    class="h-96 w-full"
                    src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Biogenix Presence Map"
                ></iframe>
            </div>
        </div>
    </section>
</div>
