@extends('adminPanel.layout')

@section('title', 'Server Sync Monitor - Biogenix Admin')

@section('admin_content')

<div class="space-y-8 pb-10">

    {{-- ─── Page Header ─── --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#091b3f] tracking-tight">Server Sync Monitor</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time status and control of Biogenix server nodes</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button class="bg-[#091b3f] hover:bg-[#112347] transition text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-[#091b3f]/20 flex items-center gap-2 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Trigger Full System Sync
            </button>
            <button class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-[#091b3f] hover:border-slate-300 transition px-5 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 cursor-pointer shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
                Sync Module
            </button>
        </div>
    </div>

    {{-- ─── Node Status Overview ─── --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-[#091b3f] rounded flex items-center justify-center h-6 w-6">
                <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h2 class="text-base font-extrabold text-[#0f172a]">Node Status Overview</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            {{-- Inventory Sync --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:shadow-lg transition duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-6">
                    <div class="bg-emerald-50 text-emerald-500 h-10 w-10 flex items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Active</span>
                </div>
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 mb-4">Inventory Sync</h3>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Success Rate</span>
                            <span class="text-emerald-500 font-black">99.8%</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Last Sync</span>
                            <span class="text-slate-800 font-bold">2m ago</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders Sync --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:shadow-lg transition duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-6">
                    <div class="bg-emerald-50 text-emerald-500 h-10 w-10 flex items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Active</span>
                </div>
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 mb-4">Orders Sync</h3>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Success Rate</span>
                            <span class="text-emerald-500 font-black">100%</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Last Sync</span>
                            <span class="text-slate-800 font-bold">45s ago</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Sync --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:shadow-lg transition duration-300 cursor-pointer">
                <div class="flex items-start justify-between mb-6">
                    <div class="bg-amber-50 text-amber-500 h-10 w-10 flex items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Warning</span>
                </div>
                <div>
                    <h3 class="text-[17px] font-extrabold text-slate-900 mb-4">Pricing Sync</h3>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Success Rate</span>
                            <span class="text-amber-500 font-black">94.2%</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Last Sync</span>
                            <span class="text-slate-800 font-bold">12m ago</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logistics Sync (Failed) --}}
            <div class="bg-white rounded-2xl p-5 border-y border-r border-slate-100 border-l-4 border-l-rose-500 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:shadow-lg transition duration-300 cursor-pointer relative overflow-hidden">
                <div class="flex items-start justify-between mb-6 relative z-10">
                    <div class="bg-rose-50 text-rose-500 h-10 w-10 flex items-center justify-center rounded-xl">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="bg-rose-50 text-rose-600 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Failed</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[17px] font-extrabold text-slate-900 mb-4">Logistics Sync</h3>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Success Rate</span>
                            <span class="text-rose-500 font-black">0% (Timed out)</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium tracking-wide">Last Sync</span>
                            <span class="text-rose-500 font-bold italic">Manual Req.</span>
                        </div>
                    </div>
                </div>
                <!-- subtle red glow in the background -->
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-rose-50 rounded-full blur-3xl opacity-60"></div>
            </div>
        </div>
    </div>

    {{-- ─── Bottom Layout Form ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Failed Sync Logs --}}
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <div class="bg-rose-500 rounded-full h-5 w-5 flex items-center justify-center shadow-sm shadow-rose-500/30">
                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-extrabold text-[#0f172a]">Failed Sync Logs</h2>
                </div>
                <div class="relative w-full sm:w-64 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search error codes..." class="w-full bg-white border border-slate-200 text-sm font-semibold text-slate-800 rounded-lg pl-9 pr-3 py-2 outline-none focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f] transition placeholder:text-slate-400 shadow-[0_2px_8px_-3px_rgba(0,0,0,0.04)] cursor-text">
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] flex-1 flex flex-col overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap min-w-max">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/4">Timestamp</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/3">Source/Module</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/4">Error Type</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-5 py-4">
                                    <p class="text-[13px] font-semibold text-slate-800">Oct 24,</p>
                                    <p class="text-[13px] font-semibold text-slate-800">14:32:01</p>
                                </td>
                                <td class="px-5 py-4 text-[13px] font-semibold text-slate-700">Logistics / Node-7</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md tracking-wide leading-tight whitespace-normal max-w-[150px]">
                                        504 Gateway Timeout
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 text-[12px] font-extrabold text-slate-500 hover:text-[#091b3f] transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Retry
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-5 py-4">
                                    <p class="text-[13px] font-semibold text-slate-800">Oct 24,</p>
                                    <p class="text-[13px] font-semibold text-slate-800">14:15:22</p>
                                </td>
                                <td class="px-5 py-4 text-[13px] font-semibold text-slate-700">Pricing / DB-Global</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md tracking-wide leading-tight whitespace-normal max-w-[150px]">
                                        Data Validation Error
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 text-[12px] font-extrabold text-slate-500 hover:text-[#091b3f] transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Retry
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-5 py-4">
                                    <p class="text-[13px] font-semibold text-slate-800">Oct 24,</p>
                                    <p class="text-[13px] font-semibold text-slate-800">13:58:45</p>
                                </td>
                                <td class="px-5 py-4 text-[13px] font-semibold text-slate-700">Inventory / Warehouse-02</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md tracking-wide leading-tight whitespace-normal max-w-[150px]">
                                        Handshake Failed
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 text-[12px] font-extrabold text-slate-500 hover:text-[#091b3f] transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Retry
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- View Archive Footer -->
                <div class="mt-auto border-t border-slate-100/80 bg-slate-50/50 p-3">
                    <a href="#" class="block w-full text-center text-[11px] font-black uppercase tracking-widest text-slate-400 hover:text-[#091b3f] hover:bg-slate-100 rounded-md py-2 transition cursor-pointer">
                        View Full Archive
                    </a>
                </div>
            </div>
        </div>

        {{-- Live Activity Feed --}}
        <div class="lg:col-span-5 xl:col-span-4 flex flex-col">
            <div class="flex items-center gap-2 mb-4 h-10">
                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h2 class="text-base font-extrabold text-[#0f172a]">Live Activity Feed</h2>
            </div>
            
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] p-6 flex-1 relative">
                <div class="absolute left-9 top-10 bottom-10 w-px bg-slate-100 hidden sm:block"></div>
                
                <div class="space-y-6">
                    {{-- Feed Item 1 --}}
                    <div class="relative flex gap-4 cursor-pointer group">
                        <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full bg-emerald-400 ring-4 ring-emerald-50 z-10 hidden sm:block"></div>
                        <div class="flex-1 sm:pl-7">
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Just Now</span>
                            <h4 class="text-[13px] font-bold text-slate-900 mt-1">Orders Sync Completed</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-snug">428 records synchronized successfully from North America Cluster.</p>
                        </div>
                    </div>

                    {{-- Feed Item 2 --}}
                    <div class="relative flex gap-4 cursor-pointer group">
                        <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full bg-[#091b3f] ring-4 ring-indigo-50 z-10 hidden sm:block"></div>
                        <div class="flex-1 sm:pl-7">
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">4 mins ago</span>
                            <h4 class="text-[13px] font-bold text-slate-900 mt-1">Manual Trigger Initialized</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-snug">Admin 'S. Thompson' triggered full inventory refresh.</p>
                        </div>
                    </div>

                    {{-- Feed Item 3 --}}
                    <div class="relative flex gap-4 cursor-pointer group">
                        <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full bg-rose-500 ring-4 ring-rose-50 z-10 hidden sm:block hidden sm:block"></div>
                        <div class="flex-1 sm:pl-7">
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">14 mins ago</span>
                            <h4 class="text-[13px] font-bold text-rose-600 mt-1">Connection Interrupted</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-snug">Logistics node lost heartbeat. Retrying in 5 seconds...</p>
                        </div>
                    </div>

                    {{-- Feed Item 4 --}}
                    <div class="relative flex gap-4 cursor-pointer group">
                        <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full bg-emerald-400 ring-4 ring-emerald-50 z-10 hidden sm:block"></div>
                        <div class="flex-1 sm:pl-7">
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">1 hour ago</span>
                            <h4 class="text-[13px] font-bold text-slate-900 mt-1">Security Protocol Update</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-snug">All sync tokens rotated successfully for the next cycle.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
