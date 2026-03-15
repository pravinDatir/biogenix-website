@extends('layouts.app')

@section('title', 'Admin Dashboard - Biogenix')

@section('content')
<div class="bg-[#f4f7fb] min-h-screen py-8">
    <div class="container mx-auto max-w-7xl flex gap-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Sidebar Navigation -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <nav class="sticky top-24 space-y-1.5 bg-white p-4 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 pb-6">
                
                <h3 class="px-3 text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 mt-2">Admin Portal</h3>

                <a href="{{ route('adminPanel.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl bg-[#eef1f6] text-[#2c3e66] font-bold text-[13px] transition w-full relative">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#091b3f] rounded-r-md"></div>
                    <svg class="h-5 w-5 text-[#091b3f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Admin Dashboard
                </a>

                @php
                $navLinks = [
                    ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Product Management'],
                    ['icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'title' => 'Pricing Management'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Quotation/ PI'],
                    ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Order Management'],
                    ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Delivery & Logistics'],
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Customer Management'],
                    ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'title' => 'Support Tickets'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Role & Permission'],
                    ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'title' => 'Reviews & Incentives'],
                    ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Sync Monitor'],
                    ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'System Settings'],
                    ['icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'title' => 'Global Settings'],
                ];
                @endphp

                @foreach($navLinks as $link)
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 font-semibold text-[13px] transition hover:bg-slate-50 hover:text-slate-800">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"></path></svg>
                    <span class="leading-tight">{{ $link['title'] }}</span>
                </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Dashboard Content -->
        <main class="flex-1 min-w-0 space-y-6 pb-12">
            
            <!-- Welcome Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h2>
                    <p class="text-sm text-slate-500 mt-1">Welcome back. Here's what's happening in your biogenic supply chain today.</p>
                </div>
                
                <!-- Quick Search -->
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search analytics or orders..." class="w-full bg-white border border-slate-200 shadow-sm text-sm rounded-xl pl-9 pr-4 py-2.5 focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f] transition outline-none text-slate-700 placeholder:text-slate-400">
                </div>
            </div>

            <!-- KPI Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 flex flex-col justify-between h-[130px] lg:h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-[#eef1f6] text-[#091b3f]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 00-2-2m4-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V9z"></path></svg>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md mb-auto mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +12%
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Total Orders</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">1,284</h3>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 flex flex-col justify-between h-[130px] lg:h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md mb-auto mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +5%
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Today's Orders</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">142</h3>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 flex flex-col justify-between h-[130px] lg:h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2v1l2 2m-3 5h3m-3 4h3"></path></svg>
                        </div>
                        <div class="text-[11px] font-semibold text-slate-400 mb-auto mt-2">Current</div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Pending Dispatch</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">48</h3>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 flex flex-col justify-between h-[130px] lg:h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="text-[11px] font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md mb-auto mt-0.5">! Urgent</div>
                    </div>
                    <div class="mt-2">
                        <p class="text-[12px] lg:text-[13px] font-semibold text-slate-500 mb-0.5">Same-day Delivery</p>
                        <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">12</h3>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Snapshot -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 lg:p-7 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 h-auto sm:h-[400px] flex flex-col justify-between relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Revenue Snapshot</h3>
                            <p class="text-[11px] lg:text-xs font-semibold text-slate-400 mt-1">Global performance tracking</p>
                            <div class="mt-4 flex flex-wrap items-center gap-3 lg:gap-4">
                                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#091b3f] tracking-tight">$248,590.00</h2>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md h-fit">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    18.2%
                                </span>
                            </div>
                        </div>
                        <!-- Toggle -->
                        <div class="bg-[#f2f5fa] rounded-lg p-1 flex items-center shadow-inner self-start">
                            <button class="px-3 lg:px-4 py-1.5 text-[11px] font-bold text-slate-500 rounded-md transition hover:text-slate-800">Weekly</button>
                            <button class="px-3 lg:px-4 py-1.5 text-[11px] font-bold text-[#091b3f] bg-white rounded-md shadow-sm">Monthly</button>
                            <button class="px-3 lg:px-4 py-1.5 text-[11px] font-bold text-slate-500 rounded-md transition hover:text-slate-800">Yearly</button>
                        </div>
                    </div>

                    <!-- Fake Chart Area -->
                    <div class="mt-8 flex-1 grid grid-cols-7 gap-2 lg:gap-4 items-end px-1 lg:px-2 pt-10 min-h-[150px]">
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[30%]"></div>
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[45%]"></div>
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[35%]"></div>
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[55%]"></div>
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[40%]"></div>
                        <div class="w-full bg-[#e8eef6] rounded-t-sm h-[60%]"></div>
                        <div class="w-full bg-[#091b3f] rounded-t-sm h-[85%] shadow-[0_0_15px_rgba(9,27,63,0.3)]"></div>
                    </div>
                    
                    <!-- Chart Labels -->
                    <div class="grid grid-cols-7 gap-2 lg:gap-4 mt-4 px-1 lg:px-2 text-center text-[9px] lg:text-[10px] font-bold text-slate-400">
                        <span>MON</span>
                        <span>TUE</span>
                        <span>WED</span>
                        <span>THU</span>
                        <span>FRI</span>
                        <span>SAT</span>
                        <span>SUN</span>
                    </div>
                </div>

                <!-- Fulfillment Distribution -->
                <div class="bg-white rounded-2xl p-6 lg:p-7 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 flex flex-col">
                    <h3 class="text-base font-bold text-slate-900 mb-6">Fulfillment Distribution</h3>
                    
                    <div class="space-y-6 flex-1">
                        <!-- Bar 1 -->
                        <div>
                            <div class="flex justify-between text-[11px] lg:text-[12px] font-semibold mb-2">
                                <span class="text-slate-700">Direct Delivery</span>
                                <span class="text-slate-500">62%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-[#091b3f] h-2 rounded-full" style="width: 62%"></div>
                            </div>
                        </div>

                        <!-- Bar 2 -->
                        <div>
                            <div class="flex justify-between text-[11px] lg:text-[12px] font-semibold mb-2">
                                <span class="text-slate-700">Express Logistics</span>
                                <span class="text-slate-500">28%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 28%"></div>
                            </div>
                        </div>

                        <!-- Bar 3 -->
                        <div>
                            <div class="flex justify-between text-[11px] lg:text-[12px] font-semibold mb-2">
                                <span class="text-slate-700">Courier Partners</span>
                                <span class="text-slate-500">10%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Box -->
                    <div class="mt-8 bg-[#f5f7fc] border border-blue-100 rounded-xl p-4 lg:p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            <span class="text-[10px] lg:text-[11px] font-bold text-[#091b3f] uppercase tracking-wider">Storage Alert</span>
                        </div>
                        <p class="text-[11px] lg:text-[12px] text-[#2c3e66] leading-relaxed mb-3">Warehouse B-4 is reaching 90% capacity for temperature-sensitive biologics. Immediate dispatch recommended.</p>
                        <a href="#" class="text-[11px] lg:text-[12px] font-bold text-[#091b3f] hover:underline underline-offset-2">View Capacity Report</a>
                    </div>
                </div>
            </div>

            <!-- Priority Orders Table -->
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 overflow-hidden mt-6">
                <div class="px-5 lg:px-7 py-5 lg:py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Priority Orders</h3>
                    <a href="#" class="text-[12px] lg:text-[13px] font-bold text-[#091b3f] hover:underline underline-offset-2">View All Orders</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Order ID</th>
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Client</th>
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Biogenic Type</th>
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Value</th>
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-5 lg:px-7 py-3 lg:py-4 text-[9px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/80 text-[12px] lg:text-[13px] font-semibold text-slate-900">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-9012</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">M</div>
                                        MetroLabs Inc.
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Reagent Kit Alpha</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$4,290</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-amber-100/50 text-amber-700 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">Dispatching</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button class="text-slate-400 hover:text-slate-700"><svg class="h-5 w-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg></button>
                                </td>
                            </tr>
                            
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-8994</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">G</div>
                                        GenTech Solutions
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Synthetic Enzyme B2</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$12,800</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100/50 text-blue-700 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">In Transit</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button class="text-slate-400 hover:text-slate-700"><svg class="h-5 w-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5">#BGX-8851</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 lg:h-7 lg:w-7 rounded bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-[10px] lg:text-[11px]">U</div>
                                        University Hospital
                                    </div>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-600 font-medium">Rapid Test Sets</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">$840</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2 py-1 bg-emerald-100/50 text-emerald-700 text-[8px] lg:text-[9px] font-extrabold uppercase tracking-wider rounded">Delivered</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-center">
                                    <button class="text-slate-400 hover:text-slate-700"><svg class="h-5 w-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection
