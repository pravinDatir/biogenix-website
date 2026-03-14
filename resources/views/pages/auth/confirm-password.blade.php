<div class="auth-shell">
    <section class="auth-stage mx-auto max-w-xl">
        <div class="auth-panel px-5 py-7 sm:px-6 sm:py-8">
            <div class="mx-auto w-full max-w-md">
                <div class="text-center">
                    <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="page-kicker mt-5">Security Check</p>
                    <h1 class="auth-title mt-3">Confirm your password</h1>
                    <p class="auth-copy mx-auto max-w-sm text-center">
                        Re-enter your current password to continue with this protected action.
                    </p>
                </div>

                <form id="confirmPasswordForm" method="POST" action="{{ route('password.confirm.store') }}" class="auth-form mt-6" novalidate>
                    @csrf

                    <div class="auth-field">
                        <label for="password" class="auth-label">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="auth-input @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        @error('password')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button id="confirmPasswordSubmitBtn" class="auth-submit" type="submit">
                        Confirm Password
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('confirmPasswordForm');
        const submitBtn = document.getElementById('confirmPasswordSubmitBtn');
        if (!form || !submitBtn) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('cursor-not-allowed', 'opacity-70');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
