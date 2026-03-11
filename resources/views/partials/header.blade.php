<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Biogenix Logo" width="120" height="64" decoding="async">
        </a>

        <button class="menu-toggle" data-menu-toggle aria-label="Toggle navigation" aria-expanded="false" aria-controls="headerMainNav">Menu</button>

        <nav id="headerMainNav" class="header-nav" aria-label="Main Navigation">
            <a href="{{ route('home') }}">Home</a>
            <div class="nav-item has-dropdown">
                <button
                    type="button"
                    class="products-link"
                    data-products-toggle
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="productsDropdownMenu"
                >
                    Products &amp; Solutions
                </button>
                <div id="productsDropdownMenu" class="products-dropdown">
                    <ul id="productCategories">
                        <li><span class="ui-small">Loading categories...</span></li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('proforma.create') }}">Generate Quote</a>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('faq') }}">FAQ</a>
            <a href="{{ route('contact') }}">Contact Us</a>
            <a href="{{ route('book-meeting') }}">Book Meeting</a>

            <div class="links auth-links">
                @auth
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" id="logoutBtn" class="btn secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" id="loginBtn" class="btn">Login</a>
                    <a href="{{ route('signup') }}" id="signupBtn" class="btn secondary">Sign Up</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
