@php
    $cardClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl';
    $accentCardClass = 'relative overflow-hidden rounded-3xl border border-primary-100 bg-white p-6 shadow-sm md:p-8';
    $titleClass = 'text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-7xl';
    $copyClass = 'mt-8 max-w-2xl text-lg leading-8 text-slate-300';
    $primaryIconClass = 'mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-700 transition-colors group-hover:bg-primary-600 group-hover:text-white';
@endphp

<div class="bg-slate-50 min-h-screen">
    <!-- Premium Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 py-10 text-white lg:py-16">
        <img src="{{ asset('upload/corousel/image4.jpg') }}" alt="Biogenix company profile" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/70 to-primary-900/30"></div>
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 relative z-10">
           
            <div class="max-w-4xl">
                <x-badge variant="inverse" class="mb-6">Company Profile</x-badge>
                <h1 class="{{ $titleClass }}">
                    Driving trustworthy diagnostics access across India.
                </h1>
                <p class="{{ $copyClass }}">
                    Biogenix blends product quality, responsive service, and healthcare domain expertise to support institutions, labs, and care providers.
                </p>
            </div>
        </div>
    </section>



    <!-- Vision/Mission/Values -->
    <section class="py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-primary-900/[0.02] transform -skew-y-3 origin-top-left -z-10"></div>
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Our Philosophy" subtitle="Vision, Mission, and Values driving our long-term healthcare impact." />
            <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <article class="relative overflow-hidden rounded-3xl border border-primary-100 bg-white p-8 shadow-xl group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="h-24 w-24 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Vision</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 relative z-10">To become the most trusted diagnostics partner for institutions and communities across India.</p>
                </article>
                <article class="relative overflow-hidden rounded-3xl border border-primary-100 bg-primary-50/40 p-8 shadow-xl group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="h-24 w-24 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Mission</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 relative z-10">Deliver high-quality diagnostics products with dependable support and transparent fulfillment workflows.</p>
                </article>
                <article class="relative rounded-3xl border border-primary-100 bg-white p-8 shadow-xl overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-primary-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A120.1 120.1 0 0010 1C5.586 1 1.732 3.943.458 8s5.128 7 9.542 7c4.414 0 8.268-2.943 9.542-7-1.274-4.057-5.064-7-9.542-7zm-4.502 6.002a2 2 0 11-4 0 2 2 0 014 0zm8.004 0a2 2 0 11-4 0 2 2 0 014 0zm-4.002 6.002a2 2 0 11-4 0 2 2 0 014 0h-4a2 2 0 114 0zM15 17h-2v2h2v-2zm-4 0h-2v2h2v-2zm-4 0H5v2h2v-2z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 relative z-10">Core Values</h3>
                    <ul class="mt-4 space-y-3 relative z-10">
                        @foreach (['Clinical reliability', 'Customer commitment', 'Execution excellence', 'Continuous improvement'] as $val)
                            <li class="flex items-center text-slate-600">
                                <span class="mr-3 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-primary-700">
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
        <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Our Journey" subtitle="A focused path from regional diagnostic support to scalable healthcare operations." />
            
            <div class="mt-16 space-y-12">
                @foreach ([
                    ['year' => '2017', 'title' => 'Foundation', 'desc' => 'Biogenix launched with a mission to improve access to reliable diagnostic products in North India.', 'year_class' => 'text-primary-600', 'node_class' => 'bg-primary-500'],
                    ['year' => '2020', 'title' => 'Portfolio Expansion', 'desc' => 'Added advanced instruments, reagents, and consumables for wider clinical workflows.', 'year_class' => 'text-primary-600', 'node_class' => 'bg-primary-500'],
                    ['year' => '2023', 'title' => 'Digital Enablement', 'desc' => 'Introduced online catalog and quotation workflows for B2B/B2C customer journeys.', 'year_class' => 'text-primary-600', 'node_class' => 'bg-primary-500'],
                    ['year' => '2026', 'title' => 'Scale & Compliance', 'desc' => 'Strengthened quality controls, partner onboarding, and logistics-led fulfillment execution.', 'year_class' => 'text-slate-700', 'node_class' => 'bg-slate-500'],
                ] as $idx => $milestone)
                    <div class="relative flex flex-col md:flex-row items-center justify-between group">
                        <!-- Desktop Line -->
                        @if(!$loop->last)
                            <div class="hidden md:block absolute left-1/2 top-16 bottom-[-3rem] w-px bg-slate-200 -translate-x-1/2"></div>
                        @endif
                        
                        <div class="w-full md:w-[45%] {{ $idx % 2 == 0 ? 'md:text-right md:pr-12' : 'md:order-3 md:pl-12' }}">
                            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-xl transition-all duration-700 opacity-0 translate-y-8 scroll-reveal-item hover:-translate-y-1 hover:shadow-2xl">
                                <p class="text-3xl font-bold tracking-tight {{ $milestone['year_class'] }}">{{ $milestone['year'] }}</p>
                                <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $milestone['title'] }}</h3>
                                <p class="mt-3 text-base text-slate-600">{{ $milestone['desc'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Center Node -->
                        <div class="absolute left-1/2 z-10 hidden h-12 w-12 -translate-x-1/2 items-center justify-center rounded-full border-4 border-white text-white shadow-xl transform transition-all duration-700 opacity-0 scale-50 scroll-reveal-node group-hover:scale-125 md:flex {{ $milestone['node_class'] }}">
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
        <div class="absolute top-0 right-0 -mr-48 -mt-48 h-96 w-96 rounded-full bg-primary-600 opacity-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-48 -mb-48 h-96 w-96 rounded-full bg-primary-600 opacity-20 blur-3xl"></div>
        
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8 relative z-10">
            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 md:p-12 backdrop-blur-sm flex flex-col items-center text-center">
                <h2 class="text-3xl font-bold tracking-tight md:text-4xl">Quality & Compliance</h2>
                <p class="mt-4 max-w-2xl text-lg text-slate-300">Biogenix follows robust quality and compliance practices to ensure safe and consistent product delivery across all lines.</p>
                <div class="mt-10 grid w-full grid-cols-2 gap-4 md:grid-cols-4">
                    @foreach (['ISO 13485', 'GMP Aligned', 'QA Audited', 'CDSCO Ready'] as $badge)
                        <div class="flex items-center justify-center rounded-2xl border border-primary-500/30 bg-primary-900/40 p-6 text-center shadow-inner transition hover:bg-primary-800/50">
                            <span class="text-base font-bold text-primary-50 tracking-wide">{{ $badge }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="py-16 md:py-24 bg-white">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Leadership Team" subtitle="Cross-functional leaders driving product quality, customer outcomes, and operational excellence." />
            
            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['name' => 'Arjun Mehta', 'role' => 'Managing Director', 'copy' => 'Leads strategic growth, governance, and partnerships.'],
                    ['name' => 'Neha Srivastava', 'role' => 'Head - Clinical Solutions', 'copy' => 'Guides product fit and technical diagnostics enablement.'],
                    ['name' => 'Rahul Verma', 'role' => 'Head - Operations', 'copy' => 'Owns inventory planning, logistics, and service continuity.'],
                ] as $leader)
                <article class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-lg transition-all hover:-translate-y-2 hover:border-primary-100 hover:shadow-2xl">
                        <div class="absolute inset-0 -z-0 translate-y-full bg-primary-50 transition-transform duration-300 group-hover:translate-y-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="mb-5 h-24 w-24 overflow-hidden rounded-full shadow-lg shadow-primary-600/30 ring-4 ring-white">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($leader['name']) }}&background=ea580c&color=fff&size=200&bold=true" alt="{{ $leader['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $leader['name'] }}</h3>
                            <p class="mt-2 inline-block rounded-full bg-primary-100 px-4 py-1 text-sm font-bold text-primary-700">{{ $leader['role'] }}</p>
                            <p class="mt-5 text-base text-slate-600">{{ $leader['copy'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Customer Outcomes -->
    <section class="py-16 md:py-24 bg-slate-50">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Customer Outcomes" subtitle="Measurable reliability across procurement, delivery, and post-install support." />
            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'On-Time Dispatch', 'value' => '97.4%', 'copy' => 'Priority routes from Lucknow hub with QA cleared stock.'],
                    ['label' => 'Mean Response', 'value' => '< 45m', 'copy' => 'Support desk triage to the right specialist within minutes.'],
                    ['label' => 'Active Install Base', 'value' => '1,200+', 'copy' => 'Devices and kits live across labs, hospitals, and research centers.'],
                    ['label' => 'Satisfaction', 'value' => '4.8 / 5', 'copy' => 'Post-service CSAT across ticket closures and deliveries.'],
                ] as $impact)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $impact['label'] }}</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ $impact['value'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $impact['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Service Blueprint -->
    

    <!-- Global map -->
   
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('scroll-reveal-item')) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    }
                    if (entry.target.classList.contains('scroll-reveal-node')) {
                        entry.target.classList.remove('opacity-0', 'scale-50');
                        entry.target.classList.add('opacity-100', 'scale-100');
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.scroll-reveal-item, .scroll-reveal-node').forEach(el => observer.observe(el));
    });
</script>
@endpush