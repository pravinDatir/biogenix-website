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

    <form id="signupForm" novalidate>

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
          <input type="text" id="firstName">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" id="lastName">
          <span class="error"></span>
        </div>

        <!-- Business Fields -->
        <div id="businessFields" style="display:none;">

          <div class="form-group">
            <label>Business Type</label>
            <select id="businessType">
              <option value="">Select Business Type</option>
              <option>Dealer</option>
              <option>Distributor</option>
              <option>Labs</option>
              <option>Hospital</option>
            </select>
            <span class="error"></span>
          </div>

          <div class="form-group">
            <label>Company Name</label>
            <input type="text" id="companyName">
            <span class="error"></span>
          </div>

        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="signupEmail">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="password-wrapper">
            <input type="password" id="signupPassword">
            <button type="button" id="toggleSignupPassword" class="toggle-password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <div class="password-wrapper">
            <input type="password" id="confirmPassword">
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

        <button type="button" class="btn btn-primary" id="nextBtn">
          Next
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

@push('scripts')
<script src="{{ asset('js/signup.js') }}"></script>
@endpush