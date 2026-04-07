@extends('layouts.app')

@section('title', 'Diagnostic Precision Quiz – Biogenix')
@section('meta_description', 'Test your diagnostic knowledge with the Biogenix Kits Mastery quiz.')

@push('styles')
    <style>
        .quiz-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        @media (min-width: 768px) {
            .quiz-container {
                flex-direction: row;
                min-height: calc(100vh - 80px);
                /* Adjust based on your header height */
            }
        }

        .quiz-step {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .quiz-step.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .quiz-option {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .quiz-step[data-step="1"] .quiz-option.selected,
        .quiz-step[data-step="2-b2c"] .quiz-option.selected {
            background-color: #1A4D2E !important;
            /* Forest Green from theme.css */
            color: white !important;
            border-color: #1A4D2E !important;
            box-shadow: 0 10px 30px rgba(26, 77, 46, 0.15);
        }

        .quiz-step[data-step="1"] .quiz-option.selected .option-title,
        .quiz-step[data-step="2-b2c"] .quiz-option.selected .option-title {
            color: white !important;
        }

        .quiz-step[data-step="1"] .quiz-option.selected .option-desc,
        .quiz-step[data-step="2-b2c"] .quiz-option.selected .option-desc {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .quiz-step[data-step="1"] .quiz-option.selected .icon-box,
        .quiz-step[data-step="2-b2c"] .quiz-option.selected .icon-box {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .quiz-option.selected .check-mark {
            display: flex !important;
        }

        #scoreRingFill {
            transition: stroke-dashoffset 1.5s ease-in-out;
        }

        .left-panel-bg {
            background: url('{{ asset('upload/corousel/image3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .left-panel-overlay {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: grayscale(100%) opacity(30%);
        }

        .btn-quiz-primary {
            background-color: #1A4D2E;
            transition: all 0.3s ease;
        }

        .btn-quiz-primary:hover {
            background-color: #133D23;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 77, 46, 0.2);
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

        // We will inject our custom first step if needed or just use it as a wrapper
        $totalSteps = count($quizQuestionsPayload);
    @endphp

    <div class="mx-auto w-full max-w-[95%] lg:max-w-[90%] xl:max-w-[80%] py-4 md:py-6">
        <div
            class="quiz-container w-full bg-white rounded-3xl md:rounded-[2rem] overflow-hidden shadow-[0_20px_60px_rgba(26,77,46,0.08)] border border-slate-100">

            {{-- LEFT VISUAL PANEL --}}
            <div
                class="relative w-full md:w-[40%] xl:w-[42%] flex flex-col p-8 md:p-10 left-panel-bg overflow-hidden border-r border-slate-100">
                <div class="absolute inset-0 left-panel-overlay z-0"></div>

                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <p class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 mb-4 shadow-sm"
                            id="leftStepCounter">STEP 01 OF 04</p>
                        <h1 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold leading-[1.15] text-primary-900 tracking-tight transition-all duration-300"
                            id="leftStepTitle">
                            Let's Understand<br>Your Setup
                        </h1>
                    </div>

                    <div class="hidden md:block">
                        {{-- Decorative element if needed --}}
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT PANEL --}}
            <div class="flex-1 flex flex-col p-6 md:p-10 xl:p-12 overflow-y-auto">

                <div id="quizFormArea" class="max-w-4xl w-full mx-auto">

                    {{-- HEADER ROW (Per image) --}}
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-12 mb-6 md:mb-8">
                        <div class="lg:w-1/3">
                            <h3 class="font-display text-lg font-bold text-primary-900 leading-tight">
                                Personalized<br>Diagnostic<br>Pathways
                            </h3>
                        </div>
                        <div class="lg:w-2/3">
                            <p class="text-[13px] md:text-sm text-primary-800/60 leading-relaxed max-w-lg">
                                Different roles demand different diagnostic strategies. Whether you are delivering care,
                                managing operations, or driving distribution, aligning products with your workflow is the
                                first step toward efficiency and growth.
                            </p>
                        </div>
                    </div>

                    {{-- STEPS CONTAINER --}}
                    <div id="quizStepsWrapper">

                        {{-- STEP 01 (Image content hardcoded as first step) --}}
                        <div class="quiz-step active" data-step="1">
                            <section>
                                <p
                                    class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 mb-4 shadow-sm">
                                    QUESTION - 0</p>
                                <h2 class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6 max-w-2xl">
                                    Which of the following best describes you?
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                                    <!-- Card 1 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'doctor', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Doctor / Clinic Owner</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Focus on
                                                    patient care and history</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#fde047] items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'lab', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Laboratory / Hospital</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">
                                                    High-volume sample processing</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#fde047] items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 3 (Dealer - Selected in Image) -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'dealer', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Dealer / Distributor</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Inventory
                                                    and logistics flow</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#fde047] items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Card 4 -->
                                    <div class="quiz-option p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group flex flex-col justify-between min-h-[100px]"
                                        onclick="handleSelection(1, 'other', this)">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="icon-box w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-primary-700 transition-all">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4
                                                    class="option-title font-semibold text-sm text-primary-900 leading-tight mb-0.5">
                                                    Other</h4>
                                                <p class="option-desc text-[11px] text-primary-800/50 font-medium">Custom
                                                    workflow definition</p>
                                            </div>
                                        </div>
                                        <div
                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#fde047] items-center justify-center text-primary-900">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div id="otherField" class="mb-6">
                                    <label class="block text-xs font-bold text-primary-800/60 mb-2 ml-1">Please specify your
                                        role or organization type</label>
                                    <input type="text"
                                        class="w-full h-12 rounded-[var(--ui-radius-field)] border-none bg-primary-50/30 px-5 text-sm font-medium text-slate-900 ring-1 ring-slate-100 outline-none transition focus:ring-2 focus:ring-primary-600 focus:bg-white"
                                        placeholder="Enter details here...">
                                </div>

                                <div class="flex items-center justify-between mt-6">
                                    <button
                                        class="flex items-center gap-2 text-slate-400 font-bold text-sm tracking-wide transition hover:text-slate-800"
                                        onclick="prevStep()">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Back
                                    </button>
                                    <button
                                        class="btn-quiz-primary px-7 py-3.5 rounded-[var(--ui-radius-button)] text-white font-semibold text-sm tracking-tight flex items-center gap-2.5 shadow-lg"
                                        onclick="nextStep(2)">
                                        Continue to Step 02
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </div>
                            </section>
                        </div>

                        {{-- DYNAMIC STEP 2 (B2C) - Two-column layout per reference --}}
                        <div id="dynamicSteps">
                            <!-- B2C Layout -->
                            <div class="quiz-step" data-step="2-b2c" style="display: none;">
                                <div class="max-w-full w-full mx-auto">
                                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                                            <div class="flex-1 flex flex-col">
                                                <div class="mb-6">
                                                    <span
                                                        class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">
                                                        STEP 2 OF 4
                                                    </span>
                                                </div>
                                                <h2
                                                    class="font-display text-2xl md:text-3xl font-bold text-primary-900 leading-[1.15] tracking-tight mb-8">
                                                    Tell Us About Your<br>Practice</h2>
                                                <div class="mb-8">
                                                    <div class="w-full h-[3px] bg-slate-200 rounded-full overflow-hidden">
                                                        <div class="h-full w-[40%] bg-primary-600 rounded-full"></div>
                                                    </div>
                                                </div>
                                                <h3
                                                    class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6">
                                                    What best defines your area of practice?</h3>
                                                <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6">
                                                    <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                        onclick="handleSelection(2, 'general_physician', this)">
                                                        <div
                                                            class="icon-box w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-700 flex-shrink-0">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="option-title font-semibold text-sm text-primary-900">General
                                                            Physician</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="3.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                        onclick="handleSelection(2, 'diabetologist', this)">
                                                        <div
                                                            class="icon-box w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700 flex-shrink-0">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19h6" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="option-title font-semibold text-sm text-primary-900">Diabetologist</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="3.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                        onclick="handleSelection(2, 'cardiologist', this)">
                                                        <div
                                                            class="icon-box w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700 flex-shrink-0">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="option-title font-semibold text-sm text-primary-900">Cardiologist</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="3.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="quiz-option flex items-center gap-3 p-4 rounded-2xl bg-primary-50 border border-transparent cursor-pointer relative group transition hover:border-primary-200 hover:shadow-sm"
                                                        onclick="handleSelection(2, 'pathologist', this)">
                                                        <div
                                                            class="icon-box w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-700 flex-shrink-0">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="option-title font-semibold text-sm text-primary-900">Pathologist</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="3.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="quiz-option flex items-center gap-3 mb-4 py-2 cursor-pointer group"
                                                    onclick="handleSelection(2, 'other_spec', this)">
                                                    <div
                                                        class="w-5 h-5 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center flex-shrink-0 transition-colors group-[.selected]:border-primary-600">
                                                        <div
                                                            class="w-2.5 h-2.5 rounded-full bg-primary-600 scale-0 transition-transform group-[.selected]:scale-100">
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="option-title font-semibold text-sm text-primary-900">Other</span>
                                                </div>
                                                <div class="mb-10"><input type="text"
                                                        class="w-full h-12 rounded-[var(--ui-radius-field)] border-none bg-primary-50/30 px-5 text-sm font-medium text-slate-900 ring-1 ring-slate-100 outline-none transition focus:ring-2 focus:ring-primary-600 focus:bg-white"
                                                        placeholder="Type your specialization"></div>
                                                <div class="flex items-center justify-between mt-10">
                                                    <button
                                                        class="flex items-center gap-2 text-slate-400 font-bold text-sm tracking-wide transition hover:text-slate-800"
                                                        onclick="prevStep(1)"><svg class="w-4 h-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                        </svg> Previous</button>
                                                    <button
                                                        class="btn-quiz-primary px-7 py-3.5 rounded-[var(--ui-radius-button)] text-white font-semibold text-sm tracking-tight flex items-center gap-2.5 shadow-lg"
                                                        onclick="nextStep(3)">Continue <svg class="w-4 h-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg></button>
                                                </div>
                                            </div>
                                            <div class="hidden lg:flex lg:w-[40%] flex-shrink-0">
                                                <div
                                                    class="relative w-full h-full rounded-3xl overflow-hidden shadow-xl">
                                                    <img src="{{ asset('upload/corousel/image3.jpg') }}"
                                                        alt="Practice Diagnostics"
                                                        class="absolute inset-0 w-full h-full object-cover">
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/60 to-transparent">
                                                    </div>
                                                    <div class="absolute bottom-0 left-0 right-0 p-8">
                                                        <h3 class="font-display text-xl font-bold text-white mb-3">
                                                            Practice-Driven Diagnostics</h3>
                                                        <p class="text-sm text-white/80 leading-relaxed">Each medical
                                                            specialization has distinct diagnostic priorities. Choosing the
                                                            right tests and kits aligned with your practice can improve
                                                            patient outcomes while creating additional in-clinic value.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <!-- B2B Layout -->
                            <div class="quiz-step" data-step="2-b2b" style="display: none;">
                                <div class="max-w-full w-full mx-auto py-2 relative">
                                        <!-- Subtle background image for the B2B step (relative to inner container or full-bleed) -->
                                        <img src="{{ asset('upload/corousel/image2.jpg') }}" alt="Background"
                                            class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30 grayscale blur-[1px]">

                                        <div class="relative z-10 h-full flex flex-col justify-between">
                                            <div class="mb-8">
                                                <span
                                                    class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">
                                                    STEP 2 OF 4
                                                </span>
                                            </div>

                                            <!-- Header Area -->
                                            <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-12">
                                                <div class="md:w-[50%]">
                                                    <h2
                                                        class="font-display text-4xl md:text-5xl lg:text-[3.5rem] font-bold text-primary-900 leading-[1.05] tracking-tight">
                                                        Tell Us About Your<br>Business</h2>
                                                </div>
                                                <div class="md:w-[45%] md:text-right mt-2">
                                                    <h3
                                                        class="font-display text-lg md:text-xl font-bold text-primary-900 mb-3">
                                                        Business Model Alignment</h3>
                                                    <p
                                                        class="text-sm text-primary-800/80 leading-relaxed md:ml-auto md:max-w-md font-medium">
                                                        From distribution to procurement, every role in the supply chain
                                                        requires a different product mix, pricing structure, and logistics
                                                        strategy. Clarity here directly impacts margins and scalability.</p>
                                                </div>
                                            </div>

                                            <!-- Content Area -->
                                            <div class="mt-auto">
                                                <h3
                                                    class="font-display text-xl md:text-2xl font-bold text-primary-900 mb-6">
                                                    What best defines your business role?</h3>

                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-8">
                                                    <!-- Distributor -->
                                                    <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'distributor', this)">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-800 flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="font-bold text-primary-900 text-[15px]">Distributor</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Dealer / Trader -->
                                                    <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'dealer_trader', this)">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-800 flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                        </div>
                                                        <span class="font-bold text-primary-900 text-[15px]">Dealer /
                                                            Trader</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Diagnostic Laboratory -->
                                                    <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'diagnostic_lab', this)">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-800 flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                            </svg>
                                                        </div>
                                                        <span class="font-bold text-primary-900 text-[15px]">Diagnostic
                                                            Laboratory</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Hospital Procurement -->
                                                    <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'hospital_procurement', this)">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-800 flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                        </div>
                                                        <span class="font-bold text-primary-900 text-[15px]">Hospital
                                                            Procurement</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Wholesale Medical Supplier -->
                                                    <div class="quiz-option flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'wholesale', this)">
                                                        <div
                                                            class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-800 flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                            </svg>
                                                        </div>
                                                        <span class="font-bold text-primary-900 text-[15px]">Wholesale
                                                            Medical Supplier</span>
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Other -->
                                                    <div class="quiz-option flex flex-col justify-center p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer relative group transition hover:border-primary-200 hover:shadow-md [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                        onclick="handleSelection(2, 'other_b2b', this)">
                                                        <div class="flex items-center gap-4 mb-3">
                                                            <div
                                                                class="w-10 h-10 rounded-xl bg-primary-900 flex items-center justify-center text-white flex-shrink-0 shadow-inner group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                                </svg>
                                                            </div>
                                                            <span
                                                                class="font-bold text-primary-900 text-[15px]">Other</span>
                                                        </div>
                                                        <input type="text"
                                                            class="w-full h-10 rounded-lg bg-[#efefef] border-none px-3 text-[13px] font-medium text-slate-800 outline-none transition focus:ring-2 focus:ring-secondary-600 focus:bg-white"
                                                            placeholder="Specify your role..."
                                                            onclick="event.stopPropagation()">
                                                        <div
                                                            class="check-mark hidden absolute right-4 top-6 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="flex justify-between items-center w-full mt-2">
                                                    <button
                                                        class="flex items-center gap-1.5 text-primary-900 bg-[#e2e8e5] px-6 py-2.5 rounded-lg font-bold text-[14px] tracking-wide transition hover:bg-[#d1ddd5]"
                                                        onclick="prevStep(1)">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                        </svg> Back
                                                    </button>
                                                    <button
                                                        class="bg-primary-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm tracking-tight flex items-center gap-2 shadow-lg transition hover:bg-primary-800 ml-auto"
                                                        onclick="nextStep(3)">
                                                        Next <svg class="w-4 h-4 transform rotate-180" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- Step 3 B2B Layout -->
                            <div class="quiz-step" data-step="3-b2b" style="display: none;">
                                <div class="max-w-full w-full mx-auto py-2">
                                        <!-- Header / Progress Area -->
                                        <div class="mb-12">
                                            <div class="flex justify-between items-end mb-3">
                                                <div>
                                                    <span
                                                        class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">STEP
                                                        03 OF 05</span>
                                                    <h2
                                                        class="font-display text-2xl md:text-3xl font-bold text-primary-900 tracking-tight">
                                                        Business Scale</h2>
                                                </div>
                                                <div class="text-[13px] font-bold text-primary-800/80">60% Complete</div>
                                            </div>
                                            <!-- Progress bar -->
                                            <div class="w-full h-2.5 bg-[#e2e8e5] rounded-full overflow-hidden">
                                                <div class="h-full w-[60%] bg-primary-900 rounded-full"></div>
                                            </div>
                                        </div>

                                        <!-- Content Area -->
                                        <div class="flex flex-col md:flex-row gap-8 lg:gap-20">
                                            <!-- Left Info -->
                                            <div class="md:w-[40%] md:pt-4">
                                                <h3
                                                    class="font-display text-3xl md:text-4xl font-bold text-primary-900 leading-[1.15] tracking-tight mb-5">
                                                    Volume-Based<br>Optimization</h3>
                                                <p class="text-[15px] text-primary-800/80 leading-relaxed font-medium">As
                                                    your monthly volume increases, procurement strategy becomes critical.
                                                    Bulk alignment, pricing tiers, and supply consistency can significantly
                                                    improve operational profitability.</p>
                                            </div>

                                            <!-- Right Card -->
                                            <div class="md:w-[60%]">
                                                <div class="relative z-10 w-full">
                                                    <h3
                                                        class="font-display text-xl font-bold text-primary-900 mb-8 tracking-tight">
                                                        What is your approximate monthly business volume?</h3>

                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 mb-10">
                                                        <!-- TIER 1 -->
                                                        <div class="quiz-option p-5 rounded-2xl border-2 border-transparent bg-primary-50/50 cursor-pointer relative transition hover:border-primary-200 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                            onclick="handleSelection(3, 'tier1', this)">
                                                            <p
                                                                class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                                TIER 1</p>
                                                            <h4
                                                                class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                                ₹50K - ₹2L</h4>
                                                            <p
                                                                class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                                </svg> Standard Procurement
                                                            </p>
                                                            <div
                                                                class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                                <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- TIER 2 -->
                                                        <div class="quiz-option p-5 rounded-2xl border-2 border-transparent bg-primary-50/50 cursor-pointer relative transition hover:border-primary-200 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                            onclick="handleSelection(3, 'tier2', this)">
                                                            <p
                                                                class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                                TIER 2</p>
                                                            <h4
                                                                class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                                ₹2L - ₹5L</h4>
                                                            <p
                                                                class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                                </svg> Managed Supply
                                                            </p>
                                                            <div
                                                                class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                                <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- TIER 3 -->
                                                        <div class="quiz-option p-5 rounded-2xl border-2 border-transparent bg-primary-50/50 cursor-pointer relative transition hover:border-primary-200 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                            onclick="handleSelection(3, 'tier3', this)">
                                                            <p
                                                                class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                                TIER 3</p>
                                                            <h4
                                                                class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                                ₹5L - ₹10L</h4>
                                                            <p
                                                                class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                                </svg> Volume Optimized
                                                            </p>
                                                            <div
                                                                class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                                <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- TIER 4 -->
                                                        <div class="quiz-option p-5 rounded-2xl border-2 border-transparent bg-primary-50/50 cursor-pointer relative transition hover:border-primary-200 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600"
                                                            onclick="handleSelection(3, 'tier4', this)">
                                                            <p
                                                                class="text-[11px] font-bold tracking-[0.1em] text-primary-800/60 mb-2 uppercase">
                                                                TIER 4</p>
                                                            <h4
                                                                class="text-xl font-bold text-primary-900 mb-3 tracking-tight">
                                                                ₹10L+</h4>
                                                            <p
                                                                class="text-[13px] font-medium text-primary-800/70 flex items-center gap-1.5">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                                </svg> Enterprise Scale
                                                            </p>
                                                            <div
                                                                class="check-mark hidden absolute right-5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                                <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-between items-center w-full">
                                                        <button
                                                            class="flex items-center gap-1.5 text-primary-900 bg-[#e2e8e5] px-6 py-2.5 rounded-lg font-bold text-[14px] tracking-wide transition hover:bg-[#d1ddd5]"
                                                            onclick="prevStep(2)">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                            </svg> Back
                                                        </button>
                                                        <button
                                                            class="bg-primary-900 text-white px-8 py-3.5 rounded-xl font-bold text-[15px] tracking-tight flex items-center gap-2 shadow-lg transition hover:bg-primary-800 ml-auto"
                                                            onclick="nextStep(4)">
                                                            Next <svg class="w-4 h-4 transform rotate-180" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                            </div>

                            <!-- Step 3 B2C Layout -->
                            <div class="quiz-step" data-step="3-b2c" style="display: none;">
                                <div class="max-w-full w-full mx-auto py-4">
                                        <!-- Top Header part -->
                                        <div class="flex flex-col md:flex-row justify-between mb-16 items-start gap-12">
                                            <div class="md:w-1/3">
                                                <h3
                                                    class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">
                                                    SETUP ANALYSIS</h3>
                                                <div class="w-12 h-[3px] bg-primary-900 rounded-full"></div>
                                            </div>
                                            <div class="md:w-2/3 md:text-right">
                                                <h2
                                                    class="font-display text-4xl md:text-5xl font-bold text-primary-900 leading-[1.05] mb-5 tracking-tight">
                                                    In-House vs Outsourced<br>Diagnostics</h2>
                                                <p
                                                    class="text-[16px] font-medium text-primary-800/80 leading-relaxed md:ml-auto">
                                                    Moving from external dependency to in-house diagnostics can unlock
                                                    better control, faster reporting, and additional revenue streams—if
                                                    supported by the right product ecosystem.</p>
                                            </div>
                                        </div>

                                        <div class="relative z-10 w-full">
                                            <!-- Progress Row -->
                                            <div class="flex justify-between items-end mb-3">
                                                <h4
                                                    class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">
                                                    STEP 3 OF 4</h4>
                                                <span class="text-[12px] font-bold text-primary-900">Step 3 of 4</span>
                                            </div>
                                            <div class="w-full h-[3px] bg-slate-100 rounded-full mb-12">
                                                <div class="h-full w-[60%] bg-primary-900 rounded-full"></div>
                                            </div>

                                            <!-- Question -->
                                            <div class="text-center mb-10">
                                                <h2
                                                    class="font-display text-3xl md:text-[2.1rem] font-bold text-primary-900 mb-3 tracking-tight">
                                                    How do you currently manage diagnostics?</h2>
                                                <p class="text-[15px] font-medium text-primary-800/70 italic">Select the
                                                    primary model for your clinical facility</p>
                                            </div>

                                            <!-- Options Stack -->
                                            <div class="flex flex-col gap-4 md:gap-5 mb-14 max-w-2xl mx-auto">
                                                <!-- Option 1 -->
                                                <div class="quiz-option flex items-center gap-5 p-5 md:p-6 rounded-2xl bg-[#f8faf9] border border-transparent cursor-pointer transition hover:bg-primary-50/50 hover:border-primary-100 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                                    onclick="handleSelection(3, 'external_labs', this)">
                                                    <div
                                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </div>
                                                    <span class="font-semibold text-[16px] text-primary-900">Fully dependent
                                                        on external labs</span>
                                                    <div
                                                        class="check-mark hidden ml-auto w-7 h-7 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Option 2 -->
                                                <div class="quiz-option flex items-center gap-5 p-5 md:p-6 rounded-2xl bg-[#f8faf9] border border-transparent cursor-pointer transition hover:bg-primary-50/50 hover:border-primary-100 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                                    onclick="handleSelection(3, 'hybrid', this)">
                                                    <div
                                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                                        </svg>
                                                    </div>
                                                    <span class="font-semibold text-[16px] text-primary-900">Partially
                                                        in-house, partially outsourced</span>
                                                    <div
                                                        class="check-mark hidden ml-auto w-7 h-7 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Option 3 -->
                                                <div class="quiz-option flex items-center gap-5 p-5 md:p-6 rounded-2xl bg-[#f8faf9] border border-transparent cursor-pointer transition hover:bg-primary-50/50 hover:border-primary-100 shadow-sm [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                                    onclick="handleSelection(3, 'in_house', this)">
                                                    <div
                                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary-900 shadow-sm flex-shrink-0 group-[.selected]:bg-secondary-600 group-[.selected]:text-primary-900 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                        </svg>
                                                    </div>
                                                    <span class="font-semibold text-[16px] text-primary-900">Fully in-house
                                                        setup</span>
                                                    <div
                                                        class="check-mark hidden ml-auto w-7 h-7 rounded-full bg-secondary-600 items-center justify-center text-primary-900">
                                                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="flex justify-between items-center w-full max-w-2xl mx-auto border-t border-slate-100 pt-8">
                                                <button
                                                    class="flex items-center gap-1.5 text-primary-900 bg-[#e2e8e5] px-6 py-2.5 rounded-lg font-bold text-[14px] tracking-wide transition hover:bg-[#d1ddd5]"
                                                    onclick="prevStep(2)">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                    </svg> Back
                                                </button>
                                                <button
                                                    class="bg-primary-900 text-white px-8 py-3.5 rounded-xl font-bold text-[15px] tracking-tight flex items-center gap-2 shadow-lg transition hover:bg-primary-800 ml-auto"
                                                    onclick="nextStep(4)">
                                                    Next <svg class="w-4 h-4 transform rotate-180" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                    </svg>
                                                </button>
                                            </div>
                                    </div>
                            </div>
                        </div>

                        <!-- Step 4-b2b Container -->
                        <div class="quiz-step" data-step="4-b2b" style="display: none;">
                                <div class="max-w-full w-full mx-auto">

                                <!-- Header Section -->
                                <div
                                    class="max-w-[950px] w-[85%] mx-auto mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                                    <div>
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">STEP
                                            4 OF 5</span>
                                        <h2
                                            class="font-display text-4xl md:text-[44px] font-bold text-primary-900 tracking-tight leading-[1.05]">
                                            Product Focus Areas</h2>
                                    </div>
                                    <div class="md:w-1/2 md:text-right">
                                        <h3 class="text-xl md:text-[18px] font-bold text-primary-900 mb-1.5">Portfolio
                                            Strength</h3>
                                        <p
                                            class="text-[14px] md:text-[14px] font-medium text-primary-800/70 leading-relaxed md:ml-auto max-w-[320px]">
                                            A well-balanced product portfolio across key diagnostic categories ensures
                                            operational resilience and clinical breadth.</p>
                                    </div>
                                </div>

                                <!-- Main Card Section -->
                                <div class="relative z-10 w-full mb-12">

                                    <!-- Question Badge & Title -->
                                    <div class="flex items-center gap-4 mb-5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-secondary-600 text-primary-900 flex items-center justify-center text-[11px] font-bold shadow-sm">
                                            4</div>
                                        <span
                                            class="text-[12px] font-bold uppercase tracking-[0.1em] text-primary-900">Question
                                            4</span>
                                    </div>

                                    <h3
                                        class="font-display text-2xl md:text-[28px] font-bold text-primary-900 mb-8 tracking-tight">
                                        Which product categories are you currently dealing in or exploring?</h3>

                                    <!-- Options Grid (Multi-Select) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-10">
                                        <!-- Option 1 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">Rapid Test Kits</span>
                                        </div>

                                        <!-- Option 2 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">ELISA</span>
                                        </div>

                                        <!-- Option 3 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">Biochemistry</span>
                                        </div>

                                        <!-- Option 4 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">Molecular
                                                Diagnostics</span>
                                        </div>

                                        <!-- Option 5 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">Instruments</span>
                                        </div>

                                        <!-- Option 6 -->
                                        <div class="flex flex-col justify-between h-[110px] p-5 rounded-2xl bg-white border border-transparent cursor-pointer transition hover:border-secondary-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] [&.selected]:ring-1 [&.selected]:ring-secondary-600 group"
                                            onclick="this.classList.toggle('selected')">
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600">
                                                <svg class="w-3.5 h-3.5 text-primary-900 opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-[15px] text-primary-900">Consumables</span>
                                        </div>
                                    </div>

                                    <!-- Other Specify Input -->
                                    <div class="mb-10">
                                        <label class="block font-bold text-[14px] text-primary-900 mb-2.5">Other
                                            (Specify)</label>
                                        <input type="text" placeholder="Specify any specialized diagnostic niche..."
                                            class="w-full xl:w-[70%] bg-[#e5e9e7] border border-transparent rounded-xl px-5 py-3.5 focus:ring-2 focus:ring-primary-900 focus:border-primary-900/20 outline-none text-primary-900 font-bold text-[14px] placeholder-primary-800/50 transition-shadow">
                                    </div>

                                    <!-- Pagination -->
                                    <div class="flex justify-between items-center w-full pt-2">
                                        <button
                                            class="flex items-center gap-1.5 text-primary-900 bg-[#e2e8e5] px-6 py-2.5 rounded-lg font-bold text-[14px] tracking-wide transition hover:bg-[#d1ddd5]"
                                            onclick="prevStep(3)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg> Back
                                        </button>
                                        <button
                                            class="bg-primary-900 text-white px-7 py-3 rounded-xl font-bold text-[14px] tracking-tight flex items-center gap-2 shadow-lg transition hover:bg-primary-800 ml-auto"
                                            onclick="nextStep(5)">
                                            Next <svg class="w-3.5 h-3.5 transform rotate-180" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Common Layout for B2C Step 4 & B2B Step 5 -->
                        <div class="quiz-step" data-step="5-common" style="display: none;">
                            <div class="w-full">

                                <!-- Header Section -->
                                <div
                                    class="max-w-[950px] w-[85%] mx-auto mb-10 flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="md:w-[55%]">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-4">PHASE
                                            05</span>
                                        <h2
                                            class="font-display text-4xl md:text-[45px] font-bold text-primary-900 tracking-tight leading-[1.05] mb-5">
                                            Identify Your Business<br>Growth Levers</h2>
                                    </div>
                                    <div class="md:w-[40%] bg-[#f4f2ef] rounded-2xl p-6 md:p-8">
                                        <h3 class="text-[11px] font-bold uppercase tracking-widest text-[#4d5c52] mb-3">
                                            Strategic Growth Focus</h3>
                                        <p class="text-[13px] md:text-[14px] font-medium text-primary-800/80 leading-loose">
                                            Improving pricing, supply reliability, or expanding your offerings are not
                                            isolated decisions. The right combination of these factors creates long-term
                                            operational advantage in diagnostics.</p>
                                    </div>
                                </div>

                                <!-- Main Card Section -->
                                <div class="relative z-10 w-full mb-12">

                                    <!-- Header / Progress Area -->
                                    <div class="mb-10">
                                        <div class="flex justify-between items-end mb-3">
                                            <span
                                                class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm">STEP
                                                05 OF 05</span>
                                            <span class="text-[12px] font-bold text-primary-900">Step 5 of 5</span>
                                        </div>
                                        <div class="w-full h-2 bg-[#f0f4f2] rounded-full overflow-hidden">
                                            <div class="h-full w-[90%] bg-primary-900 rounded-full"></div>
                                        </div>
                                    </div>

                                    <!-- Question Badge & Title -->
                                    <div class="flex items-center gap-4 mb-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-secondary-600 text-primary-900 flex items-center justify-center text-[11px] font-bold shadow-sm flex-shrink-0">
                                            5</div>
                                        <h3 class="text-[19px] md:text-[21px] font-bold text-primary-900 tracking-tight">
                                            Select your primary focus areas:</h3>
                                    </div>
                                    <p class="text-[14px] font-medium text-slate-500 mb-8 ml-12 italic">Multi-select
                                        available. Choose all that apply to your current roadmap.</p>

                                    <!-- Options Grid (Multi-Select) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10 lg:px-6">
                                        <!-- Option 1 -->
                                        <div class="flex items-center justify-between h-[95px] px-6 rounded-[14px] bg-white border-2 border-transparent cursor-pointer transition shadow-sm hover:border-secondary-300 [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] group"
                                            onclick="this.classList.toggle('selected')">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[16px] text-primary-900 mb-1">Better pricing
                                                    margins</span>
                                                <span class="text-[12.5px] text-slate-500 font-medium">Optimize procurement
                                                    and revenue cycle</span>
                                            </div>
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 2 -->
                                        <div class="flex items-center justify-between h-[95px] px-6 rounded-[14px] bg-[#eef1ef] border-2 border-transparent cursor-pointer transition hover:border-secondary-300 [&.selected]:border-secondary-600 [&.selected]:bg-white group"
                                            onclick="this.classList.toggle('selected')">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[16px] text-primary-900 mb-1">Reliable and
                                                    consistent supply</span>
                                                <span class="text-[12.5px] text-slate-500 font-medium">Ensure laboratory
                                                    uptime and material access</span>
                                            </div>
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-white bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0 shadow-[0_1px_3px_rgba(0,0,0,0.1)] group-[.selected]:shadow-none">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 3 -->
                                        <div class="flex items-center justify-between h-[95px] px-6 rounded-[14px] bg-[#eef1ef] border-2 border-transparent cursor-pointer transition hover:border-secondary-300 [&.selected]:border-secondary-600 [&.selected]:bg-white group"
                                            onclick="this.classList.toggle('selected')">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[16px] text-primary-900 mb-1">Expanding product
                                                    portfolio</span>
                                                <span class="text-[12.5px] text-slate-500 font-medium">Introduce new
                                                    diagnostic capabilities</span>
                                            </div>
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-white bg-white flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0 shadow-[0_1px_3px_rgba(0,0,0,0.1)] group-[.selected]:shadow-none">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Option 4 -->
                                        <div class="flex items-center justify-between h-[95px] px-6 rounded-[14px] bg-white border-2 border-transparent cursor-pointer transition shadow-sm hover:border-secondary-300 [&.selected]:border-secondary-600 [&.selected]:bg-[#fffdf5] group"
                                            onclick="this.classList.toggle('selected')">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[16px] text-primary-900 mb-1">Technical guidance
                                                    and support</span>
                                                <span class="text-[12.5px] text-slate-500 font-medium">Direct access to
                                                    clinical specialist insights</span>
                                            </div>
                                            <div
                                                class="w-5 h-5 rounded-[4px] border-2 border-slate-200 flex items-center justify-center transition-colors group-[.selected]:bg-secondary-600 group-[.selected]:border-secondary-600 ml-4 flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-white opacity-0 group-[.selected]:opacity-100 transition-opacity"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="flex justify-between items-center w-full mt-6 pt-8">
                                        <button
                                            class="flex items-center gap-1.5 text-primary-900 bg-[#e2e8e5] px-6 py-2.5 rounded-lg font-bold text-[14px] tracking-wide transition hover:bg-[#d1ddd5]"
                                            onclick="currentSelection.role === 'dealer' || currentSelection.role === 'lab' ? prevStep(4) : prevStep(3)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg> Back
                                        </button>
                                        <div class="flex items-center gap-6 ml-auto">
                                            <span class="italic text-[13px] text-slate-500 font-medium"
                                                id="commonStepCounter">Step 5 of 7 Complete</span>
                                            <button
                                                class="bg-primary-900 text-white px-7 py-3 rounded-[10px] font-bold text-[14px] tracking-tight flex items-center gap-2 shadow-lg shadow-primary-900/20 transition hover:bg-opacity-90"
                                                onclick="nextStep(6)">
                                                Next <svg class="w-3.5 h-3.5 transform rotate-180" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- Bottom Graphic Banner -->
                                <div
                                    class="max-w-[950px] w-[85%] mx-auto h-[160px] md:h-[220px] rounded-[2rem] overflow-hidden mt-10 relative bg-gradient-to-r from-[#eef3f0] via-[#ddebe2] to-[#eef3f0]">
                                    <!-- Subtle lighting abstract overlay -->
                                    <div class="absolute inset-0 bg-white/20"></div>
                                    <div
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-white opacity-50 blur-3xl rounded-full">
                                    </div>
                                    <!-- Minimal horizontal glow line -->
                                    <div class="absolute inset-x-0 top-1/2 h-[1px] bg-white/80 blur-[1px]"></div>
                                </div>
                            </div>
                        </div>

                        {{-- ────────── STEP 6: ASSESSMENT COMPLETE (LEAD FORM) ────────── --}}
                        <div class="quiz-step" data-step="6-lead" style="display: none;">
                            <div class="w-full">

                                <!-- Header Section - matches 5-common layout -->
                                <div
                                    class="max-w-[950px] w-[85%] mx-auto mb-10 flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="md:w-[55%]">
                                        <span
                                            class="inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 shadow-sm mb-6">ASSESSMENT
                                            COMPLETE</span>
                                        <h2
                                            class="font-display text-4xl md:text-[45px] font-bold text-primary-900 tracking-tight leading-[1.05] mb-5">
                                            Digital Concierge Insights<br>Now Ready</h2>
                                    </div>
                                    <div class="md:w-[40%] bg-[#f4f2ef] rounded-2xl p-6 md:p-8">
                                        <h3 class="text-[11px] font-bold uppercase tracking-widest text-[#4d5c52] mb-3">
                                            Analysis Completion</h3>
                                        <div
                                            class="flex items-center justify-between text-[13px] font-bold text-primary-900 mb-3">
                                            <span class="text-[14px] font-medium text-primary-800/80">Your profile
                                                assessment is complete.</span>
                                            <span>100%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-white/60 rounded-full overflow-hidden">
                                            <div class="h-full w-full bg-secondary-600 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Card Section - matches 5-common layout -->
                                <div class="relative z-10 w-full mb-12">

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                                        <!-- Left: Qualification Info -->
                                        <div>
                                            <h4 class="text-[17px] leading-snug font-bold text-primary-900 mb-2">Based on
                                                your inputs, we've identified the right product pathways and pricing
                                                opportunities for your profile.</h4>
                                            <p
                                                class="mt-3 text-[12px] font-bold uppercase tracking-wider text-slate-400 mb-5">
                                                You now qualify for:</p>
                                            <ul class="space-y-3">
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Priority access to relevant product categories
                                                </li>
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Preferential pricing aligned with your scale
                                                </li>
                                                <li class="flex items-start gap-3 text-[14px] font-medium text-primary-800">
                                                    <div
                                                        class="w-5 h-5 mt-0.5 rounded-full bg-secondary-100 flex items-center justify-center flex-shrink-0 text-secondary-700">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    Direct support from the Biogenix team
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Right: Lead Capture Form -->
                                        <div class="bg-[#f8faf9] rounded-2xl p-6 md:p-8 border border-slate-100">
                                            <div class="grid grid-cols-2 gap-4 mb-5">
                                                <div>
                                                    <label
                                                        class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-slate-500">First
                                                        Name <span class="text-red-400">*</span></label>
                                                    <input type="text" id="quizFirstName" required
                                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-900 focus:border-primary-900/20 outline-none text-primary-900 font-bold text-[14px] placeholder-slate-400 transition-shadow"
                                                        placeholder="John">
                                                </div>
                                                <div>
                                                    <label
                                                        class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-slate-500">Last
                                                        Name <span class="text-red-400">*</span></label>
                                                    <input type="text" id="quizLastName" required
                                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-900 focus:border-primary-900/20 outline-none text-primary-900 font-bold text-[14px] placeholder-slate-400 transition-shadow"
                                                        placeholder="Doe">
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <label
                                                    class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-slate-500">Email
                                                    Address <span class="text-red-400">*</span></label>
                                                <input type="email" id="quizEmail" required
                                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-900 focus:border-primary-900/20 outline-none text-primary-900 font-bold text-[14px] placeholder-slate-400 transition-shadow"
                                                    placeholder="john.doe@medical-cloud.com">
                                            </div>
                                            <p id="leadFormError" class="text-red-500 text-[12px] font-bold mb-3 hidden">
                                                Please fill in all required fields.</p>

                                            <button type="button" id="quizSubmitButton"
                                                class="w-full bg-primary-900 text-white px-7 py-3.5 rounded-xl font-bold text-[14px] tracking-tight flex items-center justify-center gap-2 shadow-lg shadow-primary-900/20 transition hover:bg-primary-800"
                                                onclick="submitLeadForm()">
                                                Unlock My Score & Reward
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>

                                            <p
                                                class="mt-5 flex items-center justify-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Secure clinical gateway
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ────────── STEP 7: RESULTS ────────── --}}
                        <div class="quiz-step" data-step="results" style="display: none;">
                            <div class="w-full">

                                <!-- Results Header - matches 5-common layout -->
                                <div class="max-w-full w-full mx-auto mb-10">
                                    <span
                                        class="inline-block px-4 py-1.5 rounded-full bg-secondary-600 text-primary-900 text-[11px] font-bold tracking-widest uppercase shadow-sm mb-6">Assessment
                                        Complete</span>
                                    <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                                        <div class="lg:w-[55%]">
                                            <h2 class="font-display text-4xl md:text-[45px] font-bold tracking-tight text-primary-900 leading-[1.05]"
                                                id="quizResultTitle">Advanced<br>Proficiency Level<br>Attained.</h2>
                                            <p class="mt-5 max-w-md text-[14px] leading-relaxed text-slate-500"
                                                id="quizResultDescription">Your technical precision in diagnostic protocols
                                                demonstrates exceptional mastery of Biogenix standards and laboratory
                                                compliance.</p>
                                        </div>
                                        <div class="lg:w-[40%] flex items-center justify-center">
                                            <div class="relative h-48 w-48">
                                                <svg viewBox="0 0 200 200" class="h-full w-full">
                                                    <circle class="stroke-slate-200" cx="100" cy="100" r="85" fill="none"
                                                        stroke-width="8" />
                                                    <circle
                                                        class="stroke-secondary-600 transition-[stroke-dashoffset] duration-[1200ms] ease-in-out"
                                                        id="scoreRingFill" cx="100" cy="100" r="85" fill="none"
                                                        stroke-width="10" stroke-linecap="round" stroke-dasharray="534"
                                                        stroke-dashoffset="134" transform="rotate(-90 100 100)" />
                                                </svg>
                                                <div
                                                    class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                                                    <span
                                                        class="font-display text-[24px] leading-tight font-extrabold text-primary-900 mt-2"
                                                        id="scoreValue">Strong<br>Match</span>
                                                    <span
                                                        class="mt-2 text-[9px] font-bold uppercase tracking-[0.15em] text-slate-400">Profile
                                                        Alignment Score</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Insight Summary Card - matches 5-common layout -->
                                <div class="relative z-10 w-full mb-12">
                                    <h3 class="flex items-center gap-3 text-[20px] font-bold text-primary-900">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        Profile Insight Summary
                                    </h3>

                                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {{-- 1. Diagnostic Fit --}}
                                        <div
                                            class="rounded-2xl border-2 border-primary-50 hover:border-primary-100 bg-[#fbfdfc] p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-900 mb-4 font-bold text-[13px]">
                                                1</div>
                                            <p class="text-[15px] font-bold text-primary-900 mb-2">Diagnostic Fit</p>
                                            <p class="text-[13px] leading-relaxed text-slate-500 min-h-[60px]">How well your
                                                current setup aligns with scalable diagnostic workflows.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-700 shadow-sm border border-slate-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-secondary-600 animate-pulse"></span>
                                                <span id="insightOutcome1">Optimal Alignment</span>
                                            </div>
                                        </div>

                                        {{-- 2. Procurement Efficiency --}}
                                        <div
                                            class="rounded-2xl border-2 border-blue-50 hover:border-blue-100 bg-[#f8fbfe] p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-900 mb-4 font-bold text-[13px]">
                                                2</div>
                                            <p class="text-[15px] font-bold text-blue-900 mb-2">Procurement Efficiency</p>
                                            <p class="text-[13px] leading-relaxed text-slate-500 min-h-[60px]">How optimized
                                                your pricing strategy currently is.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-700 shadow-sm border border-slate-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                <span id="insightOutcome2">High Potential</span>
                                            </div>
                                        </div>

                                        {{-- 3. Strategy Alignment --}}
                                        <div
                                            class="rounded-2xl border-2 border-orange-50 hover:border-orange-100 bg-[#fdfbf9] p-6 transition-all duration-300">
                                            <div
                                                class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-secondary-700 mb-4 font-bold text-[13px]">
                                                3</div>
                                            <p class="text-[15px] font-bold text-primary-900 mb-2">Platform Strategy</p>
                                            <p class="text-[13px] leading-relaxed text-slate-500 min-h-[60px]">How well your
                                                focus matches high-demand categories.</p>
                                            <div
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-700 shadow-sm border border-slate-100 w-full">
                                                <span class="h-2 w-2 rounded-full bg-secondary-600"></span>
                                                <span id="insightOutcome3">Strong Match</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-10 flex border-t border-slate-100 pt-8 items-center justify-between">
                                        <p class="text-[13px] font-medium text-slate-500">Our specialized team will reach
                                            out with your custom portfolio shortly.</p>
                                        <a href="/"
                                            class="bg-primary-900 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] tracking-tight shadow-md transition hover:bg-primary-800">Return
                                            to Home</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        let currentSelection = {
            role: ''
        };

        function handleSelection(step, value, el) {
            const parentStep = el.closest('.quiz-step');
            parentStep.querySelectorAll('.quiz-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');

            if (step === 1) {
                currentSelection.role = value;
            }
        }

        function getActualStep(step) {
            if (step === 2 || step === 3) {
                // Lab and Dealer branch to B2B
                if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') {
                    return step + '-b2b';
                } else {
                    return step + '-b2c';
                }
            }
            if (step === 4) {
                if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') return '4-b2b';
                return '5-common'; // B2C converges here at step 4
            }
            if (step === 5) {
                return '5-common'; // B2B converges here at step 5
            }
            if (step === 6) {
                return '6-lead';
            }
            if (step === 7) {
                return 'results';
            }
            return step;
        }

        function updateLayoutForStep(actualStep) {
            const leftPanel = document.querySelector('.left-panel-bg');
            const headerRow = document.getElementById('quizFormArea').querySelector('.flex.flex-col.lg\\:flex-row');
            const rightPanel = document.querySelector('.flex-1.flex.flex-col');

            if (actualStep === 1) {
                if (leftPanel) leftPanel.style.display = '';
                if (headerRow) headerRow.style.display = '';
                if (rightPanel) rightPanel.className = 'flex-1 flex flex-col p-6 md:p-12 xl:p-20 overflow-y-auto';
                document.getElementById('quizFormArea').classList.add('max-w-4xl');
                document.getElementById('quizFormArea').classList.remove('max-w-full');
                const counter = document.getElementById('leftStepCounter');
                if (counter) {
                    counter.innerText = 'STEP 01 OF 04';
                    counter.className = 'inline-flex w-fit rounded-full bg-secondary-600 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary-900 mb-4 shadow-sm';
                }
                document.getElementById('leftStepTitle').innerHTML = 'Let\'s Understand<br>Your Setup';
            } else {
                // All other steps: hide left panel and header
                if (leftPanel) leftPanel.style.display = 'none';
                if (headerRow) headerRow.style.display = 'none';
                document.getElementById('quizFormArea').classList.remove('max-w-4xl');
                document.getElementById('quizFormArea').classList.add('max-w-full');

                if (rightPanel) {
                    if (actualStep === '2-b2c') {
                        rightPanel.className = 'flex-1 flex flex-col p-6 md:p-8 xl:p-12 overflow-y-auto';
                    } else {
                        rightPanel.className = 'flex-1 flex flex-col p-0 overflow-y-auto';
                    }
                }

                // Update step counter on 5-common based on path
                if (actualStep === '5-common') {
                    const counter = document.getElementById('commonStepCounter');
                    const badge = document.querySelector('[data-step="5-common"] .inline-flex.rounded-full.bg-secondary-600');
                    if (counter && badge) {
                        if (currentSelection.role === 'dealer' || currentSelection.role === 'lab') {
                            counter.textContent = 'Step 5 of 5';
                            badge.textContent = 'STEP 05 OF 05';
                        } else {
                            counter.textContent = 'Step 4 of 4';
                            badge.textContent = 'STEP 04 OF 04';
                        }
                    }
                }

                // Update step counter on 6-lead based on path
                if (actualStep === '6-lead') {
                    const counter = document.getElementById('leadStepCounter');
                    const badge = document.querySelector('[data-step="6-lead"] .inline-flex.rounded-full.bg-secondary-600');
                    if (badge) {
                        badge.textContent = 'ASSESSMENT COMPLETE';
                    }
                }
            }
        }

        function nextStep(step) {
            const active = document.querySelector('.quiz-step.active');
            let actualStep = getActualStep(step);

            if (!active.querySelector('.quiz-option.selected') && !active.querySelector('.selected') && actualStep !== 'results' && active.dataset.step !== '6-lead' && active.dataset.step !== '5-common') {
                alert('Please select an option');
                return;
            }

            active.classList.remove('active');
            active.style.display = 'none';

            const nextEl = document.querySelector('.quiz-step[data-step="' + actualStep + '"]');
            if (nextEl) {
                nextEl.style.display = 'block';
                setTimeout(() => nextEl.classList.add('active'), 10);
            }

            updateLayoutForStep(actualStep);
        }

        function prevStep(stepTarget) {
            const active = document.querySelector('.quiz-step.active');

            if (stepTarget === undefined || !stepTarget) {
                window.history.back();
                return;
            }

            let actualStep = getActualStep(stepTarget);

            active.classList.remove('active');
            active.style.display = 'none';

            const prevEl = document.querySelector('.quiz-step[data-step="' + actualStep + '"]');
            if (prevEl) {
                prevEl.style.display = 'block';
                setTimeout(() => prevEl.classList.add('active'), 10);
            }

            updateLayoutForStep(actualStep);
        }

        function submitLeadForm() {
            const firstName = document.getElementById('quizFirstName') ? document.getElementById('quizFirstName').value.trim() : '';
            const lastName = document.getElementById('quizLastName') ? document.getElementById('quizLastName').value.trim() : '';
            const email = document.getElementById('quizEmail') ? document.getElementById('quizEmail').value.trim() : '';

            // Store lead data
            currentSelection.firstName = firstName;
            currentSelection.lastName = lastName;
            currentSelection.email = email;

            // Navigate to results
            const active = document.querySelector('.quiz-step.active');
            if (active) {
                active.classList.remove('active');
                active.style.display = 'none';
            }

            const resultsEl = document.querySelector('.quiz-step[data-step="results"]');
            if (resultsEl) {
                resultsEl.style.display = 'block';
                setTimeout(() => resultsEl.classList.add('active'), 10);
            }

            updateLayoutForStep('results');
        }
    </script>
@endsection