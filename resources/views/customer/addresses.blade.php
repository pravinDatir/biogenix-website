@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
@endphp

@section('title', 'Edit Address')
@section('customer_active', 'addresses')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="addresses"
        title="Addresses"
        description="Manage your delivery and billing addresses."
    >
        <x-slot:headerActions>
            <button type="button" onclick="toggleAddAddressModal(true)" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#091b3f] px-5 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Address
            </button>
        </x-slot:headerActions>

        @include('customer.'.$portal.'.addresses-form')

        <x-slot:footer>
            <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-[13px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none cursor-pointer">Cancel</button>
            <button type="button" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#091b3f] px-5 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none cursor-pointer">Save Changes</button>
        </x-slot:footer>
    </x-account.workspace>

    {{-- Add Address Modal --}}
    <div 
        id="addAddressModal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
    >
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="toggleAddAddressModal(false)"></div>
        
        {{-- Modal Content --}}
        <div class="relative w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl transition-all grow-0">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 md:px-8">
                <h3 class="text-lg font-bold text-slate-900">Add New Address</h3>
                <button type="button" onclick="toggleAddAddressModal(false)" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('customer.addresses.store') }}" method="POST" class="px-6 py-6 md:px-8 md:py-8">
                @csrf
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[13px] font-semibold text-slate-700">Address Line 1</label>
                        <input name="address_line1" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" placeholder="Enter building, street name" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[13px] font-semibold text-slate-700">Address Line 2 (Optional)</label>
                        <input name="address_line2" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" placeholder="Apartment, suite, etc.">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-[13px] font-semibold text-slate-700">City</label>
                            <input name="city" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" placeholder="City" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[13px] font-semibold text-slate-700">State</label>
                            <input name="state" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" placeholder="State" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[13px] font-semibold text-slate-700">Pincode</label>
                            <input name="pincode" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" placeholder="Zip code" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[13px] font-semibold text-slate-700">Country</label>
                            <select name="country" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-900 outline-none transition focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f]" required>
                                <option value="India">India</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 pt-2">
                        <label class="flex items-center gap-3 text-sm font-semibold text-slate-800 cursor-pointer">
                            <input type="checkbox" name="is_default_shipping" class="h-4 w-4 rounded border-slate-300 text-[#091b3f] focus:ring-[#091b3f]">
                            Set as Default Shipping Address
                        </label>
                        <label class="flex items-center gap-3 text-sm font-semibold text-slate-800 cursor-pointer">
                            <input type="checkbox" name="is_default_billing" class="h-4 w-4 rounded border-slate-300 text-[#091b3f] focus:ring-[#091b3f]">
                            Set as Default Billing Address
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <button type="button" onclick="toggleAddAddressModal(false)" class="h-11 rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50 cursor-pointer">Cancel</button>
                    <button type="submit" class="h-11 rounded-xl bg-[#091b3f] px-6 text-sm font-bold text-white shadow-lg transition hover:bg-slate-800 cursor-pointer">Save Address</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAddAddressModal(show) {
            const modal = document.getElementById('addAddressModal');
            if (!modal) return;
            
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection
