@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $orderId = $portal === 'b2b' ? 'ORD-20260311-2048' : 'ORD-20260311-1194';
    $invoiceId = $portal === 'b2b' ? 'INV-20260311-8814' : 'INV-20260311-4318';
    $recipientEmail = auth()->user()?->email ?? ($portal === 'b2b' ? 'procurement@metrocarelab.com' : 'customer@biogenix.com');
    $recipientName = auth()->user()?->name ?? ($portal === 'b2b' ? 'Metro Care Lab' : 'Prakhar Kapoor');
    $orderAmount = $portal === 'b2b' ? 'INR 1,84,000.00' : 'INR 8,420.00';
    $deliveryWindow = $portal === 'b2b' ? 'Estimated dispatch within 24 hours' : 'Estimated delivery by March 16, 2026';

    $timeline = $portal === 'b2b'
        ? [
            ['title' => 'Order accepted', 'copy' => 'Your procurement request is now locked and visible to the account operations team.'],
            ['title' => 'Invoice and confirmation sent', 'copy' => 'Order confirmation and invoice details were shared to the registered business email.'],
            ['title' => 'Dispatch planning started', 'copy' => 'The warehouse and account team are aligning packaging, compliance, and shipping windows.'],
        ]
        : [
            ['title' => 'Order accepted', 'copy' => 'Your payment was captured successfully and your order is now confirmed.'],
            ['title' => 'Invoice and confirmation sent', 'copy' => 'A confirmation email and invoice summary were sent to your registered email address.'],
            ['title' => 'Packing and courier handoff', 'copy' => 'The order has moved into packing and the courier update will appear in tracking next.'],
        ];
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
    <section class="rounded-[32px] border border-slate-200 bg-[linear-gradient(145deg,#ffffff_0%,#f8fbff_55%,#ecfdf5_100%)] p-6 shadow-sm md:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
            <span class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-600 shadow-sm">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Confirmed</span>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">{{ strtoupper($portal) }}</span>
                </div>

                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Your order is confirmed and already in the fulfillment pipeline.</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                    We have shared your confirmation details, invoice reference, and next-step updates to your registered email so you can track the order without losing context.
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Order ID</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $orderId }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Invoice ID</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $invoiceId }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email Sent To</p>
                <p class="mt-2 break-all text-base font-semibold text-slate-950">{{ $recipientEmail }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Order Value</p>
                <p class="mt-2 text-base font-semibold text-slate-950">{{ $orderAmount }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50/70 p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Confirmation Message</p>
            <p class="mt-3 text-base font-semibold leading-7 text-slate-900">
                Hello {{ $recipientName }}, your order {{ $orderId }} has been confirmed successfully. We have also emailed invoice {{ $invoiceId }} to {{ $recipientEmail }}.
            </p>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $deliveryWindow }}</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('order.tracking') }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">Track Order</a>
            <a href="{{ route('products.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">Continue Shopping</a>
            <a href="{{ route('contact') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">Contact Support</a>
        </div>
    </section>

    <div class="space-y-6">
        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-50 text-primary-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">What happens next</h3>
                    <p class="mt-1 text-sm text-slate-500">The order timeline stays simple, clear, and easy to scan.</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($timeline as $item)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $item['title'] }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
            <h3 class="text-lg font-semibold text-slate-950">Order Snapshot</h3>
            <div class="mt-5 space-y-3 text-sm text-slate-600">
                <div class="flex items-center justify-between gap-4">
                    <span>Recipient</span>
                    <span class="font-semibold text-slate-900">{{ $recipientName }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <span>Invoice</span>
                    <span class="font-semibold text-slate-900">{{ $invoiceId }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <span>Communication</span>
                    <span class="font-semibold text-slate-900">Email confirmation sent</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <span>Support Window</span>
                    <span class="font-semibold text-slate-900">Business hours follow-up</span>
                </div>
            </div>
        </section>
    </div>
</div>
