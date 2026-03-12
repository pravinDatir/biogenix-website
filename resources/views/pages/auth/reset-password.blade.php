<div class="auth-shell">
    <section class="auth-stage mx-auto max-w-xl">
        <div class="auth-panel px-5 py-7 sm:px-6 sm:py-8">
            <div class="mx-auto w-full max-w-md">
                <div class="text-center">
                    <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="page-kicker mt-5">Account Recovery</p>
                    <h1 class="auth-title mt-3">Set a new password</h1>
                    <p class="auth-copy mx-auto max-w-sm text-center">
                        Create a strong password for your account and use it for future sign-ins.
                    </p>
                </div>

                <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}" class="auth-form mt-6" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="auth-field">
                        <label for="email" class="auth-label">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="auth-input @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                            value="{{ old('email', $request->email) }}"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password" class="auth-label">New Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="auth-input @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                            placeholder="Minimum 8 characters"
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password_confirmation" class="auth-label">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="auth-input @error('password_confirmation') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                            placeholder="Re-enter password"
                            autocomplete="new-password"
                            required
                        >
                        @error('password_confirmation')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button id="resetPasswordSubmitBtn" class="auth-submit" type="submit">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('resetPasswordSubmitBtn');
        if (!form || !submitBtn) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('cursor-not-allowed', 'opacity-70');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
