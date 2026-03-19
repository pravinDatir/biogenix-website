@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Profile Prototype')
@section('customer_active', 'profile')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="profile"
        :title="$portal === 'b2b' ? 'Business Profile' : 'Personal Profile'"
        :description="$portal === 'b2b'
            ? 'Update your company details and contact information for the Biogenix platform.'
            : 'Update your personal details and contact preferences.'"
    >
        @include('customer.'.$portal.'.profile-form')

        <x-slot:footer>
            <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-[13px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none cursor-pointer">Cancel</button>
            <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#091b3f] px-5 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none cursor-pointer">Save Changes</button>
        </x-slot:footer>
    </x-account.workspace>
@endsection
