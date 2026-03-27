@extends('customer.layouts.main')

@section('title', 'Order Confirmation')
@section('customer_title', 'Order Confirmation')
@section('customer_description', 'Order ID, invoice reference, email confirmation, and next-step guidance after checkout.')
@section('customer_active', 'orders')

@section('customer_actions')
    <x-ui.action-link :href="route('order.tracking')">Track Order</x-ui.action-link>
    <x-ui.action-link :href="route('products.index')" variant="secondary">Continue Shopping</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.order-confirmation-panel')
@endsection
