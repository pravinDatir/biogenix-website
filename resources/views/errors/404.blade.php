@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    {{-- Navigation bar matching design --}}
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline">
                <div class="relative h-9 w-9 rounded-xl bg-primary-600">
                    <span class="absolute left-2 top-2 h-2 w-2 rounded-sm bg-white"></span>
                    <span class="absolute bottom-2 right-2 h-2 w-2 rounded-sm bg-white"></span>
                </div>
                <span class="text-lg font-semibold text-slate-900">Biogenix</span>
            </a>
            <nav class="hidden items-center gap-6 md:flex">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-slate-900">Research</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-slate-900">Innovations</a>
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-slate-900">Lab Systems</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-600 no-underline hover:text-slate-900">Support</a>
            </nav>
            <a href="{{ route('login') }}" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 no-underline hover:bg-slate-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 10-16 0M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
            </a>
        </div>
    </div>

    {{-- Under Maintenance Section --}}
    <section class="py-16 text-center md:py-24">
        <div class="mx-auto max-w-lg px-4">
            <div class="mx-auto mb-6 flex items-center justify-center gap-4 rounded-2xl bg-primary-50 px-8 py-6">
                <svg class="h-14 w-14 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                <svg class="h-14 w-14 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Under Maintenance</h1>
            <p class="mx-auto mt-4 max-w-sm text-sm leading-7 text-slate-600">We're currently fine-tuning our lab systems to serve you better. We'll be back online shortly with improved diagnostic capabilities.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Check Status
                </a>
                <a href="{{ route('home') }}" class="inline-flex h-11 items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">Return Home</a>
            </div>
        </div>
    </section>

    {{-- Access Restricted Section --}}
    <section class="border-t border-slate-100 bg-slate-50/50 py-16 text-center md:py-24">
        <div class="mx-auto max-w-lg px-4">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-primary-50">
                <svg class="h-10 w-10 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Access Restricted</h2>
            <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-slate-600">It looks like you don't have the necessary permissions to view this research data. Access to these clinical records requires Grade 4 clearance.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('contact') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                    Request Access
                </a>
                <a href="{{ route('contact') }}" class="inline-flex h-11 items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 no-underline shadow-sm transition hover:bg-slate-50">Contact Support</a>
            </div>
        </div>
    </section>

    {{-- 404 Section --}}
    <section class="border-t border-slate-100 py-16 text-center md:py-24">
        <div class="mx-auto max-w-lg px-4">
            <div class="relative mx-auto mb-6">
                <span class="text-8xl font-bold tracking-tighter text-primary-100 md:text-9xl">4
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-primary-50 align-middle md:h-20 md:w-20">
                        <svg class="h-8 w-8 text-primary-600 md:h-10 md:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                4</span>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Page Not Found</h2>
            <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-slate-600">We couldn't find the resource you're looking for. The experiment may have been relocated or the sample ID is invalid.</p>
            <div class="mt-8">
                <a href="{{ route('home') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary-600 px-6 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </section>

    {{-- Coming Soon / Newsletter Section --}}
    <section class="relative overflow-hidden border-t border-slate-100 bg-gradient-to-br from-slate-50 via-primary-50/30 to-primary-100/20 py-16 text-center md:py-24">
        <div class="mx-auto max-w-lg px-4">
            <span class="inline-flex rounded-full bg-primary-100 px-4 py-1.5 text-xs font-semibold text-primary-700">Launching Oct 2024</span>
            <h2 class="mt-6 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Something new is brewing.</h2>
            <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-slate-600">Our latest biotech innovations are just around the corner. We are synthesizing something transformative for molecular diagnostics.</p>
            <div class="mx-auto mt-8 flex max-w-sm items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <input type="email" placeholder="Enter your professional email" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <button type="button" class="h-11 shrink-0 rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Notify Me</button>
            </div>
            <p class="mt-3 text-xs text-slate-500">Join 300+ researchers awaiting the future.</p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white py-6">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <div class="relative h-7 w-7 rounded-lg bg-primary-600">
                    <span class="absolute left-1.5 top-1.5 h-1.5 w-1.5 rounded-sm bg-white"></span>
                    <span class="absolute bottom-1.5 right-1.5 h-1.5 w-1.5 rounded-sm bg-white"></span>
                </div>
                <span class="text-xs font-semibold text-slate-700">Biogenix Systems</span>
            </div>
            <nav class="flex flex-wrap gap-5">
                <a href="{{ route('privacy') }}" class="text-xs font-medium text-slate-500 no-underline hover:text-primary-600">Privacy Protocol</a>
                <a href="{{ route('terms') }}" class="text-xs font-medium text-slate-500 no-underline hover:text-primary-600">Lab Terms</a>
                <a href="{{ route('contact') }}" class="text-xs font-medium text-slate-500 no-underline hover:text-primary-600">Contact Bio-Security</a>
            </nav>
            <p class="text-xs text-slate-400">&copy; 2024 Biogenix Corp. All rights reserved.</p>
        </div>
    </footer>
</div>
@endsection
