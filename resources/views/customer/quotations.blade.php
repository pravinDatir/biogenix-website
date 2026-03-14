@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';

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
    <x-ui.action-link href="#">Generate New PI</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Download Recent PDF</x-ui.action-link>
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
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>PI ID</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotes as $quote)
                        <tr>
                            <td>{{ $quote['id'] }}</td>
                            <td>{{ $quote['recipient'] }}</td>
                            <td>{{ $quote['status'] }}</td>
                            <td>{{ $quote['amount'] }}</td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-sm" type="button">Download</button>
                                    <button class="btn secondary btn-sm" type="button">Convert</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.surface-card>
@endsection

