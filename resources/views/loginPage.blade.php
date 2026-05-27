<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }

        .card {
            width: 100%;
            max-width: 360px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 24px;
        }

        h1 {
            margin: 0 0 16px;
            font-size: 24px;
            text-align: center;
        }

        .alert {
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 14px;
        }

        .alert-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
        }

        .form-group { margin-bottom: 12px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-size: 14px;
            color: #fff;
            background: #2563eb;
            cursor: pointer;
        }

        .btn:hover { background: #1d4ed8; }

        .forgot-wrap {
            margin-top: 12px;
            text-align: center;
        }

        .forgot-link {
            color: #2563eb;
            font-size: 14px;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Login</h1>

        <form action="{{ route('login.submit') }}" method="POST" novalidate>
            @csrf

            @if ($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            @if (session('msg'))
                <div class="alert">{{ session('msg') }}</div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required>
                @if ($errors->has('username'))
                    <div class="alert">{{ $errors->first('username') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required>
                @if ($errors->has('password'))
                    <div class="alert">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me</label>
            </div>

            <button class="btn" type="submit">Login</button>

            <div class="forgot-wrap">
                <a class="forgot-link" href="{{ route('forgot.password') }}">Change password?</a>
            </div>
       
        </form>
    </main>
</body>
</html>
