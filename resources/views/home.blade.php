@extends('layouts_p.app')

@section('content')
    <div class="card">
        <h1>Biogenix Access Modules (1, 2, 3, 4, 8)</h1>
        <p class="muted">
            This build enforces Guest, B2C, B2B, default permission visibility, and strict data isolation rules.
        </p>
    </div>

    <div class="card">
        <h2>Proforma Invoice Flow Test</h2>
        <p class="muted">
            Use this page to test PI creation, quantity validation, pricing resolution, and automatic invoice PDF download.
        </p>

        <p>
            <a href="{{ route('proforma.create') }}" class="btn">Open PI Form</a>
            <a href="{{ route('products.index') }}" class="btn secondary">Browse Products</a>
            @auth
                <a href="{{ route('proforma.index') }}" class="btn secondary">View Generated PI</a>
            @endauth
        </p>

        <strong>Test Steps</strong>
        <ol>
            <li>Open the PI form and add one or more products.</li>
            <li>Enter valid quantities based on min, max, and lot size rules.</li>
            <li>Submit the form to generate the PI.</li>
            <li>The invoice PDF should download immediately after save.</li>
        </ol>
    </div>

    @guest
        <div class="card">
            <h2>Guest User (Without Login)</h2>
            <strong>Allowed</strong>
            <ul>
                <li>Visit home, browse products, search, and view product details.</li>
                <li>View only publicly allowed prices.</li>
                <li>Generate PI for self or another customer with basic details and download the invoice PDF.</li>
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
            <p class="muted">Use "Generate PI" to test user-specific pricing and download the invoice PDF.</p>
        </div>
    @endauth
@endsection
