@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/signup.css') }}?v=20260315-3">
<style>
    .signup-page .password-wrapper {
        position: relative;
        display: block;
    }

    .signup-page .password-wrapper input {
        padding-right: 40px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z'/%3E%3Ccircle cx='12' cy='12' r='3'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 16px 16px !important;
    }

    .signup-page .password-wrapper input.is-password-visible {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 3l18 18'/%3E%3Cpath d='M10.7 5.1A10.9 10.9 0 0 1 12 5c6.4 0 10 7 10 7a18.8 18.8 0 0 1-3.2 3.9'/%3E%3Cpath d='M6.6 6.6C4.2 8.3 2 12 2 12s3.6 7 10 7c1.9 0 3.6-.5 5.1-1.3'/%3E%3Cpath d='M9.9 9.9a3 3 0 0 0 4.2 4.2'/%3E%3C/svg%3E") !important;
    }

    .signup-page .password-wrapper .toggle-password {
        position: absolute !important;
        top: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 40px !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        appearance: none !important;
        background: transparent !important;
        box-shadow: none !important;
        opacity: 0 !important;
        cursor: pointer !important;
    }
</style>
@endpush

<div class="signup-page">
    <div class="signup-orb signup-orb--left"></div>
    <div class="signup-orb signup-orb--right"></div>

    <section class="signup-shell">
        <div class="signup-card">
            <form id="signupForm" method="POST" action="{{ route('register') }}" class="signup-form" novalidate>
                @csrf
                <input type="hidden" name="user_type" value="b2c">
                <input type="hidden" name="country" value="India">

                <div class="signup-card-head">
                    <h1 class="signup-title">Sign Up</h1>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-slate-600">
                            Are you a business owner or a healthcare professional?</br>
                            <a href="{{ route('b2b.signup') }}" class="font-semibold text-primary-700 hover:text-primary-600">
                                Register for a B2B Account
                            </a>
                        </p>
                    </div>
                </div>

                <div class="signup-progress" aria-label="Signup progress">
                    <div class="signup-progress-meta">
                        <span class="signup-progress-caption">Step <span id="signupCurrentStep">1</span> of 2</span>
                        <span id="signupCurrentLabel" class="signup-progress-current">Personal Details</span>
                    </div>
                    <div id="signupProgressBar" class="signup-progress-bar" role="progressbar" aria-valuemin="1" aria-valuemax="2" aria-valuenow="1">
                        <span id="signupProgressFill" class="signup-progress-fill"></span>
                    </div>
                    <div class="stepper">
                        <div class="step active">
                            <span class="step-number">1</span>
                            <span class="step-text">Personal Details</span>
                        </div>
                        <div class="step">
                            <span class="step-number">2</span>
                            <span class="step-text">Address</span>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="signup-alert" role="alert">
                        <strong>Check the form and try again.</strong>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="form-step active" data-step="1">
                    <div class="signup-grid signup-grid--two">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="first_name" value="{{ old('first_name') }}" placeholder="Aarav">
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="last_name" value="{{ old('last_name') }}" placeholder="Sharma">
                            <span class="error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signupEmail">Email</label>
                        <input type="email" id="signupEmail" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email">
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" maxlength="10" value="{{ old('phone') }}" placeholder="10-digit mobile number" inputmode="numeric">
                        <span class="error"></span>
                    </div>

                    <div class="signup-grid signup-grid--two">
                        <div class="form-group">
                            <label for="signupPassword">Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="signupPassword" name="password" placeholder="Minimum 8 characters" autocomplete="new-password">
                                <button type="button" id="toggleSignupPassword" class="toggle-password" aria-label="Show password"></button>
                            </div>
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password">
                                <button type="button" id="toggleConfirmPassword" class="toggle-password" aria-label="Show password"></button>
                            </div>
                            <span class="error"></span>
                        </div>
                    </div>

                    <div class="signup-actions signup-actions--single">
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    </div>
                </div>

                <div class="form-step" data-step="2">
                    <div class="form-group">
                        <label for="addressLine1">Flat / House / Building</label>
                        <input type="text" id="addressLine1" name="address_1" value="{{ old('address_1') }}" placeholder="Flat number, house, or building name">
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="addressLine2">Area / Street / Sector</label>
                        <input type="text" id="addressLine2" name="address_2" value="{{ old('address_2') }}" placeholder="Street, area, sector, or landmark">
                        <span class="error"></span>
                    </div>

                    <div class="signup-grid signup-grid--two">
                        <div class="form-group">
                            <label for="city">Town / City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="Mumbai">
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" placeholder="400001" inputmode="numeric">
                            <span class="error"></span>
                        </div>
                    </div>

                    <div class="signup-grid signup-grid--two">
                        <div class="form-group">
                            <label for="state">State / UT</label>
                            <select id="state" name="state" data-old-value="{{ old('state') }}">
                                <option value="">Select State / UT</option>
                            </select>
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="countryDisplay">Country</label>
                            <input type="text" id="countryDisplay" value="India" disabled>
                        </div>
                    </div>

                    <div class="signup-actions">
                        <button type="button" class="btn btn-outline" id="backBtn">Back</button>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('js/signup.js') }}?v=20260315-3"></script>
@endpush
