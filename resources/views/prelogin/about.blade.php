@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

<div class="about-page">

  <!-- Hero -->
  <section class="about-hero">
    <div class="container">
      <h1>About Biogenix</h1>
      <p>
        Empowering diagnostics and healthcare solutions through innovation,
        reliability, and trust.
      </p>
    </div>
  </section>

  <!-- Who We Are -->
  <section class="about-section">
    <div class="container">
      <h2>Who We Are</h2>
      <p>
        Biogenix is a healthcare-focused organization delivering high-quality
        diagnostic products, instruments, and consumables to laboratories,
        hospitals, and individuals across India.
      </p>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="about-section about-mv">
    <div class="container mv-container">
      <div class="mv-card">
        <h3>Our Mission</h3>
        <p>
          To make advanced diagnostic solutions accessible, reliable,
          and efficient for both B2B and B2C healthcare ecosystems.
        </p>
      </div>

      <div class="mv-card">
        <h3>Our Vision</h3>
        <p>
          To become a trusted healthcare partner by enabling faster,
          smarter, and more accurate diagnostics worldwide.
        </p>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="about-section">
    <div class="container">
      <h2>Why Choose Biogenix</h2>
      <ul class="about-points">
        <li>Wide portfolio of IVD kits, reagents, and instruments</li>
        <li>Strong logistics with same-day delivery in select cities</li>
        <li>Dedicated support for labs, hospitals, and individuals</li>
        <li>Quality-first approach with global standards</li>
      </ul>
    </div>
  </section>

</div>

@endsection