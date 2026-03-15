@extends('adminPanel.layout')

@section('title', 'Product Management - Biogenix')

@section('admin_content')
            
            <!-- Welcome Header -->
            <div class="mb-4 flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">Products</h2>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Manage your inventory and product listings</p>
                </div>
                
                <button class="bg-[#091b3f] hover:bg-[#112347] transition text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-[#091b3f]/20 flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 overflow-hidden mt-6 pb-2">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#f8fafc] border-b border-slate-100">
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-[#1e293b]">Product</th>
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-[#1e293b]">Category</th>
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-[#1e293b]">Price</th>
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-[#1e293b]">Stock</th>
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-[#1e293b]">Status</th>
                                <th class="px-5 lg:px-7 py-4 lg:py-5 text-[12px] font-bold text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/80 text-[13px] font-semibold text-slate-700">
                            
                            <!-- Item 1 -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5 font-semibold text-[#0f172a]">Wireless Headphones</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Electronics</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">$99.00</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">45</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">In Stock</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-slate-700 hover:bg-slate-200 transition"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Item 2 -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5 font-semibold text-[#0f172a]">Leather Wallet</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Accessories</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">$45.00</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">12</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#fefce8] text-[#eab308] text-[11px] font-bold rounded-full">Low Stock</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-slate-700 hover:bg-slate-200 transition"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Item 3 -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5 font-semibold text-[#0f172a]">Ceramic Vase</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Home Decor</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">$32.00</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">0</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#fef2f2] text-[#ef4444] text-[11px] font-bold rounded-full">Out of Stock</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-slate-700 hover:bg-slate-200 transition"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Item 4 -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 lg:px-7 py-4 lg:py-5 font-semibold text-[#0f172a]">Mechanical Keyboard</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#eef2ff] text-[#4f46e5] text-[11px] font-bold rounded-full">Electronics</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">$129.00</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-slate-500">28</td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#ecfdf5] text-[#10b981] text-[11px] font-bold rounded-full">In Stock</span>
                                </td>
                                <td class="px-5 lg:px-7 py-4 lg:py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-slate-700 hover:bg-slate-200 transition"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg></button>
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#f1f5f9] text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
@endsection
