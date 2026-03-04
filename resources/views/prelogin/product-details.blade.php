@extends('layouts.app')

@section('title', 'Product Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-details.css') }}">
@endpush

@section('content')

<div class="product-details-page container py-5">

  <div class="product-back mb-4">
    <a href="{{ route('products') }}" class="btn btn-light">
      ← Back to Products
    </a>
  </div>

  <div class="product-cards row">

    <!-- Left -->
    <div class="col-md-6 product-media">
      <img id="productImage" src="" class="img-fluid rounded shadow-sm">
    </div>

    <!-- Right -->
    <div class="col-md-6 product-info">
      <h1 id="productName"></h1>
      <p id="productMRP" class="mrp fw-bold"></p>

      <a href="{{ route('login') }}" 
         id="loginCTA" 
         class="btn btn-outline-primary">
         Login to see more details
      </a>

      <button id="orderCTA" 
              class="btn btn-info" 
              style="display:none;">
        Order Now
      </button>

      <div class="product-description mt-4">
        <h4>Description</h4>
        <p id="productDescription"></p>
      </div>

      <div class="product-specs mt-4">
        <h4>Technical Specifications</h4>
        <ul id="productSpecs"></ul>
      </div>

      <div class="product-brochure mt-3">
        <a id="productBrochure" href="#" target="_blank" class="btn btn-link">
          Download Brochure
        </a>
      </div>

    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
    const PRODUCT_ID = "{{ $id }}";
</script>
<script src="{{ asset('js/product-details.js') }}"></script>
@endpush