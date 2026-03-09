<div class="full-bleed">
    <section class="relative overflow-hidden bg-slate-950 py-16 text-white md:py-24">
        <img src="{{ asset('images/image2.jpg') }}" alt="Contact Biogenix" class="absolute inset-0 h-full w-full object-cover opacity-35" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-slate-900/50"></div>
        <div class="container relative z-10">
            <x-badge variant="info" class="!border-white/30 !bg-white/10 !text-blue-100">Contact Biogenix</x-badge>
            <h1 class="mt-4 max-w-4xl text-3xl font-semibold leading-tight text-white sm:text-4xl md:text-6xl">Let's connect for product inquiries, partnerships, and support.</h1>
            <p class="mt-4 max-w-3xl text-base text-slate-100 md:text-lg">Our teams are available to guide you on diagnostics products, quotations, distribution, and service support.</p>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="container">
            <x-ui.section-heading title="Reach Us by Region" subtitle="Choose the most relevant contact point for faster response." />
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['title' => 'Corporate Office', 'copy' => 'Lucknow, Uttar Pradesh', 'phone' => '+91 98765 43210', 'email' => 'support@biogenix.com'],
                    ['title' => 'Sales Support', 'copy' => 'Quotations & account onboarding', 'phone' => '+91 98765 43210', 'email' => 'sales@biogenix.com'],
                    ['title' => 'Technical Support', 'copy' => 'Product guidance & escalations', 'phone' => '+91 98765 43210', 'email' => 'tech@biogenix.com'],
                    ['title' => 'Partnership Desk', 'copy' => 'Distribution & alliances', 'phone' => '+91 98765 43210', 'email' => 'partners@biogenix.com'],
                ] as $desk)
                    <article class="saas-card">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $desk['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $desk['copy'] }}</p>
                        <p class="mt-3 text-sm text-slate-700"><strong>Phone:</strong> {{ $desk['phone'] }}</p>
                        <p class="mt-1 text-sm text-slate-700"><strong>Email:</strong> {{ $desk['email'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="container grid grid-cols-1 gap-5 lg:grid-cols-12">
            <article class="saas-card lg:col-span-7">
                <h2 class="ui-section-title">Send an Inquiry</h2>
                <p class="mt-2 text-sm text-slate-600">Tell us your requirement and our team will get back quickly.</p>

                <form id="contactForm" class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 [&_.form-group]:mb-0" novalidate>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter your name" required>
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control" placeholder="Enter 10-digit number" maxlength="10" required>
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="inquiryType">Inquiry Type</label>
                        <select id="inquiryType" class="form-control" required>
                            <option value="">Select inquiry type</option>
                            <option>Product Information</option>
                            <option>Generate Quotation</option>
                            <option>Partnership</option>
                            <option>Technical Support</option>
                        </select>
                        <span class="error"></span>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="message">Message</label>
                        <textarea id="message" class="form-control" rows="4" placeholder="How can we help?" required></textarea>
                        <span class="error"></span>
                    </div>

                    <div class="md:col-span-2 flex flex-wrap items-center gap-3">
                        <button type="submit" id="contactSubmitBtn" class="btn btn-primary">Submit Inquiry</button>
                        <a class="btn secondary" href="https://wa.me/919876543210" target="_blank" rel="noopener">WhatsApp Support</a>
                        <p id="formStatus" class="form-status"></p>
                    </div>
                </form>
            </article>

            <div class="space-y-5 lg:col-span-5">
                <article class="saas-card">
                    <h3 class="text-lg font-semibold text-slate-900">Corporate Location</h3>
                    <p class="mt-2 text-sm text-slate-600">123 Medical Park Drive, Lucknow, Uttar Pradesh, India</p>
                    <div class="mt-4 map-box">
                        <iframe
                            class="h-72 w-full"
                            src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Biogenix Location Map"
                        ></iframe>
                    </div>
                </article>

                <article class="saas-card">
                    <h3 class="text-lg font-semibold text-slate-900">Quick Contact</h3>
                    <p class="mt-2 text-sm text-slate-600"><strong>Email:</strong> support@biogenix.com</p>
                    <p class="mt-1 text-sm text-slate-600"><strong>Phone:</strong> +91 98765 43210</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-ui.action-link :href="route('proforma.create')">Generate Quote</x-ui.action-link>
                        <x-ui.action-link :href="route('faq')" variant="secondary">View FAQs</x-ui.action-link>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="container">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Response Time', 'value' => '< 24 Hours', 'copy' => 'Inquiry acknowledgment for standard requests.'],
                    ['title' => 'Escalation Desk', 'value' => 'Priority Enabled', 'copy' => 'Dedicated support for urgent product/service issues.'],
                    ['title' => 'Coverage', 'value' => 'Pan India', 'copy' => 'Central coordination with Lucknow-led operations.'],
                ] as $sla)
                    <article class="saas-card text-center">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $sla['title'] }}</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $sla['value'] }}</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $sla['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('pages.guest.book-meeting')
</div>
