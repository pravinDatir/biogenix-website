<div class="page-shell">
    <section class="section-stack">
        <x-ui.section-heading title="Frequently Asked Questions" subtitle="Answers for product usage, ordering, and delivery operations." />

        <div class="saas-card">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label for="faqSearch" class="mb-1 block text-sm font-medium text-slate-700">Search FAQs</label>
                    <input id="faqSearch" type="text" class="form-control" placeholder="Search by keyword (product, ordering, shipping...)">
                </div>
                <div>
                    <label for="faqFilter" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select id="faqFilter" class="form-control">
                        <option value="all">All categories</option>
                        <option value="product">Product FAQs</option>
                        <option value="ordering">Ordering FAQs</option>
                        <option value="delivery">Delivery & Payment FAQs</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="faqAccordion" class="mini-accordion">
            <x-accordion title="Product FAQs" :open="true" data-faq-item data-faq-category="product">
                <p>What products are available? We provide IVD kits, reagents, instruments, and consumables for diagnostics workflows.</p>
                <p class="mt-3">Do you provide technical guidance? Yes, onboarding and support assistance are available through our support team.</p>
            </x-accordion>

            <x-accordion title="Ordering FAQs" data-faq-item data-faq-category="ordering">
                <p>Can guests generate quotations? Yes, guests can generate quotations with MRP visibility only.</p>
                <p class="mt-3">Do B2B accounts need approval? Yes, B2B account access is activated after admin review and approval.</p>
            </x-accordion>

            <x-accordion title="Delivery & Payment FAQs" data-faq-item data-faq-category="delivery">
                <p>Is same-day delivery available? Same-day support is available for selected Lucknow locations and product lines.</p>
                <p class="mt-3">How are delivery timelines shared? Delivery commitments are communicated during quote confirmation.</p>
            </x-accordion>
        </div>

        <p id="faqEmptyState" class="hidden rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">No FAQs matched your search/filter.</p>
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
