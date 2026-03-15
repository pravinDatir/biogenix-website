<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biogenix Admin Portal')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f2f5] min-h-screen flex text-slate-800 antialiased overflow-hidden">

    <!-- Left Sidebar -->
    <aside class="w-[280px] bg-white border-r border-slate-200 h-screen flex flex-col flex-shrink-0 z-20">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-[#091b3f] flex items-center justify-center text-white shadow-md">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-[#091b3f] leading-tight tracking-tight">Biogenix</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin Portal</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 scrollbar-hide">
            
            <a href="{{ route('adminPanel.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl bg-[#eef1f6] text-[#2c3e66] font-bold text-[13px] transition w-full relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#091b3f] rounded-r-md"></div>
                <svg class="h-5 w-5 text-[#091b3f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Admin Dashboard
            </a>

            @php
            $navLinks = [
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Product Management'],
                ['icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'title' => 'Pricing Management'],
                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Quotation/ PI Management'],
                ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Order Management'],
                ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Delivery & Logistics'],
                ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Customer Management'],
                ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'title' => 'Support Ticket Management'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Role & Permission'],
                ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'title' => 'Review & Incentive Management'],
                ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Server Sync Monitor'],
                ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'System Settings'],
                ['icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'title' => 'Global Setting Page (Theme mode)'],
            ];
            @endphp

            @foreach($navLinks as $link)
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 font-semibold text-[13px] transition hover:bg-slate-50 hover:text-slate-800">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"></path></svg>
                <span class="leading-snug">{{ $link['title'] }}</span>
            </a>
            @endforeach
        </nav>

        <!-- Bottom Actions -->
        <div class="p-4 border-t border-slate-100 space-y-1">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 font-semibold text-[13px] transition hover:bg-slate-50 hover:text-slate-800">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Help Center
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-500 font-bold text-[13px] transition hover:bg-rose-50 hover:text-rose-600">
                <svg class="h-5 w-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f4f7fb]">
        
        <!-- Header -->
        <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between z-10 flex-shrink-0">
            <!-- Search -->
            <div class="relative w-full max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search analytics, orders, or products..." class="w-full bg-[#f0f3f8] border-transparent text-sm rounded-xl pl-11 pr-4 py-3 focus:bg-white focus:border-[#091b3f] focus:ring-1 focus:ring-[#091b3f] transition outline-none text-slate-700 placeholder:text-slate-400">
            </div>

            <!-- Header Right -->
            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <button class="relative p-2 text-slate-400 hover:text-slate-600 transition rounded-full hover:bg-slate-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Badge -->
                    <span class="absolute top-1.5 right-2 h-2.5 w-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Profile -->
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900 leading-tight">Dr. Sarah Chen</p>
                        <p class="text-[11px] font-medium text-slate-500">Senior Administrator</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Sarah+Chen&background=fecaca&color=dc2626&rounded=true" alt="Profile" class="h-9 w-9 rounded-full object-cover ring-2 ring-white shadow-sm">
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto px-8 py-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
