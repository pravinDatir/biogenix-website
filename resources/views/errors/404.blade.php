@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="page-shell">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="saas-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Error 404</p>
            <h1 class="ui-page-title mt-2">Page Not Found</h1>
            <p class="ui-small mt-2">The page you are looking for does not exist or has been moved.</p>
            <div class="mt-5">
                <x-ui.action-link :href="route('home')">Back to Home</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
