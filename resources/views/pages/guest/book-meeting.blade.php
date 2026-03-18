@php
    $sectionCardClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $accentCardClass = 'relative overflow-hidden rounded-3xl border border-primary-100 bg-white p-6 shadow-sm md:p-8';
    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $selectClass = $inputClass . ' appearance-none';
    $primaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $secondaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $quickDateBaseClass = 'inline-flex min-h-10 items-center justify-center rounded-full border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-primary-200 hover:text-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
@endphp

<div>
    <section class="relative overflow-hidden bg-slate-900 py-16 text-white md:py-24">
        <img src="{{ asset('upload/corousel/image3.jpg') }}" alt="Biogenix Meeting" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-950/90 via-slate-900/80 to-slate-900/40"></div>
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 relative z-10 text-center">
            <div class="mb-5 flex flex-wrap items-center justify-center gap-2 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span class="text-white">Book Meeting</span>
            </div>
            <x-badge variant="inverse" class="mb-4 inline-block">Talk to an Expert</x-badge>
            <h1 class="mx-auto max-w-4xl text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl">
                Accelerate your diagnostics operations with Biogenix.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-100 md:text-lg">
                Schedule a one-on-one session with our specialists to discuss product catalogs, bulk procurement, and enterprise supply chain solutions tailored to your lab or hospital.
            </p>
        </div>
    </section>

    <section class="bg-slate-50 py-12 md:py-20">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-8 lg:px-8 xl:px-10">
            <div class="flex flex-col justify-center space-y-8 lg:col-span-5">
                <div>
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Why book a meeting?</h2>
                    <p class="mt-3 max-w-none text-base leading-8 text-slate-600">We go beyond simple transactions. A dedicated session allows us to map our diagnostic solutions directly to your clinical throughput and budget requirements.</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary-50 text-primary-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Tailored Product Mapping</h3>
                            <p class="mt-1 text-sm text-slate-600">Get personalized recommendations across IVD kits, reagents, and instruments matching your facility's scale.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Custom Commercial Models</h3>
                            <p class="mt-1 text-sm text-slate-600">Discuss bulk pricing, credit terms, and compliant procurement models suited for modern healthcare accounts.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100 text-primary-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Dedicated Support Pipeline</h3>
                            <p class="mt-1 text-sm text-slate-600">Learn how our Lucknow-led fulfillment hub and priority escalation desks ensure maximum uptime for your lab.</p>
                        </div>
                    </div>
                </div>

                <div class="{{ $sectionCardClass }}">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-full bg-slate-200">
                            <img src="{{ asset('upload/icons/logo.jpg') }}" alt="Expert" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Enterprise Sales Team</p>
                            <p class="text-xs text-slate-500">Biogenix Corporate Office</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm italic text-slate-700">"We aim to streamline your entire procurement process. A quick 15-minute chat lets us tailor a solution exactly for your scale and regional requirements."</p>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="{{ $accentCardClass }}">
                    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary-50 opacity-50 blur-3xl"></div>

                    <div class="mb-8 border-b border-slate-100 pb-5">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Schedule Your Session</h2>
                        <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Select a preferred time slot, and we will confirm your meeting over email.</p>
                    </div>

                    <form id="meetingForm" class="relative z-10" novalidate>
                        <!-- Step 1: Date & Time -->
                        <div id="meetingStep1" class="transition-all duration-500">
                            <div class="mb-4 flex items-center justify-between">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">Step 1 of 2: Select Date & Time</p>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">1</span>
                            </div>
                            
                            <div id="meetingQuickDates" class="mb-5 flex flex-wrap gap-2"></div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="meetingDate" class="mb-1 block text-sm font-medium text-slate-700">Preferred Date</label>
                                    <input id="meetingDate" type="date" class="{{ $inputClass }}" required>
                                    <p data-meeting-error="meetingDate" class="mt-2 hidden text-xs font-medium text-rose-600"></p>
                                </div>
                                <div>
                                    <label for="meetingSlot" class="mb-1 block text-sm font-medium text-slate-700 flex justify-between">Time Slot <span class="text-slate-400 font-normal shadow-sm px-2 bg-slate-100 rounded text-[10px] leading-relaxed">IST Zone</span></label>
                                    <select id="meetingSlot" class="{{ $selectClass }}" required>
                                        <option value="">Select a slot</option>
                                        <option>10:00 AM - 11:00 AM</option>
                                        <option>11:30 AM - 12:30 PM</option>
                                        <option>02:00 PM - 03:00 PM</option>
                                        <option>04:00 PM - 05:00 PM</option>
                                    </select>
                                    <p data-meeting-error="meetingSlot" class="mt-2 hidden text-xs font-medium text-rose-600"></p>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button type="button" id="nextStepBtn" class="{{ $primaryButtonClass }} w-full sm:w-auto">Continue to Details <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></button>
                            </div>
                        </div>

                        <!-- Step 2: Personal Details -->
                        <div id="meetingStep2" class="hidden transition-all duration-500 translate-x-4 opacity-0">
                            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                                <div>
                                    <button type="button" id="prevStepBtn" class="mb-2 flex items-center text-xs font-semibold text-slate-500 hover:text-primary-600 transition-colors"><svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg> Back to Schedule</button>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">Step 2 of 2: Your Details</p>
                                </div>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white shadow-md">2</span>
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label for="meetingName" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                                    <input id="meetingName" type="text" class="{{ $inputClass }}" placeholder="e.g. Dr. Jane Doe" required>
                                    <p data-meeting-error="meetingName" class="mt-2 hidden text-xs font-medium text-rose-600"></p>
                                </div>

                                <div>
                                    <label for="meetingEmail" class="mb-1 block text-sm font-medium text-slate-700">Work Email</label>
                                    <input id="meetingEmail" type="email" class="{{ $inputClass }}" placeholder="you@hospital.com" required>
                                    <p data-meeting-error="meetingEmail" class="mt-2 hidden text-xs font-medium text-rose-600"></p>
                                </div>

                                <div>
                                    <label for="meetingOrg" class="mb-1 block text-sm font-medium text-slate-700">Organization / Hospital Name</label>
                                    <input id="meetingOrg" type="text" class="{{ $inputClass }}" placeholder="Name of your institution" required>
                                    <p data-meeting-error="meetingOrg" class="mt-2 hidden text-xs font-medium text-rose-600"></p>
                                </div>
                            </div>

                            <div class="mt-8 flex flex-wrap items-center gap-3">
                                <button type="submit" id="meetingSubmitBtn" class="{{ $primaryButtonClass }} w-full md:w-auto">Confirm Meeting Request</button>
                                <p id="meetingStatus" class="mt-2 min-h-[1.25rem] w-full text-sm font-medium text-slate-600 md:mt-0 md:w-auto"></p>
                            </div>
                        </div>
                    </form>

                    <div id="meetingConfirmation" class="mt-4 hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center shadow-inner">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900">Meeting Request Submitted!</h3>
                        <p id="meetingConfirmationText" class="mt-2 text-sm text-emerald-800">Our team will confirm your slot shortly via email.</p>
                        <div class="mt-6">
                            <button type="button" id="bookAnotherMeetingBtn" class="{{ $secondaryButtonClass }}">Book Another Session</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 md:py-18">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Meeting Formats" subtitle="Pick a format that fits your objective: commercial, technical, or strategic." />
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Discovery (15-20 min)', 'copy' => 'Quick fitment call to map product lines to your workflow and budget expectations.'],
                    ['title' => 'Solution Deep Dive (30-40 min)', 'copy' => 'Technical deep dive with specs, compatibility, validation approach, and QA expectations.'],
                    ['title' => 'Procurement Alignment (25-30 min)', 'copy' => 'Commercial and logistics review: SLAs, delivery windows, payment terms, and governance.'],
                ] as $format)
                    <article class="h-full rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-2 hover:shadow-xl md:p-8">
                        <h3 class="text-xl font-semibold text-slate-900">{{ $format['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $format['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-14 md:py-18">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-12 lg:items-center lg:px-8 xl:px-10">
            <div class="space-y-4 lg:col-span-6">
                <x-ui.section-heading title="Pre-Read & Checklist" subtitle="Arrive prepared and get more value from your slot." />
                <ul class="space-y-3 text-sm text-slate-700">
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-primary-600"></span>Share anticipated monthly volumes and any product preferences.</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-primary-600"></span>List current bottlenecks (stockouts, TAT, validation, compliance).</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-primary-600"></span>Identify stakeholders to join: ops, QA, finance, or clinical.</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-primary-600"></span>Have a preferred delivery city/region for accurate lead times.</li>
                </ul>
                <div class="mt-5 flex flex-wrap gap-3">
                    <x-ui.action-link :href="route('quotation.create')" class="min-h-11 px-5">Download PI Template</x-ui.action-link>
                    <x-ui.action-link :href="route('contact')" variant="secondary" class="min-h-11 px-5">Share Requirements</x-ui.action-link>
                </div>
            </div>
            <div class="lg:col-span-6">
                <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="absolute -right-14 -top-14 h-48 w-48 rounded-full bg-primary-50 opacity-60 blur-3xl"></div>
                    <h3 class="text-2xl font-bold text-slate-900">What we'll bring</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <p class="font-semibold text-slate-900">Pricing ladders</p>
                        <p>Volume-based tiers, sample PI, and payment terms draft.</p>
                        <p class="font-semibold text-slate-900">Fulfillment plan</p>
                        <p>Dispatch windows, temperature control needs, and return policy summary.</p>
                        <p class="font-semibold text-slate-900">Compliance docs</p>
                        <p>Certifications, QA checklists, and SOP snippets you can forward internally.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 text-center">
            <h2 class="text-3xl font-semibold tracking-tight text-slate-950">Need immediate assistance?</h2>
            <p class="mx-auto mt-2 max-w-2xl text-base leading-8 text-slate-600">If your request is urgent, skip the meeting booking and talk to our support desk right now.</p>
            <div class="mt-6 flex justify-center gap-4">
                <x-ui.action-link :href="route('contact')" variant="dark">Go to Support Desk</x-ui.action-link>
                <a href="tel:+919876543210" class="{{ $secondaryButtonClass }}">Call +91 98765 43210</a>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('meetingForm');
        const submitBtn = document.getElementById('meetingSubmitBtn');
        const confirmation = document.getElementById('meetingConfirmation');
        const confirmationText = document.getElementById('meetingConfirmationText');
        const bookAnotherBtn = document.getElementById('bookAnotherMeetingBtn');
        const quickDatesWrap = document.getElementById('meetingQuickDates');
        const meetingDateInput = document.getElementById('meetingDate');
        const status = document.getElementById('meetingStatus');
        
        // Step logic
        const nextStepBtn = document.getElementById('nextStepBtn');
        const prevStepBtn = document.getElementById('prevStepBtn');
        const step1 = document.getElementById('meetingStep1');
        const step2 = document.getElementById('meetingStep2');
        
        const fieldIds = ['meetingDate', 'meetingSlot', 'meetingName', 'meetingEmail', 'meetingOrg'];
        const step1Fields = ['meetingDate', 'meetingSlot'];

        if (!form || !status) return;

        function validateStep1() {
            let valid = true;
            step1Fields.forEach(function (fieldId) {
                const input = document.getElementById(fieldId);
                if (!input) return;
                const value = input.value.trim();
                resetFieldState(fieldId);

                if (!value) {
                    setFieldError(fieldId, 'This field is required');
                    valid = false;
                }
            });
            return valid;
        }

        if (nextStepBtn) {
            nextStepBtn.addEventListener('click', function() {
                if (validateStep1()) {
                    step1.classList.add('opacity-0', '-translate-x-4');
                    setTimeout(() => {
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        // Small reflow delay
                        setTimeout(() => {
                            step2.classList.remove('opacity-0', 'translate-x-4');
                            document.getElementById('meetingName').focus();
                        }, 50);
                    }, 500);
                }
            });
        }

        if (prevStepBtn) {
            prevStepBtn.addEventListener('click', function() {
                step2.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => {
                    step2.classList.add('hidden');
                    step1.classList.remove('hidden');
                    // Small reflow delay
                    setTimeout(() => {
                        step1.classList.remove('opacity-0', '-translate-x-4');
                    }, 50);
                }, 500);
            });
        }

        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        function formatFriendlyDate(date) {
            return date.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' });
        }

        function resetFieldState(id) {
            const input = document.getElementById(id);
            const error = document.querySelector('[data-meeting-error="' + id + '"]');
            if (!input || !error) return;

            input.classList.remove('border-rose-400', 'ring-4', 'ring-rose-500/10');
            error.textContent = '';
            error.classList.add('hidden');
        }

        function setFieldError(id, message) {
            const input = document.getElementById(id);
            const error = document.querySelector('[data-meeting-error="' + id + '"]');
            if (!input || !error) return;

            input.classList.add('border-rose-400', 'ring-4', 'ring-rose-500/10');
            error.textContent = message;
            error.classList.remove('hidden');
        }

        function renderQuickDates() {
            if (!quickDatesWrap || !meetingDateInput) return;

            const today = new Date();
            quickDatesWrap.innerHTML = '';

            for (let i = 0; i < 6; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                const value = formatDateForInput(date);
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = @json($quickDateBaseClass);
                chip.textContent = formatFriendlyDate(date);
                chip.dataset.date = value;

                chip.addEventListener('click', function () {
                    meetingDateInput.value = value;
                    resetFieldState('meetingDate');
                    quickDatesWrap.querySelectorAll('button').forEach(function (btn) {
                        btn.classList.remove('border-primary-200', 'bg-primary-50', 'text-primary-700');
                    });
                    chip.classList.add('border-primary-200', 'bg-primary-50', 'text-primary-700');
                });

                quickDatesWrap.appendChild(chip);
            }
        }

        fieldIds.forEach(function (fieldId) {
            const input = document.getElementById(fieldId);
            if (!input) return;

            input.addEventListener('input', function () {
                resetFieldState(fieldId);
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            let valid = true;

            fieldIds.forEach(function (fieldId) {
                const input = document.getElementById(fieldId);
                if (!input) return;

                const value = input.value.trim();
                resetFieldState(fieldId);

                if (!value) {
                    setFieldError(fieldId, 'This field is required');
                    valid = false;
                    return;
                }

                if (fieldId === 'meetingEmail' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    setFieldError(fieldId, 'Enter a valid email address');
                    valid = false;
                }
            });

            if (!valid) {
                status.textContent = 'Please fill all required meeting details.';
                status.classList.remove('text-emerald-600');
                status.classList.add('text-rose-600');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('cursor-not-allowed', 'opacity-70');
                submitBtn.setAttribute('aria-disabled', 'true');
            }

            status.textContent = 'Meeting request submitted. Our team will confirm shortly.';
            status.classList.remove('text-rose-600');
            status.classList.add('text-emerald-600');

            const dateValue = document.getElementById('meetingDate')?.value || '';
            const slotValue = document.getElementById('meetingSlot')?.value || '';
            const nameValue = document.getElementById('meetingName')?.value || 'Guest';

            if (confirmation && confirmationText) {
                confirmationText.textContent = 'Thanks, ' + nameValue + '. Request received for ' + dateValue + ' (' + slotValue + '). We will confirm on email shortly.';
                confirmation.classList.remove('hidden');
            }

            form.classList.add('hidden');

            if (submitBtn) {
                setTimeout(function () {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('cursor-not-allowed', 'opacity-70');
                    submitBtn.setAttribute('aria-disabled', 'false');
                }, 500);
            }
        });

        if (bookAnotherBtn) {
            bookAnotherBtn.addEventListener('click', function () {
                form.reset();
                form.classList.remove('hidden');
                step2.classList.add('hidden', 'opacity-0', 'translate-x-4');
                step1.classList.remove('hidden', 'opacity-0', '-translate-x-4');
                fieldIds.forEach(resetFieldState);
                status.textContent = '';
                status.classList.remove('text-emerald-600', 'text-rose-600');
                if (confirmation) confirmation.classList.add('hidden');
                renderQuickDates();
            });
        }

        renderQuickDates();
    });
</script>
@endpush
