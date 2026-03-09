@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
@include('pages.guest.contact')
@endsection

@push('scripts')
<script src="{{ asset('js/contact.js') }}"></script>
@endpush
