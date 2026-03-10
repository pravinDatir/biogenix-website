<div class="page-shell">
    <section class="mx-auto w-full max-w-6xl py-4 md:py-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <aside class="hidden overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:col-span-4 lg:flex lg:flex-col">
                <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700">
                    Join Biogenix
                </span>
                <h2 class="mt-5 text-3xl font-bold leading-tight text-slate-900">Create your professional account.</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Register as distributor, lab, hospital, institution, or retail customer to access streamlined diagnostics workflows.
                </p>

                <div class="mt-8 space-y-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm font-semibold text-slate-900">1. Submit details</p>
                        <p class="mt-1 text-xs text-slate-600">Use your business or professional contact information.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm font-semibold text-slate-900">2. Admin approval</p>
                        <p class="mt-1 text-xs text-slate-600">Your account gets activated after verification.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm font-semibold text-slate-900">3. Start ordering</p>
                        <p class="mt-1 text-xs text-slate-600">Track quotes, products, and support in one dashboard.</p>
                    </div>
                </div>
            </aside>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-8 lg:p-8">
                <div class="mx-auto w-full max-w-3xl">
                    <h1 class="ui-page-title">Signup / Registration</h1>
                    <p class="ui-small mt-2">Account will be activated after admin approval.</p>

                    @if (session('success'))
                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="signupForm" method="POST" action="{{ route('register') }}" class="mt-6 grid grid-cols-1 gap-x-4 gap-y-2 md:grid-cols-2 md:gap-y-3 [&_.form-group]:mb-0">
                        @csrf

                        <div class="form-group md:col-span-2">
                            <label for="customerType">Customer Type</label>
                            <select id="customerType" class="form-control" required>
                                <option value="retail" @selected(old('user_type') === 'b2c')>Retail</option>
                                <option value="distributor" @selected(old('b2b_type') === 'distributor')>Distributor</option>
                                <option value="lab" @selected(old('b2b_type') === 'lab')>Lab</option>
                                <option value="hospital" @selected(old('b2b_type') === 'hospital')>Hospital</option>
                                <option value="institution" @selected(old('b2b_type') === 'institution')>Institution</option>
                            </select>
                            <span class="error"></span>
                        </div>

                        <input type="hidden" name="user_type" id="userType" value="{{ old('user_type', 'b2c') }}">
                        <input type="hidden" name="b2b_type" id="b2bType" value="{{ old('b2b_type', '') }}">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') border-red-500 ring-1 ring-red-100 @enderror"
                                value="{{ old('name') }}"
                                placeholder="Full name"
                                required
                            >
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') border-red-500 ring-1 ring-red-100 @enderror"
                                value="{{ old('email') }}"
                                placeholder="you@company.com"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                class="form-control @error('phone') border-red-500 ring-1 ring-red-100 @enderror"
                                maxlength="10"
                                value="{{ old('phone') }}"
                                placeholder="10-digit mobile number"
                                inputmode="numeric"
                                required
                            >
                            @error('phone')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="company_name">Organization</label>
                            <input
                                type="text"
                                name="company_name"
                                id="company_name"
                                class="form-control @error('company_name') border-red-500 ring-1 ring-red-100 @enderror"
                                value="{{ old('company_name') }}"
                                placeholder="Organization name (if applicable)"
                            >
                            @error('company_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') border-red-500 ring-1 ring-red-100 @enderror"
                                minlength="8"
                                placeholder="Minimum 8 characters"
                                required
                            >
                            @error('password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control @error('password_confirmation') border-red-500 ring-1 ring-red-100 @enderror"
                                minlength="8"
                                placeholder="Re-enter password"
                                required
                            >
                            @error('password_confirmation')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <span class="error"></span>
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-3 md:col-span-2">
                            <button type="submit" id="signupSubmitBtn" class="btn btn-primary">Create Account</button>
                            <a href="{{ route('login') }}" class="btn secondary">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerType = document.getElementById('customerType');
        const userType = document.getElementById('userType');
        const b2bType = document.getElementById('b2bType');
        const orgField = document.getElementById('company_name');

        function syncType() {
            const selected = customerType.value;
            if (selected === 'retail') {
                userType.value = 'b2c';
                b2bType.value = '';
                orgField.required = false;
            } else {
                userType.value = 'b2b';
                b2bType.value = selected === 'institution' ? 'hospital' : selected;
                orgField.required = true;
            }
        }

        customerType.addEventListener('change', syncType);
        syncType();

        const signupForm = document.getElementById('signupForm');
        const signupSubmitBtn = document.getElementById('signupSubmitBtn');

        if (signupForm && signupSubmitBtn) {
            signupForm.addEventListener('submit', function () {
                signupSubmitBtn.disabled = true;
                signupSubmitBtn.classList.add('is-loading');
                signupSubmitBtn.setAttribute('aria-disabled', 'true');
            });
        }
    });
</script>
@endpush
