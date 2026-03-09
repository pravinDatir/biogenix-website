<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20260309">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}?v=20260309">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}?v=20260309">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <nav class="border-b border-slate-800 bg-slate-900 text-slate-100">
        <div class="container flex flex-wrap items-center justify-between gap-3 py-3">
            <div class="flex flex-wrap items-center gap-3 text-sm">
                <a href="{{ url('/AdminhomeView') }}" class="rounded px-2 py-1 font-semibold text-white hover:bg-slate-800">Home</a>
                <a href="{{ route('products.index') }}" class="rounded px-2 py-1 hover:bg-slate-800">Products</a>
                <a href="{{ route('proforma.create') }}" class="rounded px-2 py-1 hover:bg-slate-800">Generate PI</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded px-2 py-1 hover:bg-slate-800">Dashboard</a>
                    <a href="{{ route('proforma.index') }}" class="rounded px-2 py-1 hover:bg-slate-800">My PI</a>
                    <a href="{{ route('support-tickets.index') }}" class="rounded px-2 py-1 hover:bg-slate-800">Support Tickets</a>
                    <a href="{{ route('admin.users.index') }}" class="rounded px-2 py-1 hover:bg-slate-800">Admin Console</a>
                @endauth
            </div>

            <div class="flex items-center gap-2 text-sm">
                @auth
                    <span>{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="btn secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    <a href="{{ route('register') }}" class="btn secondary">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main-shell py-6">
        @if (session()->has('impersonation.impersonator_id'))
            <div class="status mb-4">
                You are currently impersonating another user.
                <form method="POST" action="{{ route('impersonation.stop') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="btn secondary">Stop Impersonation</button>
                </form>
            </div>
        @endif

        @if (session('status'))
            <x-alert type="info" class="mb-4">{{ session('status') }}</x-alert>
        @endif

        @if (session('success'))
            <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert type="error" class="mb-4">
                <strong>Validation failed:</strong>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
