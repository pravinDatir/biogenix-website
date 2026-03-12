@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $backUrl = url()->previous() ?: route('customer.addresses.preview', ['user_type' => $portal]);
@endphp

@section('title', 'Edit Address')
@section('customer_active', 'addresses')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="addresses"
        :back-url="$backUrl"
        back-label="Back"
        eyebrow="Account / Addresses / Edit Address"
        title="Edit Address"
        description="Update your delivery and billing information."
    >
        @include('customer.'.$portal.'.addresses-form')

        <x-slot:footer>
            <button type="button" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40">Cancel</button>
            <button type="button" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40">Save Changes</button>
        </x-slot:footer>
    </x-account.workspace>
@endsection
