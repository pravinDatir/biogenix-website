@extends('customer.layouts.main')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Reviews Prototype')
@section('customer_title', 'Reviews & Incentives Prototype')
@section('customer_description', 'A post-purchase engagement page for review collection and incentive visibility.')
@section('customer_active', 'reviews')

@section('customer_actions')
    <x-ui.action-link :href="route('contact')">Share Feedback</x-ui.action-link>
    <x-ui.action-link :href="route('faq')" variant="secondary">Review Help</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <x-ui.surface-card title="Review Flow" subtitle="Designed for Google review CTA, proof upload, and confirmation state.">
            <div class="space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Prompt</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Review the supply and account experience' : 'Review your ordering and delivery experience' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Proof Status</p>
                    <p class="mt-2 font-semibold text-slate-900">Awaiting screenshot or public link</p>
                </div>
            </div>
        </x-ui.surface-card>

        <x-ui.surface-card title="Incentive Tracker" subtitle="Rewards differ for B2C and B2B customers.">
            <div class="grid gap-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Applied Benefit</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Commercial adjustment request' : 'Next checkout discount' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Verification Rule</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Needs account-team approval' : 'Auto-applies after review validation' }}</p>
                </div>
            </div>
        </x-ui.surface-card>
    </div>
@endsection
