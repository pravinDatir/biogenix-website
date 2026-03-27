@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
@php
    $panelClass = 'relative overflow-hidden rounded-3xl border border-[var(--ui-border)] bg-[var(--ui-surface)] p-5 shadow-[var(--ui-shadow-card)] md:p-7';
    $accentPanelClass = 'relative overflow-hidden rounded-3xl border border-primary-100 bg-[var(--ui-surface)] p-5 shadow-[var(--ui-shadow-card)] md:p-7';
    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $textareaClass = 'block min-h-[7.5rem] w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $labelClass = 'absolute left-4 top-4 z-10 origin-[0] -translate-y-3 scale-75 transform text-sm text-slate-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-3 peer-focus:scale-75 peer-focus:text-primary-600 cursor-text';
    $contactUser = auth()->user();

    // Business step: keep old form values visible after validation so the user does not need to type everything again.
    $fullNameValue = old('full_name', $contactUser?->name ?? '');
    $emailValue = old('email', $contactUser?->email ?? '');
    $phoneValue = preg_replace('/[^0-9]/', '', (string) old('phone', $contactUser?->phone ?? ''));
    $phoneValue = strlen($phoneValue) > 10 ? substr($phoneValue, -10) : $phoneValue;
    $messageValue = old('message', '');
    $selectedEnquiryTypeId = (string) old('enquiry_type_id', '');
    $canSubmitEnquiry = isset($enquiryTypes) && $enquiryTypes->isNotEmpty();
@endphp

