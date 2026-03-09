@extends('layouts.app')

@section('title', 'Coming Soon')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <p class="hero-kicker !text-slate-800 !border-slate-300 !bg-slate-100">Upcoming</p>
            <h1 class="ui-page-title mt-4">Coming Soon</h1>
            <p class="ui-small mt-2">We're launching this page shortly. Please check back soon.</p>
            <div class="mt-5">
                <x-ui.action-link :href="route('home')">Back to Home</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
