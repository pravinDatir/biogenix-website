@php
    $panelClass = 'space-y-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
@endphp

<div class="{{ $panelClass }}">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Personal Information</h3>
            <p class="text-sm text-slate-500">Keep your contact information up to date.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Full Name</label>
            <input class="{{ $inputClass }}" value="Prakhar Kapoor" placeholder="Full name">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Mobile Number</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">+1</span>
                <input class="{{ $inputClass }} pl-12" value="(555) 012-3456" placeholder="Mobile number">
            </div>
        </div>
        <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Email Address</label>
            <input class="{{ $inputClass }}" value="prakhar@example.com" placeholder="Email">
            <p class="mt-1 text-xs text-slate-500">This email will be used for account notifications and security updates.</p>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-primary-100 bg-primary-50/50 p-5 text-sm text-slate-700 shadow-none">
    <div class="flex items-start gap-3">
        <svg class="mt-1 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 9v2m0 6a9 9 0 110-18 9 9 0 010 18z" /></svg>
        <div>
            <p class="font-semibold text-primary-800">Verification Required</p>
            <p class="mt-1 leading-6">Changing your email will require re-verification. We'll send a confirmation link to your new address.</p>
        </div>
    </div>
</div>
