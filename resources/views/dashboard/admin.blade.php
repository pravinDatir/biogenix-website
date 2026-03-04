@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="dashboard-wrapper container py-5">

  <div class="dashboard-header mb-4">
    <h2>Admin Dashboard</h2>
    <p class="text-muted">
      Manage products, users, and business operations.
    </p>
  </div>

  <div class="row g-4">

    <!-- Manage Products -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>Manage Products</h5>
        <p>Add, edit or remove products from catalog.</p>
        <a href="#" class="btn btn-danger btn-sm">Manage</a>
      </div>
    </div>

    <!-- Manage Users -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>Manage Users</h5>
        <p>View and manage customer & business accounts.</p>
        <a href="#" class="btn btn-danger btn-sm">View Users</a>
      </div>
    </div>

    <!-- Reports -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>Reports & Analytics</h5>
        <p>View sales and system reports.</p>
        <a href="#" class="btn btn-danger btn-sm">View Reports</a>
      </div>
    </div>

  </div>

</div>

@endsection