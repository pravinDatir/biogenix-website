@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $actionPrimaryClass = 'inline-flex h-9 items-center justify-center rounded-lg bg-primary-600 px-3.5 text-xs font-semibold text-white shadow-sm transition hover:bg-primary-700';
    $actionSecondaryClass = 'inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50';

    $quotes = $portal === 'b2b'
        ? [
            ['id' => 'PI-2040', 'recipient' => 'Metro Care Lab', 'status' => 'Submitted', 'amount' => 'INR 92,450'],
            ['id' => 'PI-2038', 'recipient' => 'Apollo Diagnostics', 'status' => 'Approved', 'amount' => 'INR 38,120'],
            ['id' => 'PI-2027', 'recipient' => 'Own Company', 'status' => 'Converted', 'amount' => 'INR 14,980'],
        ]
        : [
            ['id' => 'PI-1181', 'recipient' => 'Self', 'status' => 'Draft', 'amount' => 'INR 6,420'],
            ['id' => 'PI-1175', 'recipient' => 'Self', 'status' => 'Downloaded', 'amount' => 'INR 8,960'],
            ['id' => 'PI-1169', 'recipient' => 'Self', 'status' => 'Expired', 'amount' => 'INR 2,130'],
        ];
@endphp

@section('title', 'My Quotations Prototype')
@section('customer_title', 'My Quotations / PI Prototype')
@section('customer_description', 'A quotations page for PI list, PDF download, and conversion-ready states.')
@section('customer_active', 'quotations')

@section('customer_actions')
    <x-ui.action-link :href="route('proforma.create')">Generate New PI</x-ui.action-link>
    <x-ui.action-link :href="route('proforma.index')" variant="secondary">View PI Library</x-ui.action-link>
@endsection

@section('customer_content')
    <x-ui.surface-card title="Quotation Summary" subtitle="Role-aware PI language without backend workflow changes.">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400">PI Scope</p>
                <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Self + assigned clients' : 'Self only' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400">Download Mode</p>
                <p class="mt-2 font-semibold text-slate-900">Branded PDF</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400">Conversion Path</p>
                <p class="mt-2 font-semibold text-slate-900">{{ $portal === 'b2b' ? 'PI to approval or order' : 'PI to personal order' }}</p>
            </div>
        </div>
    </x-ui.surface-card>

    <x-ui.surface-card title="PI List" subtitle="This table can later be wired to live proforma data.">
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 bg-white text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <th class="px-4 py-3">PI ID</th>
                        <th class="px-4 py-3">Recipient</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($quotes as $quote)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-primary-700">{{ $quote['id'] }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $quote['recipient'] }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $quote['status'] }}</span>
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-900">{{ $quote['amount'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('proforma.index') }}" class="{{ $actionPrimaryClass }}">View PDFs</a>
                                    <a href="{{ route('proforma.create') }}" class="{{ $actionSecondaryClass }}">Open Creator</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.surface-card>
@endsection
