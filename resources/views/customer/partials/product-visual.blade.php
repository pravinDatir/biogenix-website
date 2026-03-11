@props([
    'variant' => 'vials',
    'class' => '',
])

<div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-[22px] bg-gradient-to-br from-[#0d4c58] via-[#0a3f4f] to-[#111827] ".$class]) }}>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(125,211,252,0.35),transparent_42%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_72%_28%,rgba(255,255,255,0.12),transparent_25%)]"></div>

    @switch($variant)
        @case('vials')
            <div class="absolute bottom-10 left-8 right-8 h-7 rounded-xl bg-white/15"></div>
            @for ($i = 0; $i < 5; $i++)
                <div class="absolute bottom-16 h-20 w-7 rounded-md bg-white/90 shadow-[0_10px_30px_rgba(255,255,255,0.18)]" style="left: {{ 16 + ($i * 8) }}%;">
                    <div class="h-4 rounded-t-md bg-slate-300"></div>
                    <div class="mx-1 mt-5 h-8 rounded-sm bg-amber-300/80"></div>
                </div>
            @endfor
            <div class="absolute bottom-12 right-10 h-28 w-24 rounded-2xl bg-white/90 shadow-2xl">
                <div class="mx-auto mt-5 h-5 w-14 rounded-md bg-slate-300"></div>
                <div class="mx-auto mt-5 h-10 w-16 rounded-xl bg-slate-100"></div>
            </div>
            @break

        @case('tubes')
            <div class="absolute bottom-10 left-12 h-24 w-16 rounded-[40px_40px_12px_12px] border border-white/40 bg-white/10"></div>
            <div class="absolute bottom-12 left-1/2 h-32 w-10 -translate-x-1/2 rounded-[28px_28px_10px_10px] border border-white/45 bg-white/8"></div>
            <div class="absolute bottom-12 right-16 h-36 w-8 rounded-[24px_24px_10px_10px] border border-white/45 bg-white/8"></div>
            <div class="absolute bottom-10 right-8 h-24 w-6 rounded-[20px_20px_8px_8px] border border-white/35 bg-white/10"></div>
            <div class="absolute bottom-6 left-6 h-10 w-14 rounded-full bg-emerald-300/40 blur-md"></div>
            @break

        @case('machine')
            <div class="absolute bottom-10 left-1/2 h-44 w-56 -translate-x-1/2 rounded-[28px] bg-slate-100 shadow-[0_30px_50px_rgba(15,23,42,0.35)]"></div>
            <div class="absolute bottom-32 left-1/2 h-24 w-44 -translate-x-1/2 rounded-[18px] bg-slate-300"></div>
            <div class="absolute bottom-36 left-1/2 h-16 w-24 -translate-x-1/2 rounded-xl bg-slate-700"></div>
            <div class="absolute bottom-[4.3rem] left-[30%] h-10 w-10 rounded-full border-[8px] border-slate-400 bg-slate-200"></div>
            <div class="absolute bottom-[4.3rem] right-[30%] h-10 w-10 rounded-full border-[8px] border-slate-400 bg-slate-200"></div>
            @break

        @case('tray')
            <div class="absolute bottom-10 left-10 right-10 h-12 rounded-2xl bg-white/90 shadow-2xl"></div>
            <div class="absolute bottom-20 left-1/2 h-24 w-20 -translate-x-1/2 rounded-[16px] bg-white/85"></div>
            @for ($i = 0; $i < 6; $i++)
                <div class="absolute bottom-20 h-24 w-4 rounded-full bg-white/95" style="left: {{ 52 + ($i * 4) }}%;">
                    <div class="h-3 rounded-t-full bg-slate-400"></div>
                    <div class="mx-auto mt-8 h-8 w-2 rounded-full bg-amber-400/90"></div>
                </div>
            @endfor
            @break

        @case('microscope')
            <div class="absolute bottom-10 left-1/2 h-44 w-48 -translate-x-1/2">
                <div class="absolute bottom-0 left-1/2 h-6 w-40 -translate-x-1/2 rounded-2xl bg-slate-200"></div>
                <div class="absolute bottom-6 left-1/2 h-24 w-12 -translate-x-1/2 rounded-xl bg-slate-100"></div>
                <div class="absolute bottom-24 left-[42%] h-16 w-16 rounded-[2rem] border-[14px] border-slate-100 border-r-transparent rotate-[25deg]"></div>
                <div class="absolute bottom-28 left-[58%] h-20 w-10 rounded-xl bg-slate-300 rotate-[18deg]"></div>
                <div class="absolute bottom-20 left-[63%] h-10 w-10 rounded-full bg-slate-100"></div>
            </div>
            @break

        @case('petri')
            <div class="absolute left-1/2 top-1/2 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full border-4 border-teal-300 bg-[#f2e6b8] shadow-2xl"></div>
            @for ($i = 0; $i < 20; $i++)
                <span class="absolute h-2.5 w-2.5 rounded-full bg-emerald-500/80" style="left: {{ 28 + ($i * 2.2) % 38 }}%; top: {{ 34 + ($i * 1.8) % 24 }}%;"></span>
            @endfor
            @break

        @case('centrifuge')
            <div class="absolute bottom-12 left-1/2 h-48 w-64 -translate-x-1/2 rounded-[34px] bg-slate-50 shadow-[0_26px_60px_rgba(15,23,42,0.28)]"></div>
            <div class="absolute bottom-44 left-1/2 h-20 w-44 -translate-x-1/2 rounded-full bg-slate-300"></div>
            <div class="absolute bottom-48 left-1/2 h-16 w-52 -translate-x-1/2 rounded-full border-[12px] border-slate-700 bg-slate-100"></div>
            <div class="absolute bottom-24 left-[34%] h-12 w-12 rounded-full border-[8px] border-slate-300 bg-white"></div>
            <div class="absolute bottom-24 right-[34%] h-12 w-12 rounded-full border-[8px] border-slate-300 bg-white"></div>
            <div class="absolute bottom-24 left-1/2 h-8 w-16 -translate-x-1/2 rounded-lg bg-slate-300"></div>
            @break

        @case('pipette')
            @for ($i = 0; $i < 3; $i++)
                <div class="absolute top-[20%] h-44 w-8 rounded-t-full bg-[#d8f0ff] rotate-[18deg] shadow-lg" style="left: {{ 22 + ($i * 20) }}%;"></div>
                <div class="absolute top-[48%] h-20 w-4 rounded-b-full bg-white rotate-[18deg]" style="left: {{ 26 + ($i * 20) }}%;"></div>
            @endfor
            <div class="absolute bottom-8 left-1/2 h-16 w-28 -translate-x-1/2 rounded-[2rem] bg-[#caa779] shadow-xl"></div>
            @break

        @case('vortex')
            <div class="absolute bottom-10 left-1/2 h-44 w-36 -translate-x-1/2 rounded-[2rem] bg-slate-100 shadow-2xl"></div>
            <div class="absolute bottom-32 left-1/2 h-24 w-24 -translate-x-1/2 rounded-full bg-sky-400"></div>
            <div class="absolute bottom-48 left-1/2 h-12 w-16 -translate-x-1/2 rounded-full bg-slate-500"></div>
            @break

        @case('rack')
            <div class="absolute left-1/2 top-1/2 h-56 w-44 -translate-x-1/2 -translate-y-1/2 rounded-md border-[6px] border-[#b68a49] bg-white/80 shadow-2xl"></div>
            @break
    @endswitch

    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/15 to-transparent"></div>
</div>
