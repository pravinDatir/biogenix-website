<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="@yield('meta_description', 'Biogenix Healthcare Solutions — Precision diagnostics, innovative life science research tools, and medical instruments for laboratories and healthcare professionals.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/slides/logo.jpg') }}?v=20260309">
    <link rel="shortcut icon" href="{{ asset('storage/slides/logo.jpg') }}?v=20260309">
    <link rel="apple-touch-icon" href="{{ asset('storage/slides/logo.jpg') }}?v=20260309">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen text-slate-800 antialiased"
    style="background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.16), transparent 34%), radial-gradient(circle at right 15%, rgba(14, 165, 233, 0.12), transparent 28%), linear-gradient(180deg, #f8fbff 0%, #eff5ff 100%);">
    @php($suppressShellAlerts = request()->routeIs('login', 'forgot.password', 'signup', 'b2b.signup'))

    {{-- Toast Notification Container --}}
    <div id="toastContainer" class="toast-container"></div>

    <div id="pageWrapper" class="flex min-h-screen flex-col"
        style="transition: padding-right 0.35s cubic-bezier(0.32, 0.72, 0, 1);">
        @include('partials.header')
        @include('partials.cart-sidebar')

        <main class="flex-1">
            @unless ($suppressShellAlerts)
                <div class="container space-y-4">
                    @if (session()->has('impersonation.impersonator_id'))
                        <div
                            class="animate-entrance rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                            You are currently impersonating another user.
                            <form method="POST" action="{{ route('impersonation.stop') }}" class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="ml-2 inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Stop
                                    Impersonation</button>
                            </form>
                        </div>
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

            <div class="w-full animate-entrance">
                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>

    {{-- Floating Support Widget --}}<div class="support-widget">
        <div id="supportTicketForm" class="support-panel is-hidden hidden">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Support Center</h3>
                    <p class="text-xs text-slate-500">We usually reply within a few hours</p>
                </div>
                <button onclick="toggleSupportForm()"
                    class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('contact') }}" method="GET" class="space-y-4 text-left">
                <div>
                    <label for="supportName"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Full
                        Name</label>
                    <input type="text" id="supportName" name="name"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-500/10"
                        placeholder="John Doe" required>
                </div>
                <div>
                    <label for="supportEmail"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Email
                        Address</label>
                    <input type="email" id="supportEmail" name="email"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-500/10"
                        placeholder="you@example.com" required>
                </div>
                <div>
                    <label for="supportMessage"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Message</label>
                    <textarea id="supportMessage" name="message" rows="3"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-500/10"
                        placeholder="How can we help you today?" required></textarea>
                </div>
                <button type="submit"
                    class="inline-flex h-12 w-full items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-bold text-white shadow-lg shadow-primary-600/20 transition hover:bg-primary-700 active:scale-[0.98]">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Floating Button -->
        <button onclick="toggleSupportForm()" class="support-fab" aria-label="Open support ticket">
            <svg class="relative z-10 h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.477 2 2 5.582 2 10c0 2.476 1.343 4.675 3.444 6.136.213 1.393-.454 3.125-.5 3.245a.5.5 0 00.643.64c.12-.046 1.85-.712 3.244-1.127A9.852 9.852 0 0012 18c5.523 0 10-3.582 10-8s-4.477-8-10-8zm-3 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
            </svg>
        </button>
    </div>

    {{-- Scroll-to-top Button --}}


    <script>
        // ─── Support form toggle ───
        function toggleSupportForm() {
            const form = document.getElementById('supportTicketForm');
            if (form.classList.contains('is-hidden')) {
                form.classList.remove('hidden');
                setTimeout(() => {
                    form.classList.remove('is-hidden');
                }, 10);
            } else {
                form.classList.add('is-hidden');
                setTimeout(() => {
                    form.classList.add('hidden');
                }, 300);
            }
        }

        // ─── Toast notification system ───
        window.BiogenixToast = {
            show: function (message, type, duration) {
                type = type || 'info';
                duration = duration || 5000;
                var container = document.getElementById('toastContainer');
                if (!container) return;

                var iconMap = {
                    success: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    error: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    warning: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    info: '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                };

                var toast = document.createElement('div');
                toast.className = 'toast toast--' + type;
                toast.innerHTML = (iconMap[type] || '') + '<span>' + message + '</span><button class="toast-close" onclick="this.parentElement.classList.add(\'is-leaving\');setTimeout(function(){toast.remove()},300)">&times;</button>';
                container.appendChild(toast);

                var closeRef = toast;
                setTimeout(function () {
                    closeRef.classList.add('is-leaving');
                    setTimeout(function () { closeRef.remove(); }, 300);
                }, duration);
            }
        };

        // ─── Auto-show session toasts ───
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                window.BiogenixToast.show(@json(session('success')), 'success');
            @endif
            @if (session('error'))
                window.BiogenixToast.show(@json(session('error')), 'error');
            @endif
            @if (session('status'))
                window.BiogenixToast.show(@json(session('status')), 'info');
            @endif
    });

        // ─── Scroll-to-top ───
        (function () {
            var btn = document.getElementById('scrollTopBtn');
            if (!btn) return;
            window.addEventListener('scroll', function () {
                btn.classList.toggle('is-hidden', window.scrollY < 400);
            }, { passive: true });
            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>

    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>