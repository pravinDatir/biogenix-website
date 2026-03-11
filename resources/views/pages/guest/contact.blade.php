<div class="full-bleed bg-slate-50">
    <!-- Premium Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 py-20 text-white lg:py-28">
        <img src="{{ asset('images/image2.jpg') }}" alt="Contact Biogenix" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/60 to-slate-900/40"></div>
        <div class="container relative z-10 text-center">
            <h1 class="mx-auto max-w-4xl text-4xl font-bold leading-tight tracking-tight sm:text-5xl md:text-6xl text-white">Let's Connect</h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-300">Whether you need product inquiries, partnerships, or dedicated technical support, our specialized teams are ready to guide you.</p>
        </div>
    </section>

    <!-- Floating Contact Cards -->
    <section class="-mt-12 relative z-20 pb-16">
        <div class="container">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'Corporate Office', 'copy' => 'Lucknow, Uttar Pradesh', 'phone' => '+91 98765 43210', 'email' => 'support@biogenix.com', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['title' => 'Sales Support', 'copy' => 'Quotations & onboarding', 'phone' => '+91 98765 43210', 'email' => 'sales@biogenix.com', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['title' => 'Tech Support', 'copy' => 'Guidance & escalations', 'phone' => '+91 98765 43210', 'email' => 'tech@biogenix.com', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['title' => 'Partnership', 'copy' => 'Distribution & alliances', 'phone' => '+91 98765 43210', 'email' => 'partners@biogenix.com', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ] as $desk)
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $desk['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900">{{ $desk['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $desk['copy'] }}</p>
                        <div class="mt-4 space-y-2 border-t border-slate-100 pt-4">
                            <p class="flex items-center text-sm font-medium text-slate-800">
                                <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                {{ $desk['phone'] }}
                            </p>
                            <p class="flex items-center text-sm font-medium text-slate-800">
                                <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ $desk['email'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Main Inquiry Section -->
    <section class="py-12 pb-24">
        <div class="container grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-8 hover:!translate-y-0">
            <!-- Left Area: Map and Quick Contact -->
            <div class="space-y-6 lg:col-span-5 flex flex-col justify-start">
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg relative h-full">
                    <div class="h-64 w-full bg-slate-200">
                        <iframe
                            class="h-full w-full"
                            src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Biogenix Location Map"
                        ></iframe>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Corporate Headquarters</h3>
                        <p class="text-base text-slate-600 mb-6">123 Medical Park Drive, Lucknow, Uttar Pradesh, India</p>

                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mr-4">
                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Response Time</p>
                                    <p class="font-bold text-slate-900">&lt; 24 Hours typically.</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 mr-4">
                                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Coverage Area</p>
                                    <p class="font-bold text-slate-900">Pan India Delivery.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <x-ui.action-link :href="route('proforma.create')" class="w-full justify-center">Generate Quote</x-ui.action-link>
                            <x-ui.action-link :href="route('faq')" variant="secondary" class="w-full justify-center">View FAQs</x-ui.action-link>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Right Area: The Inquiry Form -->
            <article class="lg:col-span-7 rounded-3xl border border-slate-200 bg-white p-8 shadow-xl relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-blue-50 opacity-50 blur-3xl pointer-events-none"></div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900">Send an Inquiry</h2>
                    <p class="mt-2 text-base text-slate-600">Tell us your requirement and our dedicated team will get back to you with the next steps quickly.</p>
                </div>

                <form id="contactForm" class="relative z-10 grid grid-cols-1 gap-6 md:grid-cols-2" novalidate>
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" id="name" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" placeholder="e.g. Jane Doe" required>
                        <span class="error hidden text-xs text-red-500 mt-1">Please enter your name.</span>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-slate-700">Work Email</label>
                        <input type="email" id="email" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" placeholder="jane@hospital.com" required>
                        <span class="error hidden text-xs text-red-500 mt-1">Please enter a valid email.</span>
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-slate-700">Phone Mobile</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 font-medium">+91</span>
                            <input type="text" id="phone" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 pl-12 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" placeholder="10-digit number" maxlength="10" required>
                        </div>
                        <span class="error hidden text-xs text-red-500 mt-1">Please enter your phone number.</span>
                    </div>

                    <div class="space-y-2">
                        <label for="inquiryType" class="block text-sm font-semibold text-slate-700">Inquiry Type</label>
                        <select id="inquiryType" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 appearance-none" required>
                            <option value="">Select inquiry category</option>
                            <option>Product Information</option>
                            <option>Generate Quotation</option>
                            <option>Partnership</option>
                            <option>Technical Support</option>
                        </select>
                        <span class="error hidden text-xs text-red-500 mt-1">Please select an inquiry type.</span>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="message" class="block text-sm font-semibold text-slate-700">Message</label>
                        <textarea id="message" rows="5" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" placeholder="How can we help you today?" required></textarea>
                        <span class="error hidden text-xs text-red-500 mt-1">Please enter your message.</span>
                    </div>

                    <div class="md:col-span-2 mt-4 flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" id="contactSubmitBtn" class="flex w-full items-center justify-center rounded-xl bg-blue-600 py-3.5 px-6 font-bold text-white shadow-lg shadow-blue-600/30 transition-all hover:bg-blue-700 hover:shadow-xl sm:w-auto">
                            Submit Inquiry
                        </button>
                        <a href="https://wa.me/919876543210" target="_blank" rel="noopener" class="flex w-full sm:w-auto items-center justify-center rounded-xl bg-green-500 px-6 py-3.5 font-bold text-white shadow-lg shadow-green-500/30 transition-all hover:bg-green-600 hover:shadow-xl">
                            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Support
                        </a>
                        <p id="formStatus" class="form-status text-center sm:text-left"></p>
                    </div>
                </form>
            </article>
        </div>
    </section>
</div>
