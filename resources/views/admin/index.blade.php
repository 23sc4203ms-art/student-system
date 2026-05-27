<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Users</title>
</head>
<body>
    <h1>Admin Panel</h1>
    @if(session('status'))
        <div style="color:green">{{ session('status') }}</div>
    @endif

    <h2>Students</h2>
    <a href="{{ route('admin.students.create') }}">Add Student</a>
    <ul>
        @foreach($students as $s)
            <li>{{ $s->Fname }} {{ $s->Lname }} - {{ optional($s->UserAccount)->username ?? 'no account' }}</li>
        @endforeach
    </ul>

    <h2>Teachers</h2>
    <a href="{{ route('admin.teachers.create') }}">Add Teacher</a>
    <ul>
        @foreach($teachers as $t)
            <li>{{ optional($t->userAccount)->username ?? 'no account' }} - {{ optional($t->userAccount)->email ?? 'no email' }}</li>
        @endforeach
    </ul>
</body>
</html>
