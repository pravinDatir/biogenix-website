@extends('layouts.app')

@section('title', 'Maintenance')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <h1 class="ui-page-title">Under Maintenance</h1>
            <p class="ui-small mt-2">We're performing scheduled updates. Service will resume shortly.</p>
            <div class="mt-5">
                <x-ui.action-link :href="route('contact')" variant="secondary">Contact Support</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
