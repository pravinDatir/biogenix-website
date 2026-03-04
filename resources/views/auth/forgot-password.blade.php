@extends('layouts.app')

@section('title', 'Forgot Password')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="login-page">
  <div class="login-card">
    <h2>Reset Password</h2>

    <form id="forgotForm" novalidate>
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="forgotEmail" placeholder="Enter your registered email">
        <span class="error"></span>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Send Reset Link
      </button>

      <p id="resetStatus" class="form-status mt-2"></p>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/forgot.js') }}"></script>
@endpush