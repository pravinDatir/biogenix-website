@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Profile Prototype')
@section('customer_title', 'My Profile Prototype')
@section('customer_description', 'A profile page for account details, addresses, notifications, and security blocks.')
@section('customer_active', 'profile')

@section('customer_actions')
    <x-ui.action-link href="#">Save Preferences</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Change Password</x-ui.action-link>
@endsection

@section('customer_content')
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <x-ui.surface-card title="Account Snapshot" subtitle="Static placeholders for customer profile details.">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Profile Type</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ strtoupper($portal) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Visibility Scope</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Company and assigned clients' : 'Personal only' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Address Book</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Billing + shipping branches' : 'Home + clinic addresses' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Notifications</p>
                    <p class="mt-2 font-semibold text-slate-900">Email, SMS, WhatsApp</p>
                </div>
            </div>
        </x-ui.surface-card>

        <x-ui.surface-card title="Role-Specific Details" subtitle="Extra profile panels that can later bind to live data.">
            <div class="space-y-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Company details and GST block' : 'Personal identity and default address block' }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $portal === 'b2b' ? 'Includes company name, billing setup, and shipping preferences.' : 'Includes personal details, saved locations, and communication preferences.' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Sub-user / permission panel' : 'Password and security panel' }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $portal === 'b2b' ? 'Reserved for future delegated users and approval preferences.' : 'Reserved for password change, OTP, and account security messaging.' }}</p>
                </div>
            </div>
        </x-ui.surface-card>
    </div>
@endsection

