@extends('customer.layouts.main')

@section('title', 'Payment Success')
@section('customer_title', 'Payment Success')
@section('customer_description', 'Payment confirmation, invoice reference, and a clear handoff into order management.')
@section('customer_active', 'checkout')

@section('customer_actions')
    <x-ui.action-link :href="route('order.confirmation')">Order Confirmation</x-ui.action-link>
    <x-ui.action-link :href="route('order.tracking')" variant="secondary">Track Order</x-ui.action-link>
@endsection

@section('customer_content')
    @include('customer.partials.payment-status', ['paymentStatus' => 'success'])
@endsection
