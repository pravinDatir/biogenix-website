@php
    $requestedType = request('user_type', request('portal'));
    $portal = $requestedType === 'b2b' ? 'b2b' : 'b2c';

    $views = [
        'b2c' => [
            'panelBadge' => 'BIOGENIX ACCESS',
            'panelTitle' => 'Secure diagnostic commerce for modern healthcare teams.',
            'panelCopy' => 'Sign in to continue with catalog access, quotation workflows, and account-level operations based on your role.',
            'detailBadge' => 'B2C Access',
            'detailTitle' => 'Retail Buyer Login',
            'detailCopy' => 'Use your customer account to browse catalog, generate PI for self, and manage your own orders and support history.',
            'detailCard' => 'border border-emerald-400/30 bg-emerald-900/40 backdrop-blur-md',
            'detailBadgeClass' => 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/30',
            'detailDotClass' => 'bg-emerald-400',
            'detailItems' => [
                'Retail context with personal scope',
                'Manage own orders and shipments',
                'Generate Proforma Invoices instantly',
            ],
            'detailNote' => null,
            'submitLabel' => 'Login to B2C Account',
            'bgGradient' => 'from-emerald-900 via-slate-900 to-slate-950',
            'activeButtonClass' => 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30 border-emerald-500',
        ],
        'b2b' => [
            'panelBadge' => 'BIOGENIX ACCESS',
            'panelTitle' => 'Secure diagnostic commerce for modern healthcare teams.',
            'panelCopy' => 'Sign in to continue with catalog access, quotation workflows, and account-level operations based on your role.',
            'detailBadge' => 'B2B Access',
            'detailTitle' => 'Business Account Login',
            'detailCopy' => 'Use your distributor, lab, hospital, or institutional account for customer-specific pricing and business workflows.',
            'detailCard' => 'border border-blue-400/30 bg-blue-900/40 backdrop-blur-md',
            'detailBadgeClass' => 'bg-blue-500/20 text-blue-100 border border-blue-400/30',
            'detailDotClass' => 'bg-blue-400',
            'detailItems' => [
                'Customer-specific business pricing',
                'Approval-aware ordering pipelines',
                'Access limited to assigned company visibility',
            ],
            'detailNote' => 'New B2B registrations require admin approval before first login.',
            'submitLabel' => 'Login to B2B Workspace',
            'bgGradient' => 'from-blue-900 via-slate-900 to-slate-950',
            'activeButtonClass' => 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 border-blue-500',
        ],
    ];

    $view = $views[$portal];
@endphp

