@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Generate Proforma Invoice (PI)</h1>

        @guest
            <p class="muted">Guest users can generate PI for self or another customer with basic details only.</p>
        @endguest

        @auth
            @if (auth()->user()->isB2c())
                <p class="muted">B2C users can generate PI for self only.</p>
            @elseif (auth()->user()->isB2b())
                <p class="muted">B2B users can generate PI for self and assigned clients only (if permission is available).</p>
            @endif
        @endauth
    </div>

    <div class="card">
        <form method="POST" action="{{ route('proforma.store') }}">
            @csrf

            <div class="field">
                <label for="product_id">Product</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Select product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((int) old('product_id', $prefilledProductId) === (int) $product->id)>
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="quantity">Quantity</label>
                <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required>
            </div>

            <div class="field">
                <label for="purpose">PI Purpose</label>
                <select id="purpose" name="purpose" required>
                    <option value="self" @selected(old('purpose') === 'self')>Self</option>
                    <option value="other" @selected(old('purpose') === 'other')>Another Customer</option>
                </select>
            </div>

            <div class="field">
                <label for="customer_name">Customer Name</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
            </div>

            <div class="field">
                <label for="customer_email">Customer Email</label>
                <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
            </div>

            <div class="field">
                <label for="customer_phone">Customer Phone (Optional)</label>
                <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
            </div>

            @auth
                @if (auth()->user()->isB2b())
                    <div class="field">
                        <label for="target_company_id">Target Company (for B2B "other" flow)</label>
                        <select id="target_company_id" name="target_company_id">
                            <option value="">Not selected</option>
                            @foreach ($clientCompanies as $company)
                                <option value="{{ $company->id }}" @selected((int) old('target_company_id') === (int) $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            @endauth

            <div class="field">
                <label for="notes">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn">Generate PI</button>
        </form>
    </div>
@endsection
