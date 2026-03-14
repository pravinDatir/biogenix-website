@extends('layouts.app')

@section('title', 'Coming Soon')

@section('content')
    <div class="mx-auto flex min-h-[46vh] w-full max-w-[720px] items-center justify-center px-4 py-5 sm:px-6 lg:px-8">
        <section class="w-full max-w-md text-center">
            <span class="inline-flex rounded-full bg-primary-50 px-3 py-1 text-[11px] font-semibold text-primary-700">Launching Soon</span>
            <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Something new is brewing.</h1>
            <p class="mx-auto mt-2.5 max-w-sm text-sm leading-6 text-slate-600">Our latest experience is just around the corner. We&apos;re shaping something new and will share it here once it&apos;s ready.</p>

            <div class="mx-auto mt-5 flex w-full max-w-sm flex-col items-stretch gap-2 sm:flex-row">
                <input type="email" placeholder="Enter your professional email" class="h-9 flex-1 rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10">
                <button type="button" class="inline-flex h-9 items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Notify Me</button>
            </div>

            <p class="mt-3 text-[11px] font-medium text-slate-400">Join the waitlist for updates.</p>
        </section>
    </div>
@endsection
