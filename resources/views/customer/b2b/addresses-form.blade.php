@php
    $panelClass = 'space-y-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] md:p-7';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]';
@endphp

<div class="{{ $panelClass }}">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Address Line 1</label>
            <input class="{{ $inputClass }}" value="42, Science Park, Phase II" placeholder="Address line 1">
        </div>
        <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Address Line 2 (Optional)</label>
            <input class="{{ $inputClass }}" value="Near Research Hub" placeholder="Address line 2">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">City</label>
            <input class="{{ $inputClass }}" value="Bengaluru" placeholder="City">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">State</label>
            <input class="{{ $inputClass }}" value="Karnataka" placeholder="State">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Pincode</label>
            <input class="{{ $inputClass }}" value="560100" placeholder="Pincode">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Country</label>
            <select class="{{ $inputClass }}">
                <option>India</option>
                <option>United States</option>
                <option>United Kingdom</option>
            </select>
        </div>
    </div>

    <div class="space-y-2">
        <label class="flex items-center gap-3 text-sm font-semibold text-slate-800">
            <input type="checkbox" class="form-checkbox h-4 w-4 text-primary-600" checked>
            Set as Default Shipping Address
        </label>
        <label class="flex items-center gap-3 text-sm font-semibold text-slate-800">
            <input type="checkbox" class="form-checkbox h-4 w-4 text-primary-600">
            Set as Default Billing Address
        </label>
    </div>
</div>
