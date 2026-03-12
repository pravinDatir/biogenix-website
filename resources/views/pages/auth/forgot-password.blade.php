@php
    $showSuccessState = request()->boolean('sent') || filled(session('status')) || filled(session('success'));
@endphp

<div class="mx-auto w-full max-w-xl py-4 md:py-8">
    <section class="auth-stage">
        <div class="auth-panel px-5 py-7 sm:px-6 sm:py-8">
            <div class="mx-auto w-full max-w-md text-center">
                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700 shadow-sm">
                    @if ($showSuccessState)
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 7 13.8 15.2a2.7 2.7 0 0 1-3.82 0L2 7"></path>
                            <path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    @else
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8V4"></path>
                            <path d="M8.5 6.5 12 3l3.5 3.5"></path>
                            <path d="M12 21a8 8 0 1 0-8-8"></path>
                            <path d="M4 16v5h5"></path>
                            <path d="M4 21l4-4"></path>
                        </svg>
                    @endif
                </div>

                @if ($showSuccessState)
                    <p class="page-kicker mt-5">Reset Link Sent</p>
                    <h1 class="auth-title mt-3">Check your email</h1>
                    <p class="auth-copy mx-auto max-w-sm">
                        We sent a password reset link to your registered email address. Follow the instructions in your inbox to secure your account.
                    </p>

                    <div class="mt-6 grid gap-4">
                        <a href="{{ route('login') }}" class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Back to Login</a>
                    </div>

                    <div class="mt-6 border-t border-slate-200 pt-5">
                        <p class="text-sm leading-6 text-slate-400">Didn't receive the email?</p>
                        <div class="mt-2 inline-flex flex-wrap items-center justify-center gap-3 text-sm font-semibold text-slate-500">
                            <a href="{{ route('forgot.password') }}" class="auth-link">Resend link</a>
                            <span class="text-slate-300">|</span>
                            <a href="{{ route('contact') }}" class="auth-link">Contact support</a>
                        </div>
                    </div>

                    <div class="mt-6 rounded-3xl border border-primary-100 bg-primary-50 p-4 text-left">
                        <div class="mx-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-primary-700 shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 3 5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3Z"></path>
                                <path d="M9.5 12.5 12 15l3.5-4"></path>
                            </svg>
                        </div>
                        <div class="mt-3 rounded-2xl bg-white px-4 py-3 shadow-sm">
                            <p class="page-kicker text-primary-700">Security Tip</p>
                            <p class="mt-1.5 text-sm leading-6 text-slate-700">Biogenix will never ask for your password over email or phone.</p>
                        </div>
                    </div>
                @else
                    <p class="page-kicker mt-5">Account Recovery</p>
                    <h1 class="auth-title mt-3">Forgot your password?</h1>
                    <p class="auth-copy mx-auto max-w-sm">
                        Enter the email address associated with your Biogenix account and we will send you a secure reset link.
                    </p>

                    <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" data-success-url="{{ route('forgot.password', ['sent' => 1]) }}" class="auth-form mt-6 text-left" novalidate>
                        @csrf

                        <div class="auth-field">
                            <label for="forgotEmail" class="auth-label">Work Email</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"></path>
                                    <path d="m22 8-8.97 6.39a1.8 1.8 0 0 1-2.06 0L2 8"></path>
                                </svg>
                                <input
                                    type="text"
                                    id="forgotEmail"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="auth-input auth-input--icon"
                                    placeholder="name@biogenix.com"
                                    autocomplete="email"
                                >
                            </div>
                        </div>

                        <button type="submit" id="forgotSubmitBtn" class="auth-submit">
                            Send Reset Link
                        </button>
                    </form>

                    <div class="mt-6 border-t border-slate-200 pt-5">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-primary-700 no-underline hover:text-primary-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5"></path>
                                <path d="m12 19-7-7 7-7"></path>
                            </svg>
                            Back to login
                        </a>
                        <p class="mt-3 text-sm leading-6 text-slate-400">
                            Having trouble?
                            <a href="{{ route('contact') }}" class="auth-link">Contact support</a>
                        </p>
                    </div>
                @endif

                <div class="mt-6 border-t border-slate-200 pt-4 text-center text-xs leading-6 text-slate-400">
                    &copy; 2026 Biogenix Laboratories. Precision diagnostics, consistent delivery.
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forgotForm = document.getElementById('forgotPasswordForm');
        const forgotSubmitBtn = document.getElementById('forgotSubmitBtn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || forgotForm?.querySelector('input[name="_token"]')?.value
            || '';

        if (!forgotForm || !forgotSubmitBtn) {
            return;
        }

        forgotForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            forgotSubmitBtn.disabled = true;
            forgotSubmitBtn.classList.add('cursor-not-allowed', 'opacity-70');
            forgotSubmitBtn.setAttribute('aria-disabled', 'true');

            const formData = new FormData(forgotForm);

            try {
                await fetch(forgotForm.action, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
            } catch (error) {
                // Keep the UI privacy-safe and move to the confirmation state regardless.
            }

            window.location.href = forgotForm.dataset.successUrl || '{{ route('forgot.password', ['sent' => 1]) }}';
        });
    });
</script>
@endpush
