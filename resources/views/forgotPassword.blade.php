<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
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
            max-width: 400px;
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

        .back-link {
            display: block;
            margin-top: 12px;
            text-align: center;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Change Password</h1>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('forgot.password.submit') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" value="{{ old('username') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input class="form-control" type="password" id="current_password" name="current_password" required>
            </div>

         
            <div class="form-group">
                <label for="password">New Password</label>
                <input class="form-control" type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button class="btn" type="submit">Change Password</button>
        </form>

        <a class="back-link" href="{{ route('login') }}">Back to Login</a>
    </main>
</body>
</html>
