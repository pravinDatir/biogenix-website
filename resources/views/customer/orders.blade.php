@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Orders - Coming Soon')
@section('customer_active', 'orders')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="orders"
        title="Orders"
        description="Track and manage your order history."
    >
        {{-- Coming Soon State --}}
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#eef1f6]">
                <svg class="h-8 w-8 text-[#091b3f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <span class="inline-flex rounded-md bg-amber-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-amber-600">Launching Soon</span>
            <h3 class="mt-4 text-xl font-extrabold text-slate-900 tracking-tight">Order Management is Coming</h3>
            <p class="mx-auto mt-2 max-w-sm text-[13px] leading-6 text-slate-500">We're building a complete order tracking and management experience. You'll be able to track, reorder, and manage all your orders right here.</p>

            <div class="mx-auto mt-6 flex w-full max-w-sm flex-col items-stretch gap-2 sm:flex-row">
                <input type="email" placeholder="Enter your email for updates" class="h-10 flex-1 rounded-xl border border-slate-200 bg-white px-4 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]">
                <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#091b3f] px-5 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-800 cursor-pointer">Notify Me</button>
            </div>
            <p class="mt-3 text-[11px] font-medium text-slate-400">Join the waitlist for updates.</p>
        </div>
    </x-account.workspace>
@endsection
