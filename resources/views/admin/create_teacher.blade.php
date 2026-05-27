<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Teacher</title>
</head>
<body>
    <h1>Create Teacher</h1>
    @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" action="{{ route('admin.teachers.store') }}">
        @csrf
        <div>
            <label>Username</label>
            <input name="username" value="{{ old('username') }}" required />
        </div>
        <div>
            <label>Email</label>
            <input name="email" value="{{ old('email') }}" required />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required />
        </div>
        <div>
            <button type="submit">Create Teacher</button>
        </div>
    </form>
    <p><a href="{{ route('admin.index') }}">Back</a></p>
</body>
</html>
