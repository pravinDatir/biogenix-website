@extends('layouts.app')

@section('title', 'Login')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="login-page">
  <div class="login-card">
    <h2>Login</h2>

    <!-- <form id="loginForm" novalidate> -->
    <form method="POST" action="{{ route('login') }}">
            @csrf
      <!-- Email -->
      <div class="form-group">
        <label>Email</label>
        <input type="email" name='email'id="loginEmail" placeholder="Enter your email">
        <span class="error"></span>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label>Password</label>

        <div class="password-wrapper">
          <input type="password" name="password" id="loginPassword" placeholder="Enter your password">
          <button type="button" id="togglePassword" class="toggle-password">
            <i class="bi bi-eye-slash"></i>
          </button>
        </div>

        <span class="error"></span>

        <!-- Forgot Password -->
        <div class="forgot-password mt-2">
          <a href="{{ route('forgot.password') }}">Forgot Password?</a>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Login
      </button>
    </form>
  </div>

  <div class="signup-cta">
    <p>Don’t have an account?</p>
    <a href="{{ route('signup') }}" class="btn btn-outline">Sign Up</a>
  </div>
</div>
@endsection

<!-- @push('scripts')
<script src="{{ asset('js/login.js') }}"></script>
@endpush -->
