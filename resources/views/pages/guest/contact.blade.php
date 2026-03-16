@php
    $contactCardClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-2 hover:shadow-xl';
    $panelClass = 'relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $accentPanelClass = 'relative overflow-hidden rounded-3xl border border-primary-100 bg-white p-6 shadow-sm md:p-8';
    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $textareaClass = 'block min-h-[9rem] w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $labelClass = 'absolute left-4 top-4 z-10 origin-[0] -translate-y-3 scale-75 transform text-sm text-slate-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-3 peer-focus:scale-75 peer-focus:text-primary-600 cursor-text';
@endphp


<div class="bg-slate-50">
    <section class="relative overflow-hidden bg-slate-900 py-20 text-white lg:py-28">
        <img src="{{ asset('storage/slides/image2.jpg') }}" alt="Contact Biogenix" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/60 to-slate-900/40"></div>
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 relative z-10 text-center">
            <div class="mb-5 flex flex-wrap items-center justify-center gap-2 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span class="text-white">Contact</span>
            </div>
            <h1 class="mx-auto max-w-4xl text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl">Let's Connect</h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-300 md:text-lg">Whether you need product inquiries, partnerships, or dedicated technical support, our specialized teams are ready to guide you.</p>
        </div>
    </section>

    <section class="relative z-20 -mt-12 pb-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'Corporate Office', 'copy' => 'Lucknow, Uttar Pradesh', 'phone' => '+91 98765 43210', 'email' => 'support@biogenix.com', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['title' => 'Sales Support', 'copy' => 'Quotations & onboarding', 'phone' => '+91 98765 43210', 'email' => 'sales@biogenix.com', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['title' => 'Tech Support', 'copy' => 'Guidance & escalations', 'phone' => '+91 98765 43210', 'email' => 'tech@biogenix.com', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['title' => 'Partnership', 'copy' => 'Distribution & alliances', 'phone' => '+91 98765 43210', 'email' => 'partners@biogenix.com', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ] as $desk)
                    <article class="{{ $contactCardClass }}">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-50 text-primary-700 transition-colors group-hover:bg-primary-600 group-hover:text-white">
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

    <section class="py-12 pb-24">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-8 lg:px-8 xl:px-10">
            <div class="flex flex-col justify-start space-y-6 lg:col-span-5">
                <article class="{{ $panelClass }}">
                    <div class="relative h-64 w-full overflow-hidden rounded-2xl bg-slate-200">
                        <iframe
                            id="contactMap"
                            class="h-full w-full border-0"
                            src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Biogenix Location Map"
                        ></iframe>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-3xl font-semibold tracking-tight text-slate-950">Corporate Headquarters</h3>
                        <p class="mt-2 max-w-none text-base leading-8 text-slate-600">123 Medical Park Drive, Lucknow, Uttar Pradesh, India</p>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Response Time</p>
                                    <p class="font-bold text-slate-900">&lt; 24 Hours typically.</p>
                                </div>
                            </div>
                            <div class="flex items-center rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-700">
                                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Coverage Area</p>
                                    <p class="font-bold text-slate-900">Pan India Delivery.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <x-ui.action-link :href="route('proforma.create')" class="w-full justify-center">Generate Quote</x-ui.action-link>
                            <x-ui.action-link :href="route('faq')" variant="secondary" class="w-full justify-center">View FAQs</x-ui.action-link>
                        </div>
                    </div>
                </article>
            </div>

            <article class="{{ $accentPanelClass }} lg:col-span-7">
                <div class="pointer-events-none absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-primary-50 opacity-50 blur-3xl"></div>

                <div class="mb-8">
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Send an Inquiry</h2>
                    <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Tell us your requirement and our dedicated team will get back to you with the next steps quickly.</p>
                </div>

                <form id="contactForm" class="relative z-10 grid grid-cols-1 gap-6 md:grid-cols-2" novalidate>
                    <div class="relative">
                        <input type="text" id="name" class="{{ $inputClass }} peer pt-6 pb-2" placeholder=" " required>
                        <label for="name" class="{{ $labelClass }}">Full Name</label>
                    </div>

                    <div class="relative">
                        <input type="email" id="email" class="{{ $inputClass }} peer pt-6 pb-2" placeholder=" " required>
                        <label for="email" class="{{ $labelClass }}">Work Email</label>
                    </div>

                    <div class="relative">
                        <span class="absolute left-3 top-4 flex items-center font-medium text-slate-500">+91</span>
                        <input type="text" id="phone" class="{{ $inputClass }} peer pl-12 pt-6 pb-2" placeholder=" " maxlength="10" required>
                        <label for="phone" class="{{ $labelClass }} left-12">Phone Mobile</label>
                    </div>

                    <div class="relative">
                        <select id="inquiryType" class="{{ $inputClass }} appearance-none peer pt-6 pb-2" required>
                            <option value="" disabled selected hidden></option>
                            <option value="Product Information">Product Information</option>
                            <option value="Generate Quotation">Generate Quotation</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Technical Support">Technical Support</option>
                        </select>
                        <label for="inquiryType" class="{{ $labelClass }} peer-valid:-translate-y-3 peer-valid:scale-75">Inquiry Type</label>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>

                    <div class="relative md:col-span-2">
                        <textarea id="message" rows="5" class="{{ $textareaClass }} peer pt-6 pb-2" placeholder=" " maxlength="500" required></textarea>
                        <label for="message" class="{{ $labelClass }}">Message</label>
                        <div class="absolute bottom-3 right-4 text-xs font-semibold text-slate-400">
                            <span id="charCount">0</span>/500
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col items-center gap-4 md:col-span-2 sm:flex-row">
                        <button type="submit" id="contactSubmitBtn" class="{{ $primaryButtonClass }} w-full sm:w-auto">
                            Submit Inquiry
                        </button>
                        <a href="https://wa.me/919876543210" target="_blank" rel="noopener" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-green-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-green-500/30 transition hover:bg-green-600 hover:shadow-xl sm:w-auto">
                            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Support
                        </a>
                        <p id="formStatus" class="min-h-[1.25rem] text-center text-sm font-medium text-slate-600 sm:text-left" aria-live="polite"></p>
                    </div>
                </form>
            </article>
        </div>
    </section>

    <section class="bg-white py-16 md:py-20">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Service Assurance" subtitle="Clear response commitments and logistics coverage for every request." />
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'Response SLA', 'value' => '< 45 minutes', 'copy' => 'First human response for priority product/support tickets during business hours.'],
                    ['title' => 'Dispatch Windows', 'value' => 'Same-Day | Next-Day', 'copy' => 'Lucknow hub enables fast turnarounds for in-stock items.'],
                    ['title' => 'Escalation Desk', 'value' => '24/7', 'copy' => 'Escalate critical lab-down issues straight to senior support.'],
                    ['title' => 'Coverage', 'value' => 'PAN India', 'copy' => 'Forward stocking and partner routes for tier-1 to tier-3 cities.'],
                ] as $sla)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $sla['title'] }}</p>
                        <p class="mt-3 text-2xl font-bold text-slate-950">{{ $sla['value'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $sla['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 md:py-20">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-12 lg:items-center lg:px-8 xl:px-10">
            <div class="space-y-4 lg:col-span-5">
                <x-ui.section-heading title="How We Engage" subtitle="Simple, transparent steps from inquiry to resolved request." />
                <p class="text-base leading-8 text-slate-600">Every request: sales or support, follows the same structured flow so you always know what happens next.</p>
                <div class="flex flex-wrap gap-3">
                    <x-ui.action-link :href="route('book-meeting')" class="min-h-11 px-5">Book a Discovery Call</x-ui.action-link>
                    <x-ui.action-link :href="route('faq')" variant="secondary" class="min-h-11 px-5">View FAQs</x-ui.action-link>
                </div>
            </div>

            <div class="space-y-4 lg:col-span-7">
                @foreach ([
                    ['label' => '01', 'title' => 'Triage & Ownership', 'copy' => 'We assign the right desk (sales, tech, partnership) with a named owner.'],
                    ['label' => '02', 'title' => 'Clarify & Confirm', 'copy' => 'We confirm requirements, urgency, and any compliance constraints within the first touch.'],
                    ['label' => '03', 'title' => 'Solution & SLA', 'copy' => 'You receive clear next steps: quote, PI, dispatch plan, or fix ETA with escalation paths.'],
                    ['label' => '04', 'title' => 'Close & Review', 'copy' => 'We close the loop with documentation and optional review for recurring needs.'],
                ] as $step)
                    <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-sm font-bold text-primary-700">{{ $step['label'] }}</div>
                        <div>
                            <p class="text-base font-semibold text-slate-900">{{ $step['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $step['copy'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contactForm');
        const status = document.getElementById('formStatus');
        const submitBtn = document.getElementById('contactSubmitBtn');
        const mapBtn = document.getElementById('contactMapLoadBtn');
        const mapFrame = document.getElementById('contactMap');

        if (mapBtn && mapFrame) {
            mapBtn.addEventListener('click', function () {
                if (!mapFrame.getAttribute('src')) {
                    mapFrame.setAttribute('src', mapFrame.dataset.src || '');
                }
                mapBtn.classList.add('hidden');
            });
        }

        const messageArea = document.getElementById('message');
        const charCount = document.getElementById('charCount');

        if (messageArea && charCount) {
            messageArea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length >= 500) {
                    charCount.classList.add('text-rose-600');
                } else {
                    charCount.classList.remove('text-rose-600');
                }
            });
        }

        if (form && status) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                status.textContent = 'Thanks for reaching out. We will respond within one business hour.';
                status.classList.remove('text-rose-600');
                status.classList.add('text-emerald-600');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('cursor-not-allowed', 'opacity-70');
                    setTimeout(function () {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('cursor-not-allowed', 'opacity-70');
                    }, 800);
                }
            });
        }
    });
</script>
@endpush
