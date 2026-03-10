@extends('layouts.app')

@section('title', 'Maintenance')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Error 503</p>
            <h1 class="ui-page-title mt-2">Under Maintenance</h1>
            <p class="ui-small mt-2">We're currently upgrading the platform. Please check back soon.</p>
            <div class="mt-5">
                <x-ui.action-link :href="route('contact')" variant="secondary">Contact Support</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
