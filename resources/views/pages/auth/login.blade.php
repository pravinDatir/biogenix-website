@php
    $successMessage = session('success') ?: session('status');
    $errorMessage = session('error') ?: ($errors->first('email') ?: $errors->first('password'));
@endphp

<div class="auth-shell">
    <section class="auth-stage">
        <div class="auth-grid">
            <aside class="auth-media">
                <img
                    src="{{ asset('storage/slides/home2.jpg') }}"
                    alt="Biogenix laboratory"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                    decoding="async"
                >
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.24)_0%,rgba(15,23,42,0.72)_46%,rgba(37,99,235,0.84)_100%)]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_62%_18%,rgba(255,255,255,0.22),transparent_28%)]"></div>
                <div class="absolute left-7 top-5 h-4 w-72 rounded-full bg-white/75 shadow-lg"></div>
                <div class="absolute left-[3.2rem] top-0 h-6 w-px bg-white/25"></div>
                <div class="absolute left-[18.8rem] top-0 h-6 w-px bg-white/25"></div>

                <div class="relative z-10 max-w-sm">
                    <div class="flex items-center gap-3">
                        <svg class="h-8 w-8 shrink-0 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M7 3h10"></path>
                            <path d="M10 3v3"></path>
                            <path d="M14 3v3"></path>
                            <path d="M7 6h10l-1 2.5v4.5a5 5 0 0 1-4 4.9V21"></path>
                            <path d="M17 6l1 2.5"></path>
                            <path d="M7 6 6 8.5"></path>
                            <path d="M8.5 13h7"></path>
                            <path d="M9 21h6"></path>
                            <path d="M6 21h12"></path>
                        </svg>
                        <p class="text-4xl font-bold tracking-tight text-white">Biogenix</p>
                    </div>

                    <p class="mt-6 text-lg font-semibold leading-9 text-white/95">
                        Sign in to manage catalog access, quotations, and procurement workflows from one consistent portal.
                    </p>
                </div>
            </aside>

            <div class="auth-panel">
                <div class="auth-panel-inner">
                    <div>
                        <p class="page-kicker">Secure Access</p>
                        <h1 class="auth-title mt-3">Sign in to your account</h1>
                        <p class="auth-copy">
                            Enter your credentials to continue with product discovery, order review, and account-specific pricing.
                        </p>
                    </div>

                    @if ($errorMessage)
                        <div class="auth-alert auth-alert--error" role="alert">
                            <svg class="mt-0.5 h-4.5 w-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                            <span>{{ $errorMessage }}</span>
                        </div>
                    @elseif ($successMessage)
                        <div class="auth-alert auth-alert--success" role="status">
                            <svg class="mt-0.5 h-4.5 w-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $successMessage }}</span>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
                        @csrf

                        <div class="auth-field">
                            <label for="loginEmail" class="auth-label">Email Address</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    type="email"
                                    name="email"
                                    id="loginEmail"
                                    class="auth-input auth-input--icon @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                                    placeholder="researcher@biogenix.com"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <div class="flex items-center justify-between gap-3">
                                <label for="loginPassword" class="auth-label">Password</label>
                                <a href="{{ route('forgot.password') }}" class="auth-link text-sm">Forgot Password?</a>
                            </div>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                    <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                </svg>
                                <input
                                    type="password"
                                    name="password"
                                    id="loginPassword"
                                    class="auth-input auth-input--icon pr-12 @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required
                                >
                                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Toggle password visibility">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label for="rememberCheck" class="inline-flex items-center gap-3 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                id="rememberCheck"
                                value="1"
                                class="auth-checkbox"
                                @checked(old('remember'))
                            >
                            <span>Keep me signed in</span>
                        </label>

                        <button type="submit" id="loginSubmitBtn" class="auth-submit">
                            Sign In
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p class="auth-footer-meta">
                            Need assistance?
                            <a href="{{ route('contact') }}" class="auth-link">Contact Support</a>
                        </p>

                        <p class="auth-footer-meta mt-3">
                            New here?
                            <a href="{{ route('signup') }}" class="auth-link">Create an account</a>
                        </p>

                        <div class="auth-footer-links">
                            <span>&copy; 2026 Biogenix Corp.</span>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('privacy') }}" class="text-inherit no-underline">Privacy</a>
                                <a href="{{ route('terms') }}" class="text-inherit no-underline">Terms</a>
                                <a href="{{ route('contact') }}" class="text-inherit no-underline">Security</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                loginSubmitBtn.classList.add('cursor-not-allowed', 'opacity-70');
                loginSubmitBtn.setAttribute('aria-disabled', 'true');
            });
        }
    });
</script>
@endpush
