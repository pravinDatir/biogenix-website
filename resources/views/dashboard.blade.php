@extends('layouts.app')

@section('content')
    <div class="page-shell !space-y-4 md:!space-y-6">
    <div class="card">
        <h1>User Dashboard</h1>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>User Type:</strong> {{ strtoupper($user->user_type) }}</p>
        <p><strong>Status:</strong> {{ strtoupper($user->status) }}</p>
        <p><strong>Resolved Roles:</strong> {{ implode(', ', $roleSlugs) }}</p>
        @if (count($departments))
            <p><strong>Departments:</strong> {{ implode(', ', $departments) }}</p>
        @endif
    </div>

    <div class="card">
        <h2>Visibility Summary</h2>
        <ul>
            <li>Visible products: {{ $visibleProductsCount }}</li>
            <li>Visible proforma invoices: {{ $visiblePiCount }}</li>
            <li>Default rule: Admin sees all data, others only their scope.</li>
            <li>Critical rule: no cross-user or cross-company visibility unless assigned.</li>
        </ul>
    </div>

    <div class="card">
        <h2>Resolved Permissions</h2>
        @if (count($permissions))
            <ul>
                @foreach ($permissions as $permission)
                    <li>{{ $permission }}</li>
                @endforeach
            </ul>
        @else
            <p>No permissions are currently mapped.</p>
        @endif
    </div>
    </div>
@endsection
