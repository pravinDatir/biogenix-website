@php
    $eyebrow = $eyebrow ?? 'Biogenix Access';
    $title = $title ?? 'Premium healthcare commerce experiences.';
    $copy = $copy ?? 'Secure access for diagnostics teams, institutions, and independent buyers.';
    $stats = $stats ?? [];
    $points = $points ?? [];
    $accentStart = $accentStart ?? '#2563eb';
    $accentEnd = $accentEnd ?? '#0f172a';
@endphp

<aside
    class="relative overflow-hidden rounded-[2rem] border border-slate-200 p-8 text-white shadow-[0_35px_80px_rgba(15,23,42,0.28)]"
    style="background:
        radial-gradient(circle at top right, rgba(255,255,255,0.20), transparent 34%),
        radial-gradient(circle at bottom left, rgba(14,165,233,0.24), transparent 26%),
        linear-gradient(155deg, #020617 0%, #0f172a 42%, {{ $accentStart }} 78%, {{ $accentEnd }} 100%);"
>
    <div class="absolute inset-0 opacity-30" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 3rem 3rem;"></div>

    <div class="relative z-10">
        <span class="inline-flex w-fit items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/85">
            {{ $eyebrow }}
        </span>

        <h2 class="mt-6 max-w-xl text-3xl font-semibold leading-tight text-white md:text-4xl">{{ $title }}</h2>
        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-100 md:text-base">{{ $copy }}</p>

        @if ($stats)
            <div class="mt-8 grid gap-3 sm:grid-cols-3">
                @foreach ($stats as $stat)
                    <article class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-2xl font-semibold text-white">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-200">{{ $stat['label'] }}</p>
                    </article>
                @endforeach
            </div>
        @endif

        @if ($points)
            <div class="mt-8 space-y-3">
                @foreach ($points as $point)
                    <div class="flex items-start gap-3 rounded-2xl border border-white/12 bg-white/8 px-4 py-3 backdrop-blur">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-cyan-300"></span>
                        <p class="text-sm leading-6 text-slate-100">{{ $point }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</aside>
