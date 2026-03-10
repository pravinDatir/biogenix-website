@php
    $requestedType = request('user_type', request('portal'));
    if (! in_array($requestedType, ['b2b', 'b2c'], true)) {
        $requestedType = old('user_type') === 'b2b' ? 'b2b' : 'b2c';
    }

    $portal = $requestedType === 'b2b' ? 'b2b' : 'b2c';
    $selectedCustomerType = old('b2b_type', 'distributor');

    $views = [
        'b2c' => [
            'leftBadge' => 'JOIN BIOGENIX RETAIL',
            'leftTitle' => 'Create your personal buying account.',
            'leftCopy' => 'Register as a retail customer to access MRP-oriented catalog visibility, self quotations, and personal order workflows.',
            'infoBadge' => 'B2C Signup',
            'infoTitle' => 'Retail Customer Onboarding',
            'infoCopy' => 'Create your personal healthcare buying account with MRP-oriented catalog and self-service order workflows.',
            'infoCard' => 'border border-emerald-200 bg-[#edf6f0]',
            'infoBadgeClass' => 'border border-emerald-200 bg-emerald-50 text-emerald-700',
            'infoDotClass' => 'bg-emerald-500',
            'infoItems' => [
                'Retail access and self profile scope',
                'Own quotations, own orders, own support',
            ],
            'notice' => null,
        ],
        'b2b' => [
            'leftBadge' => 'JOIN BIOGENIX BUSINESS',
            'leftTitle' => 'Create your business access account.',
            'leftCopy' => 'Register as a distributor, dealer, lab, or hospital for account-specific pricing and approval-aware workflows.',
            'infoBadge' => 'B2B Signup',
            'infoTitle' => 'Business Account Onboarding',
            'infoCopy' => 'Register your distributor, dealer, lab, or hospital profile for business pricing and approval-aware workflows.',
            'infoCard' => 'border border-blue-200 bg-[#eef4ff]',
            'infoBadgeClass' => 'border border-blue-200 bg-blue-50 text-blue-700',
            'infoDotClass' => 'bg-blue-500',
            'infoItems' => [
                'Business pricing visibility based on permissions',
                'PI generation and company context controls',
            ],
            'notice' => 'B2B accounts are activated only after admin approval.',
        ],
    ];

    $view = $views[$portal];

    $customerTypeOptions = $portal === 'b2b'
        ? [
            'dealer' => 'Dealer',
            'distributor' => 'Distributor',
            'lab' => 'Lab',
            'hospital' => 'Hospital',
        ]
        : ['retail' => 'Retail'];
@endphp

