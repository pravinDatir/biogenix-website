@extends('layouts.app')

@section('title', 'Sign Up')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/signup.css') }}">
@endpush


@section('content')
<div class="signup-page">
  <div class="signup-card">

    <h2>Sign Up</h2>

    <!-- Stepper -->
    <div class="stepper">
      <div class="step active">Personal</div>
      <div class="step">Address</div>
    </div>

    <!-- <form id="signupForm" novalidate> -->
       <form id="signupForm" method="POST" action="{{ route('register') }}">
            @csrf
      @if ($errors->any())
        <div class="form-group">
          <span class="error">{{ $errors->first() }}</span>
        </div>
      @endif

      <!-- ================= STEP 1 ================= -->
      <div class="form-step active" data-step="1">

        <!-- Account Type -->
       <div class="form-group account-type">
  <label>Account Type</label>

  <div class="radio-inline">
    <label class="radio-option">
      <input type="radio" name="accountType" value="customer" checked>
      <span>Customer</span>
    </label>

    <label class="radio-option">
      <input type="radio" name="accountType" value="business">
      <span>Business</span>
    </label>
  </div>
</div>
        <div class="form-group">
          <label>First Name</label>
          <input type="text" id="firstName" name="first_name" value="{{ old('first_name') }}">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" id="lastName" name="last_name" value="{{ old('last_name') }}">
          <span class="error"></span>
        </div>

        <!-- Business Fields -->
        <div id="businessFields" style="display:none;">

          <div class="form-group">
            <label>Business Type</label>
            <select id="businessType" name="b2b_type">
              <option value="">Select Business Type</option>
              <option value="dealer">Dealer</option>
              <option value="distributor">Distributor</option>
              <option value="lab">Labs</option>
              <option value="hospital">Hospital</option>
            </select>
            <span class="error"></span>
          </div>

          <div class="form-group">
            <label>Company Name</label>
            <input type="text" id="companyName" name="company_name" value="{{ old('company_name') }}">
            <span class="error"></span>
          </div>

        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="signupEmail" name="email" value="{{ old('email') }}">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="password-wrapper">
            <input type="password" id="signupPassword" name='password'>
            <button type="button" id="toggleSignupPassword" class="toggle-password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <div class="password-wrapper">
            <input type="password" id="confirmPassword" name='password_confirmation'>
            <button type="button" id="toggleConfirmPassword" class="toggle-password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" id="phone" maxlength="10">
          <span class="error"></span>
        </div>

        <button type="submit" class="btn btn-primary" id="nextBtn">
          Sign Up
        </button>

      </div>

      <!-- ================= STEP 2 ================= -->
      <div class="form-step" data-step="2">

        <div class="form-group">
          <label id="addressLabel">Flat / House / Building</label>
          <input type="text" id="addressLine1">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Area / Street / Sector</label>
          <input type="text" id="addressLine2">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Landmark</label>
          <input type="text" id="landmark">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Pincode</label>
          <input type="text" id="pincode">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Town / City</label>
          <input type="text" id="city">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>State / UT</label>
          <select id="state">
            <option value="">Select State / UT</option>
          </select>
          <span class="error"></span>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-outline" id="backBtn">Back</button>
          <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>

      </div>

    </form>
  </div>
</div>
@endsection

<!-- @push('scripts')
<script src="{{ asset('js/signup.js') }}"></script>
@endpush -->
