@extends('layouts.app')

@section('content')
    <div class="card center-card">
        <h1>Forgot Password</h1>
        <p class="muted">Enter your email and we will send a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <button class="btn" type="submit">Send Reset Link</button>
        </form>
    </div>
@endsection
