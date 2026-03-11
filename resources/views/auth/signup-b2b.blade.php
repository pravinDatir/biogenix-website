@extends('layouts.app')

@section('title', 'B2B Sign Up')

@section('content')
@include('pages.auth.partials.signup-b2b', ['portal' => 'b2b'])
@endsection
