@extends('layouts.app')

@if((isset($roleSlugs) && (in_array('Super Admin', $roleSlugs) || in_array('Admin', $roleSlugs) || in_array('System Admin', $roleSlugs))) || in_array(strtolower($user->user_type ?? ''), ['admin', 'super_admin', 'system_admin']))
    <script>window.location.href = "{{ route('adminPanel.dashboard') }}";</script>
@endif

@php
    $summaryCards = [
        ['label' => 'User Type', 'value' => strtoupper($user->user_type), 'tone' => 'primary'],
        ['label' => 'Status', 'value' => strtoupper($user->status), 'tone' => $user->status === 'active' ? 'success' : 'warning'],
        ['label' => 'Visible Products', 'value' => number_format($visibleProductsCount), 'tone' => 'neutral'],
        ['label' => 'Visible PI Records', 'value' => number_format($visiblePiCount), 'tone' => 'neutral'],
    ];

    $toneClasses = [
        'primary' => 'border-primary-100 bg-primary-50 text-primary-700',
        'success' => 'border-emerald-100 bg-primary-50 text-primary-600',
        'warning' => 'border-amber-100 bg-secondary-50 text-secondary-700',
        'neutral' => 'border-slate-200 bg-slate-50 text-slate-700',
    ];
@endphp

@section('content')
    <div class="mx-auto w-full max-w-none space-y-6 px-4 py-6 sm:px-6 lg:px-8 xl:px-10">
        <section class="rounded-[32px] border border-slate-200 bg-[linear-gradient(135deg,#ffffff_0%,#f8fbff_58%,#dbeafe_100%)] p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Workspace Overview</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">User Dashboard</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">
                        Review your active access scope, visibility summary, and effective permission set from one clean dashboard.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-3 shadow-sm backdrop-blur">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Signed In As</p>
                    <p class="mt-2 text-base font-semibold text-slate-950">{{ $user->name }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($summaryCards as $card)
                <article class="rounded-[28px] border bg-white p-5 shadow-sm {{ $toneClasses[$card['tone']] }}">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] opacity-75">{{ $card['label'] }}</p>
                    <p class="mt-3 text-2xl font-bold tracking-tight">{{ $card['value'] }}</p>
                </article>
            @endforeach
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex items-start gap-4">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-50 text-primary-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                            <path d="M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"></path>
                            <path d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Account Snapshot</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Current identity, roles, and department membership shown as read-only UI state.</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Resolved Roles</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ implode(', ', $roleSlugs) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Departments</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ count($departments) ? implode(', ', $departments) : 'No departments assigned' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Name</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $user->email }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex items-start gap-4">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Visibility Summary</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">High-level explanation of what the current account can see in the portal.</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">Visible products: <span class="font-semibold text-slate-950">{{ $visibleProductsCount }}</span></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">Visible proforma invoices: <span class="font-semibold text-slate-950">{{ $visiblePiCount }}</span></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">Default rule: <span class="font-semibold text-slate-950">Admin sees all data, others only their scope.</span></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">Critical rule: <span class="font-semibold text-slate-950">No cross-user or cross-company visibility unless assigned.</span></div>
                </div>
            </section>
        </div>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="flex items-start gap-4">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M4 7h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 17h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Resolved Permissions</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Permissions are shown exactly as currently mapped, with no workflow changes.</p>
                </div>
            </div>

            @if (count($permissions))
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($permissions as $permission)
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-700">{{ $permission }}</span>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    No permissions are currently mapped.
                </div>
            @endif
        </section>
    </div>
@endsection
