@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'My Profile')
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
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-6">
            @csrf

            @include('customer.'.$portal.'.profile-form')

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('customer.profile.preview') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-[13px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none no-underline">Cancel</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#091b3f] px-5 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none cursor-pointer">Save Changes</button>
            </div>
        </form>
    </x-account.workspace>
@endsection
