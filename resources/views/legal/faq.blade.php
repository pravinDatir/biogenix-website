@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="faq-page container py-5">
  <h1 class="faq-title">Biogenix FAQ</h1>

  <!-- Categories -->
  <div class="faq-categories d-flex flex-wrap gap-3 mb-4 justify-content-center">
  </div>

  <!-- Accordion -->
  <div class="accordion" id="faqAccordion"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/faq.js') }}"></script>
@endpush