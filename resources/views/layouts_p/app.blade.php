<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Biogenix') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fa; color: #1f2937; }
        .container { max-width: 980px; margin: 0 auto; padding: 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .nav { background: #0f172a; color: #fff; }
        .nav .container { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        .links a { color: #e2e8f0; text-decoration: none; margin-right: 12px; }
        .links a:hover { color: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f1f5f9; }
        .btn { display: inline-block; border: 1px solid #1d4ed8; background: #1d4ed8; color: #fff; padding: 8px 12px; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .btn.secondary { background: #fff; color: #1d4ed8; }
        .field { margin-bottom: 10px; }
        .field label { display: block; font-size: 14px; margin-bottom: 4px; }
        .field input, .field select, .field textarea { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        .status { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .errors { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .muted { color: #64748b; font-size: 14px; }
        ul { margin-top: 8px; }
        .links .active-link { color: #fff; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="container">
            <div class="links">
                <a href="{{ url('/AdminhomeView') }}" class="active-link">Home</a>
                <a href="{{ route('products.index') }}">Products</a>
                <a href="{{ route('proforma.create') }}">Generate PI</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('proforma.index') }}">My PI</a>
                    <a href="{{ route('support-tickets.index') }}">Support Tickets</a>
                    <a href="{{ route('admin.users.index') }}">Admin Console</a>
               
                @endauth
            </div>
            <div class="links">
                @auth
                    <span>{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->user_type) }})</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container">
        @if (session()->has('impersonation.impersonator_id'))
            <div class="status">
                You are currently impersonating another user.
                <form method="POST" action="{{ route('impersonation.stop') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn secondary">Stop Impersonation</button>
                </form>
            </div>
        @endif

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <strong>Validation failed:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
