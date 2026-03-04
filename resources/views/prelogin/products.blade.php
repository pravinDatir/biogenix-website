@extends('layouts.app')

@section('title', 'Products')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endpush

@section('content')

<div class="products-page container py-5">

  <div class="products-header mb-4">
    <h2 class="category-indicator">
      <span id="currentCategory">All Products</span>
    </h2>
    <p class="subtitle">
      High-quality diagnostic products designed for professional and personal use
    </p>
  </div>

  <div class="row mb-4">
    <div class="col-md-6">
      <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
    </div>
  </div>

  <div id="productGrid" class="product-grid"></div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/products.js') }}"></script>
@endpush