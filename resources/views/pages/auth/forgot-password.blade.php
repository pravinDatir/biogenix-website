@php
    $showSuccessState = request()->boolean('sent') || filled(session('status')) || filled(session('success'));
@endphp

<div class="mx-auto w-full max-w-[30.5rem] py-4 md:py-8">
    <section class="overflow-hidden rounded-[28px] border border-slate-200/90 bg-[linear-gradient(180deg,#ffffff_0%,#fcfdff_100%)] shadow-[0_28px_80px_rgba(15,23,42,0.10)]">
        <div class="px-5 py-7 sm:px-6 sm:py-8">
            @if ($showSuccessState)
                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-[linear-gradient(180deg,#f2f8ff_0%,#e8f2ff_100%)] text-[#2f83ec] shadow-[inset_0_0_0_1px_rgba(47,131,236,0.12),0_18px_44px_rgba(35,131,235,0.12)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 7 13.8 15.2a2.7 2.7 0 0 1-3.82 0L2 7"></path>
                        <path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>

                <h1 class="mt-4 text-center text-[1.45rem] font-extrabold leading-[1.15] tracking-[-0.05em] text-slate-950 sm:text-[1.55rem]">
                    Check your email
                </h1>
                <p class="mx-auto mt-3 max-w-[19.5rem] text-center text-[13px] leading-6 text-slate-500 sm:text-[13.5px]">
                    We've sent a password reset link to your registered email address. Please check your inbox and follow the instructions to secure your account.
                </p>

                <div class="mt-5 grid gap-4">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex h-11 w-full items-center justify-center rounded-[14px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] text-[13px] font-extrabold text-white no-underline shadow-[0_18px_36px_rgba(35,131,235,0.24)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_40px_rgba(35,131,235,0.28)]"
                    >
                        Back to Login
                    </a>
                </div>

                <div class="mt-5 border-t border-slate-200 pt-5 text-center">
                    <p class="text-[12px] font-medium leading-6 text-slate-400">Didn't receive the email?</p>
                    <div class="mt-2 inline-flex flex-wrap items-center justify-center gap-3 text-[12px] font-semibold text-slate-500">
                        <a href="{{ route('forgot.password') }}" class="text-[#2f83ec] no-underline">Resend link</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('contact') }}" class="text-[#2f83ec] no-underline">Contact support</a>
                    </div>
                </div>

                <div class="mt-5 rounded-[22px] bg-[linear-gradient(180deg,rgba(235,245,255,0.95)_0%,rgba(224,238,255,0.94)_100%)] p-4 shadow-[inset_0_0_0_1px_rgba(191,219,254,0.5)]">
                    <div class="mx-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/70 text-sky-400 shadow-[0_14px_34px_rgba(125,184,255,0.12)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3 5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3Z"></path>
                            <path d="M9.5 12.5 12 15l3.5-4"></path>
                        </svg>
                    </div>
                    <div class="mt-3 rounded-2xl bg-white/90 px-4 py-3 shadow-[0_12px_24px_rgba(15,23,42,0.04)]">
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#2f83ec]">Security Pro-Tip</p>
                        <p class="mt-1.5 text-[12px] leading-5 text-slate-700">Biogenix will never ask for your password via email or phone.</p>
                    </div>
                </div>
            @else
                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-[linear-gradient(180deg,#f2f8ff_0%,#e8f2ff_100%)] text-[#2f83ec] shadow-[inset_0_0_0_1px_rgba(47,131,236,0.12),0_18px_44px_rgba(35,131,235,0.12)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8V4"></path>
                        <path d="M8.5 6.5 12 3l3.5 3.5"></path>
                        <path d="M12 21a8 8 0 1 0-8-8"></path>
                        <path d="M4 16v5h5"></path>
                        <path d="M4 21l4-4"></path>
                    </svg>
                </div>

                <h1 class="mt-4 text-center text-[1.45rem] font-extrabold leading-[1.15] tracking-[-0.05em] text-slate-950 sm:text-[1.55rem]">
                    Forgot password?
                </h1>
                <p class="mx-auto mt-3 max-w-[19.5rem] text-center text-[13px] leading-6 text-slate-500 sm:text-[13.5px]">
                    No worries, it happens. Enter the email address associated with your Biogenix account and we'll send you a link to reset your password.
                </p>

                <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" data-success-url="{{ route('forgot.password', ['sent' => 1]) }}" class="mt-5 grid gap-4" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="forgotEmail" class="mb-2 block text-[12px] font-bold text-slate-950">Work Email</label>
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
                                class="h-12 w-full rounded-2xl border border-slate-300 bg-white px-11 text-[13px] font-medium text-slate-900 shadow-[0_1px_2px_rgba(15,23,42,0.02)] transition placeholder:text-slate-400 hover:border-slate-300 focus:border-[#2f83ec] focus:outline-none focus:ring-4 focus:ring-[#2f83ec]/10"
                                placeholder="name@biogenix.com"
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="forgotSubmitBtn"
                        class="inline-flex h-11 w-full items-center justify-center rounded-[14px] bg-gradient-to-r from-[#2f8fff] to-[#1d72d8] text-[13px] font-extrabold text-white shadow-[0_18px_36px_rgba(35,131,235,0.24)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_40px_rgba(35,131,235,0.28)]"
                    >
                        Send Reset Link
                    </button>
                </form>

                <div class="mt-5 border-t border-slate-200 pt-5 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 text-[12px] font-bold text-[#2f83ec] no-underline">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5"></path>
                            <path d="m12 19-7-7 7-7"></path>
                        </svg>
                        Back to login
                    </a>
                    <p class="mt-3 text-[12px] font-medium leading-5 text-slate-400">
                        Having trouble?
                        <a href="{{ route('contact') }}" class="font-semibold text-[#2f83ec] no-underline">Contact support</a>
                    </p>
                </div>
            @endif

            <div class="mt-5 border-t border-slate-200 pt-4 text-center text-[11px] font-medium leading-5 text-slate-400">
                &copy; 2024 Biogenix Laboratories. Advancing genetics for a better future.
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
            forgotSubmitBtn.classList.add('is-loading');
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
