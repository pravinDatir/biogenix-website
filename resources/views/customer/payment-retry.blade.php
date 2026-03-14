@extends('customer.layout')

@section('title', 'Retry Payment')
@section('customer_title', 'Retry Payment')
@section('customer_description', 'Continue the order with another payment method without losing checkout context.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link :href="route('checkout.page')">Return to Checkout</x-ui.action-link>
    <x-ui.action-link :href="route('payment.failed')" variant="secondary">View Failed State</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'retry'])
@endsection
