<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Teacher</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f7fb; }
        .container { max-width: 600px; margin: 40px auto; padding: 22px; }
        .panel { background: #fff; border: 1px solid #d8deea; border-radius: 14px; padding: 24px; box-shadow: 0 8px 22px rgba(0,0,0,0.08); }
        .title { font-size: 1.6rem; font-weight: 800; color: #0f2f73; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 700; color: #202938; font-size: 14px; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #dde3ef; border-radius: 8px; font-size: 14px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 10px; font-weight: 700; border: 0; cursor: pointer; color: #fff; }
        .btn-blue { background: #2f63d9; }
        .btn-gray { background: #6b7280; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="panel">
            <h2 class="title">Edit Teacher</h2>

            @if($errors->any())
                <div style="background:#fee2e2;padding:12px;border-radius:8px;margin-bottom:12px;font-weight:700;color:#991b1b;">
                    <ul style="margin:0;padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.teachers.update', $user->id) }}" class="ajax-submit">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @if($errors->has('username'))<div class="error">{{ $errors->first('username') }}</div>@endif
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @if($errors->has('email'))<div class="error">{{ $errors->first('email') }}</div>@endif
                </div>

                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <div style="margin-top:16px; display:flex; gap:8px;">
                    <button type="submit" class="btn btn-blue">Update Teacher</button>
                    <a href="{{ route('teacher.management') }}" class="btn btn-gray">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
