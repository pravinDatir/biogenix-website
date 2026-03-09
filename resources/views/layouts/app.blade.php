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

<div class="flex min-h-screen flex-col">
    @include('partials.header')

    <main class="flex-1 py-6 md:py-8">
        <div class="container space-y-4">
            @if (session()->has('impersonation.impersonator_id'))
                <div class="status">
                    You are currently impersonating another user.
                    <form method="POST" action="{{ route('impersonation.stop') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="btn secondary">Stop Impersonation</button>
                    </form>
                </div>
            @endif

            @if (session('status'))
                <x-alert type="info">{{ session('status') }}</x-alert>
            @endif

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error">
                    <strong>Validation failed:</strong>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
        </div>

        <div class="main-shell">
            @yield('content')
        </div>
    </main>

    @include('partials.footer')
</div>

@guest
    <a href="{{ route('contact') }}" class="chatbot-fab" aria-label="Open chatbot">
        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
        Chat with us
    </a>
@endguest
@auth
    <a href="{{ route('contact') }}" class="chatbot-fab" aria-label="Open chatbot">
        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
        Chat with us
    </a>
@endauth

<script src="{{ asset('js/validation.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
