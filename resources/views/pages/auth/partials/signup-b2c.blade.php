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
        'infoBadgeClass' => 'border border-primary-400/30 bg-primary-500/20 text-primary-50',
        'infoDotClass' => 'bg-primary-300',
        'infoItems' => [
            'Retail access and self profile scope',
            'Own quotations, own orders, own support',
        ],
        'notice' => null,
        'bgGradient' => 'from-primary-950 via-slate-900 to-slate-950',
    ];

    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $primaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $secondaryButtonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $primaryLinkClass = 'text-sm font-semibold text-primary-700 transition hover:text-primary-800';
@endphp

<div class="flex items-center justify-center">
    <section class="w-full max-w-6xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
        <div class="grid grid-cols-1 xl:grid-cols-[0.9fr_1.1fr]">
            <aside class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br {{ $view['bgGradient'] }} p-10 text-white xl:flex lg:p-14">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-black/20 blur-3xl"></div>

                <div class="relative z-10">
                    <span class="mb-6 inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold tracking-wider text-white backdrop-blur-md">
                        {{ $view['leftBadge'] }}
                    </span>

                    <h1 class="max-w-xl text-4xl font-bold tracking-tight text-white md:text-5xl">{{ $view['leftTitle'] }}</h1>
                    <p class="mt-5 max-w-lg text-base leading-8 text-slate-300">{{ $view['leftCopy'] }}</p>
                </div>

                <div class="relative z-10 mb-auto mt-12 rounded-3xl border border-primary-400/30 bg-primary-950/40 p-8 shadow-2xl backdrop-blur-md">
                    <span class="mb-4 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $view['infoBadgeClass'] }}">
                        {{ $view['infoBadge'] }}
                    </span>

                    <h2 class="mb-3 text-2xl font-bold text-white">{{ $view['infoTitle'] }}</h2>
                    <p class="mb-6 text-sm leading-relaxed text-slate-300">{{ $view['infoCopy'] }}</p>

                    <ul class="space-y-3 text-sm text-slate-200">
                        @foreach ($view['infoItems'] as $item)
                            <li class="flex items-start">
                                <span class="mr-3 mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $view['infoDotClass'] }}"></span>
                                <span class="leading-relaxed">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($view['notice'])
                        <div class="mt-6 flex items-start rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm font-medium text-amber-200 backdrop-blur-sm">
                            <svg class="mr-2 h-5 w-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ $view['notice'] }}
                        </div>
                    @endif
                </div>
            </aside>

            <div class="relative flex flex-col justify-center bg-white p-8 md:p-12 lg:p-14">
                <div class="mb-8 xl:hidden">
                    <span class="mb-4 inline-flex items-center rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">
                        {{ $view['leftBadge'] }}
                    </span>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-950">{{ $view['leftTitle'] }}</h1>
                    <p class="mt-2 text-base leading-8 text-slate-600">{{ $view['leftCopy'] }}</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-4xl font-bold tracking-tight text-slate-950">Signup / Registration</h2>
                    <p class="mt-2 max-w-none text-base leading-8 text-slate-600">Create your account and continue with role-based access.</p>
                </div>

                <div class="mb-8">
                    <p class="text-sm text-slate-600">
                        Are you a business owner or a healthcare professional?
                        <a href="{{ route('b2b.signup') }}" class="{{ $primaryLinkClass }}">Register for a B2B Account</a>
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 flex items-start rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                        <svg class="mr-2 h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-start rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <svg class="mr-2 h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 flex items-start rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <svg class="mr-2 h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <ul class="list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="signupForm" method="POST" action="{{ route('register') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2" novalidate>
                    @csrf

                    <div class="md:col-span-2">
                        <label for="customerType" class="mb-2 block text-sm font-semibold text-slate-700">Customer Type</label>
                        <select id="customerType" class="{{ $inputClass }}" required>
                            @foreach ($customerTypeOptions as $value => $label)
                                <option value="{{ $value }}" @selected($portal === 'b2b' ? $selectedCustomerType === $value : $value === 'retail')>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="user_type" id="userType" value="{{ $portal }}">
                    <input type="hidden" name="b2b_type" id="b2bType" value="{{ $portal === 'b2b' ? $selectedCustomerType : '' }}">

                    @if ($portal === 'b2b')
                        <div class="md:col-span-2 flex items-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            <svg class="mr-2 h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            B2B registration requires admin approval before login activation.
                        </div>
                    @endif

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" name="name" id="name" class="{{ $inputClass }} @error('name') border-rose-500 ring-4 ring-rose-500/10 @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email Address</label>
                        <input type="email" name="email" id="email" class="{{ $inputClass }} @error('email') border-rose-500 ring-4 ring-rose-500/10 @enderror" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" required>
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="{{ $inputClass }} @error('phone') border-rose-500 ring-4 ring-rose-500/10 @enderror" maxlength="10" value="{{ old('phone') }}" placeholder="10-digit number" inputmode="numeric" required>
                        @error('phone')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_name" class="mb-2 block text-sm font-semibold text-slate-700">Organization</label>
                        <input type="text" name="company_name" id="company_name" class="{{ $inputClass }} @error('company_name') border-rose-500 ring-4 ring-rose-500/10 @enderror" value="{{ old('company_name') }}" placeholder="Company (if applicable)" @required($portal === 'b2b')>
                        @error('company_name')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" id="password" class="{{ $inputClass }} @error('password') border-rose-500 ring-4 ring-rose-500/10 @enderror" minlength="8" placeholder="Min. 8 characters" required>
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="{{ $inputClass }} @error('password_confirmation') border-rose-500 ring-4 ring-rose-500/10 @enderror" minlength="8" placeholder="Re-enter password" required>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4 flex flex-col gap-4 md:col-span-2 sm:flex-row sm:items-center">
                        <button type="submit" id="signupSubmitBtn" class="{{ $primaryButtonClass }} flex-1">
                            Create Account
                        </button>
                        <a href="{{ route('login', ['user_type' => $portal]) }}" class="{{ $secondaryButtonClass }} flex-1">
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
