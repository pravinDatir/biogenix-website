@php
    $initialStep = $errors->hasAny(['address_1', 'address_2', 'city', 'pincode', 'state']) || filled(old('address_1')) || filled(old('city')) || filled(old('pincode')) || filled(old('state')) ? 2 : 1;
    $currentLabel = $initialStep === 2 ? 'Address' : 'Personal Details';
    $labelClass = 'text-sm font-semibold text-slate-700';
    $inputClass = 'h-12 w-full rounded-2xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-900 transition placeholder:text-slate-400 focus:border-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-600/10';
    $buttonPrimaryClass = 'inline-flex h-12 items-center justify-center rounded-2xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-[0_16px_35px_-18px_rgba(37,99,235,0.7)] transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
    $buttonSecondaryClass = 'inline-flex h-12 items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50';
@endphp

<div class="relative overflow-hidden px-4 py-10 sm:px-6 lg:px-8">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[24rem] bg-[radial-gradient(circle_at_top_left,rgba(37,99,235,0.18),transparent_38%),radial-gradient(circle_at_top_right,rgba(14,165,233,0.12),transparent_30%)]"></div>
    <div class="pointer-events-none absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-[120%] rounded-full bg-primary-100/70 blur-3xl"></div>
    <div class="pointer-events-none absolute right-0 top-10 -z-10 h-80 w-80 translate-x-1/3 rounded-full bg-sky-100/70 blur-3xl"></div>

    <section class="mx-auto w-full max-w-4xl">
        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_28px_80px_-36px_rgba(15,23,42,0.35)]">
            <form id="signupForm" method="POST" action="{{ route('register') }}" class="grid gap-8 px-5 py-7 sm:px-8 sm:py-9" novalidate>
                @csrf
                <input type="hidden" name="user_type" value="b2c">
                <input type="hidden" name="country" value="India">

                <div class="space-y-4 text-center">
                    <div class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-primary-700">
                        Personal Account
                    </div>
                    <div class="space-y-3">
                        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Sign Up</h1>
                        <p class="mx-auto max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">
                            Create your Biogenix account to browse products, request quotations, and manage your orders with a cleaner checkout flow.
                        </p>
                        <p class="text-sm text-slate-600">
                            Are you a business owner or a healthcare professional?
                            <a href="{{ route('b2b.signup') }}" class="font-semibold text-primary-700 no-underline transition hover:text-primary-600">
                                Register for a B2B Account
                            </a>
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50/80 px-4 py-5 sm:px-5" aria-label="Signup progress">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Step <span id="signupCurrentStep">{{ $initialStep }}</span> of 2</span>
                        <span id="signupCurrentLabel" class="text-sm font-semibold text-slate-800">{{ $currentLabel }}</span>
                    </div>

                    <div id="signupProgressBar" class="mt-4" role="progressbar" aria-valuemin="1" aria-valuemax="2" aria-valuenow="{{ $initialStep }}">
                        <div class="flex items-center gap-3">
                            @foreach (['Personal Details', 'Address'] as $index => $stepLabel)
                                @php
                                    $stepNumber = $index + 1;
                                    $isCurrent = $stepNumber === $initialStep;
                                    $isActive = $stepNumber <= $initialStep;
                                @endphp
                                <div class="flex flex-1 items-center gap-3 @if($stepNumber === 2) justify-end text-right @endif">
                                    <div
                                        data-signup-step
                                        data-step-index="{{ $stepNumber }}"
                                        class="@if($stepNumber === 2) ml-auto @endif flex min-w-0 items-center gap-3 rounded-2xl border px-3 py-3 transition sm:px-4 {{ $isCurrent ? 'border-primary-200 bg-primary-50/80 shadow-sm' : 'border-slate-200 bg-white' }}"
                                    >
                                        <span
                                            data-signup-step-circle
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isActive ? 'border-primary-600 bg-primary-600 text-white' : 'border-slate-300 bg-white text-slate-400' }}"
                                        >
                                            <span data-signup-step-number>{{ $stepNumber }}</span>
                                        </span>
                                        <div class="min-w-0">
                                            <p
                                                data-signup-step-caption
                                                class="text-[11px] font-semibold uppercase tracking-[0.18em] transition {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}"
                                            >
                                                Step {{ $stepNumber }}
                                            </p>
                                            <p data-signup-step-label class="truncate text-sm font-semibold transition {{ $isActive ? 'text-slate-900' : 'text-slate-500' }}">
                                                {{ $stepLabel }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($stepNumber === 1)
                                        <div
                                            data-signup-connector
                                            class="hidden h-0.5 flex-1 rounded-full transition sm:block {{ $initialStep > 1 ? 'bg-primary-600' : 'bg-slate-200' }}"
                                        ></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="flex flex-col gap-1 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700" role="alert">
                        <strong class="font-semibold">Check the form and try again.</strong>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div data-signup-panel data-step="1" class="grid gap-6 @if($initialStep !== 1) hidden @endif">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2" data-field-group>
                            <label for="firstName" class="{{ $labelClass }}">First Name</label>
                            <input type="text" id="firstName" name="first_name" value="{{ old('first_name') }}" class="{{ $inputClass }}" placeholder="Aarav">
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>

                        <div class="grid gap-2" data-field-group>
                            <label for="lastName" class="{{ $labelClass }}">Last Name</label>
                            <input type="text" id="lastName" name="last_name" value="{{ old('last_name') }}" class="{{ $inputClass }}" placeholder="Sharma">
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>
                    </div>

                    <div class="grid gap-2" data-field-group>
                        <label for="signupEmail" class="{{ $labelClass }}">Email</label>
                        <input type="email" id="signupEmail" name="email" value="{{ old('email') }}" class="{{ $inputClass }}" placeholder="you@example.com" autocomplete="email">
                        <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                    </div>

                    <div class="grid gap-2" data-field-group>
                        <label for="phone" class="{{ $labelClass }}">Phone Number</label>
                        <input type="text" id="phone" name="phone" maxlength="10" value="{{ old('phone') }}" class="{{ $inputClass }}" placeholder="10-digit mobile number" inputmode="numeric">
                        <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2" data-field-group>
                            <label for="signupPassword" class="{{ $labelClass }}">Password</label>
                            <div class="relative">
                                <input type="password" id="signupPassword" name="password" class="{{ $inputClass }} pr-12" placeholder="Minimum 8 characters" autocomplete="new-password">
                                <button type="button" id="toggleSignupPassword" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center text-slate-400 transition hover:text-slate-600" aria-label="Show password" aria-pressed="false">
                                    <svg data-password-hidden-icon class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg data-password-visible-icon class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18"></path>
                                        <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                                        <path d="M9.9 5.2A10.5 10.5 0 0 1 12 5c6 0 9.5 7 9.5 7a18.8 18.8 0 0 1-3.2 3.9"></path>
                                        <path d="M6.2 6.2C3.9 7.9 2.5 12 2.5 12s3.5 7 9.5 7c1.7 0 3.2-.4 4.6-1.1"></path>
                                    </svg>
                                </button>
                            </div>
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>

                        <div class="grid gap-2" data-field-group>
                            <label for="confirmPassword" class="{{ $labelClass }}">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="confirmPassword" name="password_confirmation" class="{{ $inputClass }} pr-12" placeholder="Repeat your password" autocomplete="new-password">
                                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center text-slate-400 transition hover:text-slate-600" aria-label="Show password" aria-pressed="false">
                                    <svg data-password-hidden-icon class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg data-password-visible-icon class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18"></path>
                                        <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                                        <path d="M9.9 5.2A10.5 10.5 0 0 1 12 5c6 0 9.5 7 9.5 7a18.8 18.8 0 0 1-3.2 3.9"></path>
                                        <path d="M6.2 6.2C3.9 7.9 2.5 12 2.5 12s3.5 7 9.5 7c1.7 0 3.2-.4 4.6-1.1"></path>
                                    </svg>
                                </button>
                            </div>
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="{{ $buttonPrimaryClass }} min-w-[8rem]" id="nextBtn">Next</button>
                    </div>
                </div>

                <div data-signup-panel data-step="2" class="grid gap-6 @if($initialStep !== 2) hidden @endif">
                    <div class="grid gap-2" data-field-group>
                        <label for="addressLine1" class="{{ $labelClass }}">Flat / House / Building</label>
                        <input type="text" id="addressLine1" name="address_1" value="{{ old('address_1') }}" class="{{ $inputClass }}" placeholder="Flat number, house, or building name">
                        <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                    </div>

                    <div class="grid gap-2" data-field-group>
                        <label for="addressLine2" class="{{ $labelClass }}">Area / Street / Sector</label>
                        <input type="text" id="addressLine2" name="address_2" value="{{ old('address_2') }}" class="{{ $inputClass }}" placeholder="Street, area, sector, or landmark">
                        <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2" data-field-group>
                            <label for="city" class="{{ $labelClass }}">Town / City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" class="{{ $inputClass }}" placeholder="Mumbai">
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>

                        <div class="grid gap-2" data-field-group>
                            <label for="pincode" class="{{ $labelClass }}">Pincode</label>
                            <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" class="{{ $inputClass }}" placeholder="400001" inputmode="numeric">
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="grid gap-2" data-field-group>
                            <label for="state" class="{{ $labelClass }}">State / UT</label>
                            <select id="state" name="state" data-old-value="{{ old('state') }}" class="{{ $inputClass }}">
                                <option value="">Select State / UT</option>
                            </select>
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        </div>

                        <div class="grid gap-2">
                            <label for="countryDisplay" class="{{ $labelClass }}">Country</label>
                            <input type="text" id="countryDisplay" value="India" disabled class="{{ $inputClass }} cursor-not-allowed bg-slate-100 text-slate-500">
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <button type="button" class="{{ $buttonSecondaryClass }}" id="backBtn">Back</button>
                        <button type="submit" class="{{ $buttonPrimaryClass }}" id="signupSubmitBtn">Sign Up</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('js/signup.js') }}?v=20260315-3"></script>
@endpush
