@extends('layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-none space-y-4 px-4 py-6 sm:px-6 lg:px-8 xl:px-10 md:space-y-6">
    <div class="card">
        <h1>Visible Proforma Invoices</h1>
        <p class="muted">Results are filtered by your role and data visibility scope.</p>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>PI Number</th>
                        <th>Owner</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proformas as $pi)
                        <tr>
                            <td>{{ $pi->pi_number }}</td>
                            <td>
                                {{ $pi->owner_name ?? 'Guest' }}
                                @if ($pi->owner_company_name)
                                    <div class="muted">{{ $pi->owner_company_name }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $pi->target_name ?? '-' }}
                                @if ($pi->target_company_name)
                                    <div class="muted">{{ $pi->target_company_name }}</div>
                                @endif
                            </td>
                            <td>{{ strtoupper($pi->status) }}</td>
                            <td>{{ number_format($pi->total_amount, 2) }}</td>
                            <td>{{ $pi->created_at }}</td>
                            <td>
                                <a href="{{ route('proforma.download', $pi->id) }}">Download PDF</a>
                            </td>
                        </tr>
                    @empty
                        <x-ui.table-empty-row
                            colspan="7"
                            title="No PI records visible"
                            description="There are no proforma invoices available for your current scope."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-ui.pagination :paginator="$proformas" />
    </div>
    </div>
@endsection
