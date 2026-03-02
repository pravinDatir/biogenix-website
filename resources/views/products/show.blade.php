@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>{{ $product->name }}</h1>
        <p><strong>SKU:</strong> {{ $product->sku }}</p>
        <p><strong>Category:</strong> {{ $product->category_name ?? '-' }}</p>
        <p><strong>Visibility Scope:</strong> {{ strtoupper($product->visibility_scope) }}</p>
        <p>{{ $product->description ?: 'No description available.' }}</p>

        <p>
            <strong>Visible Price:</strong>
            @if ($price)
                {{ $price['currency'] }} {{ number_format($price['amount'], 2) }}
                <span class="muted">({{ $price['price_type'] }})</span>
            @else
                Not available
            @endif
        </p>

        <a class="btn" href="{{ route('proforma.create', ['product_id' => $product->id]) }}">Generate PI</a>
        <a class="btn secondary" href="{{ route('products.index') }}">Back to Products</a>
    </div>
@endsection
