@php
    $requestedType = request('user_type', request('portal'));
    $portal = $requestedType === 'b2b' ? 'b2b' : 'b2c';

    $views = [
        'b2c' => [
            'panelBadge' => 'BIOGENIX ACCESS',
            'panelTitle' => 'Secure diagnostic commerce for modern healthcare teams.',
            'panelCopy' => 'Sign in to continue with catalog access, quotation workflows, and account-level operations based on your role.',
            'detailBadge' => 'B2C Access',
            'detailTitle' => 'Retail Buyer Login',
            'detailCopy' => 'Use your customer account to browse catalog, generate PI for self, and manage your own orders and support history.',
            'detailCard' => 'border border-emerald-200/60 bg-[#d9e7e2]',
            'detailBadgeClass' => 'border border-emerald-200 bg-emerald-50 text-emerald-700',
            'detailDotClass' => 'bg-emerald-500',
            'detailItems' => [
                'Retail / MRP context with your personal account scope',
                'Own orders, own shipments, own support tickets only',
                'PI generation for self',
            ],
            'detailNote' => null,
            'submitLabel' => 'Login to B2C Account',
        ],
        'b2b' => [
            'panelBadge' => 'BIOGENIX ACCESS',
            'panelTitle' => 'Secure diagnostic commerce for modern healthcare teams.',
            'panelCopy' => 'Sign in to continue with catalog access, quotation workflows, and account-level operations based on your role.',
            'detailBadge' => 'B2B Access',
            'detailTitle' => 'Business Account Login',
            'detailCopy' => 'Use your distributor, lab, hospital, or institutional account for customer-specific pricing and business workflows.',
            'detailCard' => 'border border-blue-200/70 bg-[#dbe4f2]',
            'detailBadgeClass' => 'border border-blue-200 bg-blue-50 text-blue-700',
            'detailDotClass' => 'bg-blue-500',
            'detailItems' => [
                'Customer-specific business pricing and approval-aware ordering',
                'PI for self and assigned client scope when permitted',
                'Access is limited to your company and assigned client visibility',
            ],
            'detailNote' => 'New B2B registrations require admin approval before first login.',
            'submitLabel' => 'Login to B2B Workspace',
        ],
    ];

    $view = $views[$portal];
@endphp

<div class="page-shell">
    <section class="mx-auto w-full max-w-6xl py-6 md:py-10">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[0.92fr_1.08fr]">
            <aside class="overflow-hidden rounded-[2rem] bg-[linear-gradient(180deg,#020b3a_0%,#172a67_58%,#0e617f_100%)] p-8 text-white shadow-[0_24px_60px_rgba(15,23,42,0.20)]">
                <span class="auth-kicker border border-white/20 bg-white/10 text-white">
                    {{ $view['panelBadge'] }}
                </span>

                <h1 class="auth-panel-title text-white">
                    {{ $view['panelTitle'] }}
                </h1>

                <p class="auth-copy max-w-lg text-blue-50">
                    {{ $view['panelCopy'] }}
                </p>

                <div class="mt-9 rounded-[1.6rem] p-5 {{ $view['detailCard'] }}">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $view['detailBadgeClass'] }}">
                        {{ $view['detailBadge'] }}
                    </span>

                    <h2 class="auth-section-title text-slate-950">
                        {{ $view['detailTitle'] }}
                    </h2>

                    <p class="auth-copy text-slate-700">
                        {{ $view['detailCopy'] }}
                    </p>

                    <ul class="auth-detail-list text-slate-800">
                        @foreach ($view['detailItems'] as $item)
                            <li class="flex items-start gap-3">
                                <span class="mt-3 h-2.5 w-2.5 shrink-0 rounded-full {{ $view['detailDotClass'] }}"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($view['detailNote'])
                        <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            {{ $view['detailNote'] }}
                        </div>
                    @endif
                </div>
            </aside>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-[0_18px_40px_rgba(15,23,42,0.08)] md:p-10">
                <h2 class="auth-page-title">Welcome back</h2>
                <p class="auth-page-subtitle">Choose your access type and login with your registered account.</p>

                <div class="mt-8 grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('login', ['user_type' => 'b2c']) }}"
                        @class([
                            'auth-switch-link',
                            'border-emerald-300 bg-emerald-50 text-emerald-700' => $portal === 'b2c',
                            'border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:text-slate-900' => $portal !== 'b2c',
                        ])
                    >
                        B2C Login
                    </a>
                    <a
                        href="{{ route('login', ['user_type' => 'b2b']) }}"
                        @class([
                            'auth-switch-link',
                            'border-blue-300 bg-blue-50 text-blue-700' => $portal === 'b2b',
                            'border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:text-slate-900' => $portal !== 'b2b',
                        ])
                    >
                        B2B Login
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

                <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-10 space-y-5 [&_.form-group]:mb-0">
                    @csrf

                    <div class="form-group">
                        <label for="loginEmail">Email / ID</label>
                        <input
                            type="email"
                            name="email"
                            id="loginEmail"
                            class="form-control h-12 @error('email') border-red-500 ring-1 ring-red-100 @enderror"
                            placeholder="you@company.com"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="loginPassword" class="mb-0">Password</label>
                            <a href="{{ route('forgot.password') }}" class="text-sm font-semibold text-blue-700 hover:underline">Forgot Password?</a>
                        </div>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                name="password"
                                id="loginPassword"
                                class="form-control h-12 pr-16 @error('password') border-red-500 ring-1 ring-red-100 @enderror"
                                placeholder="Enter password"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" id="togglePassword" class="toggle-password">Show</button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="error"></span>
                    </div>

                    <label class="auth-check-label">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            @checked(old('remember'))
                        >
                        Remember me on this device
                    </label>

                    <button type="submit" id="loginSubmitBtn" class="btn btn-primary mt-2 w-full !py-3.5 text-base">
                        {{ $view['submitLabel'] }}
                    </button>
                </form>

                <p class="auth-footer-copy">
                    New here?
                    <a href="{{ route('signup', ['user_type' => $portal]) }}" class="font-semibold text-blue-700 hover:underline">Create account</a>
                </p>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof setupPasswordToggle === 'function') {
            setupPasswordToggle('loginPassword', 'togglePassword');
        }

        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');

        if (loginForm && loginSubmitBtn) {
            loginForm.addEventListener('submit', function () {
                loginSubmitBtn.disabled = true;
                loginSubmitBtn.classList.add('is-loading');
                loginSubmitBtn.setAttribute('aria-disabled', 'true');
            });
        }
    });
</script>
@endpush
