<div class="page-shell">
    <section class="mx-auto w-full max-w-md">
        <x-ui.surface-card>
            <h1 class="ui-page-title">Confirm Password</h1>
            <p class="ui-small mt-1">Please confirm your password to continue.</p>

            <form id="confirmPasswordForm" method="POST" action="{{ route('password.confirm.store') }}" class="mt-5 space-y-3 [&_.form-group]:mb-0">
                @csrf

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control @error('password') border-red-500 ring-1 ring-red-100 @enderror" required>
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <span class="error"></span>
                </div>

                <button id="confirmPasswordSubmitBtn" class="btn btn-primary w-full" type="submit">Confirm Password</button>
            </form>
        </x-ui.surface-card>
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
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-disabled', 'true');
        });
    });
</script>
@endpush
