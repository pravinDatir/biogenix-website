@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')

<div class="dashboard-wrapper container py-5">

  <div class="dashboard-header mb-4">
    <h2>Welcome to Customer Dashboard</h2>
    <p class="text-muted">
      Manage your orders, track shipments and explore products.
    </p>
  </div>

  <div class="row g-4">

    <!-- My Orders -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>My Orders</h5>
        <p>View your order history and track delivery status.</p>
        <a href="#" class="btn btn-primary btn-sm">View Orders</a>
      </div>
    </div>

    <!-- Profile -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>My Profile</h5>
        <p>Update your personal details and address.</p>
        <a href="#" class="btn btn-primary btn-sm">Edit Profile</a>
      </div>
    </div>

    <!-- Explore Products -->
    <div class="col-md-4">
      <div class="dashboard-card">
        <h5>Explore Products</h5>
        <p>Browse latest products and categories.</p>
        <a href="/products" class="btn btn-primary btn-sm">Browse</a>
      </div>
    </div>

  </div>

</div>

@endsection