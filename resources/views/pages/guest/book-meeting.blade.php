<section id="book-meeting" class="section-stack !py-6 md:!py-8">
    <div class="mx-auto w-full max-w-5xl">
        <x-ui.section-heading title="Book a Meeting" subtitle="Pick a date and slot, then share your details. We'll confirm quickly." />
    </div>

    <x-ui.surface-card class="mx-auto w-full max-w-5xl !p-4 md:!p-5">
        <form id="meetingForm" class="grid grid-cols-1 gap-3 md:grid-cols-2 [&_.form-group]:mb-0" novalidate>
            <div class="form-group md:col-span-2">
                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-500">Quick Date Picker</p>
                <div id="meetingQuickDates" class="mb-3 flex flex-wrap gap-2"></div>
                <label for="meetingDate" class="mb-1 block text-sm font-medium text-slate-700">Preferred Date</label>
                <input id="meetingDate" type="date" class="form-control" required>
                <span class="error"></span>
            </div>

            <div class="form-group">
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

            <div class="form-group">
                <label for="meetingName" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input id="meetingName" type="text" class="form-control" placeholder="Your full name" required>
                <span class="error"></span>
            </div>

            <div class="form-group">
                <label for="meetingEmail" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input id="meetingEmail" type="email" class="form-control" placeholder="you@company.com" required>
                <span class="error"></span>
            </div>

            <div class="form-group">
                <label for="meetingOrg" class="mb-1 block text-sm font-medium text-slate-700">Organization</label>
                <input id="meetingOrg" type="text" class="form-control" placeholder="Organization name" required>
                <span class="error"></span>
            </div>

            <div class="md:col-span-2 flex flex-wrap items-center gap-2">
                <button type="submit" id="meetingSubmitBtn" class="btn btn-primary btn-sm">Confirm Meeting Request</button>
                <p id="meetingStatus" class="form-status"></p>
            </div>
        </form>

        <div id="meetingConfirmation" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-4 md:p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Confirmation</p>
            <h3 class="mt-2 text-xl font-semibold text-emerald-900">Meeting request submitted</h3>
            <p id="meetingConfirmationText" class="mt-2 text-sm text-emerald-800">Our team will confirm your slot shortly.</p>
            <div class="mt-4">
                <button type="button" id="bookAnotherMeetingBtn" class="btn secondary btn-sm">Book Another Meeting</button>
            </div>
        </div>
    </x-ui.surface-card>
</section>

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
