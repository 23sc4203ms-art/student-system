<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Student</title>
</head>
<body>
    <h1>Create Student</h1>
    @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" action="{{ route('admin.students.store') }}">
        @csrf
        <div>
            <label>First name</label>
            <input name="Fname" value="{{ old('Fname') }}" required />
        </div>
        <div>
            <label>Middle name</label>
            <input name="Mname" value="{{ old('Mname') }}" />
        </div>
        <div>
            <label>Last name</label>
            <input name="Lname" value="{{ old('Lname') }}" required />
        </div>
        <div>
            <label>Email</label>
            <input name="Email" value="{{ old('Email') }}" required />
        </div>
        <div>
            <label>Contact</label>
            <input name="Contactno" value="{{ old('Contactno') }}" />
        </div>
        <div>
            <label>Username</label>
            <input name="username" value="{{ old('username') }}" required />
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
            <button type="submit">Create Student</button>
        </div>
    </form>
    <p><a href="{{ route('admin.index') }}">Back</a></p>
</body>
</html>
