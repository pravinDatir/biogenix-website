@php
    $mode = $mode ?? 'login';
    $portal = $portal ?? 'b2c';
    $items = [
        [
            'label' => 'B2C Login',
            'href' => route('login', ['portal' => 'b2c']),
            'active' => $mode === 'login' && $portal === 'b2c',
        ],
        [
            'label' => 'B2B Login',
            'href' => route('login', ['portal' => 'b2b']),
            'active' => $mode === 'login' && $portal === 'b2b',
        ],
        [
            'label' => 'B2C Sign Up',
            'href' => route('signup'),
            'active' => $mode === 'signup' && $portal === 'b2c',
        ],
        [
            'label' => 'B2B Sign Up',
            'href' => route('b2b.signup'),
            'active' => $mode === 'signup' && $portal === 'b2b',
        ],
    ];
@endphp

<nav aria-label="Portal access navigation" class="grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($items as $item)
        <a
            href="{{ $item['href'] }}"
            @class([
                'rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-200',
                'border-slate-900 bg-slate-900 text-white shadow-lg shadow-slate-900/10' => $item['active'],
                'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:bg-white hover:text-slate-900' => ! $item['active'],
            ])
            @if ($item['active']) aria-current="page" @endif
        >
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
