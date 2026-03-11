@extends('customer.checkout-layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

    $steps = ['Address', 'Delivery', 'Payment', 'Review'];
@endphp

@section('title', 'Checkout')

@section('checkout_content')
    <div class="grid gap-10 xl:grid-cols-[minmax(0,1fr)_460px]">
        <div class="space-y-10">
            <div class="space-y-3">
                <h1 class="text-5xl font-semibold tracking-tight text-slate-950">Checkout</h1>
                <p class="text-xl text-slate-500">{{ $portal === 'b2b' ? 'Complete your professional supply order' : 'Complete your order and choose a secure payment method' }}</p>
            </div>

            <section class="space-y-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white shadow-lg shadow-blue-600/20">1</span>
                    <h2 class="text-4xl font-semibold tracking-tight text-slate-950">Shipping Address</h2>
                </div>

                <div class="grid gap-5 lg:grid-cols-3">
                    @foreach ([
                        ['Corporate Lab - HQ', '123 Science Park, Phase II', 'Lucknow, UP 226010', true],
                        ['Research Center Alpha', '45 Biotech Zone, South Gate', 'Lucknow, UP 226002', false],
                    ] as $address)
                        <article class="{{ $address[3] ? 'border-blue-500 bg-blue-50/40 shadow-[0_0_0_3px_rgba(37,99,235,0.08)]' : 'border-slate-200 bg-white' }} rounded-[28px] border p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm">
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l8-4 6 4v14"></path><path d="M9 9h1"></path><path d="M9 13h1"></path><path d="M9 17h1"></path><path d="M14 9h1"></path><path d="M14 13h1"></path><path d="M14 17h1"></path></svg>
                                </div>
                                @if ($address[3])
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 12 4 4L19 6"></path></svg>
                                    </span>
                                @endif
                            </div>
                            <div class="mt-6 space-y-2">
                                <h3 class="text-3xl font-semibold tracking-tight text-slate-950">{{ $address[0] }}</h3>
                                <p class="text-xl leading-8 text-slate-500">{{ $address[1] }}<br>{{ $address[2] }}</p>
                            </div>
                            <button type="button" class="mt-6 text-base font-semibold text-blue-700">Edit</button>
                        </article>
                    @endforeach

                    <article class="flex min-h-[256px] items-center justify-center rounded-[28px] border-2 border-dashed border-slate-200 bg-transparent p-6 text-center">
                        <div class="space-y-4">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path><path d="M20 7V4a2 2 0 0 0-2-2h-3"></path></svg>
                            </div>
                            <p class="text-3xl font-semibold tracking-tight text-slate-500">Add New Address</p>
                        </div>
                    </article>
                </div>
            </section>

            <section class="space-y-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white shadow-lg shadow-blue-600/20">2</span>
                    <h2 class="text-4xl font-semibold tracking-tight text-slate-950">Delivery Method</h2>
                </div>

                <article class="rounded-[28px] border border-blue-100 bg-blue-50 px-6 py-6 shadow-sm">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-4">
                            <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white text-blue-600 shadow-sm">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z"></path></svg>
                            </span>
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-[32px] font-semibold tracking-tight text-blue-700">Lucknow Same-Day Delivery</p>
                                    <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">Fastest</span>
                                </div>
                                <p class="text-xl text-slate-600">Order within the next <span class="font-semibold text-slate-900">2h 14m</span> (Before 3 PM) to receive it by 8 PM today.</p>
                            </div>
                        </div>
                        <div class="space-y-1 text-right">
                            <p class="text-4xl font-semibold tracking-tight text-slate-950">FREE</p>
                            <p class="text-lg text-slate-500">Priority Tier Included</p>
                        </div>
                    </div>
                </article>
            </section>

            <section class="space-y-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white shadow-lg shadow-blue-600/20">3</span>
                    <h2 class="text-4xl font-semibold tracking-tight text-slate-950">Payment Method</h2>
                </div>

                <div class="space-y-5">
                    <label class="flex cursor-pointer items-center justify-between rounded-[28px] border-2 border-blue-500 bg-white px-6 py-6 shadow-sm">
                        <div class="flex items-center gap-5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-blue-600 bg-white">
                                <span class="h-4 w-4 rounded-full bg-blue-600"></span>
                            </span>
                            <div class="space-y-1">
                                <p class="text-[30px] font-semibold tracking-tight text-slate-950">Credit / Debit Card</p>
                                <p class="text-lg text-slate-500">Secure via Stripe</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="h-9 w-14 rounded-xl bg-slate-100"></span>
                            <span class="h-9 w-14 rounded-xl bg-slate-100"></span>
                        </div>
                    </label>

                    <label class="flex cursor-pointer items-center gap-5 rounded-[28px] border border-slate-200 bg-white px-6 py-6 shadow-sm">
                        <span class="h-9 w-9 rounded-full border-2 border-slate-300 bg-white"></span>
                        <div class="space-y-1">
                            <p class="text-[30px] font-semibold tracking-tight text-slate-950">Net Banking</p>
                        </div>
                    </label>

                    @if ($portal === 'b2b')
                        <div class="rounded-[28px] border border-slate-200 bg-white px-6 py-6 shadow-sm">
                            <div class="flex items-start gap-5">
                                <span class="mt-2 h-9 w-9 rounded-full border-2 border-slate-300 bg-white"></span>
                                <div class="min-w-0 flex-1 space-y-5">
                                    <div class="space-y-1">
                                        <p class="text-[30px] font-semibold tracking-tight text-slate-950">B2B Purchase Order (PO)</p>
                                        <p class="text-lg text-slate-500">Available for verified institutions</p>
                                    </div>
                                    <div class="rounded-[28px] border-2 border-dashed border-slate-200 bg-[#fafbfc] px-6 py-10 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                                        </div>
                                        <p class="mt-5 text-2xl font-semibold text-slate-900">Upload Signed PO Document</p>
                                        <p class="mt-2 text-base text-slate-500">PDF, JPG up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="sticky top-8 overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-8 py-7">
                    <h2 class="text-4xl font-semibold tracking-tight text-slate-950">Order Summary</h2>
                </div>

                <div class="space-y-6 px-8 py-7">
                    @foreach ([
                        ['TAQ DNA Polymerase (5U/µL)', 'Qty: 2 • 500 Units', '₹12,450.00', 'vials'],
                        ['High-Speed Microcentrifuge', 'Qty: 1 • Model LX-200', '₹48,900.00', 'centrifuge'],
                    ] as $item)
                        <div class="flex gap-4">
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-slate-200">
                                @include('customer.partials.product-visual', ['variant' => $item[3], 'class' => 'h-full'])
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-[22px] font-semibold leading-7 text-slate-950">{{ $item[0] }}</h3>
                                <p class="mt-1 text-lg text-slate-500">{{ $item[1] }}</p>
                                <p class="mt-2 text-[32px] font-semibold tracking-tight text-blue-600">{{ $item[2] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-200 px-8 py-7">
                    <div class="space-y-4 text-[30px] text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-950">₹61,350.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Shipping (Same-Day)</span>
                            <span class="font-semibold text-emerald-500">FREE</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>GST (18%)</span>
                            <span class="font-semibold text-slate-950">₹11,043.00</span>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-6">
                        <span class="text-[36px] font-semibold tracking-tight text-slate-950">Total</span>
                        <span class="text-[42px] font-semibold tracking-tight text-blue-600">₹72,393.00</span>
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <button type="button" class="inline-flex h-16 w-full items-center justify-center gap-3 rounded-[22px] bg-blue-600 text-[28px] font-semibold text-white shadow-[0_24px_40px_rgba(37,99,235,0.18)] hover:bg-blue-700">
                        Place Order
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="10" width="12" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                    </button>
                    <p class="mt-5 text-center text-lg text-slate-500">Bank-grade encrypted secure transaction</p>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white px-6 py-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <span class="mt-1 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10V8a6 6 0 0 0-12 0v2"></path><rect x="4" y="10" width="16" height="10" rx="2"></rect><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path></svg>
                    </span>
                    <div>
                        <p class="text-2xl font-semibold tracking-tight text-slate-950">Need help with your order?</p>
                        <p class="mt-2 text-lg text-slate-500">Contact our bioscience experts at 1800-BIO-GENIX</p>
                    </div>
                </div>
            </section>
        </aside>
    </div>
@endsection
