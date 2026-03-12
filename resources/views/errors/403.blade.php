@extends('layouts.app')

@section('title', 'Not Authorized')

@section('content')
<div class="mx-auto w-full max-w-none px-4 py-8 sm:px-6 lg:px-8 xl:px-10">
    <section class="mx-auto w-full max-w-2xl text-center">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Error 403</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">Not Authorized</h1>
            <p class="mt-2 text-sm leading-7 text-slate-500">You do not have permission to access this resource.</p>
            <div class="mt-5 flex justify-center gap-3">
                <x-ui.action-link :href="route('home')">Home</x-ui.action-link>
                <x-ui.action-link :href="route('contact')" variant="secondary">Support</x-ui.action-link>
            </div>
        </div>
    </section>
</div>
@endsection
