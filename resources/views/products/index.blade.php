@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Product Listing</h1>
        <form method="GET" action="{{ route('products.index') }}">
            <div class="field">
                <label for="q">Search by name or SKU</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="e.g. BIO-GLV-001">
            </div>
            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Apply</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Visibility</th>
                    <th>Visible Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category_name ?? '-' }}</td>
                        <td>{{ strtoupper($product->visibility_scope) }}</td>
                        <td>
                            @if ($product->visible_price !== null)
                                {{ $product->visible_currency }} {{ number_format($product->visible_price, 2) }}
                                <span class="muted">({{ $product->visible_price_type }})</span>
                            @else
                                Not available
                            @endif
                        </td>
                        <td>
                            <a class="btn secondary" href="{{ route('products.show', $product->id) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No products are visible for this user context.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
