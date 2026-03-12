@props([
    'steps' => [],
    'currentStep' => 1,
])

<div {{ $attributes->class(['w-full py-4']) }}>
    <div class="relative flex items-center justify-between">
        {{-- Background Line --}}
        <div class="absolute left-0 top-1/2 h-0.5 w-full -translate-y-1/2 bg-slate-200"></div>
        
        {{-- Active Line --}}
        @php
            $percentage = count($steps) > 1 ? (($currentStep - 1) / (count($steps) - 1)) * 100 : 0;
        @endphp
        <div class="absolute left-0 top-1/2 h-0.5 -translate-y-1/2 bg-primary-600 transition-all duration-500 ease-in-out" style="width: {{ $percentage }}%"></div>

        @foreach ($steps as $index => $step)
            @php
                $stepNum = $index + 1;
                $isActive = $stepNum <= $currentStep;
                $isCurrent = $stepNum === $currentStep;
            @endphp
            
            <div class="relative flex flex-col items-center">
                <div @class([
                    'flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-300 z-10',
                    'border-primary-600 bg-primary-600 text-white shadow-lg shadow-primary-600/20' => $isActive,
                    'border-slate-300 bg-white text-slate-400' => !$isActive,
                    'ring-4 ring-primary-100 scale-110' => $isCurrent,
                ])>
                    @if ($isActive && !$isCurrent && $stepNum < $currentStep)
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <span class="text-sm font-bold">{{ $stepNum }}</span>
                    @endif
                </div>
                <div class="absolute top-12 whitespace-nowrap text-center">
                    <span @class([
                        'text-xs font-bold uppercase tracking-wider transition-colors duration-300',
                        'text-primary-700' => $isActive,
                        'text-slate-400' => !$isActive,
                    ])>
                        {{ $step }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