<div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 py-12">
    <section class="w-full max-w-6xl">
        <div class="rounded-[2.5rem] bg-white shadow-2xl overflow-hidden grid grid-cols-1 xl:grid-cols-[1fr_1.1fr] border border-slate-100">
            
            <!-- Left Panel (Dynamic Gradient) -->
            <aside class="relative overflow-hidden bg-gradient-to-br {{ $view['bgGradient'] }} p-10 lg:p-14 text-white flex flex-col justify-between hidden xl:flex">
                <!-- Abstract decorations -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-black/20 blur-3xl"></div>
                
                <div class="relative z-10">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider border border-white/20 bg-white/10 backdrop-blur-md text-white mb-6">
                        {{ $view['panelBadge'] }}
                    </span>

                    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight mb-6">
                        {{ $view['panelTitle'] }}
                    </h1>

                    <p class="text-lg text-slate-300 max-w-lg leading-relaxed">
                        {{ $view['panelCopy'] }}
                    </p>
                </div>

                <!-- Detail Glass Card -->
                <div class="relative z-10 mt-12 rounded-[2rem] p-8 {{ $view['detailCard'] }} shadow-2xl">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $view['detailBadgeClass'] }} mb-4">
                        {{ $view['detailBadge'] }}
                    </span>

                    <h2 class="text-2xl font-bold text-white mb-3">
                        {{ $view['detailTitle'] }}
                    </h2>

                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        {{ $view['detailCopy'] }}
                    </p>

                    <ul class="space-y-3 text-sm text-slate-200">
                        @foreach ($view['detailItems'] as $item)
                            <li class="flex items-start">
                                <span class="mt-1.5 mr-3 h-2 w-2 shrink-0 rounded-full {{ $view['detailDotClass'] }}"></span>
                                <span class="leading-relaxed">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($view['detailNote'])
                        <div class="mt-6 rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm font-medium text-amber-200 backdrop-blur-sm flex items-start">
                            <svg class="h-5 w-5 mr-2 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ $view['detailNote'] }}
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Right Panel (Form) -->
            <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white relative">
                 <!-- Mobile Only Header -->
                 <div class="xl:hidden mb-8">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider border border-blue-200 bg-blue-50 text-blue-700 mb-4">
                        {{ $view['panelBadge'] }}
                    </span>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $view['panelTitle'] }}</h1>
                    <p class="text-sm text-slate-600">{{ $view['panelCopy'] }}</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome back</h2>
                    <p class="mt-2 text-slate-500">Choose your access type and login with your registered account.</p>
                </div>

                <!-- Portal Switcher -->
                <div class="flex rounded-xl bg-slate-100 p-1.5 mb-8 border border-slate-200">
                    <a
                        href="{{ route('login', ['user_type' => 'b2c']) }}"
                        class="flex-1 text-center py-2.5 rounded-lg text-sm font-bold transition-all duration-200 {{ $portal === 'b2c' ? $views['b2c']['activeButtonClass'] : 'text-slate-600 hover:text-slate-900 hover:bg-white/50 border border-transparent' }}"
                    >
                        B2C Retail Login
                    </a>
                    <a
                        href="{{ route('login', ['user_type' => 'b2b']) }}"
                        class="flex-1 text-center py-2.5 rounded-lg text-sm font-bold transition-all duration-200 {{ $portal === 'b2b' ? $views['b2b']['activeButtonClass'] : 'text-slate-600 hover:text-slate-900 hover:bg-white/50 border border-transparent' }}"
                    >
                        B2B Business Login
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 flex items-start">
                        <svg class="h-5 w-5 mr-2 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 flex items-start">
                        <svg class="h-5 w-5 mr-2 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                    @csrf
                    <input type="hidden" name="user_type" value="{{ $portal }}">

                    <div>
                        <label for="loginEmail" class="block text-sm font-semibold text-slate-700 mb-2">Email Address or ID</label>
                        <input
                            type="email"
                            name="email"
                            id="loginEmail"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                            placeholder="you@company.com"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="loginPassword" class="block text-sm font-semibold text-slate-700">Password</label>
                            <a href="{{ route('forgot.password') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="loginPassword"
                                class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-12 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition-colors outline-none focus:ring-2 focus:ring-blue-500/50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            id="rememberCheck"
                            value="1"
                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer"
                            @checked(old('remember'))
                        >
                        <label for="rememberCheck" class="ml-3 block text-sm text-slate-600 cursor-pointer select-none">
                            Remember me on this device
                        </label>
                    </div>

                    <button type="submit" id="loginSubmitBtn" class="flex w-full items-center justify-center rounded-xl py-4 pt-4 px-6 font-bold text-white shadow-lg transition-all border {{ $portal === 'b2c' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30 hover:shadow-emerald-500/40 border-emerald-500 flex' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 border-blue-500' }}">
                        {{ $view['submitLabel'] }}
                    </button>
                </form>

                <p class="mt-8 text-center text-slate-600">
                    New here?
                    <a href="{{ $portal === 'b2b' ? route('b2b.signup') : route('signup') }}" class="font-bold text-blue-600 hover:text-blue-800 transition-colors underline decoration-blue-200 underline-offset-4 hover:decoration-blue-600">Create an account</a>
                </p>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof setupPasswordToggle === 'function') {
            setupPasswordToggle('loginPassword', 'togglePassword');
        }

        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');

        if (loginForm && loginSubmitBtn) {
            loginForm.addEventListener('submit', function () {
                loginSubmitBtn.disabled = true;
                loginSubmitBtn.classList.add('is-loading');
                loginSubmitBtn.setAttribute('aria-disabled', 'true');
            });
        }
    });
</script>
@endpush
