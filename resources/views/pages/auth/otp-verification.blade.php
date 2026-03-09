@extends('layouts.app')

@section('title', 'OTP Verification')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-md">
        <x-ui.surface-card>
            <h1 class="ui-page-title">OTP / Email Verification</h1>
            <p class="ui-small mt-1">Enter the 6-digit code sent to your registered email.</p>

            <form id="otpForm" action="#" method="POST" class="mt-5 space-y-3" novalidate>
                <div class="otp-row">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" class="otp-input" inputmode="numeric" aria-label="OTP digit {{ $i + 1 }}">
                    @endfor
                </div>

                <button id="otpSubmitBtn" type="submit" class="btn btn-primary w-full">Verify</button>
                <p id="otpStatus" class="form-status text-center"></p>
                <p class="text-center text-sm text-slate-600">Didn't receive code? <a class="text-blue-700 hover:underline" href="#">Resend OTP</a></p>
            </form>
        </x-ui.surface-card>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('otpForm');
        const submitBtn = document.getElementById('otpSubmitBtn');
        const status = document.getElementById('otpStatus');
        if (!form || !submitBtn || !status) return;

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-disabled', 'true');

            status.textContent = 'Verification request submitted.';
            status.classList.remove('error');
            status.classList.add('success');

            setTimeout(function () {
                submitBtn.disabled = false;
                submitBtn.classList.remove('is-loading');
                submitBtn.setAttribute('aria-disabled', 'false');
            }, 500);
        });
    });
</script>
@endpush
@endsection
