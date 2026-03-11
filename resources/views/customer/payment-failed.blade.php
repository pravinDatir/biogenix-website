@extends('customer.layout')

@section('title', 'Payment Failed Prototype')
@section('customer_title', 'Payment Failed Prototype')
@section('customer_description', 'A dedicated failure-state page for payment interruptions and safe recovery.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link href="#">Retry Payment</x-ui.action-link>
    <x-ui.action-link href="#" variant="secondary">Open Support Flow</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'failed'])
@endsection
