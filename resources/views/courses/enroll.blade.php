@extends('format.layout')

@section('title', 'Enroll Student')

@section('content')
    <style>
        .enroll-wrap {
            max-width: 560px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .enroll-wrap h1 {
            margin: 0;
            color: #0f2f73;
            font-size: 2rem;
            font-weight: 800;
        }

        .enroll-sub {
            margin: 8px 0 14px;
            color: #4b5563;
            border-top: 1px solid #d6dce8;
            padding-top: 8px;
            font-size: 1.02rem;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            color: #374151;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .field select {
            width: 100%;
            border: 1px solid #cfd7e6;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 1rem;
            background: #ffffff;
            color: #1f2937;
        }

        .btn-row {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-confirm {
            border: none;
            border-radius: 10px;
            background: #1d4ed8;
            color: #fff;
            padding: 10px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-cancel {
            color: #6b7280;
            text-decoration: none;
            font-weight: 700;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 14px;
            border-radius: 8px;
            margin: 0 auto 14px;
            max-width: 560px;
            font-weight: 700;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-weight: 600;
        }
    </style>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="enroll-wrap">
        <h1>Student Enrollment</h1>
        <p class="enroll-sub">Select a course and a student to complete the enrollment.</p>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('enrollment.store') }}" class="ajax-submit">
            @csrf

            <div class="field">
                <label for="course_id">Select Course</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Choose Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                            {{ 'ELEC' . $course->id . ' - ' . $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="student_id">Select Student</label>
                <select id="student_id" name="student_id" required>
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                            {{ $student->Lname . ', ' . $student->Fname . ' ' . $student->Mname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-confirm">Confirm Enrollment</button>
                <a href="{{ route('courses.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection
