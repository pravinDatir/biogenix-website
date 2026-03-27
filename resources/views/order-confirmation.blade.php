@extends('layouts.app')

@section('title', 'Order Confirmation')
@section('customer_minimal', 'minimal')

@section('content')
    <div class="flex min-h-[calc(100vh-120px)] items-center justify-center p-4 pt-[10vh]">
        <div class="w-full max-w-md translate-y-[5%] text-center">
            {{-- Success Icon --}}
            <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-primary-50">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-600 text-white shadow-lg shadow-primary-600/20">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            {{-- Content --}}
            <h1 class="font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Order Successful!</h1>
            <p class="mt-4 text-base leading-7 text-slate-500">
                Thank you for your order <span class="font-semibold text-slate-900">#{{ session('last_order_number', 'BGX-123456') }}</span>.
                We've sent a confirmation email with all the details and tracking information.
            </p>

            {{-- Actions --}}
            <div class="mt-10 flex flex-col gap-4">
                <a href="{{ route('orders.index') }}" class="inline-flex h-14 items-center justify-center rounded-2xl bg-primary-600 text-base font-semibold text-white shadow-[0_16px_35px_-18px_rgba(26,77,46,0.35)] transition hover:bg-primary-700">
                    View Order Details
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex h-14 items-center justify-center rounded-2xl bg-white border border-slate-200 text-base font-semibold text-slate-600 transition hover:bg-slate-50 hover:border-slate-300">
                    Continue Shopping
                </a>
            </div>

            {{-- Optional Footer Info --}}
            <div class="mt-12 pt-8 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    Need help? <a href="{{ route('contact') }}" class="font-semibold text-primary-600 hover:text-primary-700 transition">Contact Support</a>
                </p>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                    &copy; 2026 Biogenix Corp.
                </p>
            </div>
        </div>
    </div>
@endsection
