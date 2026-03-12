@extends('layouts.app')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $isMinimal = trim($__env->yieldContent('customer_minimal')) === 'minimal';
    $title = trim($__env->yieldContent('customer_title', 'Customer Workspace'));
    $description = trim($__env->yieldContent('customer_description'));
@endphp

@section('content')
    <div class="mx-auto w-full max-w-none px-4 py-4 sm:px-6 md:py-6 lg:px-8 xl:px-10">
        @if (! $isMinimal)
            <x-ui.breadcrumb />
            <section class="animate-entrance hero-premium mb-6 md:mb-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            {{ $portal === 'b2b' ? 'B2B Customer Workspace' : 'B2C Customer Workspace' }}
                        </p>
                        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">
                            {{ $title }}
                        </h1>
                        @if ($description !== '')
                            <p class="mt-3 text-sm leading-7 text-slate-500 md:text-base">
                                {{ $description }}
                            </p>
                        @endif
                    </div>

                    @if (trim($__env->yieldContent('customer_actions')))
                        <div class="flex flex-wrap items-center gap-3">
                            @yield('customer_actions')
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <div class="animate-entrance-delay-1">
            @yield('customer_content')
        </div>
    </div>
@endsection
