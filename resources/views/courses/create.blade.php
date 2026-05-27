@extends('format.layout')

@section('title', 'Add Course')

@section('content')
    <style>
        .form-wrap {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.14);
        }

        .form-title {
            margin: 0 0 16px;
            color: #0f2f73;
            font-size: 1.9rem;
            font-weight: 800;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            color: #1f2937;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            border: 1px solid #cfd7e6;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.95rem;
        }

        .error {
            color: #b91c1c;
            font-size: 0.9rem;
            margin-top: 6px;
            display: block;
        }

        .btn-row {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-save,
        .btn-back {
            text-decoration: none;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save {
            background: #1d4ed8;
            color: white;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }
    </style>

    <div class="form-wrap">
        <h1 class="form-title">Add Course</h1>

        <form action="{{ route('courses.store') }}" method="POST" class="ajax-submit">
            @csrf
            <div class="field">
                <label for="course_name">Course Name</label>
                <input type="text" id="course_name" name="course_name" value="{{ old('course_name') }}" placeholder="e.g. Elective 1" required>
                @error('course_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-save">Save Course</button>
                <a href="{{ route('courses.index') }}" class="btn-back">Back</a>
            </div>
        </form>
    </div>
@endsection
