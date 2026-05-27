@extends('format.layout')

@section('title', 'Create Student')

@section('content')
    <div class="container" style="max-width: 720px;">
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3">Create Student</h1>
                        <p class="text-muted mb-0">Register a new student account in the system.</p>
                    </div>
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.students.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First name</label>
                            <input type="text" name="Fname" value="{{ old('Fname') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Middle name</label>
                            <input type="text" name="Mname" value="{{ old('Mname') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last name</label>
                            <input type="text" name="Lname" value="{{ old('Lname') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" value="{{ old('Email') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact</label>
                            <input type="text" name="Contactno" value="{{ old('Contactno') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
