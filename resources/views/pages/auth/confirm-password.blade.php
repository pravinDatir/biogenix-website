@php
    $kickerClass = 'inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-primary-700';
    $fieldClass = 'grid gap-2';
    $labelClass = 'text-sm font-semibold text-slate-700';
    $inputClass = 'h-14 w-full rounded-2xl border border-slate-300 bg-white px-4 text-base font-medium text-slate-900 transition placeholder:text-slate-400 focus:border-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-600/10';
    $submitClass = 'inline-flex h-14 w-full items-center justify-center rounded-2xl bg-primary-600 text-base font-semibold text-white shadow-[0_16px_35px_-18px_rgba(37,99,235,0.7)] transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-70';
@endphp

<div class="mx-auto w-full max-w-xl py-4 md:py-8">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_24px_60px_-30px_rgba(15,23,42,0.32)]">
        <div class="bg-[linear-gradient(180deg,rgba(255,255,255,1)_0%,rgba(252,253,255,1)_100%)] px-5 py-7 sm:px-6 sm:py-8">
            <div class="mx-auto w-full max-w-md">
                <div class="text-center">
                    <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="{{ $kickerClass }} mt-5">Security Check</p>
                    <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-950 md:text-4xl">Confirm your password</h1>
                    <p class="mx-auto mt-4 max-w-sm text-center text-sm leading-7 text-slate-500 md:text-base">
                        Re-enter your current password to continue with this protected action.
                    </p>
                </div>

                <form id="confirmPasswordForm" method="POST" action="{{ route('password.confirm.store') }}" class="mt-6 grid gap-5" novalidate>
                    @csrf

                    <div class="{{ $fieldClass }}" data-field-group>
                        <label for="password" class="{{ $labelClass }}">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="{{ $inputClass }} @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-200 @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        @error('password')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @else
                            <p data-field-error class="hidden text-sm font-medium text-rose-600"></p>
                        @enderror
                    </div>

                    <button id="confirmPasswordSubmitBtn" class="{{ $submitClass }}" type="submit">
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
