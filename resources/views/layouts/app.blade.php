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

    {{-- Floating Support Widget --}}
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-4">
        <div id="supportTicketForm" class="is-hidden hidden w-[360px] sm:w-[400px] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl transition-all duration-300 origin-bottom-right">
            <!-- Header -->
            <div class="bg-[#0b74fb] px-5 py-4 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#3b93ff] text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 13v4m-2-2h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-white leading-tight">Biogenix Support</h3>
                        <p class="text-[11px] text-blue-100 mt-0.5">Average response time: &lt; 2 hours</p>
                    </div>
                </div>
                <button onclick="toggleSupportForm()" class="text-blue-100 hover:text-white transition-colors p-1 -mr-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 flex-1 overflow-y-auto bg-white text-left">
                <h4 class="text-lg font-bold text-slate-900 leading-tight mb-1">Raise a Ticket</h4>
                <p class="text-[13px] text-slate-500 mb-5 leading-relaxed">Submit your request and our biotech experts will assist you.</p>

                <form action="{{ route('contact') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="supportSubject" class="mb-1.5 block text-[13px] font-semibold text-slate-800">Subject</label>
                        <input type="text" id="supportSubject" name="subject" class="h-10 w-full rounded-md border border-slate-200 px-3 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#0b74fb] focus:ring-1 focus:ring-[#0b74fb]" placeholder="Briefly describe the issue" required>
                    </div>

                    <div>
                        <label for="supportCategory" class="mb-1.5 block text-[13px] font-semibold text-slate-800">Category</label>
                        <div class="relative">
                            <select id="supportCategory" name="category" class="h-10 w-full appearance-none rounded-md border border-slate-200 pl-3 pr-8 text-[13px] text-slate-900 outline-none transition focus:border-[#0b74fb] focus:ring-1 focus:ring-[#0b74fb]" required>
                                <option value="">Select a category</option>
                                <option value="Product">Product Issue</option>
                                <option value="Billing">Billing & Invoice</option>
                                <option value="Shipping">Shipping</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="supportMessage" class="mb-1.5 block text-[13px] font-semibold text-slate-800">Message</label>
                        <textarea id="supportMessage" name="message" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#0b74fb] focus:ring-1 focus:ring-[#0b74fb]" placeholder="Provide detailed information about your request..." required></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[13px] font-semibold text-slate-800">Attachments</label>
                        <label class="flex flex-col items-center justify-center rounded-md border border-dashed border-slate-300 bg-[#fbfcfd] py-5 px-4 text-center cursor-pointer hover:bg-slate-50 transition-colors">
                            <svg class="h-4 w-4 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="text-[11px] text-slate-500 font-medium">Click to upload or drag and drop</span>
                            <span class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider">PDF, PNG, JPG (MAX. 5MB)</span>
                            <input type="file" class="hidden" name="attachment">
                        </label>
                    </div>
                    
                    <button type="submit" class="hidden">Submit</button>

                    <div class="mt-2 text-center pt-3 pb-2">
                        <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-[0.05em] flex items-center justify-center gap-1.5">
                            ENCRYPTED CONNECTION &bull; BIOGENIX SECURE SUPPORT
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Floating Button -->
        <button onclick="toggleSupportForm()" class="group flex h-14 w-14 items-center justify-center rounded-full bg-[#0b74fb] text-white shadow-xl shadow-blue-500/30 transition-all hover:scale-105 hover:bg-blue-700" aria-label="Open support ticket">
            <svg class="h-7 w-7 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 5.582 2 10c0 2.476 1.343 4.675 3.444 6.136.213 1.393-.454 3.125-.5 3.245a.5.5 0 00.643.64c.12-.046 1.85-.712 3.244-1.127A9.852 9.852 0 0012 18c5.523 0 10-3.582 10-8s-4.477-8-10-8zm-3 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
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