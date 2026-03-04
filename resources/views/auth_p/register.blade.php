@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 620px; margin: 0 auto;">
        <h1>Register</h1>
        <p class="muted">Use this form for B2C and B2B users. B2B accounts require Admin approval before login.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="user_type">User Type</label>
                <select id="user_type" name="user_type" required>
                    <option value="b2c" @selected(old('user_type', 'b2c') === 'b2c')>B2C Customer</option>
                    <option value="b2b" @selected(old('user_type') === 'b2b')>B2B User</option>
                </select>
            </div>

            <div class="field">
                <label for="b2b_type">B2B Type (required for B2B)</label>
                <select id="b2b_type" name="b2b_type">
                    <option value="">Select B2B type</option>
                    <option value="dealer" @selected(old('b2b_type') === 'dealer')>Dealer</option>
                    <option value="distributor" @selected(old('b2b_type') === 'distributor')>Distributor</option>
                    <option value="lab" @selected(old('b2b_type') === 'lab')>Lab</option>
                    <option value="hospital" @selected(old('b2b_type') === 'hospital')>Hospital</option>
                </select>
            </div>

            <div class="field">
                <label for="company_name">Company Name (required for B2B)</label>
                <input id="company_name" name="company_name" value="{{ old('company_name') }}">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <button class="btn" type="submit">Create Account</button>
        </form>
    </div>
@endsection
