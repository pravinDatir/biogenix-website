@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Order Details</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="muted">{{ $error }}</p>
            @endforeach
        @endif

        @if (! $order)
            <p class="muted">Order details are not available.</p>
        @else
            @if (session('success'))
                <p class="muted">{{ session('success') }}</p>
            @endif

            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Status:</strong> {{ strtoupper($order->status) }}</p>
            <p><strong>Placed By:</strong> {{ $order->placedByUser?->name ?? '-' }} ({{ $order->placedByUser?->email ?? '-' }})</p>
            <p><strong>Company:</strong> {{ $order->company?->name ?? '-' }}</p>
            <p><strong>Currency:</strong> {{ $order->currency }}</p>
            <p><strong>Subtotal:</strong> {{ number_format((float) $order->subtotal_amount, 2) }}</p>
            <p><strong>Tax:</strong> {{ number_format((float) $order->tax_amount, 2) }}</p>
            <p><strong>Discount:</strong> {{ number_format((float) $order->discount_amount, 2) }}</p>
            <p><strong>Shipping:</strong> {{ number_format((float) $order->shipping_amount, 2) }}</p>
            <p><strong>Adjustment:</strong> {{ number_format((float) $order->adjustment_amount, 2) }}</p>
            <p><strong>Rounding:</strong> {{ number_format((float) $order->rounding_amount, 2) }}</p>
            <p><strong>Total:</strong> {{ number_format((float) $order->total_amount, 2) }}</p>
            <p><strong>Notes:</strong> {{ $order->notes ?: '-' }}</p>
            <p><strong>Created At:</strong> {{ $order->created_at?->format('d M Y H:i') }}</p>

            <p>
                <a class="btn secondary" href="{{ route('orders.index', ['edit_order_id' => $order->id]) }}">Edit Order</a>
                <a class="btn secondary" href="{{ route('orders.index') }}">Back to Orders</a>
            </p>
        @endif
    </div>

    @if ($order)
        <div class="card">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->variant_name ?: 'Default Variant' }}</td>
                            <td>{{ $item->sku ?: '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td>{{ number_format((float) $item->tax_amount, 2) }}</td>
                            <td>{{ number_format((float) $item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Pricing Snapshot</h2>
            <pre style="white-space: pre-wrap;">{{ json_encode($order->pricing_snapshot, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
@endsection
