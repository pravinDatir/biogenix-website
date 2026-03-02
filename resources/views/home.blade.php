@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Biogenix Access Modules (1, 2, 3, 4, 8)</h1>
        <p class="muted">
            This build enforces Guest, B2C, B2B, default permission visibility, and strict data isolation rules.
        </p>
    </div>

    @guest
        <div class="card">
            <h2>Guest User (Without Login)</h2>
            <strong>Allowed</strong>
            <ul>
                <li>Visit home, browse products, search, and view product details.</li>
                <li>View only publicly allowed prices.</li>
                <li>Generate PI for self or another customer with basic details.</li>
            </ul>

            <strong>Not Allowed</strong>
            <ul>
                <li>Place orders, request quotations, or view detailed price lists.</li>
                <li>Create support tickets or view order/shipment history.</li>
                <li>Access inventory or internal data.</li>
            </ul>
        </div>
    @endguest

    @auth
        <div class="card">
            <h2>Current User Context</h2>
            <p><strong>User Type:</strong> {{ strtoupper(auth()->user()->user_type) }}</p>
            <p><strong>Status:</strong> {{ strtoupper(auth()->user()->status) }}</p>
            <p><strong>Resolved Roles:</strong> {{ implode(', ', $roleSlugs) }}</p>
            <p class="muted">Default visibility: B2C = own data, B2B = own company + assigned clients, Admin = all data.</p>
        </div>
    @endauth
@endsection
