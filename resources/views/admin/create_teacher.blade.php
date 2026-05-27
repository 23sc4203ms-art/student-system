@extends('format.layout')

@section('title', 'Create Teacher')

@section('content')
    <div class="container" style="max-width: 680px;">
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3">Create Teacher</h1>
                        <p class="text-muted mb-0">Add a new teacher account for your school.</p>
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

                <form method="POST" action="{{ route('admin.teachers.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Teacher</button>
                </form>
            </div>
        </div>
    </div>
@endsection
