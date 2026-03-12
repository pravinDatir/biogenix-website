@php
    $portal = ($portal ?? 'b2c') === 'b2b' ? 'b2b' : 'b2c';
    $active = $active ?? 'profile';
    $user = auth()->user();
    $displayName = $user?->name ?? 'Prakhar Kapoor';
    $displayEmail = $user?->email ?? ($portal === 'b2b' ? 'prakhar@biogenix.com' : 'prakhar@example.com');
    $accountLabel = $portal === 'b2b' ? 'Admin Account' : 'Customer Account';
    $navLinks = [
        ['key' => 'profile', 'label' => 'My Profile', 'href' => route('customer.profile.preview', ['user_type' => $portal]), 'icon' => 'user'],
        ['key' => 'addresses', 'label' => 'Addresses', 'href' => route('customer.addresses.preview', ['user_type' => $portal]), 'icon' => 'map'],
        ['key' => 'orders', 'label' => 'Orders', 'href' => route('orders.index'), 'icon' => 'clipboard'],
        ['key' => 'support', 'label' => 'Support Tickets', 'href' => route('support-tickets.index'), 'icon' => 'lifebuoy'],
    ];
@endphp

<aside class="flex flex-col lg:min-h-[32rem]">
    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
            <div class="h-12 w-12 overflow-hidden rounded-full bg-slate-100">
                <img src="{{ asset('images/logo.jpg') }}" alt="Avatar" class="h-full w-full object-cover">
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $displayName }}</p>
                <p class="text-xs text-slate-500">{{ $displayEmail }}</p>
                <p class="text-[11px] font-semibold text-primary-700">{{ $accountLabel }}</p>
            </div>
        </div>

        <nav class="py-2">
            @foreach ($navLinks as $item)
                @php($isActive = $item['key'] === $active)
                <a class="flex items-center gap-3 px-5 py-3 text-sm font-semibold {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-700 hover:bg-slate-50' }}" href="{{ $item['href'] }}">
                    @switch($item['icon'])
                        @case('user')
                            <svg class="h-4 w-4 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            @break
                        @case('map')
                            <svg class="h-4 w-4 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 011.553-1.948L9 2m0 0l6 3m-6-3v18m6-15l5.447 2.724A2 2 0 0121 8.618v8.764a2 2 0 01-1.553 1.948L15 22m0 0V4" /></svg>
                            @break
                        @case('clipboard')
                            <svg class="h-4 w-4 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            @break
                        @case('lifebuoy')
                            <svg class="h-4 w-4 {{ $isActive ? 'text-primary-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12h0m0 0a3 3 0 100-6 3 3 0 000 6zm0 0a3 3 0 010 6 3 3 0 010-6zm0 0l2.121 2.121M12 12l-2.121 2.121M12 12l2.121-2.121M12 12l-2.121-2.121M4.929 4.929l2.121 2.121M16.95 16.95l2.121 2.121M4.929 19.071l2.121-2.121M16.95 7.05l2.121-2.121" /></svg>
                            @break
                    @endswitch
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="flex-1"></div>

    @auth
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-rose-600 hover:text-rose-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1" /></svg>
                Sign Out
            </button>
        </form>
    @else
        <a class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-rose-600 hover:text-rose-700" href="#">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1" /></svg>
            Sign Out
        </a>
    @endauth
</aside>
