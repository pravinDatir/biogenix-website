<div class="full-bleed bg-slate-50 min-h-screen">
    <!-- Premium Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 py-20 text-white lg:py-28">
        <img src="{{ asset('images/image4.jpg') }}" alt="Biogenix company profile" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/70 to-blue-900/30"></div>
        <div class="container relative z-10">
            <div class="max-w-4xl">
                <x-badge variant="info" class="mb-6 !border-white/20 !bg-white/10 !text-blue-100 !px-4 !py-1.5 backdrop-blur-md">Company Profile</x-badge>
                <h1 class="text-4xl font-bold leading-tight tracking-tight sm:text-5xl md:text-6xl lg:text-7xl text-white">
                    Driving trustworthy diagnostics access across India.
                </h1>
                <p class="mt-8 max-w-2xl text-lg leading-relaxed text-slate-300 md:text-xl">
                    Biogenix blends product quality, responsive service, and healthcare domain expertise to support institutions, labs, and care providers.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section overlapping hero -->
    <section class="-mt-12 relative z-20 pb-16">
        <div class="container">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Founded', 'value' => '2017', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['label' => 'Product Categories', 'value' => '4+', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['label' => 'Operational Hub', 'value' => 'Lucknow', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'Service Coverage', 'value' => 'PAN India', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $stat)
                    <article class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-blue-300">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Vision/Mission/Values -->
    <section class="py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-900/[0.02] transform -skew-y-3 origin-top-left -z-10"></div>
        <div class="container">
            <x-ui.section-heading title="Our Philosophy" subtitle="Vision, Mission, and Values driving our long-term healthcare impact." />
            <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <article class="relative rounded-3xl border border-blue-100 bg-white p-8 shadow-xl overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Vision</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 relative z-10">To become the most trusted diagnostics partner for institutions and communities across India.</p>
                </article>
                <article class="relative rounded-3xl border border-emerald-100 bg-emerald-50/50 p-8 shadow-xl overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Mission</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 relative z-10">Deliver high-quality diagnostics products with dependable support and transparent fulfillment workflows.</p>
                </article>
                <article class="relative rounded-3xl border border-purple-100 bg-white p-8 shadow-xl overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A120.1 120.1 0 0010 1C5.586 1 1.732 3.943.458 8s5.128 7 9.542 7c4.414 0 8.268-2.943 9.542-7-1.274-4.057-5.064-7-9.542-7zm-4.502 6.002a2 2 0 11-4 0 2 2 0 014 0zm8.004 0a2 2 0 11-4 0 2 2 0 014 0zm-4.002 6.002a2 2 0 11-4 0 2 2 0 014 0h-4a2 2 0 114 0zM15 17h-2v2h2v-2zm-4 0h-2v2h2v-2zm-4 0H5v2h2v-2z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Core Values</h3>
                    <ul class="mt-4 space-y-3 relative z-10">
                        @foreach (['Clinical reliability', 'Customer commitment', 'Execution excellence', 'Continuous improvement'] as $val)
                            <li class="flex items-center text-slate-600">
                                <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </span>
                                {{ $val }}
                            </li>
                        @endforeach
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <!-- Modern Journey Timeline -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container max-w-5xl">
            <x-ui.section-heading title="Our Journey" subtitle="A focused path from regional diagnostic support to scalable healthcare operations." />
            
            <div class="mt-16 space-y-12">
                @foreach ([
                    ['year' => '2017', 'title' => 'Foundation', 'desc' => 'Biogenix launched with a mission to improve access to reliable diagnostic products in North India.', 'color' => 'blue'],
                    ['year' => '2020', 'title' => 'Portfolio Expansion', 'desc' => 'Added advanced instruments, reagents, and consumables for wider clinical workflows.', 'color' => 'emerald'],
                    ['year' => '2023', 'title' => 'Digital Enablement', 'desc' => 'Introduced online catalog and quotation workflows for B2B/B2C customer journeys.', 'color' => 'purple'],
                    ['year' => '2026', 'title' => 'Scale & Compliance', 'desc' => 'Strengthened quality controls, partner onboarding, and logistics-led fulfillment execution.', 'color' => 'indigo'],
                ] as $idx => $milestone)
                    <div class="relative flex flex-col md:flex-row items-center justify-between group">
                        <!-- Desktop Line -->
                        @if(!$loop->last)
                            <div class="hidden md:block absolute left-1/2 top-16 bottom-[-3rem] w-px bg-slate-200 -translate-x-1/2"></div>
                        @endif
                        
                        <div class="w-full md:w-[45%] {{ $idx % 2 == 0 ? 'md:text-right md:pr-12' : 'md:order-3 md:pl-12' }}">
                            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-2xl">
                                <p class="text-3xl font-bold tracking-tight text-{{ $milestone['color'] }}-600">{{ $milestone['year'] }}</p>
                                <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $milestone['title'] }}</h3>
                                <p class="mt-3 text-base text-slate-600">{{ $milestone['desc'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Center Node -->
                        <div class="absolute left-1/2 md:flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-{{ $milestone['color'] }}-500 text-white shadow-xl -translate-x-1/2 transform transition-transform group-hover:scale-125 z-10 hidden">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        
                        <!-- Mobile spacing -->
                        <div class="md:hidden h-8 w-px bg-slate-200 my-2"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Leadership / Quality Split -->
    <section class="py-16 md:py-24 bg-slate-950 text-white relative overflow-hidden">
        <!-- Abstract Bg -->
        <div class="absolute top-0 right-0 -mr-48 -mt-48 h-96 w-96 rounded-full bg-blue-600 opacity-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-48 -mb-48 h-96 w-96 rounded-full bg-purple-600 opacity-20 blur-3xl"></div>
        
        <div class="container relative z-10 grid grid-cols-1 gap-12 lg:grid-cols-2">
            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm">
                <h2 class="text-3xl font-bold tracking-tight">Leadership & Teams</h2>
                <p class="mt-4 text-lg text-slate-300">Our management, operations, and support teams collaborate to deliver end-to-end diagnostics service reliability.</p>
                
                <div class="mt-8 space-y-4">
                    <div class="flex items-start rounded-2xl bg-white/5 p-5 border border-white/10">
                        <div class="mr-4 mt-1 rounded-full bg-blue-500 p-2"><svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></div>
                        <div>
                            <p class="font-bold text-white">Management</p>
                            <p class="mt-1 text-sm text-slate-300">Strategy, governance, and growth planning</p>
                        </div>
                    </div>
                    <div class="flex items-start rounded-2xl bg-white/5 p-5 border border-white/10">
                        <div class="mr-4 mt-1 rounded-full bg-emerald-500 p-2"><svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                        <div>
                            <p class="font-bold text-white">Operations</p>
                            <p class="mt-1 text-sm text-slate-300">Inventory, dispatch, logistics orchestration</p>
                        </div>
                    </div>
                    <div class="flex items-start rounded-2xl bg-white/5 p-5 border border-white/10">
                        <div class="mr-4 mt-1 rounded-full bg-purple-500 p-2"><svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg></div>
                        <div>
                            <p class="font-bold text-white">Support</p>
                            <p class="mt-1 text-sm text-slate-300">Product guidance, issue resolution, escalation</p>
                        </div>
                    </div>
                </div>
            </article>

            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm flex flex-col justify-center">
                <h2 class="text-3xl font-bold tracking-tight">Quality & Compliance</h2>
                <p class="mt-4 text-lg text-slate-300">Biogenix follows robust quality and compliance practices to ensure safe and consistent product delivery across all lines.</p>
                <div class="mt-10 grid grid-cols-2 gap-4">
                    @foreach (['ISO 13485', 'GMP Aligned', 'QA Audited', 'CDSCO Ready'] as $badge)
                        <div class="flex items-center justify-center rounded-2xl border border-blue-500/30 bg-blue-900/40 p-6 text-center shadow-inner transition hover:bg-blue-800/50">
                            <span class="text-base font-bold text-blue-100 tracking-wide">{{ $badge }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container">
            <x-ui.section-heading title="Leadership Team" subtitle="Cross-functional leaders driving product quality, customer outcomes, and operational excellence." />
            
            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['name' => 'Arjun Mehta', 'role' => 'Managing Director', 'copy' => 'Leads strategic growth, governance, and partnerships.'],
                    ['name' => 'Neha Srivastava', 'role' => 'Head - Clinical Solutions', 'copy' => 'Guides product fit and technical diagnostics enablement.'],
                    ['name' => 'Rahul Verma', 'role' => 'Head - Operations', 'copy' => 'Owns inventory planning, logistics, and service continuity.'],
                ] as $leader)
                    <article class="group rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-lg transition-all hover:-translate-y-2 hover:shadow-2xl hover:border-blue-300 relative overflow-hidden">
                        <div class="absolute inset-0 bg-blue-50 translate-y-full transition-transform duration-300 group-hover:translate-y-0 -z-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="mb-5 flex h-24 w-24 items-center justify-center rounded-full bg-blue-600 text-3xl font-bold text-white shadow-lg shadow-blue-600/30">
                                {{ strtoupper(substr($leader['name'], 0, 1)) }}
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $leader['name'] }}</h3>
                            <p class="mt-2 inline-block rounded-full bg-blue-100 px-4 py-1 text-sm font-bold text-blue-700">{{ $leader['role'] }}</p>
                            <p class="mt-5 text-base text-slate-600">{{ $leader['copy'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Global map -->
    <section class="py-16 bg-slate-50">
        <div class="container">
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-xl lg:p-6">
                <div class="mb-6 text-center">
                    <h2 class="text-3xl font-bold text-slate-900">National Presence</h2>
                    <p class="mt-2 text-slate-600">A scalable service footprint with Lucknow as a strong operational center.</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-slate-200">
                    <iframe
                        class="h-[400px] w-full border-0"
                        src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Biogenix Presence Map"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
