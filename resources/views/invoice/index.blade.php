@extends('layouts.app')

@section('title', 'Visible Invoices')

@php
    $panelClass = 'rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $tableWrapClass = 'overflow-hidden rounded-2xl border border-slate-200';
    $tableHeadClass = 'bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500';
    $tableCellClass = 'px-4 py-4 align-top text-sm text-slate-700';
    $secondaryButtonClass = 'inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50';
@endphp

@section('content')
    <div class="mx-auto w-full max-w-none space-y-6 px-4 py-6 sm:px-6 lg:px-8 xl:px-10">
        <section class="rounded-[32px] border border-slate-200 bg-[linear-gradient(135deg,#ffffff_0%,#f8fafc_52%,#dcfce7_100%)] p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Billing Workspace</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Visible Invoices</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">
                        Review invoice visibility within your current scope and open the generated document directly from the listing below.
                    </p>
                </div>
                <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-3 shadow-sm backdrop-blur">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Visible Records</p>
                    <p class="mt-2 text-2xl font-bold text-slate-950">{{ $proformas->total() }}</p>
                </div>
            </div>
        </section>

        <section class="{{ $panelClass }}">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Invoice Library</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Each record surfaces ownership, target details, status, totals, and a direct export action.</p>
                </div>
                <span class="inline-flex items-center rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Role-filtered</span>
            </div>

            <div class="mt-6 {{ $tableWrapClass }}">
                <table class="min-w-full divide-y divide-slate-200 bg-white">
                    <thead class="{{ $tableHeadClass }}">
                        <tr>
                            <th class="px-4 py-3">PI Number</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3">Target</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Created At</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($proformas as $pi)
                            @php
                                $isPendingInternalReview = in_array(strtolower($pi->status), ['pending_review', 'requested', 'submitted'], true);
                                $statusClass = match (strtolower($pi->status)) {
                                    'approved', 'active' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                    'pending', 'draft', 'pending_review', 'requested', 'submitted' => 'border-amber-200 bg-amber-50 text-amber-700',
                                    'rejected', 'expired' => 'border-rose-200 bg-rose-50 text-rose-700',
                                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                                };
                            @endphp
                            <tr>
                                <td class="{{ $tableCellClass }} font-semibold text-slate-950">{{ $pi->pi_number }}</td>
                                <td class="{{ $tableCellClass }}">
                                    <p class="font-semibold text-slate-950">{{ $pi->owner_name ?? 'Guest' }}</p>
                                    @if ($pi->owner_company_name)
                                        <p class="mt-1 text-xs text-slate-500">{{ $pi->owner_company_name }}</p>
                                    @endif
                                </td>
                                <td class="{{ $tableCellClass }}">
                                    <p class="font-semibold text-slate-950">{{ $pi->target_name ?? '-' }}</p>
                                    @if ($pi->target_company_name)
                                        <p class="mt-1 text-xs text-slate-500">{{ $pi->target_company_name }}</p>
                                    @endif
                                </td>
                                <td class="{{ $tableCellClass }}">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ strtoupper($pi->status) }}</span>
                                </td>
                                <td class="{{ $tableCellClass }} font-semibold text-slate-950">INR {{ number_format($pi->total_amount, 2) }}</td>
                                <td class="{{ $tableCellClass }}">{{ $pi->created_at }}</td>
                                <td class="{{ $tableCellClass }}">
                                    @if ($isPendingInternalReview)
                                        <span class="inline-flex h-10 items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 text-sm font-semibold text-amber-700">Awaiting Internal Review</span>
                                    @else
                                        <a href="{{ route('proforma.download', $pi->id) }}" class="{{ $secondaryButtonClass }}">Download PDF</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <x-ui.table-empty-row
                                colspan="7"
                                title="No invoice records visible"
                                description="There are no invoice records available for your current scope."
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-ui.pagination :paginator="$proformas" class="pt-6" />
        </section>
    </div>
@endsection
