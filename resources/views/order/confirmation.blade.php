@extends('layouts.app')

@section('title', 'Order Successful')

@section('content')
<div class="min-h-screen bg-slate-50">
    {{-- Centered order confirmation --}}
    <div class="flex min-h-[70vh] flex-col items-center justify-center px-4 py-16">
        <div class="mx-auto w-full max-w-md text-center">
            {{-- Success checkmark --}}
            <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Order Successful!</h1>
            <p class="mt-3 text-base text-slate-500">Thank you for your order #BGX-123456</p>

            {{-- Action buttons --}}
            <div class="mt-10 space-y-3">
                <a href="{{ route('orders.index') }}" class="flex h-12 w-full items-center justify-center rounded-xl bg-primary-600 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">View Order Details</a>
                <a href="{{ route('products.index') }}" class="flex h-12 w-full items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 no-underline transition hover:bg-slate-100">Continue Shopping</a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white py-6">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <nav class="mb-4 flex flex-wrap items-center justify-center gap-6">
                <a href="{{ route('privacy') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Terms of Service</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-primary-600">Contact</a>
            </nav>
            <div class="mb-3 flex justify-center">
                <div class="relative h-8 w-8 rounded-lg bg-primary-600">
                    <span class="absolute left-1.5 top-1.5 h-1.5 w-1.5 rounded-sm bg-white"></span>
                    <span class="absolute bottom-1.5 right-1.5 h-1.5 w-1.5 rounded-sm bg-white"></span>
                </div>
            </div>
            <p class="text-sm text-slate-400">&copy; 2024 Biogenix. Innovating for a healthier future.</p>
        </div>
    </footer>
</div>
@endsection
