@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endpush

@section('content')

<div class="homepage">

    {{-- HERO SECTION --}}
<section class="hero position-relative">
  <div id="heroCarousel" 
       class="carousel slide" 
       data-bs-ride="carousel" 
       data-bs-interval="5000">

    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0"
              class="active" aria-current="true"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active">
        <img src="{{ asset('images/home1.jpg') }}"
             class="d-block w-100"
             alt="Slide 1">

        <div class="carousel-caption text-start">
          <h1>Advancing Diagnostics.<br>Empowering Healthcare.</h1>
          <p>Reliable IVD solutions for labs, hospitals, and institutions.</p>

          <div class="hero-actions mt-3">
            <a href="{{ route('products.index') }}" 
               class="btn btn-primary">
               Explore Products
            </a>

            <a href="{{ route('contact') }}" 
               class="btn btn-secondary">
               Book a Meeting
            </a>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item">
        <img src="{{ asset('images/home2.jpg') }}"
             class="d-block w-100"
             alt="Slide 2">

        <div class="carousel-caption text-start">
          <h1>Innovating Laboratory Solutions</h1>
          <p>High-quality instruments and consumables for every lab.</p>

          <div class="hero-actions mt-3">
            <a href="{{ route('products.index') }}"
               class="btn btn-primary me-2">
               Generate Quote
            </a>
          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item">
        <img src="{{ asset('images/home3.jpg') }}"
             class="d-block w-100"
             alt="Slide 3">

        <div class="carousel-caption text-start">
          <h1>One Day Delivery - Lucknow</h1>
          <p>
            We deliver all over India with precision and reliability.
            Connect with our operations team.
          </p>
        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>

  </div>
</section>


    {{-- PRODUCT CATEGORIES --}}
    <section class="products py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Our Product Categories</h2>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">IVD Kits</h5>
                            <p class="text-muted">High-quality diagnostic kits.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                View Products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Reagents</h5>
                            <p class="text-muted">Reliable laboratory reagents.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                View Products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Instruments</h5>
                            <p class="text-muted">Precision lab instruments.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                View Products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold">Consumables</h5>
                            <p class="text-muted">Daily laboratory essentials.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                View Products
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- B2B / B2C SECTION --}}
    <section class="segments py-5">
        <div class="container text-center">
            <h2 class="mb-5 fw-bold">Who Are You?</h2>

            <div class="row g-4 justify-content-center">

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <h5 class="fw-bold">For Labs & Hospitals</h5>
                        <p class="text-muted">
                            Professional solutions tailored for institutions.
                        </p>
                        <a href="#" class="btn btn-primary">Request Quote</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <h5 class="fw-bold">For Home & Personal Use</h5>
                        <p class="text-muted">
                            Easy-to-use diagnostic products for home.
                        </p>
                        <a href="#" class="btn btn-primary">Order Now</a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- NEWSLETTER --}}
    <section class="newsletter py-5 bg-light text-center">
        <div class="container">
            <h3 class="fw-bold">Stay Updated</h3>
            <p class="text-muted">Subscribe for latest product updates</p>

            <form class="d-flex justify-content-center mt-3">
                <input type="email" class="form-control w-25 me-2" placeholder="Enter email">
                <button class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </section>

</div>

@endsection