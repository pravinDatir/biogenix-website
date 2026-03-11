@php
    $portal = 'b2c';
    $selectedCustomerType = 'retail';
    $customerTypeOptions = ['retail' => 'Retail'];

    $view = [
        'leftBadge' => 'JOIN BIOGENIX RETAIL',
        'leftTitle' => 'Create your personal buying account.',
        'leftCopy' => 'Register as a retail customer to access MRP-oriented catalog visibility, self quotations, and personal order workflows.',
        'infoBadge' => 'B2C Signup',
        'infoTitle' => 'Retail Customer Onboarding',
        'infoCopy' => 'Create your personal healthcare buying account with MRP-oriented catalog and self-service order workflows.',
        'infoCard' => 'border border-emerald-400/30 bg-emerald-900/40 backdrop-blur-md',
        'infoBadgeClass' => 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/30',
        'infoDotClass' => 'bg-emerald-400',
        'infoItems' => [
            'Retail access and self profile scope',
            'Own quotations, own orders, own support',
        ],
        'notice' => null,
        'bgGradient' => 'from-emerald-900 via-slate-900 to-slate-950',
    ];
@endphp

<div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 py-12">
    <section class="w-full max-w-6xl">
        <div class="rounded-[2.5rem] bg-white shadow-2xl overflow-hidden grid grid-cols-1 xl:grid-cols-[0.9fr_1.1fr] border border-slate-100">
            
            <!-- Left Panel (Dynamic Gradient) -->
            <aside class="relative overflow-hidden bg-gradient-to-br {{ $view['bgGradient'] }} p-10 lg:p-14 text-white flex flex-col justify-between hidden xl:flex">
                <!-- Abstract decorations -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-black/20 blur-3xl"></div>
                
                <div class="relative z-10">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider border border-white/20 bg-white/10 backdrop-blur-md text-white mb-6">
                        {{ $view['leftBadge'] }}
                    </span>

                    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight mb-6">
                        {{ $view['leftTitle'] }}
                    </h1>

                    <p class="text-lg text-slate-300 max-w-lg leading-relaxed">
                        {{ $view['leftCopy'] }}
                    </p>
                </div>

                <!-- Detail Glass Card -->
                <div class="relative z-10 mt-12 mb-auto rounded-[2rem] p-8 {{ $view['infoCard'] }} shadow-2xl">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $view['infoBadgeClass'] }} mb-4">
                        {{ $view['infoBadge'] }}
                    </span>

                    <h2 class="text-2xl font-bold text-white mb-3">
                        {{ $view['infoTitle'] }}
                    </h2>

                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        {{ $view['infoCopy'] }}
                    </p>

                    <ul class="space-y-3 text-sm text-slate-200">
                        @foreach ($view['infoItems'] as $item)
                            <li class="flex items-start">
                                <span class="mt-1.5 mr-3 h-2 w-2 shrink-0 rounded-full {{ $view['infoDotClass'] }}"></span>
                                <span class="leading-relaxed">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($view['notice'])
                        <div class="mt-6 rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm font-medium text-amber-200 backdrop-blur-sm flex items-start">
                            <svg class="h-5 w-5 mr-2 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ $view['notice'] }}
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Right Panel (Form) -->
            <div class="p-8 md:p-12 lg:p-14 flex flex-col justify-center bg-white relative">
                <!-- Mobile Only Header -->
                <div class="xl:hidden mb-8">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider border border-emerald-200 bg-emerald-50 text-emerald-700 mb-4">
                        {{ $view['leftBadge'] }}
                    </span>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $view['leftTitle'] }}</h1>
                    <p class="text-sm text-slate-600">{{ $view['leftCopy'] }}</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Signup / Registration</h2>
                    <p class="mt-2 text-slate-500">Create your account and continue with role-based access.</p>
                </div>

                <!-- B2B Link -->
                <div class="mb-8">
                    <p class="text-sm text-slate-600">
                        Are you a business owner or a healthcare professional? 
                        <a href="{{ route('b2b.signup') }}" class="font-bold text-emerald-600 hover:text-emerald-700 underline underline-offset-2">Register for a B2B Account</a>
                    </p>
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

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 flex items-start">
                        <svg class="h-5 w-5 mr-2 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="signupForm" method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5" novalidate>
                    @csrf

                    <div class="md:col-span-2">
                        <label for="customerType" class="block text-sm font-semibold text-slate-700 mb-2">Customer Type</label>
                        <select id="customerType" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400" required>
                            @foreach ($customerTypeOptions as $value => $label)
                                <option value="{{ $value }}" @selected($portal === 'b2b' ? $selectedCustomerType === $value : $value === 'retail')>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="user_type" id="userType" value="{{ $portal }}">
                    <input type="hidden" name="b2b_type" id="b2bType" value="{{ $portal === 'b2b' ? $selectedCustomerType : '' }}">

                    @if ($portal === 'b2b')
                        <div class="md:col-span-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            B2B registration requires admin approval before login activation.
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                            value="{{ old('name') }}"
                            placeholder="John Doe"
                            required
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                            value="{{ old('email') }}"
                            placeholder="you@company.com"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('phone') border-red-500 ring-2 ring-red-200 @enderror"
                            maxlength="10"
                            value="{{ old('phone') }}"
                            placeholder="10-digit number"
                            inputmode="numeric"
                            required
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-slate-700 mb-2">Organization</label>
                        <input
                            type="text"
                            name="company_name"
                            id="company_name"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('company_name') border-red-500 ring-2 ring-red-200 @enderror"
                            value="{{ old('company_name') }}"
                            placeholder="Company (if applicable)"
                            @required($portal === 'b2b')
                        >
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                            minlength="8"
                            placeholder="Min. 8 characters"
                            required
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-blue-400 @error('password_confirmation') border-red-500 ring-2 ring-red-200 @enderror"
                            minlength="8"
                            placeholder="Re-enter password"
                            required
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4 md:col-span-2">
                        <button type="submit" id="signupSubmitBtn" class="flex-1 rounded-xl py-3.5 px-6 font-bold text-white shadow-lg transition-all border {{ $portal === 'b2c' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30 hover:shadow-emerald-500/40 border-emerald-500' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 border-blue-500' }}">
                            Create Account
                        </button>
                        <a href="{{ route('login', ['user_type' => $portal]) }}" class="flex-1 rounded-xl py-3.5 px-6 font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition-all text-center">
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
