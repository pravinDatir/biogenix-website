@extends('customer.layouts.main')

@section('title', 'Payment Failed')
@section('customer_title', 'Payment Failed')
@section('customer_description', 'A recovery page for payment interruptions, safe retry, and support escalation.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link :href="route('payment.retry')">Retry Payment</x-ui.action-link>
    <x-ui.action-link :href="route('contact')" variant="secondary">Open Support Flow</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'failed'])
@endsection
