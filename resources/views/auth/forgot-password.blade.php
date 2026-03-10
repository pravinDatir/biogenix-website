@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
@include('pages.auth.forgot-password')
@endsection

@push('scripts')
<script src="{{ asset('js/forgot.js') }}"></script>
@endpush
