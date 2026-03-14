@extends('customer.layout')

@section('title', 'Payment Success Prototype')
@section('customer_title', 'Payment Success Prototype')
@section('customer_description', 'A dedicated success-state page for the logged-in order flow.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link href="#">Download Invoice</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Track Shipment</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'success'])
@endsection
