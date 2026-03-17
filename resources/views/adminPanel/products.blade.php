@extends('adminPanel.layout')

@section('title', 'Product Management - Biogenix')

@section('admin_content')
            
            <!-- Breadcrumb -->
            <nav class="flex text-[13px] text-slate-500 font-medium mb-2">
                <a href="{{ route('adminPanel.dashboard') }}" class="ajax-link hover:text-slate-900 transition flex items-center gap-1.5">
                    Admin
                </a>
                <span class="mx-2 text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Products</span>
            </nav>

            <!-- Welcome Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight">Products</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your biogenix inventory and product listings.</p>
                </div>
                
                <a href="{{ route('adminPanel.products.create') }}" class="ajax-link bg-[#091b3f] hover:bg-[#112347] transition text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-[#091b3f]/20 flex items-center gap-2 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </a>
            </div>

            <!-- Products Table -->
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
                        <input type="text" placeholder="Search product name, SKU, or category..." class="w-full bg-[#f8fafc] border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f] transition outline-none text-slate-800 placeholder:text-slate-400 font-medium">
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 scrollbar-hide">
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-[#091b3f] text-white">All Products</a>
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Reagents</a>
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Assay Kits</a>
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Lab Equipment</a>
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition">Consumables</a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Product</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">SKU</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Price</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Stock</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-5 lg:px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            
                            <!-- Item 1 -->
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-[#f0f3f8] text-[#091b3f] flex items-center justify-center shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#0f172a]">Molecular Grade Reagent Kit</span>
                                            <span class="text-[11px] font-medium text-slate-400">500 reactions / vial</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">BGX-7700</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Reagents</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-900">$449.00</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">145</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">In Stock</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Item 2 -->
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#0f172a]">Rapid ELISA Assay Kit</span>
                                            <span class="text-[11px] font-medium text-slate-400">96-well format</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">BGX-3310</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Assay Kits</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-900">$289.00</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">12</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#fefce8] text-[#eab308] text-[11px] font-bold rounded-full">Low Stock</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Item 3 -->
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#0f172a]">Cryo-Preservation Serum</span>
                                            <span class="text-[11px] font-medium text-slate-400">50 mL vial</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">BGX-7740</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Reagents</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-900">$549.00</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">0</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#fef2f2] text-[#ef4444] text-[11px] font-bold rounded-full">Out of Stock</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Item 4 -->
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#0f172a]">PCR Thermal Cycler Pro</span>
                                            <span class="text-[11px] font-medium text-slate-400">96-well / gradient</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">BGX-9200</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Lab Equipment</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-900">$8,990.00</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">28</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">In Stock</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Item 5 -->
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 lg:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#0f172a]">Lysis Buffer Solution</span>
                                            <span class="text-[11px] font-medium text-slate-400">1L bottle</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">LYS-9921</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Consumables</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-bold text-slate-900">$78.00</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="text-[13px] font-semibold text-slate-600">320</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">In Stock</span>
                                </td>
                                <td class="px-5 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="p-2 text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-lg transition" title="Edit"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-5 lg:px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[13px] text-slate-500 font-medium">
                        Showing 1-5 of 34 results
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
@endsection