<div class="bg-primary-50/10">
    <section class="relative overflow-hidden bg-primary-800 py-20 text-white lg:py-28">
        <img src="{{ asset('upload/corousel/image2.jpg') }}" alt="Contact Biogenix" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-800/95 via-primary-800/70 to-primary-600/30"></div>
        <div class="relative z-10 mx-auto w-full max-w-none px-4 text-center sm:px-6 lg:px-8 xl:px-10">
            <h1 class="mx-auto max-w-4xl font-display text-4xl font-bold tracking-tight text-secondary-600 md:text-5xl lg:text-6xl">Let's Connect</h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-secondary-600 md:text-lg">Whether you need product inquiries, partnerships, or dedicated technical support, our specialized teams are ready to guide you.</p>
        </div>
    </section>

    <section class="py-10 pb-20">
        <div class="mx-auto grid w-full max-w-none grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-12 lg:gap-8 lg:px-8 xl:px-10">
            <div class="flex flex-col justify-start space-y-6 lg:col-span-5">
                <article class="{{ $panelClass }} bg-gradient-to-br from-[var(--ui-surface)] via-[var(--ui-surface-muted)] to-primary-50/35">
                    <div class="relative h-56 w-full overflow-hidden rounded-2xl border border-[var(--ui-border)] bg-gradient-to-br from-[var(--ui-surface-subtle)] via-primary-50/60 to-[var(--ui-surface-muted)]">
                        <iframe
                            id="contactMap"
                            class="h-full w-full border-0"
                            src="https://www.google.com/maps?q=Lucknow%2C%20Uttar%20Pradesh&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Biogenix Location Map"
                        ></iframe>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-3xl font-semibold tracking-tight text-slate-950">Corporate Headquarters</h3>
                        <p class="mt-2 max-w-none text-base leading-8 text-slate-600">123 Medical Park Drive, Lucknow, Uttar Pradesh, India</p>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-primary-600">
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
                    </div>
                </article>
            </div>

            <article class="{{ $accentPanelClass }} lg:col-span-7">
                <div class="pointer-events-none absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-primary-50 opacity-50 blur-3xl"></div>

                <div class="mb-6">
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Send an Inquiry</h2>
                    <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Tell us your requirement and our dedicated team will get back to you with the next steps quickly.</p>
                </div>

                <form id="contactForm" action="{{ route('contact.store') }}" method="POST" class="relative z-10 grid grid-cols-1 gap-5 md:grid-cols-2">
                    @csrf

                    <div class="relative">
                        <input type="text" id="full_name" name="full_name" value="{{ $fullNameValue }}" class="{{ $inputClass }} @error('full_name') border-rose-400 ring-4 ring-rose-500/10 @enderror peer pt-6 pb-2" placeholder=" " maxlength="150" required>
                        <label for="full_name" class="{{ $labelClass }}">Full Name</label>
                        @error('full_name')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ $emailValue }}" class="{{ $inputClass }} @error('email') border-rose-400 ring-4 ring-rose-500/10 @enderror peer pt-6 pb-2" placeholder=" " maxlength="150" required>
                        <label for="email" class="{{ $labelClass }}">Work Email</label>
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <span class="absolute left-3 top-4 flex items-center font-medium text-slate-500">+91</span>
                        <input type="text" id="phone" name="phone" value="{{ $phoneValue }}" class="{{ $inputClass }} @error('phone') border-rose-400 ring-4 ring-rose-500/10 @enderror peer pl-12 pt-6 pb-2" placeholder=" " maxlength="10" inputmode="numeric" required>
                        <label for="phone" class="{{ $labelClass }} left-12">Phone Mobile</label>
                        @error('phone')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <select id="enquiry_type_id" name="enquiry_type_id" class="{{ $inputClass }} @error('enquiry_type_id') border-rose-400 ring-4 ring-rose-500/10 @enderror appearance-none peer pt-6 pb-2" required>
                            <option value="" disabled @selected($selectedEnquiryTypeId === '') hidden></option>
                            @forelse (($enquiryTypes ?? collect()) as $enquiryType)
                                <option value="{{ $enquiryType->id }}" @selected($selectedEnquiryTypeId === (string) $enquiryType->id)>{{ $enquiryType->name }}</option>
                            @empty
                                <option value="" disabled>Inquiry types will be available soon</option>
                            @endforelse
                        </select>
                        <label for="enquiry_type_id" class="{{ $labelClass }} peer-valid:-translate-y-3 peer-valid:scale-75">Inquiry Type</label>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        @error('enquiry_type_id')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative md:col-span-2">
                        <textarea id="message" name="message" rows="5" class="{{ $textareaClass }} @error('message') border-rose-400 ring-4 ring-rose-500/10 @enderror peer pt-6 pb-2" placeholder=" " maxlength="500" required>{{ $messageValue }}</textarea>
                        <label for="message" class="{{ $labelClass }}">Message</label>
                        <div class="absolute bottom-3 right-4 text-xs font-semibold text-slate-400">
                            <span id="charCount">{{ strlen($messageValue) }}</span>/500
                        </div>
                        @error('message')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 flex flex-col items-center gap-4 md:col-span-2 sm:flex-row">
                        <button type="submit" id="contactSubmitBtn" class="{{ $primaryButtonClass }} w-full sm:w-auto {{ $canSubmitEnquiry ? '' : 'cursor-not-allowed opacity-60' }}" @disabled(! $canSubmitEnquiry)>
                            Submit Inquiry
                        </button>
                        <a href="https://wa.me/919876543210" target="_blank" rel="noopener" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-green-500/30 transition hover:bg-primary-600 hover:shadow-xl sm:w-auto">
                            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Support
                        </a>
                        @if (! $canSubmitEnquiry)
                            <p class="text-sm font-medium text-secondary-700">Inquiry types are not available right now. Please try again later.</p>
                        @endif
                    </div>
                </form>
            </article>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageArea = document.getElementById('message');
        const charCount = document.getElementById('charCount');

        if (!messageArea || !charCount) {
            return;
        }

        // Business step: keep the live character count clear so the user knows the message length before submit.
        const syncCharacterCount = function () {
            charCount.textContent = messageArea.value.length;
            charCount.classList.toggle('text-rose-600', messageArea.value.length >= 500);
        };

        syncCharacterCount();
        messageArea.addEventListener('input', syncCharacterCount);
    });
</script>
@endpush
@endsection
