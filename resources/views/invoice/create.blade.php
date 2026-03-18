@extends('layouts.app')

@php
    // Business step: let the wrapper title follow the flow that the controller requested.
    $quotationFlowMode = $quotationFlowMode ?? 'quote';
@endphp

@section('title', $quotationFlowMode === 'pi_request' ? 'Request Proforma Invoice' : 'Generate Quotation')

@section('content')
@include('pages.guest.generate-quotation')
@endsection
