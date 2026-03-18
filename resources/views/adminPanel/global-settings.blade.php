@extends('adminPanel.layout')

@section('title', 'Global Settings - Biogenix Admin')

@section('admin_content')

<div class="space-y-10 pb-20 relative min-h-[calc(100vh-100px)] flex flex-col">

    {{-- ─── Page Header ─── --}}
    <div class="mb-2">
        <h1 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">General Configuration</h1>
        <p class="text-sm text-slate-500 mt-1">Manage your organization's theme preferences and portal appearance.</p>
    </div>

    {{-- ─── Settings Sections ─── --}}
    <div class="space-y-10 flex-1">
        
        {{-- Theme Mode Section --}}
        <section>
            <div class="flex items-center gap-2 mb-4">
                <svg class="h-5 w-5 text-[#091b3f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                <h2 class="text-[17px] font-extrabold text-[#0f172a]">Theme Mode</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Active Light Mode Card --}}
                <div class="bg-[#f8fafc]/50 border-2 border-[#091b3f] rounded-xl p-6 flex flex-col items-center justify-center gap-3 cursor-pointer shadow-sm relative transition duration-200">
                    <svg class="h-7 w-7 text-[#0f172a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-[13px] font-bold text-[#0f172a]">Light Mode</span>
                </div>

                {{-- Inactive Dark Mode Card --}}
                <div class="bg-white border border-slate-200 hover:border-slate-300 rounded-xl p-6 flex flex-col items-center justify-center gap-3 cursor-pointer shadow-sm transition duration-200 group">
                    <svg class="h-7 w-7 text-slate-700 group-hover:text-slate-900 transition" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                    <span class="text-[13px] font-bold text-slate-700 group-hover:text-slate-900 transition">Dark Mode</span>
                </div>

                {{-- Inactive System Default Card --}}
                <div class="bg-white border border-slate-200 hover:border-slate-300 rounded-xl p-6 flex flex-col items-center justify-center gap-3 cursor-pointer shadow-sm transition duration-200 group">
                    <svg class="h-7 w-7 text-slate-700 group-hover:text-slate-900 transition" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.228l-1.002 1.336A1 1 0 0110.97 18H9.03a1 1 0 01-.8-.4l-1.002-1.336H5a2 2 0 01-2-2V5zm12 8V5H5v8h10z" clip-rule="evenodd" />
                        <path d="M9 10a1 1 0 100-2 1 1 0 000 2z" />
                    </svg>
                    <span class="text-[13px] font-bold text-slate-700 group-hover:text-slate-900 transition">System Default</span>
                </div>
            </div>
        </section>

        {{-- Portal Color Theme Section --}}
        <section>
            <div class="flex items-center gap-2 mb-4">
                <svg class="h-5 w-5 text-[#091b3f] -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <h2 class="text-[17px] font-extrabold text-[#0f172a]">Portal Color Theme</h2>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm overflow-x-auto">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-5">Select a preset palette</p>
                
                <div class="flex flex-nowrap items-center gap-6 sm:gap-8 min-w-max">
                    {{-- Active Biogenix Blue --}}
                    <div class="border border-[#091b3f] rounded-lg p-2.5 flex items-center gap-3 cursor-pointer bg-slate-50/50">
                        <div class="w-7 h-7 rounded-full bg-[#091b3f] ring-2 ring-white shadow-sm flex-shrink-0"></div>
                        <span class="text-[13px] font-bold text-[#091b3f] pr-1">Biogenix Blue</span>
                    </div>

                    {{-- Inactive Forest Green --}}
                    <div class="p-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-50 transition rounded-lg group">
                        <div class="w-7 h-7 rounded-full bg-[#047857] shadow-sm flex-shrink-0 group-hover:ring-2 ring-transparent transition"></div>
                        <span class="text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition">Forest Green</span>
                    </div>

                    {{-- Inactive Modern Indigo --}}
                    <div class="p-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-50 transition rounded-lg group">
                        <div class="w-7 h-7 rounded-full bg-[#4f46e5] shadow-sm flex-shrink-0 group-hover:ring-2 ring-transparent transition"></div>
                        <span class="text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition">Modern Indigo</span>
                    </div>

                    {{-- Inactive Midnight Black --}}
                    <div class="p-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-50 transition rounded-lg group">
                        <div class="w-7 h-7 rounded-full bg-[#0f172a] shadow-sm flex-shrink-0 group-hover:ring-2 ring-transparent transition"></div>
                        <span class="text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition">Midnight Black</span>
                    </div>
                </div>
            </div>
        </section>
        
    </div>

    {{-- ─── Bottom Actions ─── --}}
    <!-- Actions removed as requested -->

</div>

@endsection
