@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $states = [
        'success' => [
            'badge' => 'Payment Success',
            'variant' => 'success',
            'title' => 'Payment captured and order is confirmed.',
            'copy' => 'Use this state for invoice download, communication confirmation, and next-step guidance.',
        ],
        'failed' => [
            'badge' => 'Payment Failed',
            'variant' => 'danger',
            'title' => 'Payment did not complete.',
            'copy' => 'Use this screen to show retry messaging, support escalation, and saved cart continuity.',
        ],
        'retry' => [
            'badge' => 'Retry Payment',
            'variant' => 'warning',
            'title' => 'Retry your payment with an alternate method.',
            'copy' => 'Use this state for bank switch, UPI retry, or account team follow-up.',
        ],
    ];

    $state = $states[$paymentStatus];
@endphp

<div class="grid gap-5 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
    <x-ui.surface-card title="{{ $state['title'] }}" subtitle="{{ $state['copy'] }}">
        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-3">
                <x-badge variant="{{ $state['variant'] }}">{{ $state['badge'] }}</x-badge>
                <x-badge variant="info">{{ strtoupper($portal) }}</x-badge>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Reference</p>
                    <p class="mt-2 font-semibold text-slate-900">PAY-20260311-1058</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Amount</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'INR 60,180' : 'INR 15,623.20' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Follow-Up</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $paymentStatus === 'success' ? 'Order pipeline started' : 'Commercial or payment retry required' }}</p>
                </div>
            </div>
            <x-alert type="{{ $paymentStatus === 'success' ? 'success' : ($paymentStatus === 'failed' ? 'error' : 'warning') }}">
                {{ $portal === 'b2b'
                    ? 'B2B flows can branch into PO confirmation, account-team follow-up, or client notification.'
                    : 'B2C flows can branch into delivery ETA, invoice download, and direct customer confirmation.' }}
            </x-alert>
        </div>
    </x-ui.surface-card>

    <x-ui.surface-card title="Suggested Actions" subtitle="Buttons remain non-wired placeholders because backend routes are unchanged.">
        <div class="space-y-3">
            <div class="grid gap-3 sm:grid-cols-2">
                <x-ui.action-link href="#">{{ $paymentStatus === 'success' ? 'Download Invoice' : 'Retry Payment' }}</x-ui.action-link>
                <x-ui.action-link href="#" variant="secondary">{{ $paymentStatus === 'success' ? 'Track Order' : 'Contact Support' }}</x-ui.action-link>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                {{ $paymentStatus === 'success'
                    ? 'After successful payment, this area can show SMS/email confirmation, invoice access, and order tracking hand-off.'
                    : 'After failed or retry states, this area can show error codes, safe retry tips, and saved checkout context.' }}
            </div>
        </div>
    </x-ui.surface-card>
</div>
