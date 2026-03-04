@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <h1>Reset Password</h1>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', request('email')) }}" required>
            </div>

            <div class="field">
                <label for="password">New Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <button class="btn" type="submit">Reset Password</button>
        </form>
    </div>
@endsection
