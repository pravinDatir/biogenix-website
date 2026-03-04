@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <h1>Confirm Password</h1>
        <p class="muted">Please confirm your password to continue.</p>

        <form method="POST" action="{{ route('password.confirm.store') }}">
            @csrf

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required autofocus>
            </div>

            <button class="btn" type="submit">Confirm</button>
        </form>
    </div>
@endsection
