@extends('layouts.app')

@section('title', 'Book a Meeting')

@section('content')
@php
    $sectionCardClass = 'rounded-[var(--ui-radius-card)] border border-slate-200/80 bg-white/95 p-6 shadow-[var(--ui-shadow-card)] backdrop-blur md:p-8';
    $accentCardClass = 'relative overflow-hidden rounded-[var(--ui-radius-card)] border border-primary-100/70 bg-gradient-to-br from-white via-primary-50/55 to-white p-6 shadow-[var(--ui-shadow-panel)] backdrop-blur md:p-8';
    $inputClass = 'block min-h-12 w-full rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 text-sm text-slate-900 shadow-[var(--ui-shadow-soft)] transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-12 items-center justify-center rounded-2xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 transition hover:-translate-y-px hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $meetingUser = auth()->user();

    // Business step: keep old values visible after validation so the user can correct only the fields that failed.
    $minimumMeetingDate = $minimumMeetingDate instanceof \Carbon\Carbon ? $minimumMeetingDate->toDateString() : ($minimumMeetingDate ?? now()->toDateString());
    $preferredDateValue = old('preferred_date', $minimumMeetingDate);
    $startTimeValue = old('start_time', '09:00');
    $endTimeValue = old('end_time', '10:00');
    $fullNameValue = old('full_name', $meetingUser?->name ?? '');
    $emailValue = old('email', $meetingUser?->email ?? '');
    $phoneValue = preg_replace('/[^0-9]/', '', (string) old('phone', $meetingUser?->phone ?? ''));
    $phoneValue = strlen($phoneValue) > 10 ? substr($phoneValue, -10) : $phoneValue;
    $organizationNameValue = old('organization_name', '');
@endphp

