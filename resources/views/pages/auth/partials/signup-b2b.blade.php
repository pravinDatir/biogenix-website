<div class="min-h-screen bg-slate-50 flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-3xl">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Create Your Biogenix B2B Account</h1>
            <p class="mt-2 text-sm text-slate-500">Join our network of healthcare professionals and research laboratories.</p>
            
            <div class="mt-4">
                <a href="{{ route('signup') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Looking for Retail Signup? Switch to B2C &rarr;
                </a>
            </div>
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

        <form id="signupForm" method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <input type="hidden" name="user_type" id="userType" value="b2b">
            <!-- Hidden required fields from standard auth since we're adapting the UI -->
            <input type="hidden" name="b2b_type" id="b2bType" value="distributor">

            <!-- Card 1: Company Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center">
                    <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 text-blue-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Company Information</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Company Name</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="Enter company name" value="{{ old('company_name') }}" required>
                        @error('company_name')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Legal Business Name -->
                    <div>
                        <label for="legal_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Legal Business Name</label>
                        <input type="text" name="legal_name" id="legal_name" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="As per PAN/GST" value="{{ old('legal_name') }}">
                    </div>

                    <!-- GST Number -->
                    <div>
                        <label for="gst_number" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">GST Number</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="gst_number" id="gst_number" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 uppercase" placeholder="22AAAAA0000A1Z5">
                    </div>

                    <!-- PAN Number -->
                    <div>
                        <label for="pan_number" class="block text-xs font-semibold text-slate-700 mb-1.5">PAN Number</label>
                        <input type="text" name="pan_number" id="pan_number" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 uppercase" placeholder="ABCDE1234F">
                    </div>

                    <!-- Company Registration -->
                    <div>
                        <label for="reg_number" class="block text-xs font-semibold text-slate-700 mb-1.5">Company Registration Number</label>
                        <input type="text" name="reg_number" id="reg_number" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 uppercase" placeholder="U00000XX0000XX000000">
                    </div>

                    <!-- Year of Establishment -->
                    <div>
                        <label for="established_year" class="block text-xs font-semibold text-slate-700 mb-1.5">Year of Establishment</label>
                        <select id="established_year" name="established_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300">
                            <option value="">Select Year</option>
                            @for ($i = date('Y'); $i >= 1950; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Website -->
                    <div class="md:col-span-2">
                        <label for="website" class="block text-xs font-semibold text-slate-700 mb-1.5">Company Website</label>
                        <input type="url" name="website" id="website" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="https://www.biogenix.com">
                    </div>
                </div>
            </div>

            <!-- Card 2: Contact Person Details + Account Security -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center">
                    <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Contact Person Details & Security</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Contact Person Name</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="John Doe" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label for="designation" class="block text-xs font-semibold text-slate-700 mb-1.5">Designation</label>
                        <input type="text" name="designation" id="designation" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="e.g. Purchase Manager">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Email Address</span> <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="john.doe@company.com" autocomplete="username" value="{{ old('email') }}" required>
                        @error('email')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Mobile Number</span> <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-sm">
                                +91
                            </span>
                            <input type="text" name="phone" id="phone" class="w-full rounded-r-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="9876543210" value="{{ old('phone') }}" required maxlength="10">
                        </div>
                        @error('phone')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Alt Phone -->
                    <div>
                        <label for="alt_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Alternate Phone</label>
                        <input type="text" name="alt_phone" id="alt_phone" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="022-12345678">
                    </div>

                    <div class="md:col-span-2 pt-2"><hr class="border-slate-100"></div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Account Password</span> <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="Min. 8 characters" required>
                        @error('password')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Confirm Password</span> <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="Re-enter password" required>
                        @error('password_confirmation')<p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Card 3: Business Address -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center">
                    <div class="h-8 w-8 rounded-lg bg-teal-100 flex items-center justify-center mr-3 text-teal-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Business Address</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Address 1 -->
                    <div class="md:col-span-2">
                        <label for="address_1" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Billing Address Line 1</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="address_1" id="address_1" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="Suite, Building, Street">
                    </div>

                    <!-- Address 2 -->
                    <div class="md:col-span-2">
                        <label for="address_2" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Billing Address Line 2</span></label>
                        <input type="text" name="address_2" id="address_2" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="Area, Landmark">
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">City</span> <span class="text-red-500">*</span></label>
                        <select id="city" name="city" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300">
                            <option value="">Select City</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Pune">Pune</option>
                        </select>
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">State</span> <span class="text-red-500">*</span></label>
                        <select id="state" name="state" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300">
                            <option value="">Select State</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Karnataka">Karnataka</option>
                        </select>
                    </div>

                    <!-- Pincode -->
                    <div>
                        <label for="pincode" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Pincode</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="pincode" id="pincode" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300" placeholder="400001">
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center"><span class="mr-1">Country</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="country" id="country" class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-600 cursor-not-allowed" value="India" readonly>
                    </div>
                </div>
            </div>

            <!-- Action Section -->
            <div class="px-4 text-center max-w-2xl mx-auto">
                <div class="flex items-start justify-center mb-6 text-left">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500" required>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-slate-600">
                            I agree to Biogenix's <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">Privacy Policy</a>. I confirm that the information provided is accurate and legitimate.
                        </label>
                    </div>
                </div>

                <button type="submit" id="b2bSubmitBtn" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 hover:shadow-blue-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                    Complete Registration
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
                
                <p class="mt-4 text-xs text-slate-500">Registration applications are typically reviewed within 24-48 business hours.</p>
            </div>
        </form>
    </div>
</div>
