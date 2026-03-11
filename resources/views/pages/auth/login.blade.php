@php
    $successMessage = session('success') ?: session('status');
    $errorMessage = session('error') ?: ($errors->first('email') ?: $errors->first('password'));
@endphp

<div class="mx-auto w-full max-w-[64rem] py-2 md:py-4">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200/90 bg-white shadow-[0_28px_80px_rgba(15,23,42,0.12)]">
        <div class="grid min-h-[40rem] grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(420px,500px)]">
            <aside class="relative hidden overflow-hidden lg:flex lg:items-end lg:p-9">
                <img
                    src="{{ asset('images/home2.jpg') }}"
                    alt="Biogenix laboratory"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                    decoding="async"
                >
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(7,52,87,0.18)_0%,rgba(5,62,120,0.62)_48%,rgba(17,111,221,0.88)_100%)]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_62%_18%,rgba(122,217,255,0.42),transparent_28%)]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_24%_12%,rgba(255,255,255,0.2),transparent_22%)]"></div>
                <div class="absolute left-7 top-5 h-[1.05rem] w-[18rem] rounded-full bg-[linear-gradient(180deg,rgba(239,248,255,0.96)_0%,rgba(191,225,255,0.72)_100%)] shadow-[0_0_0_6px_rgba(255,255,255,0.08),0_16px_45px_rgba(120,203,255,0.35)]"></div>
                <div class="absolute left-[3.2rem] top-0 h-6 w-[2px] bg-white/25"></div>
                <div class="absolute left-[18.8rem] top-0 h-6 w-[2px] bg-white/25"></div>

                <div class="relative z-10 max-w-[24rem]">
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
                        <p class="text-[2.2rem] font-extrabold leading-none tracking-[-0.04em] text-white">Biogenix</p>
                    </div>

                    <p class="mt-6 text-[1.15rem] font-semibold leading-9 text-white/95">
                        Advancing human health through precision biotechnology and collaborative research.
                    </p>
                </div>
            </aside>

            <div class="flex items-center bg-[linear-gradient(180deg,#ffffff_0%,#fcfdff_100%)] px-5 py-8 sm:px-7 lg:px-10">
                <div class="mx-auto w-full max-w-[25.5rem]">
                    <div>
                        <h1 class="text-[2rem] font-extrabold leading-[1.08] tracking-[-0.04em] text-slate-950 sm:text-[2.2rem]">
                            Login Page
                        </h1>
                        <p class="mt-3 text-[1rem] leading-8 text-slate-500">
                            Please enter your credentials to access your account.
                        </p>
                    </div>

                    @if ($errorMessage)
                        <div class="mt-8 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-[0.95rem] font-medium leading-6 text-red-700" role="alert">
                            <svg class="mt-0.5 h-[1.05rem] w-[1.05rem] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v5"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                            <span>{{ $errorMessage }}</span>
                        </div>
                    @elseif ($successMessage)
                        <div class="mt-8 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-[0.95rem] font-medium leading-6 text-emerald-700" role="status">
                            <svg class="mt-0.5 h-[1.05rem] w-[1.05rem] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $successMessage }}</span>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-8 grid gap-5" novalidate>
                        @csrf

                        <div>
                            <label for="loginEmail" class="mb-3 block text-[0.95rem] font-bold text-slate-700">Email Address</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    type="email"
                                    name="email"
                                    id="loginEmail"
                                    class="h-[3.55rem] w-full rounded-2xl border border-slate-300 bg-white px-12 text-[0.98rem] font-medium text-slate-900 shadow-[0_1px_2px_rgba(15,23,42,0.02)] transition placeholder:text-slate-400 hover:border-slate-300 focus:border-[#2f83ec] focus:outline-none focus:ring-4 focus:ring-[#2f83ec]/10 @error('email') border-red-300 focus:border-red-400 focus:ring-red-200/70 @enderror"
                                    placeholder="researcher@biogenix.com"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-[0.82rem] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label for="loginPassword" class="block text-[0.95rem] font-bold text-slate-700">Password</label>
                                <a href="{{ route('forgot.password') }}" class="text-[0.9rem] font-bold text-[#2f83ec] no-underline hover:text-[#1769d2]">
                                    Forgot Password?
                                </a>
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
                                    class="h-[3.55rem] w-full rounded-2xl border border-slate-300 bg-white px-12 pr-12 text-[0.98rem] font-medium text-slate-900 shadow-[0_1px_2px_rgba(15,23,42,0.02)] transition placeholder:text-slate-400 hover:border-slate-300 focus:border-[#2f83ec] focus:outline-none focus:ring-4 focus:ring-[#2f83ec]/10 @error('password') border-red-300 focus:border-red-400 focus:ring-red-200/70 @enderror"
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
                                <p class="mt-2 text-[0.82rem] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="remember"
                                id="rememberCheck"
                                value="1"
                                class="h-4 w-4 rounded border-slate-300 text-[#2f83ec] focus:ring-[#2f83ec]"
                                @checked(old('remember'))
                            >
                            <label for="rememberCheck" class="text-[0.96rem] font-medium text-slate-600">Keep me signed in</label>
                        </div>

                        <button
                            type="submit"
                            id="loginSubmitBtn"
                            class="inline-flex h-[3.6rem] w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] text-[1rem] font-extrabold text-white shadow-[0_18px_36px_rgba(35,131,235,0.24)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_40px_rgba(35,131,235,0.28)]"
                        >
                            Login to Portal
                        </button>
                    </form>

                    <p class="mt-6 text-center text-[0.96rem] font-medium leading-7 text-slate-500">
                        Need assistance?
                        <a href="{{ route('contact') }}" class="font-bold text-[#2f83ec] no-underline">Contact Support</a>
                    </p>

                    <p class="mt-3 text-center text-[0.96rem] font-medium leading-7 text-slate-500">
                        New here?
                        <a href="{{ route('signup') }}" class="font-bold text-[#2f83ec] no-underline">Create an account</a>
                    </p>

                    <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-5 text-[0.72rem] font-bold uppercase tracking-[0.18em] text-slate-400">
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
