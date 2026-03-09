@extends('layouts.app')

@section('content')
    <div class="card center-card">
        <h1>Login</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field">
                <label for="remember">
                    <input id="remember" name="remember" type="checkbox" value="1">
                    Remember me
                </label>
            </div>

            <button class="btn" type="submit">Login</button>
        </form>

        <p class="muted mt-3">
            New user? <a href="{{ route('register') }}">Register here</a>
        </p>
        <p class="muted">
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </p>
    </div>
@endsection
