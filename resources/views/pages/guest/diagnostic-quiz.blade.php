@extends('layouts.app')

@section('title', 'Diagnostic Precision Quiz – Biogenix')
@section('meta_description', 'Test your diagnostic knowledge with the Biogenix Kits Mastery quiz. Answer 4 questions and unlock a 15% discount on your first clinical order.')

@push('styles')
<style>
    .quiz-page {
        min-height: calc(100vh - 88px);
    }

    /* ─── Progress bar ─── */
    .quiz-progress-track {
        height: 6px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .quiz-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--color-primary-600);
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ─── Option cards ─── */
    .quiz-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-radius: 1rem;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        cursor: pointer;
        transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
    }
    .quiz-option:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .quiz-option.selected {
        border-color: var(--color-primary-600);
        background: rgba(26, 77, 46, 0.06);
        box-shadow: 0 0 0 3px rgba(26, 77, 46, 0.1);
    }
    .quiz-option.correct {
        border-color: var(--color-primary-500);
        background: rgba(22, 163, 74, 0.06);
    }

    .quiz-option-radio {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.25rem;
        width: 2.25rem;
        border-radius: 9999px;
        border: 2px solid #cbd5e1;
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        flex-shrink: 0;
        transition: background 0.25s, border-color 0.25s, color 0.25s;
    }
    .quiz-option.selected .quiz-option-radio {
        background: var(--color-primary-600);
        border-color: var(--color-primary-600);
        color: #ffffff;
    }

    .quiz-option-check {
        display: none;
        margin-left: auto;
    }
    .quiz-option.selected .quiz-option-check {
        display: flex;
    }

    /* ─── Clinical tip cards ─── */
    .quiz-tip-card {
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        background: #fffbeb;
        padding: 1.25rem;
    }
    .quiz-context-card {
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 1.25rem;
    }
    .quiz-insight-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #fef9c3;
        padding: 1rem;
    }
    .quiz-ref-card {
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 1.25rem;
    }

    /* ─── Step transitions ─── */
    .quiz-step {
        display: none;
        opacity: 0;
        transform: translateY(12px);
    }
    .quiz-step.active {
        display: block;
        animation: quizFadeIn 0.4s ease forwards;
    }
    @keyframes quizFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ─── Result score ring ─── */
    .score-ring {
        width: 200px;
        height: 200px;
    }
    .score-ring-bg { stroke: #e2e8f0; }
    .score-ring-fill {
        stroke: var(--color-primary-600);
        stroke-linecap: round;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dashoffset 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ─── Performance bars ─── */
    .perf-bar-track {
        height: 8px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .perf-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: #0f172a;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1) 0.3s;
    }

    /* ─── Voucher code ─── */
    .voucher-code {
        letter-spacing: 0.3em;
        font-family: 'Sora', monospace;
    }

    /* ─── Form fields ─── */
    .quiz-field {
        height: 3rem;
        width: 100%;
        border-radius: 0.75rem;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        padding: 0 1rem;
        font-size: 0.875rem;
        color: #0f172a;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .quiz-field:focus {
        outline: none;
        border-color: var(--color-primary-600);
        box-shadow: 0 0 0 3px rgba(26, 77, 46, 0.1);
    }
    .quiz-field::placeholder {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
@php
    $quizQuestionsPayload = collect($quizQuestions ?? [])
        ->values()
        ->map(function ($quizQuestion): array {
            return [
                'id' => (int) $quizQuestion->id,
                'phase_title' => (string) $quizQuestion->phase_title,
                'question_text' => (string) $quizQuestion->question_text,
                'question_support_details' => is_array($quizQuestion->question_support_details ?? null)
                    ? $quizQuestion->question_support_details
                    : [],
                'answer_options' => collect($quizQuestion->answerOptions ?? [])
                    ->values()
                    ->map(function ($answerOption): array {
                        return [
                            'id' => (int) $answerOption->id,
                            'option_label' => (string) $answerOption->option_label,
                            'option_text' => (string) $answerOption->option_text,
                        ];
                    })
                    ->all(),
            ];
        })
        ->all();
    $hasQuizData = count($quizQuestionsPayload) > 0;
    $firstQuizQuestion = $hasQuizData ? $quizQuestionsPayload[0] : null;
@endphp
<div class="quiz-page" id="quizApp">

    {{-- ═══ HEADER BAR ═══ --}}
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto w-full max-w-none px-4 py-5 sm:px-6 lg:px-8 xl:px-10">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500" id="quizPhaseLabel">{{ $hasQuizData ? 'Assessment Phase: 01' : 'Assessment Setup' }}</p>
                    <h1 class="mt-1 font-['Sora'] text-2xl font-bold tracking-tight text-slate-950 md:text-3xl" id="quizTitle">{{ $firstQuizQuestion['phase_title'] ?? 'Diagnostic Precision Quiz' }}</h1>
                    <div class="quiz-progress-track mt-3 w-48 sm:w-64">
                        <div class="quiz-progress-fill" id="quizProgressBar" style="width: {{ $hasQuizData ? round(100 / count($quizQuestionsPayload)) : 0 }}%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-base font-bold text-slate-950" id="quizStepLabel">{{ $hasQuizData ? 'Step 1 of '.count($quizQuestionsPayload) : '' }}</p>
                    <p class="text-sm text-slate-500" id="quizPercentLabel">{{ $hasQuizData ? round(100 / count($quizQuestionsPayload)).'% Complete' : '0% Complete' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ QUIZ CONTENT ═══ --}}
    <div class="mx-auto w-full max-w-none px-4 py-8 sm:px-6 lg:px-8 xl:px-10">
        @if (! $hasQuizData)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-bold text-slate-950">Quiz is not available right now.</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">Please run the quiz migration and seeder, then reload this page.</p>
            </div>
        @else

        {{-- ────────── STEP 1 ────────── --}}
        <div class="quiz-step active" data-step="1">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold text-white">1</span>
                            <h2 class="text-lg font-bold text-slate-950 sm:text-xl">Which reagent kit is best suited for high-throughput automation?</h2>
                        </div>

                        <div class="mt-6 space-y-3" id="q1Options">
                            <div class="quiz-option" data-answer="A" onclick="selectOption(1, this)">
                                <span class="quiz-option-radio">A</span>
                                <span class="text-sm font-semibold text-slate-800">Precision-X LIMS Kit</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="B" onclick="selectOption(1, this)">
                                <span class="quiz-option-radio">B</span>
                                <span class="text-sm font-semibold text-slate-800">Bio-RGT Standard</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="C" onclick="selectOption(1, this)">
                                <span class="quiz-option-radio">C</span>
                                <span class="text-sm font-semibold text-slate-800">Clinical-Max Assay</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="D" onclick="selectOption(1, this)">
                                <span class="quiz-option-radio">D</span>
                                <span class="text-sm font-semibold text-slate-800">Eco-Lite Consumable</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <button type="button" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 cursor-not-allowed" disabled>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                            Previous
                        </button>
                        <button type="button" class="quiz-next-btn inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-md" onclick="nextStep(2)">
                            Next
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-5">
                    <div class="quiz-tip-card">
                        <p class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-secondary-700">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-secondary-600"><svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg></span>
                            Clinical Tip
                        </p>
                        <h3 class="mt-2 text-base font-bold text-primary-600">Automation Integration</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Automation-compatible kits utilize standard SBS footprints and barcoded vials. When selecting a kit for high-throughput environments, prioritize those with liquid-level sensing compatibility to minimize aspiration errors.</p>
                    </div>

                    <div class="quiz-context-card">
                        <h4 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Assessment Context</h4>
                        <ul class="mt-3 space-y-3">
                            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h4"/></svg>
                                Module: Reagent Classification
                            </li>
                            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Difficulty: Intermediate
                            </li>
                            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 2"/></svg>
                                Time Limit: No constraints
                            </li>
                        </ul>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl min-h-[180px]">
                        <img src="{{ asset('upload/corousel/image3.jpg') }}" alt="Automated pipetting system" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(26,77,46,0.2),rgba(26,77,46,0.85))"></div>
                        <p class="absolute bottom-3 left-3 right-3 z-10 text-xs font-medium italic text-white/90">Fig 1.1: Automated pipetting system with Biogenix reagents.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────── STEP 2 ────────── --}}
        <div class="quiz-step" data-step="2">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="text-lg font-bold text-slate-950 sm:text-xl">What is the required storage temperature for the DNA Polymerase High Fidelity kit?</h2>
                        <div class="mt-6 space-y-3" id="q2Options">
                            <div class="quiz-option" data-answer="A" onclick="selectOption(2, this)">
                                <span class="quiz-option-radio">A</span>
                                <span class="text-sm font-semibold text-slate-800">Room Temperature</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="B" onclick="selectOption(2, this)">
                                <span class="quiz-option-radio">B</span>
                                <span class="text-sm font-semibold text-slate-800">4°C</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="C" onclick="selectOption(2, this)">
                                <span class="quiz-option-radio">C</span>
                                <span class="text-sm font-semibold text-slate-800">-20°C</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="D" onclick="selectOption(2, this)">
                                <span class="quiz-option-radio">D</span>
                                <span class="text-sm font-semibold text-slate-800">-80°C</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <button type="button" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900" onclick="prevStep(1)">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                            Previous
                        </button>
                        <button type="button" class="quiz-next-btn inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-md" onclick="nextStep(3)">
                            Next Question
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="quiz-context-card">
                        <h3 class="flex items-center gap-2 text-base font-bold text-slate-950">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h4"/></svg>
                            Storage Best Practices
                        </h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <p class="flex items-center gap-2 text-sm font-bold text-slate-900"><span class="inline-block h-2 w-2 rounded-full bg-secondary-600"></span> Enzymatic Stability</p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">Most high-fidelity polymerases lose activity if exposed to repeated freeze-thaw cycles. Always use a cooling block during use.</p>
                            </li>
                            <li>
                                <p class="flex items-center gap-2 text-sm font-bold text-slate-900"><span class="inline-block h-2 w-2 rounded-full bg-secondary-600"></span> Reagent Segregation</p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">Keep dNTPs and primers in separate aliquots to prevent cross-contamination during library preparation.</p>
                            </li>
                        </ul>
                    </div>
                    <div class="quiz-insight-card">
                        <p class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-800">
                            <span class="inline-block h-2 w-2 rounded-full bg-secondary-600"></span>
                            Clinical Insight
                        </p>
                        <p class="mt-2 text-sm leading-6 text-slate-700">Storing at -20°C in a non-frost-free freezer is critical for maintaining long-term buffer molarity.</p>
                    </div>
                    <div class="quiz-ref-card">
                        <h4 class="text-base font-bold text-slate-950">Reference Material</h4>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                    <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Kit_Datasheet_V4.pdf
                                </span>
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                    <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Storage_Protocol_Guide
                                </span>
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────── STEP 3 ────────── --}}
        <div class="quiz-step" data-step="3">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold text-white">3</span>
                            <h2 class="text-lg font-bold text-slate-950 sm:text-xl">Which certification standard governs IVD reagent manufacturing quality?</h2>
                        </div>
                        <div class="mt-6 space-y-3" id="q3Options">
                            <div class="quiz-option" data-answer="A" onclick="selectOption(3, this)">
                                <span class="quiz-option-radio">A</span>
                                <span class="text-sm font-semibold text-slate-800">ISO 9001:2015</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="B" onclick="selectOption(3, this)">
                                <span class="quiz-option-radio">B</span>
                                <span class="text-sm font-semibold text-slate-800">ISO 13485:2016</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="C" onclick="selectOption(3, this)">
                                <span class="quiz-option-radio">C</span>
                                <span class="text-sm font-semibold text-slate-800">CE-IVD Directive 98/79/EC</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="D" onclick="selectOption(3, this)">
                                <span class="quiz-option-radio">D</span>
                                <span class="text-sm font-semibold text-slate-800">GMP Annex 15</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <button type="button" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900" onclick="prevStep(2)">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                            Previous
                        </button>
                        <button type="button" class="quiz-next-btn inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-md" onclick="nextStep(4)">
                            Next Question
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="quiz-tip-card">
                        <p class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-secondary-700">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-secondary-600"><svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg></span>
                            Clinical Tip
                        </p>
                        <h3 class="mt-2 text-base font-bold text-primary-600">Regulatory Compliance</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">ISO 13485 is the primary quality management standard for medical devices and IVD products. It ensures traceability, risk management, and process validation throughout the product lifecycle.</p>
                    </div>
                    <div class="quiz-context-card">
                        <h4 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Assessment Context</h4>
                        <ul class="mt-3 space-y-3">
                            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h4"/></svg>
                                Module: Compliance Standards
                            </li>
                            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Difficulty: Advanced
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────── STEP 4 ────────── --}}
        <div class="quiz-step" data-step="4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold text-white">4</span>
                            <h2 class="text-lg font-bold text-slate-950 sm:text-xl">Which sample preparation method yields highest DNA purity for NGS workflows?</h2>
                        </div>
                        <div class="mt-6 space-y-3" id="q4Options">
                            <div class="quiz-option" data-answer="A" onclick="selectOption(4, this)">
                                <span class="quiz-option-radio">A</span>
                                <span class="text-sm font-semibold text-slate-800">Phenol-chloroform extraction</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="B" onclick="selectOption(4, this)">
                                <span class="quiz-option-radio">B</span>
                                <span class="text-sm font-semibold text-slate-800">Magnetic bead-based purification</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="C" onclick="selectOption(4, this)">
                                <span class="quiz-option-radio">C</span>
                                <span class="text-sm font-semibold text-slate-800">Silica membrane column</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                            <div class="quiz-option" data-answer="D" onclick="selectOption(4, this)">
                                <span class="quiz-option-radio">D</span>
                                <span class="text-sm font-semibold text-slate-800">Salting-out precipitation</span>
                                <span class="quiz-option-check"><svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <button type="button" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900" onclick="prevStep(3)">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                            Previous
                        </button>
                        <button type="button" class="quiz-next-btn inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-md" onclick="nextStep(5)">
                            Finish Quiz
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="quiz-tip-card">
                        <p class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-secondary-700">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-secondary-600"><svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg></span>
                            Clinical Tip
                        </p>
                        <h3 class="mt-2 text-base font-bold text-primary-600">NGS Library Prep</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Magnetic bead-based purification provides the best combination of purity and automation compatibility for next-generation sequencing, with minimal carry-over contamination.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl min-h-[180px]">
                        <img src="{{ asset('upload/corousel/image5.jpg') }}" alt="NGS sample preparation" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(26,77,46,0.2),rgba(26,77,46,0.85))"></div>
                        <p class="absolute bottom-3 left-3 right-3 z-10 text-xs font-medium italic text-white/90">Fig 4.1: NGS library preparation workflow.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────── STEP 5: LEAD CAPTURE FORM ────────── --}}
        <div class="quiz-step" data-step="5">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="flex flex-col justify-center">
                    <span class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-800">Final Step</span>
                    <h2 class="mt-4 font-['Sora'] text-3xl font-bold tracking-tight text-slate-950 md:text-4xl lg:text-5xl">You're almost<br>there!</h2>
                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-600 md:text-base">Enter your details to calculate your precision score and unlock your exclusive coupon code.</p>
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-sm font-semibold text-slate-800">
                            <span>Analysis Completion</span>
                            <span>100%</span>
                        </div>
                        <div class="quiz-progress-track mt-2">
                            <div class="quiz-progress-fill" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="mt-8 flex items-start gap-3">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-600">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-slate-950">Privacy Protocol</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Your clinical data is encrypted using 256-bit AES standards. We never share your results with third-party providers.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="quiz-progress-track mb-6">
                            <div class="quiz-progress-fill" style="width:100%;background:var(--color-secondary-600)"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">First Name</label>
                                <input type="text" id="quizFirstName" class="quiz-field" placeholder="John">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Last Name</label>
                                <input type="text" id="quizLastName" class="quiz-field" placeholder="Doe">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Email Address</label>
                            <input type="email" id="quizEmail" class="quiz-field" placeholder="john.doe@medical-cloud.com">
                        </div>
                        <button type="button" id="quizSubmitButton" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 hover:-translate-y-0.5 hover:shadow-md" onclick="showResults()">
                            Unlock My Score & Reward
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                        <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Secure submission via Biogenix Clinical Gateway
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────── STEP 6: RESULTS ────────── --}}
        <div class="quiz-step" data-step="6">
            <div class="mb-8">
                <span class="inline-flex rounded-full bg-primary-500 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-white">Assessment Complete</span>
                <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <div>
                        <h2 class="font-['Sora'] text-3xl font-bold tracking-tight text-slate-950 md:text-4xl lg:text-5xl" id="quizResultTitle">Advanced<br>Proficiency Level<br>Attained.</h2>
                        <p class="mt-4 max-w-lg text-sm leading-6 text-slate-600 md:text-base" id="quizResultDescription">Your technical precision in diagnostic protocols demonstrates exceptional mastery of Biogenix standards and laboratory compliance.</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="score-ring relative">
                            <svg viewBox="0 0 200 200" class="h-full w-full">
                                <circle class="score-ring-bg" cx="100" cy="100" r="85" fill="none" stroke-width="10"/>
                                <circle class="score-ring-fill" id="scoreRingFill" cx="100" cy="100" r="85" fill="none" stroke-width="10" stroke-dasharray="534" stroke-dashoffset="534"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-['Sora'] text-5xl font-bold text-slate-950" id="scoreValue">0%</span>
                                <span class="mt-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Precision Score</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h3 class="flex items-center gap-2 text-base font-bold text-slate-950">
                        <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Phase Performance Breakdown
                    </h3>
                    <div class="mt-6 space-y-5">
                        <div>
                            <div class="flex items-center justify-between text-sm"><span class="font-semibold text-slate-800" id="quizPerfLabel1">Kits Mastery</span><span class="font-bold text-slate-950" id="quizPerfValue1">98%</span></div>
                            <div class="perf-bar-track mt-2"><div class="perf-bar-fill" id="quizPerfBar1" data-perf="98" style="width:0%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm"><span class="font-semibold text-slate-800" id="quizPerfLabel2">Storage Requirements</span><span class="font-bold text-slate-950" id="quizPerfValue2">85%</span></div>
                            <div class="perf-bar-track mt-2"><div class="perf-bar-fill" id="quizPerfBar2" data-perf="85" style="width:0%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-sm"><span class="font-semibold text-slate-800" id="quizPerfLabel3">System Compatibility</span><span class="font-bold text-slate-950" id="quizPerfValue3">94%</span></div>
                            <div class="perf-bar-track mt-2"><div class="perf-bar-fill" id="quizPerfBar3" data-perf="94" style="width:0%"></div></div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl bg-primary-600 p-6 text-white shadow-sm sm:p-8">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl" style="background:rgba(212,160,23,0.25)">
                        <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <h3 class="mt-4 font-['Sora'] text-2xl font-bold text-white">Certification<br>Reward</h3>
                    <p class="mt-3 text-sm leading-6 text-white/70">Redeem your exclusive proficiency discount on any premium diagnostic kit.</p>
                    <div class="mt-5 rounded-xl border border-white/15 px-5 py-4 text-center" style="background:rgba(255,255,255,0.08)">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-white/60">Voucher Code</p>
                        <p class="voucher-code mt-2 text-2xl font-bold text-white" id="voucherCode">BIOGENIX15</p>
                    </div>
                    <button type="button" class="mt-4 inline-flex w-full items-center justify-center rounded-xl border-2 border-white/20 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10" onclick="copyVoucher()">
                        Copy and Shop
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    var quizQuestions = @json($quizQuestionsPayload);
    var quizAnswers = {};
    var currentStep = 1;
    var totalQuestions = quizQuestions.length;
    var quizSubmitUrl = @json(route('diagnostic-quiz.store'));
    var quizSubmitButtonDefaultHtml = '';
    var currentVoucherCode = document.getElementById('voucherCode') ? document.getElementById('voucherCode').textContent : 'BIOGENIX15';

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function resolveAssetUrl(path) {
        if (!path) {
            return '';
        }

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return window.location.origin + '/' + String(path).replace(/^\/+/, '');
    }

    function getQuestionByStep(step) {
        return quizQuestions[step - 1] || null;
    }

    function findSidebarCard(questionSupportDetails, cardType) {
        var sidebarCards = Array.isArray(questionSupportDetails && questionSupportDetails.sidebar_cards)
            ? questionSupportDetails.sidebar_cards
            : [];

        return sidebarCards.find(function (sidebarCard) {
            return sidebarCard && sidebarCard.card_type === cardType;
        }) || null;
    }

    function iconMarkup(iconName) {
        if (iconName === 'check-circle') {
            return '<svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }

        if (iconName === 'clock') {
            return '<svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 2"/></svg>';
        }

        if (iconName === 'file-green') {
            return '<svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
        }

        if (iconName === 'download') {
            return '<svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
        }

        if (iconName === 'external-link') {
            return '<svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
        }

        return '<svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h4"/></svg>';
    }

    function buildOptionMarkup(stepNumber, questionData) {
        return (questionData.answer_options || []).map(function (answerOption) {
            return '<div class="quiz-option" data-answer="' + escapeHtml(answerOption.option_label) + '" data-question-id="' + escapeHtml(questionData.id) + '" data-option-id="' + escapeHtml(answerOption.id) + '" onclick="selectOption(' + stepNumber + ', this)">' +
                '<span class="quiz-option-radio">' + escapeHtml(answerOption.option_label) + '</span>' +
                '<span class="text-sm font-semibold text-slate-800">' + escapeHtml(answerOption.option_text) + '</span>' +
                '<span class="quiz-option-check"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>' +
            '</div>';
        }).join('');
    }

    function buildContextListMarkup(items) {
        return (Array.isArray(items) ? items : []).map(function (item) {
            return '<li class="flex items-center gap-2.5 text-sm text-slate-700">' +
                iconMarkup(item.icon) +
                escapeHtml(item.text) +
            '</li>';
        }).join('');
    }

    function buildContextSectionMarkup(sections) {
        return (Array.isArray(sections) ? sections : []).map(function (section) {
            return '<li>' +
                '<p class="flex items-center gap-2 text-sm font-bold text-slate-900"><span class="inline-block h-2 w-2 rounded-full" style="background:#d4a017"></span> ' + escapeHtml(section.title) + '</p>' +
                '<p class="mt-1 text-sm leading-6 text-slate-600">' + escapeHtml(section.description) + '</p>' +
            '</li>';
        }).join('');
    }

    function buildReferenceListMarkup(items) {
        return (Array.isArray(items) ? items : []).map(function (item) {
            return '<div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">' +
                '<span class="flex items-center gap-2 text-sm font-medium text-slate-700">' +
                    iconMarkup(item.leading_icon) +
                    escapeHtml(item.document_name) +
                '</span>' +
                iconMarkup(item.trailing_icon) +
            '</div>';
        }).join('');
    }

    function hydrateQuestionStep(stepNumber) {
        var questionData = getQuestionByStep(stepNumber);
        var stepElement = document.querySelector('.quiz-step[data-step="' + stepNumber + '"]');

        if (!questionData || !stepElement) {
            return;
        }

        // Step 1: replace the question text and answer options with the database-backed quiz content.
        var questionTitle = stepElement.querySelector('.lg\\:col-span-2 h2');
        var optionsContainer = document.getElementById('q' + stepNumber + 'Options');

        if (questionTitle) {
            questionTitle.textContent = questionData.question_text || '';
        }

        if (optionsContainer) {
            optionsContainer.innerHTML = buildOptionMarkup(stepNumber, questionData);
        }

        // Step 2: update the sidebar cards shown beside the question while keeping the same visual layout.
        var questionSupportDetails = questionData.question_support_details || {};
        var tipCard = findSidebarCard(questionSupportDetails, 'tip');
        var contextListCard = findSidebarCard(questionSupportDetails, 'context_list');
        var contextSectionsCard = findSidebarCard(questionSupportDetails, 'context_sections');
        var insightCard = findSidebarCard(questionSupportDetails, 'insight');
        var referenceListCard = findSidebarCard(questionSupportDetails, 'reference_list');
        var imageCard = findSidebarCard(questionSupportDetails, 'image');

        if (stepNumber === 1) {
            if (tipCard) {
                var stepOneTipCard = stepElement.querySelector('.quiz-tip-card');
                if (stepOneTipCard) {
                    var stepOneTipTitle = stepOneTipCard.querySelector('h3');
                    var stepOneTipBody = stepOneTipCard.querySelector('p.mt-2.text-sm');

                    if (stepOneTipTitle) stepOneTipTitle.textContent = tipCard.title || '';
                    if (stepOneTipBody) stepOneTipBody.textContent = tipCard.description || '';
                }
            }

            if (contextListCard) {
                var stepOneContextCard = stepElement.querySelector('.quiz-context-card');
                if (stepOneContextCard) {
                    var stepOneContextTitle = stepOneContextCard.querySelector('h4');
                    var stepOneContextList = stepOneContextCard.querySelector('ul');

                    if (stepOneContextTitle) stepOneContextTitle.textContent = contextListCard.title || '';
                    if (stepOneContextList) stepOneContextList.innerHTML = buildContextListMarkup(contextListCard.items);
                }
            }

            if (imageCard) {
                var stepOneImage = stepElement.querySelector('img');
                var stepOneImageCaption = stepElement.querySelector('.relative p');

                if (stepOneImage) {
                    stepOneImage.src = resolveAssetUrl(imageCard.image_path);
                    stepOneImage.alt = imageCard.image_alt_text || 'Diagnostic quiz supporting visual';
                }

                if (stepOneImageCaption) {
                    stepOneImageCaption.textContent = imageCard.image_caption || '';
                }
            }
        }

        if (stepNumber === 2) {
            if (contextSectionsCard) {
                var stepTwoContextCard = stepElement.querySelector('.quiz-context-card');
                if (stepTwoContextCard) {
                    var stepTwoContextTitle = stepTwoContextCard.querySelector('h3');
                    var stepTwoContextSections = stepTwoContextCard.querySelector('ul');

                    if (stepTwoContextTitle) {
                        stepTwoContextTitle.innerHTML = '<svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h4"/></svg> ' + escapeHtml(contextSectionsCard.title || '');
                    }

                    if (stepTwoContextSections) {
                        stepTwoContextSections.innerHTML = buildContextSectionMarkup(contextSectionsCard.sections);
                    }
                }
            }

            if (insightCard) {
                var stepTwoInsightCard = stepElement.querySelector('.quiz-insight-card');
                if (stepTwoInsightCard) {
                    var stepTwoInsightParagraphs = stepTwoInsightCard.querySelectorAll('p');

                    if (stepTwoInsightParagraphs[0]) {
                        stepTwoInsightParagraphs[0].innerHTML = '<span class="inline-block h-2 w-2 rounded-full" style="background:#d4a017"></span> ' + escapeHtml(insightCard.eyebrow || 'Clinical Insight');
                    }

                    if (stepTwoInsightParagraphs[1]) {
                        stepTwoInsightParagraphs[1].textContent = insightCard.description || '';
                    }
                }
            }

            if (referenceListCard) {
                var stepTwoReferenceCard = stepElement.querySelector('.quiz-ref-card');
                if (stepTwoReferenceCard) {
                    var stepTwoReferenceTitle = stepTwoReferenceCard.querySelector('h4');
                    var stepTwoReferenceList = stepTwoReferenceCard.querySelector('.mt-3.space-y-2');

                    if (stepTwoReferenceTitle) stepTwoReferenceTitle.textContent = referenceListCard.title || '';
                    if (stepTwoReferenceList) stepTwoReferenceList.innerHTML = buildReferenceListMarkup(referenceListCard.items);
                }
            }
        }

        if (stepNumber === 3) {
            if (tipCard) {
                var stepThreeTipCard = stepElement.querySelector('.quiz-tip-card');
                if (stepThreeTipCard) {
                    var stepThreeTipTitle = stepThreeTipCard.querySelector('h3');
                    var stepThreeTipBody = stepThreeTipCard.querySelector('p.mt-2.text-sm');

                    if (stepThreeTipTitle) stepThreeTipTitle.textContent = tipCard.title || '';
                    if (stepThreeTipBody) stepThreeTipBody.textContent = tipCard.description || '';
                }
            }

            if (contextListCard) {
                var stepThreeContextCard = stepElement.querySelector('.quiz-context-card');
                if (stepThreeContextCard) {
                    var stepThreeContextTitle = stepThreeContextCard.querySelector('h4');
                    var stepThreeContextList = stepThreeContextCard.querySelector('ul');

                    if (stepThreeContextTitle) stepThreeContextTitle.textContent = contextListCard.title || '';
                    if (stepThreeContextList) stepThreeContextList.innerHTML = buildContextListMarkup(contextListCard.items);
                }
            }
        }

        if (stepNumber === 4) {
            if (tipCard) {
                var stepFourTipCard = stepElement.querySelector('.quiz-tip-card');
                if (stepFourTipCard) {
                    var stepFourTipTitle = stepFourTipCard.querySelector('h3');
                    var stepFourTipBody = stepFourTipCard.querySelector('p.mt-2.text-sm');

                    if (stepFourTipTitle) stepFourTipTitle.textContent = tipCard.title || '';
                    if (stepFourTipBody) stepFourTipBody.textContent = tipCard.description || '';
                }
            }

            if (imageCard) {
                var stepFourImage = stepElement.querySelector('img');
                var stepFourImageCaption = stepElement.querySelector('.relative p');

                if (stepFourImage) {
                    stepFourImage.src = resolveAssetUrl(imageCard.image_path);
                    stepFourImage.alt = imageCard.image_alt_text || 'Diagnostic quiz supporting visual';
                }

                if (stepFourImageCaption) {
                    stepFourImageCaption.textContent = imageCard.image_caption || '';
                }
            }
        }
    }

    function hydrateQuizContent() {
        if (totalQuestions === 0) {
            return;
        }

        for (var stepNumber = 1; stepNumber <= totalQuestions; stepNumber++) {
            hydrateQuestionStep(stepNumber);
        }

        updateHeader(1);
    }

    function selectOption(stepNumber, element) {
        var container = element.parentNode;
        var options = container.querySelectorAll('.quiz-option');
        options.forEach(function (optionElement) {
            optionElement.classList.remove('selected');
        });

        element.classList.add('selected');

        var questionData = getQuestionByStep(stepNumber);
        var selectedOptionId = Number(element.getAttribute('data-option-id'));

        if (questionData && selectedOptionId > 0) {
            quizAnswers[questionData.id] = selectedOptionId;
        }
    }

    function updateHeader(step) {
        var phaseLabel = document.getElementById('quizPhaseLabel');
        var title = document.getElementById('quizTitle');
        var stepLabel = document.getElementById('quizStepLabel');
        var percentLabel = document.getElementById('quizPercentLabel');
        var progressBar = document.getElementById('quizProgressBar');

        if (step <= totalQuestions) {
            var questionData = getQuestionByStep(step);
            var pct = totalQuestions > 0 ? Math.round((step / totalQuestions) * 100) : 0;
            var normalizedStepLabel = step < 10 ? '0' + step : String(step);

            phaseLabel.textContent = 'Assessment Phase: ' + normalizedStepLabel;
            title.textContent = questionData ? questionData.phase_title : 'Diagnostic Precision Quiz';
            stepLabel.textContent = 'Step ' + step + ' of ' + totalQuestions;
            percentLabel.textContent = pct + '% Complete';
            progressBar.style.width = pct + '%';
        } else if (step === totalQuestions + 1) {
            phaseLabel.textContent = 'Final Step';
            title.textContent = 'Claim Your Results';
            stepLabel.textContent = '';
            percentLabel.textContent = '100% Complete';
            progressBar.style.width = '100%';
        } else if (step === totalQuestions + 2) {
            phaseLabel.textContent = 'Assessment Complete';
            title.textContent = 'Your Results';
            stepLabel.textContent = '';
            percentLabel.textContent = 'Complete';
            progressBar.style.width = '100%';
        }
    }

    function goToStep(step) {
        var allSteps = document.querySelectorAll('.quiz-step');
        allSteps.forEach(function (stepElement) {
            stepElement.classList.remove('active');
        });

        var target = document.querySelector('[data-step="' + step + '"]');
        if (target) {
            target.classList.add('active');
        }

        currentStep = step;
        updateHeader(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentQuestionSelection(step) {
        var questionData = getQuestionByStep(step);

        if (!questionData) {
            return true;
        }

        if (quizAnswers[questionData.id]) {
            return true;
        }

        if (window.BiogenixToast) {
            window.BiogenixToast.show('Please select one answer before moving ahead.', 'warning');
        }

        return false;
    }

    function nextStep(step) {
        if (currentStep <= totalQuestions && !validateCurrentQuestionSelection(currentStep)) {
            return;
        }

        goToStep(step);
    }

    function prevStep(step) {
        goToStep(step);
    }

    function clearFieldValidationState(field) {
        if (!field) {
            return;
        }

        field.classList.remove('border-rose-400', 'ring-4', 'ring-rose-500/10');
    }

    function markFieldInvalid(field) {
        if (!field) {
            return;
        }

        field.classList.add('border-rose-400', 'ring-4', 'ring-rose-500/10');
    }

    function validateLeadDetails() {
        var firstNameField = document.getElementById('quizFirstName');
        var lastNameField = document.getElementById('quizLastName');
        var emailField = document.getElementById('quizEmail');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        clearFieldValidationState(firstNameField);
        clearFieldValidationState(lastNameField);
        clearFieldValidationState(emailField);

        if (!firstNameField || firstNameField.value.trim() === '') {
            markFieldInvalid(firstNameField);

            if (window.BiogenixToast) {
                window.BiogenixToast.show('Please enter the first name before submitting the quiz.', 'warning');
            }

            return false;
        }

        if (!emailField || !emailPattern.test(emailField.value.trim())) {
            markFieldInvalid(emailField);

            if (window.BiogenixToast) {
                window.BiogenixToast.show('Please enter a valid email address before submitting the quiz.', 'warning');
            }

            return false;
        }

        return true;
    }

    function buildSelectedAnswersPayload() {
        var selectedAnswers = {};

        quizQuestions.forEach(function (questionData) {
            if (quizAnswers[questionData.id]) {
                selectedAnswers[questionData.id] = quizAnswers[questionData.id];
            }
        });

        return selectedAnswers;
    }

    function toggleSubmitButtonState(isSubmitting) {
        var submitButton = document.getElementById('quizSubmitButton');

        if (!submitButton) {
            return;
        }

        if (!quizSubmitButtonDefaultHtml) {
            quizSubmitButtonDefaultHtml = submitButton.innerHTML;
        }

        submitButton.disabled = isSubmitting;
        submitButton.classList.toggle('cursor-not-allowed', isSubmitting);
        submitButton.classList.toggle('opacity-70', isSubmitting);

        if (isSubmitting) {
            submitButton.innerHTML = 'Submitting Quiz...';
            return;
        }

        submitButton.innerHTML = quizSubmitButtonDefaultHtml;
    }

    function applyQuizResult(resultData) {
        var resultTitle = document.getElementById('quizResultTitle');
        var resultDescription = document.getElementById('quizResultDescription');
        var voucherCode = document.getElementById('voucherCode');
        var performanceBreakdown = Array.isArray(resultData.performance_breakdown) ? resultData.performance_breakdown : [];

        if (resultTitle) {
            resultTitle.innerHTML = resultData.result_title_html || 'Assessment Complete';
        }

        if (resultDescription) {
            resultDescription.textContent = resultData.result_description || '';
        }

        if (voucherCode) {
            voucherCode.textContent = resultData.reward_coupon_code || '';
            currentVoucherCode = voucherCode.textContent;
        }

        for (var index = 0; index < 3; index++) {
            var performanceItem = performanceBreakdown[index] || { label: 'Result Segment', percentage: 0 };
            var perfLabel = document.getElementById('quizPerfLabel' + (index + 1));
            var perfValue = document.getElementById('quizPerfValue' + (index + 1));
            var perfBar = document.getElementById('quizPerfBar' + (index + 1));

            if (perfLabel) perfLabel.textContent = performanceItem.label || 'Result Segment';
            if (perfValue) perfValue.textContent = String(performanceItem.percentage || 0) + '%';
            if (perfBar) {
                perfBar.setAttribute('data-perf', String(performanceItem.percentage || 0));
                perfBar.style.width = '0%';
            }
        }
    }

    function animateQuizResults(scorePercentage) {
        setTimeout(function () {
            var score = Number(scorePercentage || 0);
            var circumference = 534;
            var offset = circumference - (score / 100) * circumference;
            var ring = document.getElementById('scoreRingFill');
            var scoreValue = document.getElementById('scoreValue');

            if (ring) {
                ring.style.strokeDashoffset = offset;
            }

            var current = 0;
            var interval = setInterval(function () {
                current += 1;

                if (current > score) {
                    clearInterval(interval);
                    return;
                }

                if (scoreValue) {
                    scoreValue.textContent = current + '%';
                }
            }, 15);

            var performanceBars = [
                document.getElementById('quizPerfBar1'),
                document.getElementById('quizPerfBar2'),
                document.getElementById('quizPerfBar3'),
            ];

            performanceBars.forEach(function (performanceBar) {
                if (!performanceBar) {
                    return;
                }

                performanceBar.style.width = performanceBar.getAttribute('data-perf') + '%';
            });
        }, 300);
    }

    async function showResults() {
        if (totalQuestions === 0) {
            if (window.BiogenixToast) {
                window.BiogenixToast.show('Quiz questions are not available right now.', 'error');
            }

            return;
        }

        for (var stepNumber = 1; stepNumber <= totalQuestions; stepNumber++) {
            if (!validateCurrentQuestionSelection(stepNumber)) {
                goToStep(stepNumber);
                return;
            }
        }

        if (!validateLeadDetails()) {
            return;
        }

        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        var firstNameField = document.getElementById('quizFirstName');
        var lastNameField = document.getElementById('quizLastName');
        var emailField = document.getElementById('quizEmail');

        toggleSubmitButtonState(true);

        try {
            var response = await fetch(quizSubmitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    participant_first_name: firstNameField ? firstNameField.value.trim() : '',
                    participant_last_name: lastNameField ? lastNameField.value.trim() : '',
                    participant_email: emailField ? emailField.value.trim() : '',
                    selected_answers: buildSelectedAnswersPayload(),
                }),
            });

            var result = await response.json();

            if (!response.ok || result.status !== 'success') {
                if (window.BiogenixToast) {
                    window.BiogenixToast.show(result.message || 'Unable to submit the quiz right now.', 'error');
                }

                return;
            }

            applyQuizResult(result.data || {});
            goToStep(totalQuestions + 2);
            animateQuizResults((result.data || {}).score_percentage || 0);
        } catch (error) {
            if (window.BiogenixToast) {
                window.BiogenixToast.show('Unable to submit the quiz right now. Please try again.', 'error');
            }
        } finally {
            toggleSubmitButtonState(false);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        hydrateQuizContent();
    });

    function copyVoucher() {
        var code = document.getElementById('voucherCode');
        if (!code) return;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(code.textContent);
        } else {
            var range = document.createRange();
            range.selectNodeContents(code);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
            selection.removeAllRanges();
        }

        if (window.BiogenixToast) {
            window.BiogenixToast.show('Voucher code copied! Use ' + currentVoucherCode + ' at checkout.', 'success');
        }
    }
</script>
@endpush
