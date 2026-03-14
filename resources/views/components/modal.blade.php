@props([
    'id' => 'ui-modal',
    'open' => false,
    'title' => null,
])

<div
    id="{{ $id }}"
    class="{{ $open ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <div class="w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-5 shadow-xl md:p-6">
        <div class="mb-4 flex items-center justify-between gap-4">
            @if($title)
                <h2 id="{{ $id }}-title" class="text-xl font-semibold tracking-tight text-slate-950">{{ $title }}</h2>
            @endif
            <button
                type="button"
                class="modal-close inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                data-modal-close="{{ $id }}"
            >
                Close
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
