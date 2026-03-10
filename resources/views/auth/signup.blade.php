@extends('layouts.app')

@section('title', request('user_type', request('portal')) === 'b2b' ? 'B2B Sign Up' : 'B2C Sign Up')

@section('content')
@include('pages.auth.signup')
@endsection
