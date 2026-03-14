@extends('layouts.app')

@section('title', 'Access Restricted')

@section('content')
<div class="flex min-h-[80vh] flex-col items-center justify-center bg-gradient-to-b from-slate-50 to-white px-4 py-16 text-center">
    <div class="mx-auto max-w-lg">
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-primary-50">
            <svg class="h-10 w-10 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
        </div>

        <h1 class="text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Access Restricted</h1>
        <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-slate-600">It looks like you don't have the necessary permissions to view this research data. Access to these clinical records requires Grade 4 clearance.</p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('contact') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                Request Access
            </a>
            <a href="{{ route('contact') }}" class="inline-flex h-11 items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">Contact Support</a>
        </div>
    </div>
</div>
@endsection
