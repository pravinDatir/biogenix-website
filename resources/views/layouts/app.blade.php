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
@php($suppressShellAlerts = request()->routeIs('login', 'forgot.password'))

<div class="flex min-h-screen flex-col">
    @include('partials.header')

    <main class="flex-1 py-6 md:py-8">
        @unless ($suppressShellAlerts)
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
        @endunless

        <div class="main-shell">
            @yield('content')
        </div>
    </main>

    @include('partials.footer')
</div>

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <!-- Chat Form Modal -->
    <div id="supportTicketForm" class="hidden mb-4 w-80 max-w-[calc(100vw-3rem)] rounded-2xl bg-white p-5 shadow-2xl border border-slate-100 transition-all duration-300 transform origin-bottom-right">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Support Ticket</h3>
            <button type="button" onclick="toggleSupportForm()" class="text-slate-400 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('contact') }}" method="GET" class="space-y-4 text-left">
            <div>
                <label for="supportName" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input type="text" id="supportName" name="name" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border bg-white text-slate-900" required>
            </div>
            <div>
                <label for="supportEmail" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" id="supportEmail" name="email" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border bg-white text-slate-900" required>
            </div>
            <div>
                <label for="supportMessage" class="block text-sm font-medium text-slate-700 mb-1">How can we help?</label>
                <textarea id="supportMessage" name="message" rows="3" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border bg-white text-slate-900" required></textarea>
            </div>
            <button type="submit" class="w-full rounded-md bg-indigo-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">Submit Ticket</button>
        </form>
    </div>

    <!-- Floating Button -->
    <button onclick="toggleSupportForm()" class="group flex h-14 w-14 items-center justify-center rounded-full bg-[#8A9CEC] text-white shadow-lg transition-transform hover:scale-105 hover:bg-[#788BE0] focus:outline-none focus:ring-2 focus:ring-[#8A9CEC] focus:ring-offset-2" aria-label="Open support ticket">
        <!-- Chat Icon matches screenshot (purple/blue circle with white chat bubble and 3 dots) -->
        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.477 2 2 5.582 2 10c0 2.476 1.343 4.675 3.444 6.136.213 1.393-.454 3.125-.5 3.245a.5.5 0 00.643.64c.12-.046 1.85-.712 3.244-1.127A9.852 9.852 0 0012 18c5.523 0 10-3.582 10-8s-4.477-8-10-8zm-3 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
        </svg>
    </button>
</div>

<script>
    function toggleSupportForm() {
        const form = document.getElementById('supportTicketForm');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            setTimeout(() => {
                form.classList.remove('opacity-0', 'scale-95');
                form.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            form.classList.remove('opacity-100', 'scale-100');
            form.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                form.classList.add('hidden');
            }, 300);
        }
    }
</script>

<script src="{{ asset('js/validation.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
