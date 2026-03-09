@extends('layouts.app')

@section('title', 'Not Authorized')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <h1 class="ui-page-title">Not Authorized</h1>
            <p class="ui-small mt-2">You don't have permission to access this section.</p>
            <div class="mt-5 flex justify-center gap-3">
                <x-ui.action-link :href="route('home')">Go to Home</x-ui.action-link>
                <x-ui.action-link :href="route('contact')" variant="secondary">Contact Support</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