<div class="bg-gradient-to-b from-white via-primary-50/20 to-white">
    <section class="relative overflow-hidden bg-primary-800 py-16 text-white md:py-24">
        <img src="{{ asset('upload/corousel/image3.jpg') }}" alt="Biogenix Meeting" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-800/95 via-primary-800/70 to-primary-600/30"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
           
            <h1 class="mx-auto max-w-5xl font-display text-4xl font-bold tracking-tight text-secondary-600 md:text-5xl lg:text-6xl">
                Accelerate your diagnostics operations with Biogenix.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-secondary-600 md:text-lg">
                Schedule a one-on-one session with our specialists to discuss product catalogs, bulk procurement, and enterprise supply chain solutions tailored to your lab or hospital.
            </p>
        </div>
    </section>

    <section class="bg-transparent py-12 md:py-20">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-8 lg:px-8 xl:px-10">
            <div class="flex flex-col justify-center space-y-8 lg:col-span-5">
                <div>
                    <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Why book a meeting?</h2>
                    <p class="mt-3 max-w-none text-base leading-8 text-slate-600">We go beyond simple transactions. A dedicated session allows us to map our diagnostic solutions directly to your clinical throughput and budget requirements.</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4 rounded-3xl border border-slate-200/80 bg-white/80 p-4 shadow-[var(--ui-shadow-soft)] backdrop-blur">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary-50 text-primary-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Tailored Product Mapping</h3>
                            <p class="mt-1 text-sm text-slate-600">Get personalized recommendations across IVD kits, reagents, and instruments matching your facility's scale.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-3xl border border-slate-200/80 bg-white/80 p-4 shadow-[var(--ui-shadow-soft)] backdrop-blur">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-primary-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Custom Commercial Models</h3>
                            <p class="mt-1 text-sm text-slate-600">Discuss bulk pricing, credit terms, and compliant procurement models suited for modern healthcare accounts.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-3xl border border-slate-200/80 bg-white/80 p-4 shadow-[var(--ui-shadow-soft)] backdrop-blur">
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
                        <h2 class="font-display text-3xl font-semibold tracking-tight text-slate-950">Schedule Your Session</h2>
                        <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Choose your preferred date and time range, then share your details. Our team will confirm the meeting over email or phone.</p>
                    </div>

                    <form id="meetingForm" action="{{ route('book-meeting.store') }}" method="POST" class="relative z-10">
                        @csrf

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="preferred_date" class="mb-1 block text-sm font-medium text-slate-700">Preferred Date</label>
                                <input id="preferred_date" name="preferred_date" type="date" min="{{ $minimumMeetingDate }}" value="{{ $preferredDateValue }}" class="{{ $inputClass }} @error('preferred_date') border-rose-400 ring-4 ring-rose-500/10 @enderror" required>
                                @error('preferred_date')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end">
                                <p class="w-full rounded-2xl border border-primary-100/80 bg-primary-50/70 px-4 py-3 text-sm font-medium text-slate-600">
                                    Meeting hours: <span class="font-semibold text-slate-900">09:00 to 18:00 IST</span>
                                </p>
                            </div>

                            <div>
                                <label for="start_time" class="mb-2 block text-sm font-medium text-slate-700">Start time</label>
                                <div class="relative">
                                    <input id="start_time" name="start_time" type="time" min="09:00" max="18:00" value="{{ $startTimeValue }}" class="{{ $inputClass }} @error('start_time') border-rose-400 ring-4 ring-rose-500/10 @enderror pr-11" required>
                                </div>
                                @error('start_time')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="mb-2 block text-sm font-medium text-slate-700">End time</label>
                                <div class="relative">
                                    <input id="end_time" name="end_time" type="time" min="09:00" max="18:00" value="{{ $endTimeValue }}" class="{{ $inputClass }} @error('end_time') border-rose-400 ring-4 ring-rose-500/10 @enderror pr-11" required>
                                </div>
                                @error('end_time')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                @error('time_range')
                                    <p class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="full_name" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ $fullNameValue }}" class="{{ $inputClass }} @error('full_name') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="e.g. Dr. Jane Doe" maxlength="150" required>
                                @error('full_name')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Work Email</label>
                                <input id="email" name="email" type="email" value="{{ $emailValue }}" class="{{ $inputClass }} @error('email') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="you@hospital.com" maxlength="150" required>
                                @error('email')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone Number</label>
                                <div class="relative">
                                    <input id="phone" name="phone" type="text" value="{{ $phoneValue }}" class="{{ $inputClass }} @error('phone') border-rose-400 ring-4 ring-rose-500/10 @enderror pl-12" placeholder="9876543210" maxlength="10" inputmode="numeric" required>
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="organization_name" class="mb-1 flex items-center justify-between text-sm font-medium text-slate-700">
                                    <span>Organization / Hospital Name</span>
                                    <span class="text-xs font-normal text-slate-400">Optional</span>
                                </label>
                                <input id="organization_name" name="organization_name" type="text" value="{{ $organizationNameValue }}" class="{{ $inputClass }} @error('organization_name') border-rose-400 ring-4 ring-rose-500/10 @enderror" placeholder="Name of your institution" maxlength="150">
                                @error('organization_name')
                                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-[var(--ui-shadow-soft)]">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-700">Voice Note</p>
                                            <p class="mt-1 text-sm leading-6 text-slate-500">Tap the mic, speak your note, and tap again to stop. For now, the recognized speech will appear below on this page.</p>
                                        </div>

                                        <button type="button" id="meetingVoiceButton" class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-primary-200 bg-primary-50 text-primary-700 shadow-[var(--ui-shadow-soft)] transition hover:-translate-y-px hover:bg-primary-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20" aria-label="Start speech capture">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.25a5.25 5.25 0 005.25-5.25v-2.25a.75.75 0 00-1.5 0v2.25a3.75 3.75 0 01-7.5 0v-2.25a.75.75 0 00-1.5 0v2.25A5.25 5.25 0 0012 18.25z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a2.75 2.75 0 002.75-2.75V7.75a2.75 2.75 0 10-5.5 0v5a2.75 2.75 0 002.75 2.75z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.25v2.25" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 20.5h5" />
                                            </svg>
                                        </button>
                                    </div>

                                    <p id="meetingVoiceStatus" class="mt-4 text-sm font-medium text-slate-500">Speech recognition is loading.</p>

                                    <div class="mt-4">
                                        <label for="meetingSpeechPreview" class="mb-2 block text-sm font-medium text-slate-700">Speech Preview</label>
                                        <textarea id="meetingSpeechPreview" rows="4" class="{{ $inputClass }} min-h-32 resize-y" placeholder="Your recognized speech will appear here." readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <button type="submit" id="meetingSubmitBtn" class="{{ $primaryButtonClass }} w-full md:w-auto">Confirm Meeting Request</button>
                            <p class="text-sm font-medium text-slate-500">Business meetings are confirmed manually by our team.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-b from-white via-primary-50/10 to-white py-14 md:py-20">
        <div class="mx-auto w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10">
            <x-ui.section-heading title="Meeting Formats" subtitle="Pick a format that fits your objective: commercial, technical, or strategic." />
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Discovery (15-20 min)', 'copy' => 'Quick fitment call to map product lines to your workflow and budget expectations.'],
                    ['title' => 'Solution Deep Dive (30-40 min)', 'copy' => 'Technical deep dive with specs, compatibility, validation approach, and QA expectations.'],
                    ['title' => 'Procurement Alignment (25-30 min)', 'copy' => 'Commercial and logistics review: SLAs, delivery windows, payment terms, and governance.'],
                ] as $format)
                    <article class="h-full rounded-[var(--ui-radius-card)] border border-slate-200/80 bg-white/95 p-6 shadow-[var(--ui-shadow-card)] backdrop-blur transition hover:-translate-y-1.5 hover:border-primary-100 hover:shadow-[var(--ui-shadow-panel)] md:p-8">
                        <h3 class="font-display text-xl font-semibold text-slate-950">{{ $format['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $format['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const meetingVoiceButton = document.getElementById('meetingVoiceButton');
        const meetingVoiceStatus = document.getElementById('meetingVoiceStatus');
        const meetingSpeechPreview = document.getElementById('meetingSpeechPreview');
        const speechRecognitionToolClass = window.SpeechRecognition || window.webkitSpeechRecognition;
        let meetingSpeechTool = null;
        let isMeetingSpeechToolRunning = false;
        let shouldKeepMeetingSpeechToolRunning = false;
        let savedMeetingSpeechText = '';

        if (!startTimeInput || !endTimeInput) {
            return;
        }

        // Step 1: keep the end time aligned with the chosen start time.
        const syncEndTimeWindow = function () {
            endTimeInput.min = startTimeInput.value || '09:00';

            if (endTimeInput.value && startTimeInput.value && endTimeInput.value <= startTimeInput.value) {
                endTimeInput.value = '';
            }
        };

        syncEndTimeWindow();
        startTimeInput.addEventListener('input', syncEndTimeWindow);

        if (!meetingVoiceButton || !meetingVoiceStatus || !meetingSpeechPreview) {
            return;
        }

        // Step 2: show the latest speech status near the mic button.
        function updateMeetingVoiceStatus(statusText, statusType) {
            meetingVoiceStatus.textContent = statusText;
            meetingVoiceStatus.className = 'mt-4 text-sm font-medium';

            if (statusType === 'success') {
                meetingVoiceStatus.classList.add('text-emerald-600');

                return;
            }

            if (statusType === 'error') {
                meetingVoiceStatus.classList.add('text-rose-600');

                return;
            }

            meetingVoiceStatus.classList.add('text-slate-500');
        }

        // Step 3: show the recognized speech inside the page.
        function updateMeetingSpeechPreview(speechPreviewText) {
            meetingSpeechPreview.value = speechPreviewText;
        }

        // Step 4: keep the mic button style aligned with the listening state.
        function updateMeetingVoiceButton(isListening) {
            if (isListening) {
                meetingVoiceButton.classList.remove('border-primary-200', 'bg-primary-50', 'text-primary-700');
                meetingVoiceButton.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-600');
                meetingVoiceButton.setAttribute('aria-label', 'Stop speech capture');

                return;
            }

            meetingVoiceButton.classList.remove('border-rose-200', 'bg-rose-50', 'text-rose-600');
            meetingVoiceButton.classList.add('border-primary-200', 'bg-primary-50', 'text-primary-700');
            meetingVoiceButton.setAttribute('aria-label', 'Start speech capture');
        }

        // Step 5: disable the mic button when speech recognition is not available.
        function disableMeetingVoiceButton() {
            meetingVoiceButton.disabled = true;
            meetingVoiceButton.classList.remove('border-primary-200', 'bg-primary-50', 'text-primary-700');
            meetingVoiceButton.classList.add('cursor-not-allowed', 'border-slate-200', 'bg-slate-100', 'text-slate-400', 'opacity-70');
            meetingVoiceButton.setAttribute('aria-label', 'Speech recognition is not supported by this browser');
        }

        // Step 6: prepare the browser speech tool.
        function buildMeetingSpeechTool() {
            if (!speechRecognitionToolClass) {
                return null;
            }

            const speechRecognitionTool = new speechRecognitionToolClass();
            speechRecognitionTool.lang = 'en-IN';
            speechRecognitionTool.continuous = true;
            speechRecognitionTool.interimResults = true;
            speechRecognitionTool.maxAlternatives = 1;

            return speechRecognitionTool;
        }

        // Step 7: start the browser speech capture.
        function startMeetingSpeechCapture() {
            meetingSpeechTool = buildMeetingSpeechTool();

            if (!meetingSpeechTool) {
                disableMeetingVoiceButton();
                updateMeetingVoiceStatus('Speech recognition is not supported by this browser.', 'error');

                return;
            }

            savedMeetingSpeechText = '';
            shouldKeepMeetingSpeechToolRunning = true;
            isMeetingSpeechToolRunning = true;
            updateMeetingSpeechPreview('');

            // Step 7A: show the active listening state.
            meetingSpeechTool.onstart = function () {
                updateMeetingVoiceButton(true);
                updateMeetingVoiceStatus('Listening... Speak now and tap the mic again when you want to stop.', 'info');
            };

            // Step 7B: keep the preview box updated with the latest speech.
            meetingSpeechTool.onresult = function (event) {
                let previewSpeechText = savedMeetingSpeechText;

                for (let resultIndex = event.resultIndex; resultIndex < event.results.length; resultIndex++) {
                    const currentSpeechResult = event.results[resultIndex];
                    const currentSpeechText = currentSpeechResult[0] ? currentSpeechResult[0].transcript || '' : '';
                    const cleanSpeechText = currentSpeechText.trim();

                    if (cleanSpeechText === '') {
                        continue;
                    }

                    if (currentSpeechResult.isFinal) {
                        if (savedMeetingSpeechText === '') {
                            savedMeetingSpeechText = cleanSpeechText;
                        } else {
                            savedMeetingSpeechText = savedMeetingSpeechText + ' ' + cleanSpeechText;
                        }

                        previewSpeechText = savedMeetingSpeechText;

                        continue;
                    }

                    if (savedMeetingSpeechText === '') {
                        previewSpeechText = cleanSpeechText;
                    } else {
                        previewSpeechText = savedMeetingSpeechText + ' ' + cleanSpeechText;
                    }
                }

                previewSpeechText = previewSpeechText.trim();

                if (previewSpeechText !== '') {
                    updateMeetingSpeechPreview(previewSpeechText);
                    updateMeetingVoiceStatus('Listening... The recognized speech is showing below.', 'info');
                }
            };

            // Step 7C: show clear browser error messages on the page.
            meetingSpeechTool.onerror = function (event) {
                updateMeetingVoiceButton(false);
                isMeetingSpeechToolRunning = false;

                if (event.error === 'aborted' && !shouldKeepMeetingSpeechToolRunning) {
                    return;
                }

                if (event.error === 'not-allowed' || event.error === 'service-not-allowed') {
                    shouldKeepMeetingSpeechToolRunning = false;
                    updateMeetingVoiceStatus('Microphone access was not allowed.', 'error');

                    return;
                }

                if (event.error === 'no-speech') {
                    updateMeetingVoiceStatus('No speech was detected yet. Keep speaking or tap the mic to stop.', 'info');

                    return;
                }

                shouldKeepMeetingSpeechToolRunning = false;
                updateMeetingVoiceStatus('Speech recognition could not continue in this browser.', 'error');
            };

            // Step 7D: keep listening until the user stops the mic button.
            meetingSpeechTool.onend = function () {
                updateMeetingVoiceButton(false);
                isMeetingSpeechToolRunning = false;

                if (shouldKeepMeetingSpeechToolRunning) {
                    window.setTimeout(function () {
                        if (!shouldKeepMeetingSpeechToolRunning || !meetingSpeechTool) {
                            return;
                        }

                        try {
                            meetingSpeechTool.start();
                        } catch (error) {
                            shouldKeepMeetingSpeechToolRunning = false;
                            updateMeetingVoiceStatus('Speech recognition paused. Please tap the mic again.', 'error');
                        }
                    }, 150);

                    return;
                }

                const finalSpeechText = meetingSpeechPreview.value.trim();

                if (finalSpeechText === '') {
                    updateMeetingVoiceStatus('Speech capture stopped. No speech text was found.', 'error');

                    return;
                }

                updateMeetingVoiceStatus('Speech capture stopped. The recognized text is shown below.', 'success');
            };

            try {
                meetingSpeechTool.start();
            } catch (error) {
                shouldKeepMeetingSpeechToolRunning = false;
                isMeetingSpeechToolRunning = false;
                updateMeetingVoiceButton(false);
                updateMeetingVoiceStatus('Speech recognition could not start. Please try again.', 'error');
            }
        }

        // Step 8: stop the browser speech capture.
        function stopMeetingSpeechCapture() {
            if (!meetingSpeechTool) {
                return;
            }

            shouldKeepMeetingSpeechToolRunning = false;
            isMeetingSpeechToolRunning = false;

            try {
                meetingSpeechTool.stop();
            } catch (error) {
                // Step 8A: keep the stop flow stable when the browser already ended the current listen cycle.
            }

            updateMeetingVoiceButton(false);
            updateMeetingVoiceStatus('Stopping speech capture...', 'info');
        }

        // Step 9: toggle between start and stop in one simple flow.
        function handleMeetingVoiceButtonClick() {
            if (meetingVoiceButton.disabled) {
                return;
            }

            if (shouldKeepMeetingSpeechToolRunning || isMeetingSpeechToolRunning) {
                stopMeetingSpeechCapture();

                return;
            }

            startMeetingSpeechCapture();
        }

        // Step 10: disable the mic button when the browser does not support this feature.
        if (!speechRecognitionToolClass) {
            disableMeetingVoiceButton();
            updateMeetingVoiceStatus('Speech recognition is not supported by this browser.', 'error');

            return;
        }

        // Step 11: show the ready state when the browser supports speech recognition.
        updateMeetingVoiceStatus('Speech recognition is ready. Tap the mic and start speaking.', 'info');
        meetingVoiceButton.addEventListener('click', handleMeetingVoiceButtonClick);
    });
</script>
@endpush
@endsection
