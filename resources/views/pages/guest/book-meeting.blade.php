<div class="full-bleed">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-slate-900 py-16 text-white md:py-24">
        <img src="{{ asset('images/image3.jpg') }}" alt="Biogenix Meeting" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-slate-900/80 to-slate-900/40"></div>
        <div class="container relative z-10 text-center">
            <x-badge variant="info" class="mb-4 inline-block !border-white/20 !bg-white/10 !text-blue-100">Talk to an Expert</x-badge>
            <h1 class="mx-auto max-w-4xl text-3xl font-semibold leading-tight text-white sm:text-4xl md:text-5xl lg:text-6xl">
                Accelerate your diagnostics operations with Biogenix.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base text-slate-100 md:text-lg">
                Schedule a one-on-one session with our specialists to discuss product catalogs, bulk procurement, and enterprise supply chain solutions tailored to your lab or hospital.
            </p>
        </div>
    </section>

    <!-- Main Content: Form & What to Expect -->
    <section class="bg-slate-50 py-12 md:py-20">
        <div class="container grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-8">
            
            <!-- Left Side: Features / What to Expect -->
            <div class="lg:col-span-5 flex flex-col justify-center space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">Why book a meeting?</h2>
                    <p class="mt-3 text-sm text-slate-600 md:text-base">We go beyond simple transactions. A dedicated session allows us to map our diagnostic solutions directly to your clinical throughput and budget requirements.</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
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
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Dedicated Support Pipeline</h3>
                            <p class="mt-1 text-sm text-slate-600">Learn how our Lucknow-led fulfillment hub and priority escalation desks ensure maximum uptime for your lab.</p>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-full bg-slate-200">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Expert" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Enterprise Sales Team</p>
                            <p class="text-xs text-slate-500">Biogenix Corporate Office</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm italic text-slate-700">"We aim to streamline your entire procurement process. A quick 15-minute chat lets us tailor a solution exactly for your scale and regional requirements."</p>
                </div>
            </div>

            <!-- Right Side: The Form -->
            <div class="lg:col-span-7">
                <x-ui.surface-card class="!p-6 md:!p-8 shadow-xl border-t-4 border-t-blue-600 relative overflow-hidden">
                    <!-- Decorative Background element -->
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-blue-50 opacity-50 blur-3xl pointer-events-none"></div>

                    <div class="mb-8 border-b border-slate-100 pb-5">
                        <h2 class="text-2xl font-bold text-slate-900">Schedule Your Session</h2>
                        <p class="mt-2 text-sm text-slate-600">Select a preferred time slot, and we will confirm your meeting over email.</p>
                    </div>

                    <form id="meetingForm" class="relative z-10 grid grid-cols-1 gap-5 md:grid-cols-2 [&_.form-group]:mb-0" novalidate>
                        <div class="form-group md:col-span-2">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-600">1. Select Date & Time</p>
                            <div id="meetingQuickDates" class="mb-3 flex flex-wrap gap-2"></div>
                            
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="meetingDate" class="mb-1 block text-sm font-medium text-slate-700">Preferred Date</label>
                                    <input id="meetingDate" type="date" class="form-control" required>
                                    <span class="error"></span>
                                </div>
                                <div>
                                    <label for="meetingSlot" class="mb-1 block text-sm font-medium text-slate-700">Time Slot</label>
                                    <select id="meetingSlot" class="form-control" required>
                                        <option value="">Select a slot</option>
                                        <option>10:00 AM - 11:00 AM</option>
                                        <option>11:30 AM - 12:30 PM</option>
                                        <option>02:00 PM - 03:00 PM</option>
                                        <option>04:00 PM - 05:00 PM</option>
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group md:col-span-2 mt-2">
                            <p class="mb-3 border-t border-slate-100 pt-5 text-xs font-semibold uppercase tracking-wide text-blue-600">2. Your Details</p>
                        </div>

                        <div class="form-group">
                            <label for="meetingName" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                            <input id="meetingName" type="text" class="form-control" placeholder="e.g. Dr. Jane Doe" required>
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="meetingEmail" class="mb-1 block text-sm font-medium text-slate-700">Work Email</label>
                            <input id="meetingEmail" type="email" class="form-control" placeholder="you@hospital.com" required>
                            <span class="error"></span>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label for="meetingOrg" class="mb-1 block text-sm font-medium text-slate-700">Organization / Hospital Name</label>
                            <input id="meetingOrg" type="text" class="form-control" placeholder="Name of your institution" required>
                            <span class="error"></span>
                        </div>

                        <div class="md:col-span-2 mt-4 flex flex-wrap items-center gap-3">
                            <button type="submit" id="meetingSubmitBtn" class="btn btn-primary w-full md:w-auto !px-8 !py-3">Confirm Meeting Request</button>
                            <p id="meetingStatus" class="form-status w-full md:w-auto mt-2 md:mt-0"></p>
                        </div>
                    </form>

                    <div id="meetingConfirmation" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center shadow-inner mt-4">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900">Meeting Request Submitted!</h3>
                        <p id="meetingConfirmationText" class="mt-2 text-sm text-emerald-800">Our team will confirm your slot shortly via email.</p>
                        <div class="mt-6">
                            <button type="button" id="bookAnotherMeetingBtn" class="btn secondary">Book Another Session</button>
                        </div>
                    </div>
                </x-ui.surface-card>
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="bg-white py-12 md:py-16">
        <div class="container text-center">
            <h2 class="text-2xl font-bold text-slate-900">Need immediate assistance?</h2>
            <p class="mt-2 text-slate-600">If your request is urgent, skip the meeting booking and talk to our support desk right now.</p>
            <div class="mt-6 flex justify-center gap-4">
                <x-ui.action-link :href="route('contact')" class="!bg-slate-900 hover:!bg-slate-800">Go to Support Desk</x-ui.action-link>
                <a href="tel:+919876543210" class="btn secondary">Call +91 98765 43210</a>
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
        if (!form) return;

        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        function formatFriendlyDate(date) {
            return date.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' });
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
                chip.className = 'chip-filter';
                chip.textContent = formatFriendlyDate(date);
                chip.dataset.date = value;

                chip.addEventListener('click', function () {
                    meetingDateInput.value = value;
                    quickDatesWrap.querySelectorAll('button').forEach(function (btn) {
                        btn.classList.remove('border-blue-300', 'text-blue-700');
                    });
                    chip.classList.add('border-blue-300', 'text-blue-700');
                });

                quickDatesWrap.appendChild(chip);
            }
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const status = document.getElementById('meetingStatus');
            const fields = [
                { id: 'meetingDate', rules: ['required'] },
                { id: 'meetingSlot', rules: ['required'] },
                { id: 'meetingName', rules: ['required'] },
                { id: 'meetingEmail', rules: ['required', 'email'] },
                { id: 'meetingOrg', rules: ['required'] }
            ];

            if (typeof validateFields === 'function' && !validateFields(fields)) {
                status.textContent = 'Please fill all required meeting details.';
                status.classList.remove('success');
                status.classList.add('error');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('is-loading');
                submitBtn.setAttribute('aria-disabled', 'true');
            }

            status.textContent = 'Meeting request submitted. Our team will confirm shortly.';
            status.classList.remove('error');
            status.classList.add('success');
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
                    submitBtn.classList.remove('is-loading');
                    submitBtn.setAttribute('aria-disabled', 'false');
                }, 500);
            }
        });

        if (bookAnotherBtn) {
            bookAnotherBtn.addEventListener('click', function () {
                form.reset();
                form.classList.remove('hidden');
                if (confirmation) {
                    confirmation.classList.add('hidden');
                }
                renderQuickDates();
            });
        }

        renderQuickDates();
    });
</script>
@endpush
