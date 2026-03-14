@php
    $portal = ($portal ?? 'b2c') === 'b2b' ? 'b2b' : 'b2c';
    $active = $active ?? 'profile';
    $user = auth()->user();
    $displayName = $user?->name ?? 'Prakhar Kapoor';
    $displayEmail = $user?->email ?? ($portal === 'b2b' ? 'prakhar@biogenix.com' : 'prakhar@example.com');
    $accountLabel = $portal === 'b2b' ? 'Admin Account' : 'Customer Account';
    $ordersHref = $user ? route('orders.index') : route('login');
    $supportHref = $user ? route('support-tickets.index') : route('customer.support.preview');
    $navLinks = [
        ['key' => 'profile', 'label' => 'My Profile', 'href' => route('customer.profile.preview', ['user_type' => $portal]), 'icon' => 'user'],
        ['key' => 'addresses', 'label' => 'Addresses', 'href' => route('customer.addresses.preview', ['user_type' => $portal]), 'icon' => 'map'],
        ['key' => 'orders', 'label' => 'Orders', 'href' => $ordersHref, 'icon' => 'clipboard'],
        ['key' => 'support', 'label' => 'Support Tickets', 'href' => $supportHref, 'icon' => 'lifebuoy'],
    ];
@endphp

<aside class="lg:sticky lg:top-24 lg:self-start">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        {{-- User avatar section --}}
        <div class="relative border-b border-slate-100 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 px-4 py-5">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full border-4 border-white/20"></div>
                <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full border-4 border-white/20"></div>
            </div>
            <div class="relative flex items-center gap-3">
                <div class="relative shrink-0">
                    <div class="h-11 w-11 overflow-hidden rounded-xl border-2 border-white/30 bg-white/20">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Avatar" class="h-full w-full object-cover">
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-primary-700 bg-emerald-400"></span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white">{{ $displayName }}</p>
                    <p class="truncate text-xs text-primary-200">{{ $displayEmail }}</p>
                    <span class="mt-1 inline-flex rounded-md bg-white/15 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">{{ $accountLabel }}</span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="space-y-0.5 p-2">
            @foreach ($navLinks as $item)
                @php($isActive = $item['key'] === $active)
                <a
                    href="{{ $item['href'] }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium no-underline transition {{ $isActive ? 'bg-primary-50 font-semibold text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    @switch($item['icon'])
                        @case('user')
                            <svg class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            @break
                        @case('map')
                            <svg class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            @break
                        @case('clipboard')
                            <svg class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            @break
                        @case('lifebuoy')
                            <svg class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            @break
                    @endswitch
                    <span>{{ $item['label'] }}</span>
                    @if ($isActive)
                        <span class="ml-auto h-1.5 w-1.5 shrink-0 rounded-full bg-primary-600"></span>
                    @endif
                </a>
            @endforeach
        </nav>
        <div class="border-t border-slate-100 p-2">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1" /></svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            @else
                <a class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-primary-700 no-underline transition hover:bg-primary-50 hover:text-primary-800" href="{{ route('login') }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1" /></svg>
                    <span>Sign In</span>
                </a>
            @endauth
        </div>
    </div>
</aside>
