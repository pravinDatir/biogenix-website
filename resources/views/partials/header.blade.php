<header class="site-header">
  <div class="header-inner">

    <!-- Logo -->
    <div class="logo">
      <a href="{{ route('home') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="BioGenix Logo" />
      </a>
    </div>

    <!-- Navigation -->
    <nav class="header-nav">

      <!-- Products Dropdown -->
      <div class="nav-item has-dropdown">
        <span class="products-link">Products & Solutions</span>

        <div class="products-dropdown">
          <ul id="productCategories">
            <!-- Categories injected by JS -->
          </ul>
        </div>
      </div>

      <a href="{{ route('about') }}">About Us</a>
      <a href="{{ route('contact') }}">Contact Us</a>

      <!-- Login / Logout buttons -->
      <a href="{{ route('login') }}" id="loginBtn" class="btn btn-primary">
        Login
      </a>

      <button id="logoutBtn" class="btn btn-outline" style="display:none;">
        Logout
      </button>

    </nav>

    <!-- Mobile toggle -->
    <button class="menu-toggle" data-menu-toggle>☰</button>

  </div>
</header>