@extends('layouts.app')

@section('title', 'B2C Sign Up')

@section('content')
@include('pages.auth.partials.signup-b2c', ['portal' => 'b2c'])
@endsection
