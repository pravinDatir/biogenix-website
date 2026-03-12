@php
    $panelClass = 'space-y-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
@endphp

<div class="{{ $panelClass }}">
    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
        <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M10 14h10M10 18h10M4 14h2m-2 4h2" /></svg>
        Company Information
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Company Name</label>
            <input class="{{ $inputClass }}" value="Biogenix Solutions Pvt Ltd">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Legal Business Name</label>
            <input class="{{ $inputClass }}" value="Biogenix Life Sciences Solutions">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Registration Number</label>
            <input class="{{ $inputClass }}" value="U12345DL2023PTC123456">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">GST Number</label>
            <input class="{{ $inputClass }}" value="07AAAAA0000A1Z5">
        </div>
        <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">PAN Number</label>
            <input class="{{ $inputClass }} max-w-xl" value="ABCDE1234F">
        </div>
    </div>
</div>

<div class="{{ $panelClass }}">
    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
        <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M9 6v12m6-12v12M4 12h16" /></svg>
        Contact Person Information
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Contact Person Name</label>
            <input class="{{ $inputClass }}" value="Prakhar Kapoor">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Designation</label>
            <input class="{{ $inputClass }}" value="Supply Chain Manager">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Email Address</label>
            <input class="{{ $inputClass }}" value="prakhar@biogenix.com">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Mobile Number</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">+91</span>
                <input class="{{ $inputClass }} pl-12" value="+91 98765 43210">
            </div>
        </div>
    </div>
</div>
