@extends('format.layout')

@section('title', 'Admin - Users')

@section('content')
    <div class="container" style="max-width: 900px;">
        <h1>Admin Panel</h1>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h4">Students</h2>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.students.create') }}">Add Student</a>
                <ul class="mt-3">
                    @foreach($students as $s)
                        <li>{{ $s->Fname }} {{ $s->Lname }} - {{ optional($s->UserAccount)->username ?? 'no account' }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="h4">Teachers</h2>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.teachers.create') }}">Add Teacher</a>
                <ul class="mt-3">
                    @foreach($teachers as $t)
                        <li>{{ optional($t->userAccount)->username ?? 'no account' }} - {{ optional($t->userAccount)->email ?? 'no email' }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
