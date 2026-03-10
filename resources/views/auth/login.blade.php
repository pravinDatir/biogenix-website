@extends('layouts.app')

@section('title', request('user_type', request('portal')) === 'b2b' ? 'B2B Login' : 'B2C Login')

@section('content')
@include('pages.auth.login')
@endsection
