@props([
    'variant' => 'vials',
    'class' => '',
])

@php
    $resolvedClass = str_replace(
        ['product-visual-stage-lg', 'product-visual-stage-sm', 'product-visual-stage'],
        ['h-80 sm:h-96 xl:h-[32rem]', 'h-20 sm:h-24', 'h-56'],
        $class
    );

    $wrapperClass = trim('relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 before:absolute before:inset-0 before:z-0 before:content-[\'\'] before:bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.15),transparent_42%)] after:absolute after:inset-0 after:z-0 after:content-[\'\'] after:bg-[radial-gradient(circle_at_72%_28%,rgba(255,255,255,0.08),transparent_25%)] [&>*]:relative [&>*]:z-10 ' . $resolvedClass);

    $vialOffsets = ['left-[16%]', 'left-[24%]', 'left-[32%]', 'left-[40%]', 'left-[48%]'];
    $trayOffsets = ['left-[52%]', 'left-[56%]', 'left-[60%]', 'left-[64%]', 'left-[68%]', 'left-[72%]'];
    $petriOffsets = [
        'left-[28%] top-[34%]',
        'left-[30.2%] top-[35.8%]',
        'left-[32.4%] top-[37.6%]',
        'left-[34.6%] top-[39.4%]',
        'left-[36.8%] top-[41.2%]',
        'left-[39%] top-[43%]',
        'left-[41.2%] top-[44.8%]',
        'left-[43.4%] top-[46.6%]',
        'left-[45.6%] top-[48.4%]',
        'left-[47.8%] top-[50.2%]',
        'left-[50%] top-[52%]',
        'left-[52.2%] top-[53.8%]',
        'left-[54.4%] top-[55.6%]',
        'left-[56.6%] top-[57.4%]',
        'left-[58.8%] top-[35.2%]',
        'left-[61%] top-[37%]',
        'left-[63.2%] top-[38.8%]',
        'left-[65.4%] top-[40.6%]',
        'left-[29.6%] top-[42.4%]',
        'left-[31.8%] top-[44.2%]',
    ];
    $pipetteOffsets = [
        ['left-[22%]', 'left-[26%]'],
        ['left-[42%]', 'left-[46%]'],
        ['left-[62%]', 'left-[66%]'],
    ];
@endphp

<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    @switch($variant)
        @case('vials')
            <div class="absolute bottom-10 left-8 right-8 h-7 rounded-xl bg-white/15"></div>
            @foreach ($vialOffsets as $offsetClass)
                <div class="{{ $offsetClass }} absolute bottom-16 h-20 w-7 rounded-md bg-white/90 shadow-[0_10px_30px_rgba(255,255,255,0.18)]">
                    <div class="h-4 rounded-t-md bg-slate-300"></div>
                    <div class="mx-1 mt-5 h-8 rounded-sm bg-amber-300/80"></div>
                </div>
            @endforeach
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
            @foreach ($trayOffsets as $offsetClass)
                <div class="{{ $offsetClass }} absolute bottom-20 h-24 w-4 rounded-full bg-white/95">
                    <div class="h-3 rounded-t-full bg-slate-400"></div>
                    <div class="mx-auto mt-8 h-8 w-2 rounded-full bg-amber-400/90"></div>
                </div>
            @endforeach
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
            <div class="absolute left-1/2 top-1/2 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full border-4 border-primary-100 bg-amber-100 shadow-2xl"></div>
            @foreach ($petriOffsets as $offsetClass)
                <span class="{{ $offsetClass }} absolute h-2.5 w-2.5 rounded-full bg-primary-600/80"></span>
            @endforeach
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
            @foreach ($pipetteOffsets as [$tubeLeftClass, $tipLeftClass])
                <div class="{{ $tubeLeftClass }} absolute top-[20%] h-44 w-8 rounded-t-full bg-primary-100 rotate-[18deg] shadow-lg"></div>
                <div class="{{ $tipLeftClass }} absolute top-[48%] h-20 w-4 rounded-b-full bg-white rotate-[18deg]"></div>
            @endforeach
            <div class="absolute bottom-8 left-1/2 h-16 w-28 -translate-x-1/2 rounded-[2rem] bg-secondary-600 shadow-xl"></div>
            @break

        @case('vortex')
            <div class="absolute bottom-10 left-1/2 h-44 w-36 -translate-x-1/2 rounded-[2rem] bg-slate-100 shadow-2xl"></div>
            <div class="absolute bottom-32 left-1/2 h-24 w-24 -translate-x-1/2 rounded-full bg-primary-600/80"></div>
            <div class="absolute bottom-48 left-1/2 h-12 w-16 -translate-x-1/2 rounded-full bg-slate-500"></div>
            @break

        @case('rack')
            <div class="absolute left-1/2 top-1/2 h-56 w-44 -translate-x-1/2 -translate-y-1/2 rounded-md border-[6px] border-amber-500 bg-white/80 shadow-2xl"></div>
            @break
    @endswitch

    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/15 to-transparent"></div>
</div>
