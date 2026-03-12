@php
    $shellClass = 'mx-auto flex max-w-5xl flex-col items-center px-4 sm:px-6 lg:px-8';
    $cardShellClass = 'w-full max-w-4xl';
    $panelClass = 'mb-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm';
    $sectionHeaderClass = 'flex items-center gap-3 border-b border-slate-200 px-6 py-5';
    $sectionIconClass = 'flex h-9 w-9 items-center justify-center rounded-2xl bg-primary-50 text-primary-700';
    $panelTitleClass = 'text-lg font-semibold text-slate-950';
    $inputClass = 'block min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10';
    $buttonClass = 'inline-flex min-h-11 items-center justify-center rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/20';
    $linkClass = 'text-sm font-semibold text-primary-700 transition hover:text-primary-800';
    $checkboxClass = 'h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500/20';
@endphp

<div class="{{ $shellClass }}">
    <div class="{{ $cardShellClass }}">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-slate-950">Create Your Biogenix B2B Account</h1>
            <p class="mx-auto mt-3 max-w-2xl text-base leading-8 text-slate-600">Join our network of healthcare professionals and research laboratories.</p>

            <div class="mt-4">
                <a href="{{ route('signup') }}" class="{{ $linkClass }}">
                    Looking for Retail Signup? Switch to B2C &rarr;
                </a>
            </div>
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

        <form id="signupForm" method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <input type="hidden" name="user_type" id="userType" value="b2b">
            <input type="hidden" name="b2b_type" id="b2bType" value="distributor">

            <div class="{{ $panelClass }}">
                <div class="{{ $sectionHeaderClass }}">
                    <div class="{{ $sectionIconClass }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h2 class="{{ $panelTitleClass }}">Company Information</h2>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                    <div>
                        <label for="company_name" class="mb-1.5 block text-xs font-semibold text-slate-700">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" class="{{ $inputClass }}" placeholder="Enter company name" value="{{ old('company_name') }}" required>
                        @error('company_name')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="legal_name" class="mb-1.5 block text-xs font-semibold text-slate-700">Legal Business Name</label>
                        <input type="text" name="legal_name" id="legal_name" class="{{ $inputClass }}" placeholder="As per PAN/GST" value="{{ old('legal_name') }}">
                    </div>

                    <div>
                        <label for="gst_number" class="mb-1.5 block text-xs font-semibold text-slate-700">GST Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="gst_number" id="gst_number" class="{{ $inputClass }} uppercase" placeholder="22AAAAA0000A1Z5">
                    </div>

                    <div>
                        <label for="pan_number" class="mb-1.5 block text-xs font-semibold text-slate-700">PAN Number</label>
                        <input type="text" name="pan_number" id="pan_number" class="{{ $inputClass }} uppercase" placeholder="ABCDE1234F">
                    </div>

                    <div>
                        <label for="reg_number" class="mb-1.5 block text-xs font-semibold text-slate-700">Company Registration Number</label>
                        <input type="text" name="reg_number" id="reg_number" class="{{ $inputClass }} uppercase" placeholder="U00000XX0000XX000000">
                    </div>

                    <div>
                        <label for="established_year" class="mb-1.5 block text-xs font-semibold text-slate-700">Year of Establishment</label>
                        <select id="established_year" name="established_year" class="{{ $inputClass }}">
                            <option value="">Select Year</option>
                            @for ($i = date('Y'); $i >= 1950; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="website" class="mb-1.5 block text-xs font-semibold text-slate-700">Company Website</label>
                        <input type="url" name="website" id="website" class="{{ $inputClass }}" placeholder="https://www.biogenix.com">
                    </div>
                </div>
            </div>

            <div class="{{ $panelClass }}">
                <div class="{{ $sectionHeaderClass }}">
                    <div class="{{ $sectionIconClass }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h2 class="{{ $panelTitleClass }}">Contact Person Details & Security</h2>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="name" class="mb-1.5 block text-xs font-semibold text-slate-700">Contact Person Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" class="{{ $inputClass }}" placeholder="John Doe" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="designation" class="mb-1.5 block text-xs font-semibold text-slate-700">Designation</label>
                        <input type="text" name="designation" id="designation" class="{{ $inputClass }}" placeholder="e.g. Purchase Manager">
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-semibold text-slate-700">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" class="{{ $inputClass }}" placeholder="john.doe@company.com" autocomplete="username" value="{{ old('email') }}" required>
                        @error('email')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-semibold text-slate-700">Mobile Number <span class="text-rose-500">*</span></label>
                        <div class="flex">
                            <span class="inline-flex min-h-11 items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-100 px-3 text-sm text-slate-500">+91</span>
                            <input type="text" name="phone" id="phone" class="{{ $inputClass }} rounded-l-none" placeholder="9876543210" value="{{ old('phone') }}" required maxlength="10">
                        </div>
                        @error('phone')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="alt_phone" class="mb-1.5 block text-xs font-semibold text-slate-700">Alternate Phone</label>
                        <input type="text" name="alt_phone" id="alt_phone" class="{{ $inputClass }}" placeholder="022-12345678">
                    </div>

                    <div class="md:col-span-2 pt-2"><hr class="border-slate-100"></div>

                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-semibold text-slate-700">Account Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="password" class="{{ $inputClass }}" placeholder="Min. 8 characters" required>
                        @error('password')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-slate-700">Confirm Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="{{ $inputClass }}" placeholder="Re-enter password" required>
                        @error('password_confirmation')<p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="{{ $panelClass }}">
                <div class="{{ $sectionHeaderClass }}">
                    <div class="{{ $sectionIconClass }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h2 class="{{ $panelTitleClass }}">Business Address</h2>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="address_1" class="mb-1.5 block text-xs font-semibold text-slate-700">Billing Address Line 1 <span class="text-rose-500">*</span></label>
                        <input type="text" name="address_1" id="address_1" class="{{ $inputClass }}" placeholder="Suite, Building, Street">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address_2" class="mb-1.5 block text-xs font-semibold text-slate-700">Billing Address Line 2</label>
                        <input type="text" name="address_2" id="address_2" class="{{ $inputClass }}" placeholder="Area, Landmark">
                    </div>

                    <div>
                        <label for="city" class="mb-1.5 block text-xs font-semibold text-slate-700">City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" class="{{ $inputClass }}">
                            <option value="">Select City</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Pune">Pune</option>
                        </select>
                    </div>

                    <div>
                        <label for="state" class="mb-1.5 block text-xs font-semibold text-slate-700">State <span class="text-rose-500">*</span></label>
                        <select id="state" name="state" class="{{ $inputClass }}">
                            <option value="">Select State</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Karnataka">Karnataka</option>
                        </select>
                    </div>

                    <div>
                        <label for="pincode" class="mb-1.5 block text-xs font-semibold text-slate-700">Pincode <span class="text-rose-500">*</span></label>
                        <input type="text" name="pincode" id="pincode" class="{{ $inputClass }}" placeholder="400001">
                    </div>

                    <div>
                        <label for="country" class="mb-1.5 block text-xs font-semibold text-slate-700">Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" id="country" class="{{ $inputClass }} cursor-not-allowed bg-slate-100 text-slate-600" value="India" readonly>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-2xl px-4 text-center">
                <div class="mb-6 flex items-start justify-center text-left">
                    <div class="flex h-5 items-center">
                        <input id="terms" name="terms" type="checkbox" class="{{ $checkboxClass }}" required>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-slate-600">
                            I agree to Biogenix's <a href="{{ route('terms') }}" class="{{ $linkClass }}">Terms of Service</a> and <a href="{{ route('privacy') }}" class="{{ $linkClass }}">Privacy Policy</a>. I confirm that the information provided is accurate and legitimate.
                        </label>
                    </div>
                </div>

                <button type="submit" id="b2bSubmitBtn" class="{{ $buttonClass }} w-full sm:w-auto">
                    Complete Registration
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>

                <p class="mt-4 text-xs text-slate-500">Registration applications are typically reviewed within 24-48 business hours.</p>
            </div>
        </form>
    </div>
</div>
