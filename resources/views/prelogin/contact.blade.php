@extends('layouts.app')

@section('title', 'Contact Us')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')

<div class="contact-page">

  <section class="contact-hero">
    <div class="container">
      <h1>Contact Us</h1>
      <p>
        Have questions or need support? Reach out to us and our team
        will get back to you shortly.
      </p>
    </div>
  </section>

  <section class="contact-content">
    <div class="container contact-grid">

      <div class="contact-info">
        <h2>Get in Touch</h2>

        <p><strong>Email:</strong>
          <a href="mailto:support@biogenix.com" class="contact-link">
            support@biogenix.com
          </a>
        </p>

        <p><strong>Phone:</strong>
          <a href="tel:+919876543210" class="contact-link">
            +91 98765 43210
          </a>
        </p>

        <p><strong>Address:</strong> Lucknow, Uttar Pradesh, India</p>
      </div>

      <form id="contactForm" class="contact-form" novalidate>

        <h2>Send Us a Message</h2>

        <div class="form-group">
          <label>First Name</label>
          <input type="text" id="firstName" placeholder="Enter your first name">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" id="lastName" placeholder="Enter your last name">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" placeholder="Enter your email address">
          <span class="error"></span>
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea id="description" rows="4" placeholder="Enter your message"></textarea>
          <span class="error"></span>
        </div>

        <button type="submit" class="btn btn-primary">
          Submit
        </button>

        <p id="formStatus" class="form-status"></p>

      </form>

    </div>
  </section>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/contact.js') }}"></script>
@endpush