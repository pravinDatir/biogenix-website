<div class="page-shell">
    <section class="mx-auto w-full max-w-md">
        <x-ui.surface-card>
            <h1 class="ui-page-title">Reset Password</h1>
            <p class="ui-small mt-1">Set your new password below.</p>

            <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-3 [&_.form-group]:mb-0">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') border-red-500 ring-1 ring-red-100 @enderror" value="{{ old('email', $request->email) }}" required>
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" class="form-control @error('password') border-red-500 ring-1 ring-red-100 @enderror" required>
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') border-red-500 ring-1 ring-red-100 @enderror" required>
                    @error('password_confirmation')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <button id="resetPasswordSubmitBtn" class="btn btn-primary w-full" type="submit">Reset Password</button>
            </form>
        </x-ui.surface-card>
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
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
