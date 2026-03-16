@extends('adminPanel.layout')

@section('title', 'Order Management - Biogenix Admin')

@section('admin_content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-[13px] text-slate-500 font-medium mb-2">
        <a href="{{ route('adminPanel.dashboard') }}" class="hover:text-slate-900 transition flex items-center gap-1.5">
            Admin
        </a>
        <span class="mx-2 text-slate-300">/</span>
        <span class="text-slate-900 font-semibold">Order Management</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight">Order Management</h1>
            <p class="text-sm text-slate-500 mt-1">Review and track medical supply chain operations across all regions.</p>
        </div>
    </div>

    <!-- Main Card container matching Dashboard specifications -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 overflow-hidden flex flex-col relative">
        
        <!-- Filter Bar -->
        <div class="px-5 lg:px-6 py-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Search -->
            <div class="relative w-full lg:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search Order ID, Client, or SKU..." class="w-full bg-[#f8fafc] border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f] transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
            </div>

            <!-- Status Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 scrollbar-hide">
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-[#091b3f] text-white">All Orders</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Pending</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Processing</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Dispatched</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Delivered</a>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Cancelled</a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Order ID</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Customer Name</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Amount</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Payment Status</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Fulfillment</th>
                        <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-[#0f172a]">#ORD-99281</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-900">Mount Sinai Hospital</span>
                                <span class="text-[12px] font-medium text-slate-400">New York, NY</span>
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-semibold text-slate-600">Oct 24, 2023</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-slate-900">$1,240.00</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-md">Paid</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#eff6ff] text-[#3b82f6] text-[11px] font-bold rounded-full">Processing</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Download Invoice">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit Order">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-[#0f172a]">#ORD-99280</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-900">Genotech Labs</span>
                                <span class="text-[12px] font-medium text-slate-400">San Francisco, CA</span>
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-semibold text-slate-600">Oct 23, 2023</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-slate-900">$850.00</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#fffbeb] text-[#d97706] text-[11px] font-bold rounded-md">Pending</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#f1f5f9] text-[#64748b] text-[11px] font-bold rounded-full">Hold</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Download Invoice">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit Order">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-[#0f172a]">#ORD-99279</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-900">Dr. Aris Thorne</span>
                                <span class="text-[12px] font-medium text-slate-400">Private Practice</span>
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-semibold text-slate-600">Oct 23, 2023</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-slate-900">$2,100.00</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-md">Paid</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#f5f3ff] text-[#7c3aed] text-[11px] font-bold rounded-full">Dispatched</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Download Invoice">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit Order">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-[#0f172a]">#ORD-99278</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-900">BioHealth Clinic</span>
                                <span class="text-[12px] font-medium text-slate-400">London, UK</span>
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-semibold text-slate-600">Oct 22, 2023</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-slate-900">$450.00</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#fff1f2] text-[#e11d48] text-[11px] font-bold rounded-md">Refunded</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#f1f5f9] text-[#64748b] text-[11px] font-bold rounded-full">Cancelled</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.943 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Download Invoice">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit Order">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-[#0f172a]">#ORD-99277</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-900">St. Jude Research</span>
                                <span class="text-[12px] font-medium text-slate-400">Memphis, TN</span>
                            </div>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-semibold text-slate-600">Oct 21, 2023</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="text-[13px] font-bold text-slate-900">$3,200.00</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-md">Paid</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">Delivered</span>
                        </td>
                        <td class="px-5 lg:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Download Invoice">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit Order">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 lg:px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[13px] text-slate-500 font-medium">
                Showing 1-5 of 48 results
            </p>
            <div class="flex items-center gap-2">
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <div class="flex font-semibold text-[13px]">
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-[#091b3f] text-white">1</button>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200">2</button>
                    <button class="h-9 w-9 flex items-center justify-center rounded bg-white text-slate-600 hover:bg-slate-50 transition border border-transparent hover:border-slate-200">3</button>
                </div>
                <button class="h-9 w-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 bg-white hover:bg-slate-50 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

    </div>

</div>

@endsection
