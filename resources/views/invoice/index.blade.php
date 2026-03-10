@extends('layouts.app')

@section('content')
    <div class="page-shell !space-y-4 md:!space-y-6">
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
                        <tr>
                            <td colspan="7">No PI records are visible for your current scope.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $proformas->links() }}
        </div>
    </div>
    </div>
@endsection
