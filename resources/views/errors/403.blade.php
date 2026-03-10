@extends('layouts.app')

@section('title', 'Not Authorized')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Error 403</p>
            <h1 class="ui-page-title mt-2">Not Authorized</h1>
            <p class="ui-small mt-2">You do not have permission to access this resource.</p>
            <div class="mt-5 flex justify-center gap-3">
                <x-ui.action-link :href="route('home')">Home</x-ui.action-link>
                <x-ui.action-link :href="route('contact')" variant="secondary">Support</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
