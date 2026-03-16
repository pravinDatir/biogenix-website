@extends('layouts.app')

@if((isset($roleSlugs) && (in_array('Super Admin', $roleSlugs) || in_array('Admin', $roleSlugs) || in_array('System Admin', $roleSlugs))) || in_array(strtolower(auth()->user()->user_type ?? ''), ['admin', 'super_admin', 'system_admin']))
    <script>window.location.href = "{{ route('adminPanel.dashboard') }}";</script>
@endif

@section('title', 'Home')

@section('content')
@include('pages.guest.home')
@endsection