<div class="page-shell">
    <section class="mx-auto w-full max-w-6xl py-6 md:py-10">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[0.92fr_1.08fr]">
            <aside class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                <span class="auth-kicker border {{ $portal === 'b2b' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                    {{ $view['leftBadge'] }}
                </span>

                <h1 class="auth-panel-title text-slate-950">
                    {{ $view['leftTitle'] }}
                </h1>

                <p class="auth-copy max-w-lg text-slate-600">
                    {{ $view['leftCopy'] }}
                </p>

                <div class="mt-9 rounded-[1.6rem] p-5 {{ $view['infoCard'] }}">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $view['infoBadgeClass'] }}">
                        {{ $view['infoBadge'] }}
                    </span>

                    <h2 class="auth-section-title text-slate-950">
                        {{ $view['infoTitle'] }}
                    </h2>

                    <p class="auth-copy text-slate-700">
                        {{ $view['infoCopy'] }}
                    </p>

                    <ul class="auth-detail-list text-slate-800">
                        @foreach ($view['infoItems'] as $item)
                            <li class="flex items-start gap-3">
                                <span class="mt-3 h-2.5 w-2.5 shrink-0 rounded-full {{ $view['infoDotClass'] }}"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($view['notice'])
                        <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            {{ $view['notice'] }}
                        </div>
                    @endif
                </div>
            </aside>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-[0_18px_40px_rgba(15,23,42,0.08)] md:p-10">
                <h2 class="auth-page-title">Signup / Registration</h2>
                <p class="auth-page-subtitle">Create your account and continue with role-based access.</p>

                <div class="mt-8 grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('signup', ['user_type' => 'b2c']) }}"
                        @class([
                            'auth-switch-link',
                            'border-emerald-300 bg-emerald-50 text-emerald-700' => $portal === 'b2c',
                            'border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:text-slate-900' => $portal !== 'b2c',
                        ])
                    >
                        B2C Registration
                    </a>
                    <a
                        href="{{ route('signup', ['user_type' => 'b2b']) }}"
                        @class([
                            'auth-switch-link',
                            'border-blue-300 bg-blue-50 text-blue-700' => $portal === 'b2b',
                            'border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:text-slate-900' => $portal !== 'b2b',
                        ])
                    >
                        B2B Registration
                    </a>
                </div>

                @if (session('success'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="signupForm" method="POST" action="{{ route('register') }}" class="mt-10 grid grid-cols-1 gap-5 [&_.form-group]:mb-0 md:grid-cols-2">
                    @csrf

                    <div class="form-group md:col-span-2">
                        <label for="customerType">Customer Type</label>
                        <select id="customerType" class="form-control h-12" required>
                            @foreach ($customerTypeOptions as $value => $label)
                                <option value="{{ $value }}" @selected($portal === 'b2b' ? $selectedCustomerType === $value : $value === 'retail')>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="error"></span>
                    </div>

                    <input type="hidden" name="user_type" id="userType" value="{{ $portal }}">
                    <input type="hidden" name="b2b_type" id="b2bType" value="{{ $portal === 'b2b' ? $selectedCustomerType : '' }}">

                    @if ($portal === 'b2b')
                        <div class="md:col-span-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            B2B registration requires admin approval before login activation.
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control h-12 @error('name') border-red-500 ring-1 ring-red-100 @enderror"
                            value="{{ old('name') }}"
                            placeholder="Full name"
                            required
                        >
                        @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control h-12 @error('email') border-red-500 ring-1 ring-red-100 @enderror"
                            value="{{ old('email') }}"
                            placeholder="you@company.com"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            class="form-control h-12 @error('phone') border-red-500 ring-1 ring-red-100 @enderror"
                            maxlength="10"
                            value="{{ old('phone') }}"
                            placeholder="10-digit mobile number"
                            inputmode="numeric"
                            required
                        >
                        @error('phone')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="company_name">Organization</label>
                        <input
                            type="text"
                            name="company_name"
                            id="company_name"
                            class="form-control h-12 @error('company_name') border-red-500 ring-1 ring-red-100 @enderror"
                            value="{{ old('company_name') }}"
                            placeholder="Organization name (if applicable)"
                            @required($portal === 'b2b')
                        >
                        @error('company_name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control h-12 @error('password') border-red-500 ring-1 ring-red-100 @enderror"
                            minlength="8"
                            placeholder="Minimum 8 characters"
                            required
                        >
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control h-12 @error('password_confirmation') border-red-500 ring-1 ring-red-100 @enderror"
                            minlength="8"
                            placeholder="Re-enter password"
                            required
                        >
                        @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="mt-1 flex flex-wrap items-center gap-3 md:col-span-2">
                        <button type="submit" id="signupSubmitBtn" class="btn btn-primary !px-7">Create Account</button>
                        <a href="{{ route('login', ['user_type' => $portal]) }}" class="btn secondary">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerType = document.getElementById('customerType');
        const userType = document.getElementById('userType');
        const b2bType = document.getElementById('b2bType');
        const organizationField = document.getElementById('company_name');
        const signupForm = document.getElementById('signupForm');
        const signupSubmitBtn = document.getElementById('signupSubmitBtn');

        function syncType() {
            const portal = '{{ $portal }}';
            const selected = customerType ? customerType.value : 'retail';

            if (userType) {
                userType.value = portal;
            }

            if (portal === 'b2b') {
                if (b2bType) {
                    b2bType.value = selected || 'distributor';
                }
                if (organizationField) {
                    organizationField.required = true;
                }
            } else {
                if (b2bType) {
                    b2bType.value = '';
                }
                if (organizationField) {
                    organizationField.required = false;
                }
            }
        }

        if (customerType) {
            customerType.addEventListener('change', syncType);
        }

        syncType();

        if (signupForm && signupSubmitBtn) {
            signupForm.addEventListener('submit', function () {
                signupSubmitBtn.disabled = true;
                signupSubmitBtn.classList.add('is-loading');
                signupSubmitBtn.setAttribute('aria-disabled', 'true');
            });
        }
    });
</script>
@endpush
