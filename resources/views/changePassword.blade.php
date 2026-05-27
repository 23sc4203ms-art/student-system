<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Temporary Password</title>
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 24px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
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

        .alert-info {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #1e40af;
        }

        .form-group { 
            margin-bottom: 16px; 
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #2563eb;
            cursor: pointer;
            margin-top: 8px;
        }

        .btn:hover { 
            background: #1d4ed8; 
        }

        .requirements {
            margin-top: 16px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 12px;
            color: #374151;
        }

        .requirements h4 {
            margin: 0 0 8px;
            font-size: 13px;
        }

        .requirements ul {
            margin: 0;
            padding-left: 20px;
        }

        .requirements li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Change Password</h1>
        <p class="subtitle">Kailangan mong baguhin ang iyong temporary password upang magpatuloy</p>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="alert alert-info">
            May maximum na 3 failed attempts. Kapag naubos, maghintay ng 5 segundo bago muling sumubok.
        </div>

        <form action="{{ route('student.change-password.store') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input 
                    class="form-control" 
                    type="password" 
                    id="current_password" 
                    name="current_password" 
                    autocomplete="current-password" 
                    required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input 
                    class="form-control" 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    autocomplete="new-password" 
                    required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input 
                    class="form-control" 
                    type="password" 
                    id="new_password_confirmation" 
                    name="new_password_confirmation" 
                    autocomplete="new-password" 
                    required>
            </div>

            <button class="btn" type="submit">Change Password</button>

            <div class="requirements">
                <h4>Password Requirements:</h4>
                <ul>
                    <li>Minimum 6 characters</li>
                    <li>Passwords must match</li>
                </ul>
            </div>
        </form>
    </main>
</body>
</html>
