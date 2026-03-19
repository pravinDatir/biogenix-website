<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="@yield('meta_description', 'Biogenix Healthcare Solutions - Precision diagnostics, innovative life science research tools, and medical instruments for laboratories and healthcare professionals.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biogenix')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="shortcut icon" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="apple-touch-icon" href="{{ asset('upload/icons/logo.jpg') }}?v=20260309">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.16),transparent_34%),radial-gradient(circle_at_right_15%,rgba(14,165,233,0.12),transparent_28%),linear-gradient(180deg,#f8fbff_0%,#eff5ff_100%)] font-sans text-slate-800 antialiased">
    @php($suppressShellAlerts = request()->routeIs('login', 'forgot.password', 'signup', 'b2b.signup'))
    @php($loaderLogoPath = public_path('upload/icons/biogenix3D.png'))
    @php($loaderLogoSrc = file_exists($loaderLogoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($loaderLogoPath)) : asset('upload/icons/logo.jpg'))

    <div id="toastContainer" class="pointer-events-none fixed right-4 top-5 z-[120] flex w-[min(calc(100vw-2rem),24rem)] flex-col gap-3 sm:right-6 sm:top-6"></div>

    <div id="globalPageLoader" aria-hidden="true" class="fixed inset-x-0 bottom-0 top-[72px] z-40 opacity-100 visible transition-opacity duration-200">
        <div class="h-full w-full bg-slate-950 flex items-center justify-center overflow-hidden relative">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(14,165,233,0.18),transparent_35%),radial-gradient(circle_at_bottom,rgba(34,197,94,0.12),transparent_30%)]"></div>

            <div class="relative flex flex-col items-center gap-0">
                <div class="relative">
                    <!-- <div class="absolute inset-[-12px] rounded-[2rem] bg-white/5 blur-xl"></div> -->

                    <!-- <div class="relative rounded-[28px] bg-white shadow-2xl shadow-cyan-500/10 px-6 py-5 border border-white/60"> -->
                        <img
                            src="{{ $loaderLogoSrc }}"
                            alt="Biogenix"
                            class="w-[260px] max-w-[72vw] drop-shadow-sm"
                            decoding="sync"
                        >
                    <!-- </div> -->
                </div>

                <div class="flex flex-col items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400/95"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-cyan-400/95"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-300/95"></span>
                    </div>

                    <div class="text-center">
                        <p class="text-white text-xl md:text-2xl font-semibold tracking-[0.24em] uppercase">
                            Loading your store
                        </p>
                        <p class="mt-2 text-slate-300 text-sm md:text-base">
                            Preparing a faster, smarter shopping experience...
                        </p>
                    </div>
                </div>

                <div class="w-72 max-w-[80vw]">
                    <div class="h-1.5 rounded-full bg-white/10 overflow-hidden">
                        <div class="loader-bar h-full w-1/2 rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-amber-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        html {
            scrollbar-gutter: stable both-edges;
        }

        html.biogenix-loading,
        body.biogenix-loading {
            overflow: hidden;
        }

        #globalPageLoader {
            contain: layout paint style;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        @keyframes loader {
            0% { transform: translateX(-110%) scaleX(0.9); }
            50% { transform: translateX(100%) scaleX(1); }
            100% { transform: translateX(220%) scaleX(0.9); }
        }

        .loader-bar {
            animation: loader 1.6s ease-in-out infinite;
            will-change: transform;
        }
    </style>

    <div id="pageWrapper" class="flex min-h-screen flex-col">
        @include('partials.header')
        @include('partials.cart-sidebar')

        <main class="flex-1 transition-[padding] duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">
            @unless ($suppressShellAlerts)
                <div class="mx-auto w-full max-w-7xl space-y-4 px-4 sm:px-6 lg:px-8 xl:px-10">
                    @if (session()->has('impersonation.impersonator_id'))
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                            You are currently impersonating another user.
                            <form method="POST" action="{{ route('impersonation.stop') }}" class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="ml-2 inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Stop Impersonation
                                </button>
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

            <div class="w-full">
                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>

    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-4">
        <div id="supportTicketForm" class="hidden w-[360px] origin-bottom-right translate-y-3 scale-95 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white opacity-0 pointer-events-none shadow-2xl transition duration-300 sm:w-[400px]">
            <div class="flex items-start justify-between bg-[#0b74fb] px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#3b93ff] text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 13v4m-2-2h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold leading-tight text-white">Biogenix Support</h3>
                        <p class="mt-0.5 text-[11px] text-blue-100">Average response time: &lt; 2 hours</p>
                    </div>
                </div>
                <button onclick="toggleSupportForm()" class="-mr-2 p-1 text-blue-100 transition-colors hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto bg-white p-6 text-left">
                <h4 class="mb-1 text-lg font-bold leading-tight text-slate-900">Raise a Ticket</h4>
                <p class="mb-5 text-[13px] leading-relaxed text-slate-500">Submit your request and our biotech experts will assist you.</p>

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
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="supportMessage" class="mb-1.5 block text-[13px] font-semibold text-slate-800">Message</label>
                        <textarea id="supportMessage" name="message" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#0b74fb] focus:ring-1 focus:ring-[#0b74fb]" placeholder="Provide detailed information about your request..." required></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[13px] font-semibold text-slate-800">Attachments</label>
                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-md border border-dashed border-slate-300 bg-[#fbfcfd] px-4 py-5 text-center transition-colors hover:bg-slate-50">
                            <svg class="mb-2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="text-[11px] font-medium text-slate-500">Click to upload or drag and drop</span>
                            <span class="mt-1 text-[10px] uppercase tracking-wider text-slate-400">PDF, PNG, JPG (MAX. 5MB)</span>
                            <input type="file" class="hidden" name="attachment">
                        </label>
                    </div>

                    <button type="submit" class="hidden">Submit</button>

                    <div class="mt-2 pb-2 pt-3 text-center">
                        <span class="flex items-center justify-center gap-1.5 text-[9px] font-semibold uppercase tracking-[0.05em] text-slate-400">
                            ENCRYPTED CONNECTION &bull; BIOGENIX SECURE SUPPORT
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <button onclick="toggleSupportForm()" class="group flex h-14 w-14 items-center justify-center rounded-full bg-[#0b74fb] text-white shadow-xl shadow-blue-500/30 transition-all hover:scale-105 hover:bg-blue-700" aria-label="Open support ticket">
            <svg class="h-7 w-7 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 5.582 2 10c0 2.476 1.343 4.675 3.444 6.136.213 1.393-.454 3.125-.5 3.245a.5.5 0 00.643.64c.12-.046 1.85-.712 3.244-1.127A9.852 9.852 0 0012 18c5.523 0 10-3.582 10-8s-4.477-8-10-8zm-3 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm3 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
            </svg>
        </button>
    </div>

    <script>
        (function () {
            const loaderElement = document.getElementById('globalPageLoader');
            const rootElement = document.documentElement;
            const bodyElement = document.body;
            let loaderStartedAt = Date.now();
            let loaderFallbackTimer = null;

            const showLoader = function () {
                if (!loaderElement) {
                    return;
                }

                // Business step: show the global loader immediately before a real page change starts.
                loaderStartedAt = Date.now();
                rootElement.classList.add('biogenix-loading');
                bodyElement.classList.add('biogenix-loading');
                loaderElement.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                loaderElement.classList.add('opacity-100', 'visible');
            };

            const hideLoader = function () {
                if (!loaderElement) {
                    return;
                }

                // Business step: keep the loader visible for a very short minimum time so it fades out smoothly instead of flashing.
                const visibleFor = Date.now() - loaderStartedAt;
                const remainingDelay = Math.max(0, 260 - visibleFor);

                window.clearTimeout(loaderFallbackTimer);
                window.setTimeout(function () {
                    rootElement.classList.remove('biogenix-loading');
                    bodyElement.classList.remove('biogenix-loading');
                    loaderElement.classList.remove('opacity-100', 'visible');
                    loaderElement.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                }, remainingDelay);
            };

            const showLoaderForNavigation = function () {
                showLoader();

                // Business step: close the loader automatically if the page does not actually navigate, such as blocked downloads or interrupted submits.
                window.clearTimeout(loaderFallbackTimer);
                loaderFallbackTimer = window.setTimeout(function () {
                    hideLoader();
                }, 10000);
            };

            const isSelfNavigationForm = function (form) {
                if (!(form instanceof HTMLFormElement)) {
                    return false;
                }

                return !form.target || form.target === '_self';
            };

            const canSubmitWithoutBrowserValidationError = function (form) {
                if (!(form instanceof HTMLFormElement)) {
                    return false;
                }

                if (form.noValidate || typeof form.checkValidity !== 'function') {
                    return true;
                }

                return form.checkValidity();
            };

            // Business step: remove the startup loader as soon as the current page is fully ready.
            window.addEventListener('load', hideLoader, { once: true });
            window.addEventListener('pageshow', hideLoader);

            document.addEventListener('click', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                const link = event.target.closest('a[href]');
                if (!link) {
                    return;
                }

                if (link.hasAttribute('download') || link.target === '_blank') {
                    return;
                }

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                    return;
                }

                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin !== window.location.origin) {
                    return;
                }

                // Business step: show the loader for normal same-site navigation links.
                showLoaderForNavigation();
            });

            document.addEventListener('submit', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                const form = event.target;
                if (!isSelfNavigationForm(form) || !canSubmitWithoutBrowserValidationError(form)) {
                    return;
                }

                // Business step: show the loader only for valid form submits that keep the user in the same browser tab.
                showLoaderForNavigation();
            });

            document.addEventListener('invalid', function () {
                // Business step: close the loader immediately when browser validation stops the submit on the same page.
                hideLoader();
            }, true);

            window.BiogenixPageLoader = {
                show: showLoaderForNavigation,
                hide: hideLoader,
            };
        })();

        function dismissToast(toast) {
            if (!toast) return;
            toast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(function () {
                toast.remove();
            }, 300);
        }

        function toggleSupportForm() {
            const form = document.getElementById('supportTicketForm');
            if (!form) return;

            const isHidden = form.classList.contains('hidden') || form.classList.contains('opacity-0');
            if (isHidden) {
                form.classList.remove('hidden');
                window.requestAnimationFrame(function () {
                    form.classList.remove('translate-y-3', 'scale-95', 'opacity-0', 'pointer-events-none');
                });
                return;
            }

            form.classList.add('translate-y-3', 'scale-95', 'opacity-0', 'pointer-events-none');
            window.setTimeout(function () {
                if (form.classList.contains('opacity-0')) {
                    form.classList.add('hidden');
                }
            }, 300);
        }

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
                var toneMap = {
                    success: ['border-emerald-200', 'bg-emerald-50/95', 'text-emerald-700'],
                    error: ['border-rose-200', 'bg-rose-50/95', 'text-rose-700'],
                    warning: ['border-amber-200', 'bg-amber-50/95', 'text-amber-700'],
                    info: ['border-sky-200', 'bg-sky-50/95', 'text-sky-700']
                };

                var toast = document.createElement('div');
                toast.className = 'pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-lg backdrop-blur transition duration-300';
                (toneMap[type] || toneMap.info).forEach(function (className) {
                    toast.classList.add(className);
                });

                var icon = document.createElement('div');
                icon.innerHTML = iconMap[type] || iconMap.info;

                var text = document.createElement('p');
                text.className = 'min-w-0 flex-1 text-sm font-medium leading-6';
                text.textContent = message;

                var closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-current/70 transition hover:bg-black/5 hover:text-current';
                closeBtn.setAttribute('aria-label', 'Dismiss notification');
                closeBtn.innerHTML = '&times;';
                closeBtn.addEventListener('click', function () {
                    dismissToast(toast);
                });

                toast.appendChild(icon.firstElementChild || document.createElement('span'));
                toast.appendChild(text);
                toast.appendChild(closeBtn);
                container.appendChild(toast);

                window.setTimeout(function () {
                    dismissToast(toast);
                }, duration);
            }
        };

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
    </script>

    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
