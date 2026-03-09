<div class="page-shell">
    <section class="mx-auto w-full max-w-6xl py-4 md:py-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <aside class="hidden overflow-hidden rounded-3xl border border-blue-200/50 bg-gradient-to-br from-slate-950 via-blue-950 to-cyan-900 p-8 text-white shadow-xl lg:col-span-5 lg:flex lg:flex-col">
                <span class="inline-flex w-fit items-center rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-100">
                    Biogenix Access
                </span>
                <h2 class="mt-5 text-3xl font-bold leading-tight text-white">Secure diagnostic commerce for modern teams.</h2>
                <p class="mt-3 text-sm leading-relaxed text-blue-100">
                    Sign in to manage catalog requests, quotation workflows, and account-level order visibility from one workspace.
                </p>

                <ul class="mt-8 space-y-3 text-sm text-blue-50">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span>
                        Quote generation with MRP-safe guest and account workflows
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span>
                        Faster approvals and distribution support across India
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span>
                        Access tailored pricing and enterprise order controls
                    </li>
                </ul>
            </aside>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-7 lg:p-8">
                <div class="mx-auto w-full max-w-lg">
                    <h1 class="ui-page-title">Welcome back</h1>
                    <p class="ui-small mt-2">Login to continue your Biogenix dashboard and order workflows.</p>

                    @if (session('success'))
                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-6 space-y-3 [&_.form-group]:mb-0">
                        @csrf

                        <div class="form-group">
                            <label for="loginEmail">Email / ID</label>
                            <input
                                type="email"
                                name="email"
                                id="loginEmail"
                                class="form-control @error('email') border-red-500 ring-1 ring-red-100 @enderror"
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
                            <div class="flex items-center justify-between gap-3">
                                <label for="loginPassword">Password</label>
                                <a href="{{ route('forgot.password') }}" class="text-sm font-medium text-blue-700 hover:underline">Forgot Password?</a>
                            </div>
                            <div class="password-wrapper">
                                <input
                                    type="password"
                                    name="password"
                                    id="loginPassword"
                                    class="form-control pr-16 @error('password') border-red-500 ring-1 ring-red-100 @enderror"
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

                        <button type="submit" id="loginSubmitBtn" class="btn btn-primary mt-2 w-full">Login</button>
                    </form>

                    <p class="mt-5 text-sm text-slate-600">
                        New here?
                        <a href="{{ route('signup') }}" class="font-semibold text-blue-700 hover:underline">Create account</a>
                    </p>
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
