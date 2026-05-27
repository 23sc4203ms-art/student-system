<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password - Teacher</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root{
            --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --primary:#1565d8; --accent:#2f63d9; --success:#16a34a; --danger:#dc2626;
        }
        body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#eef6ff 0%,#f7fbff 100%); color:#16202a; }
        /* navbar moved to partial */
        .container { max-width: 600px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .title { font-size: 1.6rem; font-weight: 800; color: #0f2f73; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 700; color: #202938; font-size: 14px; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #dde3ef; border-radius: 8px; font-size: 14px; }
        input:focus { outline: none; border-color: var(--accent); box-shadow: 0 10px 30px rgba(47,99,217,0.12); }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 10px; font-weight: 700; text-decoration: none; border: 0; cursor: pointer; color: #fff; }
        .btn-blue { background: var(--accent); }
        .btn-blue:hover { opacity: 0.95; }
        .btn-gray { background: #6b7280; }
        .btn-gray:hover { opacity: 0.95; }
        .btn-group { margin-top: 20px; display: flex; gap: 10px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-weight: 700; }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Change Password'])

    <div class="container">
        <div class="panel">
            <h2 class="title">Change Password</h2>

            @if($errors->any())
                <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.change-password.update') }}" class="ajax-submit">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required />
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-blue">Update Password</button>
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-gray">Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
