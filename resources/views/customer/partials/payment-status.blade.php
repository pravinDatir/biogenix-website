@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $states = [
        'success' => [
            'badge' => 'Payment Success',
            'title' => 'Payment captured and the order is ready for fulfillment.',
            'copy' => 'This state confirms the transaction, shows the commercial reference, and gives the customer a clear handoff into order management.',
            'icon_wrap' => 'bg-emerald-100 text-primary-600',
            'badge_class' => 'border-primary-200 bg-primary-50 text-primary-600',
            'panel_class' => 'border-primary-200 bg-primary-50/80',
            'note_class' => 'border-primary-200 bg-primary-50/70',
            'reference' => 'PAY-20260311-1058',
            'order_id' => $portal === 'b2b' ? 'ORD-20260311-2048' : 'ORD-20260311-1194',
            'invoice_id' => $portal === 'b2b' ? 'INV-20260311-8814' : 'INV-20260311-4318',
            'amount' => $portal === 'b2b' ? 'INR 60,180.00' : 'INR 15,623.20',
            'method' => $portal === 'b2b' ? 'Bank transfer' : 'UPI',
            'message' => 'Confirmation and invoice details were sent to the registered email immediately after payment capture.',
            'next_steps' => [
                'Order confirmation and invoice dispatch completed',
                'Warehouse processing started',
                'Tracking will unlock after dispatch',
            ],
            'primary_href' => route('order.confirmation'),
            'primary_label' => 'View Confirmation',
            'secondary_href' => route('order.tracking'),
            'secondary_label' => 'Track Order',
            'meta_label' => 'Commercial follow-up',
            'meta_value' => 'Fulfillment started',
        ],
        'failed' => [
            'badge' => 'Payment Failed',
            'title' => 'Payment was not completed and the order is still waiting.',
            'copy' => 'This state should reassure the customer, preserve their checkout context, and make the recovery path obvious.',
            'icon_wrap' => 'bg-rose-100 text-rose-600',
            'badge_class' => 'border-rose-200 bg-rose-50 text-rose-700',
            'panel_class' => 'border-rose-200 bg-rose-50/80',
            'note_class' => 'border-rose-200 bg-rose-50/70',
            'reference' => 'PAY-20260311-1058',
            'order_id' => $portal === 'b2b' ? 'ORD-20260311-2048' : 'ORD-20260311-1194',
            'invoice_id' => 'Awaiting retry',
            'amount' => $portal === 'b2b' ? 'INR 60,180.00' : 'INR 15,623.20',
            'method' => $portal === 'b2b' ? 'Net banking' : 'Card',
            'message' => 'Your cart and checkout details are still available. Retry with the same or a different payment method.',
            'next_steps' => [
                'Review bank or gateway response',
                'Retry payment safely from checkout',
                'Contact support if the issue repeats',
            ],
            'primary_href' => route('payment.retry'),
            'primary_label' => 'Retry Payment',
            'secondary_href' => route('contact'),
            'secondary_label' => 'Contact Support',
            'meta_label' => 'Commercial follow-up',
            'meta_value' => 'Payment retry required',
        ],
        'retry' => [
            'badge' => 'Retry Payment',
            'title' => 'Retry the payment with another method and keep the order moving.',
            'copy' => 'This state keeps the customer close to completion by focusing on safe retry, continuity, and support confidence.',
            'icon_wrap' => 'bg-amber-100 text-secondary-700',
            'badge_class' => 'border-amber-200 bg-secondary-50 text-secondary-700',
            'panel_class' => 'border-amber-200 bg-secondary-50/80',
            'note_class' => 'border-amber-200 bg-secondary-50/70',
            'reference' => 'PAY-20260311-1058',
            'order_id' => $portal === 'b2b' ? 'ORD-20260311-2048' : 'ORD-20260311-1194',
            'invoice_id' => 'Will generate after success',
            'amount' => $portal === 'b2b' ? 'INR 60,180.00' : 'INR 15,623.20',
            'method' => $portal === 'b2b' ? 'Switch payment rail' : 'UPI or card retry',
            'message' => 'Your order is still open. Pick an alternate method and continue without rebuilding the cart.',
            'next_steps' => [
                'Change payment method if needed',
                'Return to checkout with saved order context',
                'Use support if you need assisted checkout',
            ],
            'primary_href' => route('checkout.page'),
            'primary_label' => 'Return to Checkout',
            'secondary_href' => route('payment.failed'),
            'secondary_label' => 'View Failed State',
            'meta_label' => 'Commercial follow-up',
            'meta_value' => 'Awaiting alternate payment',
        ],
    ];

    $state = $states[$paymentStatus];
    $email = auth()->user()?->email ?? ($portal === 'b2b' ? 'procurement@metrocarelab.com' : 'customer@biogenix.com');
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.08fr)_minmax(0,0.92fr)]">
    <section class="rounded-[32px] border border-slate-200 bg-[linear-gradient(145deg,#ffffff_0%,#f8fbff_54%,#f8fafc_100%)] p-6 shadow-sm md:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
            <span class="inline-flex h-16 w-16 items-center justify-center rounded-3xl {{ $state['icon_wrap'] }} shadow-sm">
                @if ($paymentStatus === 'success')
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                @elseif ($paymentStatus === 'failed')
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12"></path>
                    </svg>
                @else
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-3.2-6.9"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 3v6h-6"></path>
                    </svg>
                @endif
            </span>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $state['badge_class'] }}">{{ $state['badge'] }}</span>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">{{ strtoupper($portal) }}</span>
                </div>

                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">{{ $state['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">{{ $state['copy'] }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Payment Ref</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $state['reference'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Order ID</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $state['order_id'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Amount</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $state['amount'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Method</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $state['method'] }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-3xl border p-5 {{ $state['panel_class'] }}">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Confirmation Message</p>
            <p class="mt-3 text-base font-semibold leading-7 text-slate-900">{{ $state['message'] }}</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">Registered email: <span class="font-semibold text-slate-900">{{ $email }}</span></p>
        </div>
    </section>

    <div class="space-y-6">
        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
            <h3 class="text-lg font-semibold text-slate-950">Suggested Actions</h3>
            <p class="mt-1 text-sm text-slate-500">Keep the next step obvious and low-friction.</p>

            <div class="mt-5 flex flex-col gap-3">
                <a href="{{ $state['primary_href'] }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">{{ $state['primary_label'] }}</a>
                <a href="{{ $state['secondary_href'] }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">{{ $state['secondary_label'] }}</a>
            </div>

            <div class="mt-5 rounded-2xl border p-4 {{ $state['note_class'] }}">
                <p class="text-sm font-semibold text-slate-900">{{ $state['meta_label'] }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $state['meta_value'] }}</p>
            </div>
        </section>

        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
            <h3 class="text-lg font-semibold text-slate-950">Next Steps</h3>
            <div class="mt-5 space-y-3">
                @foreach ($state['next_steps'] as $index => $step)
                    <article class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-sm font-semibold text-primary-700 shadow-sm">{{ $index + 1 }}</span>
                        <p class="text-sm font-medium leading-6 text-slate-700">{{ $step }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                <div class="flex items-center justify-between gap-4">
                    <span>Invoice Reference</span>
                    <span class="font-semibold text-slate-900">{{ $state['invoice_id'] }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between gap-4">
                    <span>Support Escalation</span>
                    <span class="font-semibold text-slate-900">{{ $paymentStatus === 'success' ? 'Not required' : 'Available now' }}</span>
                </div>
            </div>
        </section>
    </div>
</div>
