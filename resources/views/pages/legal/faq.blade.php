<div class="full-bleed bg-slate-50 min-h-screen">
    <!-- Premium Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 py-20 text-white lg:py-24">
        <img src="{{ asset('images/image4.jpg') }}" alt="FAQ Background" class="absolute inset-0 h-full w-full object-cover opacity-10" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/50 to-slate-950/80"></div>
        <div class="container relative z-10 text-center">
            <h1 class="mx-auto max-w-4xl text-4xl font-bold leading-tight tracking-tight sm:text-5xl md:text-6xl text-white">Frequently Asked Questions</h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-300">Got questions about products, ordering, or delivery? We've got answers.</p>
        </div>
    </section>

    <!-- FAQ Search area -->
    <section class="-mt-10 relative z-20">
        <div class="container max-w-4xl">
            <div class="rounded-2xl bg-white p-6 shadow-xl border border-slate-100">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label for="faqSearch" class="mb-2 block text-sm font-semibold text-slate-700">Search FAQs</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input id="faqSearch" type="text" class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pl-10 pr-4 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10" placeholder="e.g. shipping time...">
                        </div>
                    </div>
                    <div>
                        <label for="faqFilter" class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                        <select id="faqFilter" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 appearance-none">
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

    <!-- FAQ Accordions -->
    <section class="py-16">
        <div class="container max-w-3xl">
            <div id="faqAccordion" class="space-y-6">
                <!-- Product Category -->
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="product">
                    <x-accordion title="What products are available?" :open="true" class="!border-0">
                        <p class="text-slate-600">We provide IVD kits, reagents, instruments, and consumables tailored for high-throughput diagnostics workflows.</p>
                        <p class="mt-3 text-slate-600"><strong>Note:</strong> Some specialized kits require a complete B2B account approval.</p>
                    </x-accordion>
                </div>
                
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="product">
                    <x-accordion title="Do you provide technical guidance?" :open="false" class="!border-0">
                        <p class="text-slate-600">Yes, dedicated onboarding and technical support assistance are available through our specialized technical support team.</p>
                    </x-accordion>
                </div>

                <!-- Ordering Category -->
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="ordering">
                    <x-accordion title="Can guests generate quotations?" :open="false" class="!border-0">
                        <p class="text-slate-600">Yes, guests can generate Proforma Invoices with MRP visibility only. To access your custom agreed pricing, please log into your B2B account.</p>
                    </x-accordion>
                </div>
                
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="ordering">
                    <x-accordion title="Do B2B accounts need approval?" :open="false" class="!border-0">
                        <p class="text-slate-600">Yes, B2B account access is provisioned after administrative review and verification of your organization's credentials.</p>
                    </x-accordion>
                </div>

                <!-- Delivery Category -->
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="delivery">
                    <x-accordion title="Is same-day delivery available?" :open="false" class="!border-0">
                        <p class="text-slate-600">Same-day logistics support is available for priority locations in and around Lucknow for select product lines.</p>
                    </x-accordion>
                </div>
                
                <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition hover:border-blue-200" data-faq-item data-faq-category="delivery">
                    <x-accordion title="How are delivery timelines communicated?" :open="false" class="!border-0">
                        <p class="text-slate-600">Final delivery commitments and expected dates are confirmed directly over email alongside your quote and PO approvals.</p>
                    </x-accordion>
                </div>
            </div>

            <!-- Empty State -->
            <div id="faqEmptyState" class="hidden mt-8 rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">No results found</h3>
                <p class="mt-2 text-sm text-slate-500">We couldn't find any FAQs matching your search or filter. Try adjusting your keywords.</p>
                <div class="mt-6">
                    <a href="{{ route('contact') }}" class="btn secondary">Contact Support Instead</a>
                </div>
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
