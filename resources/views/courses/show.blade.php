@extends('format.layout')

@section('title', 'Course Details')

@section('content')
    <style>
        .details-wrap {
            max-width: 760px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .details-wrap h1 {
            color: #0f2f73;
            margin: 0 0 10px;
            font-size: 2.2rem;
            font-weight: 800;
        }

        .info-row {
            margin-bottom: 10px;
            color: #1f2937;
        }

        .info-row span {
            font-weight: 700;
            color: #111827;
        }

        .section-title {
            color: #0f2f73;
            font-size: 2rem;
            margin: 14px 0 10px;
            font-weight: 800;
        }

        .divider {
            border: none;
            border-top: 1px solid #d6dce8;
            margin: 8px 0 14px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
            background: #ffffff;
        }

        .students-table th,
        .students-table td {
            border: 1px solid #dde3ef;
            padding: 11px 12px;
            text-align: left;
            font-size: 1rem;
            color: #202938;
        }

        .students-table th {
            background: #e9eef7;
            font-weight: 700;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-weight: 700;
        }

        .back-link {
            margin-top: 16px;
            display: inline-block;
            color: #5b3b7c;
            font-size: 1.05rem;
        }

        .empty-note {
            color: #6b7280;
            font-size: 0.95rem;
        }
    </style>

    <div class="details-wrap">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <h1>Course: {{ $course->course_name }} ({{ 'ELEC' . $course->id }})</h1>
        <hr class="divider">

        <p class="info-row">Default course: {{ $course->course_name }}</p>

        <h2 class="section-title">Enrolled Students</h2>

        @if($course->students->count())
            <table class="students-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->Fname }} {{ $student->Mname }} {{ $student->Lname }}</td>
                            <td>{{ $student->Email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">No enrolled students yet.</p>
        @endif

        <a href="{{ route('courses.index') }}" class="back-link">Back to Courses</a>
    </div>
@endsection
