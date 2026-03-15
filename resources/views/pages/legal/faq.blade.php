@php
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
@endphp

<div class="min-h-screen bg-slate-50">
    <section class="relative overflow-hidden bg-slate-900 py-16 text-white lg:py-20">
        <img src="{{ asset('storage/slides/image4.jpg') }}" alt="FAQ Background" class="absolute inset-0 h-full w-full object-cover opacity-10" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/50 to-slate-950/80"></div>
        <div class="container relative z-10 text-center">
            <div class="mb-5 flex flex-wrap items-center justify-center gap-2 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span class="text-white">FAQ</span>
            </div>
            <h1 class="mx-auto max-w-4xl text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl">Frequently Asked Questions</h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-300">Got questions about products, ordering, or delivery? We've got answers.</p>
        </div>
    </section>

    <section class="relative z-20 -mt-8">
        <div class="container max-w-4xl">
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label for="faqSearch" class="mb-2 block text-sm font-semibold text-slate-700">Search FAQs</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input id="faqSearch" type="text" class="{{ $inputClass }} pl-10" placeholder="e.g. shipping time...">
                        </div>
                    </div>
                    <div>
                        <label for="faqFilter" class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                        <select id="faqFilter" class="{{ $inputClass }} appearance-none">
                            <option value="all">All Categories</option>
                            <option value="product">Product Info</option>
                            <option value="ordering">Ordering Process</option>
                            <option value="delivery">Delivery & Payment</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container max-w-5xl">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Ordering & Quotes', 'copy' => 'Generating PIs, approving B2B access, and seeing contract prices.', 'cta' => route('proforma.create')],
                    ['title' => 'Delivery & Logistics', 'copy' => 'Cold-chain handling, delivery timelines, and shipment visibility.', 'cta' => route('contact')],
                    ['title' => 'Support & Warranty', 'copy' => 'Ticket SLAs, escalation, and post-install validation support.', 'cta' => route('book-meeting')],
                ] as $topic)
                    <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md animate-rise">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $topic['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $topic['copy'] }}</p>
                        <div class="mt-4">
                            <x-ui.action-link :href="$topic['cta']" variant="secondary">Open</x-ui.action-link>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="container max-w-3xl">
            <div id="faqAccordion" class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="product">
                    <x-accordion title="What products are available?" :open="true" class="ui-accordion--flat">
                        <p class="text-slate-600">We provide IVD kits, reagents, instruments, and consumables tailored for high-throughput diagnostics workflows.</p>
                        <p class="mt-3 text-slate-600"><strong>Note:</strong> Some specialized kits require a complete B2B account approval.</p>
                    </x-accordion>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="product">
                    <x-accordion title="Do you provide technical guidance?" :open="false" class="ui-accordion--flat">
                        <p class="text-slate-600">Yes, dedicated onboarding and technical support assistance are available through our specialized technical support team.</p>
                    </x-accordion>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="ordering">
                    <x-accordion title="Can guests generate quotations?" :open="false" class="ui-accordion--flat">
                        <p class="text-slate-600">Yes, guests can generate Proforma Invoices with MRP visibility only. To access your custom agreed pricing, please log into your B2B account.</p>
                    </x-accordion>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="ordering">
                    <x-accordion title="Do B2B accounts need approval?" :open="false" class="ui-accordion--flat">
                        <p class="text-slate-600">Yes, B2B account access is provisioned after administrative review and verification of your organization's credentials.</p>
                    </x-accordion>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="delivery">
                    <x-accordion title="Is same-day delivery available?" :open="false" class="ui-accordion--flat">
                        <p class="text-slate-600">Same-day logistics support is available for priority locations in and around Lucknow for select product lines.</p>
                    </x-accordion>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-primary-100" data-faq-item data-faq-category="delivery">
                    <x-accordion title="How are delivery timelines communicated?" :open="false" class="ui-accordion--flat">
                        <p class="text-slate-600">Final delivery commitments and expected dates are confirmed directly over email alongside your quote and PO approvals.</p>
                    </x-accordion>
                </div>
            </div>

            <div id="faqEmptyState" class="mt-8 hidden rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">No results found</h3>
                <p class="mt-2 text-sm text-slate-500">We couldn't find any FAQs matching your search or filter. Try adjusting your keywords.</p>
                <div class="mt-6">
                    <a href="{{ route('contact') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Contact Support Instead</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 py-10 text-white">
        <div class="container max-w-4xl space-y-4 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-300">Still need help?</p>
            <h2 class="text-3xl font-bold tracking-tight">Talk to our specialists</h2>
            <p class="text-base text-slate-200">We will route you to the right desk: sales, technical, or partnerships, and respond within one business hour.</p>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                <x-ui.action-link :href="route('contact')" class="min-h-11 px-5">Open Support Desk</x-ui.action-link>
                <x-ui.action-link :href="route('book-meeting')" variant="secondary" class="min-h-11 px-5">Book a Call</x-ui.action-link>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const filterInput = document.getElementById('faqFilter');
        const items = Array.from(document.querySelectorAll('[data-faq-item]'));
        const emptyState = document.getElementById('faqEmptyState');

        function normalize(value) {
            return (value || '').toLowerCase().trim();
        }

        function applyFilter() {
            const searchTerm = normalize(searchInput ? searchInput.value : '');
            const selectedCategory = filterInput ? filterInput.value : 'all';
            let visibleCount = 0;

            items.forEach(function (item) {
                const category = item.getAttribute('data-faq-category') || '';
                const text = normalize(item.textContent);
                const categoryMatch = selectedCategory === 'all' || selectedCategory === category;
                const searchMatch = !searchTerm || text.includes(searchTerm);
                const visible = categoryMatch && searchMatch;

                item.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });

            if (emptyState) {
                emptyState.classList.toggle('hidden', visibleCount !== 0);
            }
        }

        if (searchInput) searchInput.addEventListener('input', applyFilter);
        if (filterInput) filterInput.addEventListener('change', applyFilter);
        applyFilter();
    });
</script>
@endpush
