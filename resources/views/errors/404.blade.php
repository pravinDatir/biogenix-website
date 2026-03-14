@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="mx-auto flex min-h-[46vh] w-full max-w-[720px] items-center justify-center px-4 py-5 sm:px-6 lg:px-8">
        <section class="w-full max-w-md text-center">
            <div class="mx-auto mb-4">
                <span class="text-[3.75rem] font-extrabold tracking-[-0.08em] text-primary-100 md:text-[5rem]">4</span>
                <span class="mx-1 inline-flex h-11 w-11 -translate-y-1 items-center justify-center rounded-full bg-primary-50 text-primary-600 align-middle shadow-sm md:h-12 md:w-12">
                    <svg class="h-5 w-5 md:h-6 md:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m15 9-6 6"></path>
                        <path d="m9 9 6 6"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </span>
                <span class="text-[3.75rem] font-extrabold tracking-[-0.08em] text-primary-100 md:text-[5rem]">4</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Page Not Found</h1>
            <p class="mx-auto mt-2.5 max-w-sm text-sm leading-6 text-slate-600">We couldn&apos;t find the resource you&apos;re looking for. The page may have been moved or the URL may be incorrect.</p>

            <div class="mt-5">
                <a href="{{ route('home') }}" class="inline-flex h-9 items-center gap-2 rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-primary-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </section>
    </div>
@endsection
