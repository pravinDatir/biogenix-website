@extends('customer.layout')

@section('title', 'Payment Retry Prototype')
@section('customer_title', 'Payment Retry Prototype')
@section('customer_description', 'A retry-state page for alternate method selection and order continuity.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link href="#">Try Alternate Method</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Return to Checkout</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'retry'])
@endsection
