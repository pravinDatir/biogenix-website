@props([
    'id',
    'name',
    'label' => null,
    'hint' => null,
    'multiple' => false,
    'accept' => null,
    'errorKey' => null,
    'disabled' => false,
])

@php
    use Illuminate\Support\Str;

    $fieldKey = $errorKey ?: Str::of($name)->before('[')->value();
    $messages = array_merge($errors->get($fieldKey), $errors->get($fieldKey.'.*'));
@endphp

<div {{ $attributes->class(['space-y-2']) }}>
    @if ($label)
        <label for="{{ $id }}" class="text-sm font-semibold text-slate-700">{{ $label }}</label>
    @endif

    <div class="{{ $messages ? 'border-rose-300 bg-rose-50/40' : 'border-slate-300 bg-slate-50' }} rounded-2xl border border-dashed p-4">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="file"
            @if ($multiple) multiple @endif
            @if ($accept) accept="{{ $accept }}" @endif
            @disabled($disabled)
            class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-60"
        >

        @if ($hint)
            <p class="mt-2 text-xs leading-5 text-slate-500">{{ $hint }}</p>
        @endif
    </div>

    @if ($messages)
        <p class="text-sm text-rose-600">{{ $messages[0] }}</p>
    @endif
</div>
