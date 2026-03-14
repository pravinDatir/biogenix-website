@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
    <div class="mx-auto w-full max-w-[1280px] px-4 py-8 sm:px-6 md:py-10 lg:px-8 xl:px-10">
        <div class="mb-6 max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Confirmation</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Order confirmation</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">
                Review the confirmed order number, invoice reference, email confirmation, and immediate next steps from one place.
            </p>
        </div>

        @include('customer.partials.order-confirmation-panel')
    </div>
@endsection
