@extends('format.layout')

@section('title', 'Courses')

@section('content')
    <style>
        .courses-wrap {
            max-width: 760px;
            margin: 0 auto;
            background: #f5f7fb;
            border-radius: 14px;
            border: 1px solid #d8deea;
            padding: 18px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
        }

        .courses-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .courses-title {
            margin: 0;
            color: #0f2f73;
            font-size: 2rem;
            font-weight: 800;
        }

        .courses-subtitle {
            margin: 4px 0 0;
            color: #4b5563;
            font-size: 1rem;
        }

        .courses-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-blue,
        .btn-dark {
            color: #fff;
            text-decoration: none;
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-blue {
            background: #2f63d9;
        }

        .btn-dark {
            background: #101726;
        }

        .course-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .course-table th,
        .course-table td {
            border: 1px solid #dde3ef;
            padding: 11px 12px;
            text-align: left;
            font-size: 0.96rem;
            color: #202938;
        }

        .course-table th {
            background: #e9eef7;
            font-weight: 700;
        }

        .table-link {
            color: #1e40af;
            font-weight: 700;
            text-decoration: underline;
            margin-right: 8px;
        }

        .delete-btn-link {
            color: #dc2626;
            background: transparent;
            border: none;
            font-weight: 700;
            padding: 0;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 14px;
            border-radius: 8px;
            margin: 0 auto 14px;
            max-width: 760px;
            font-weight: 700;
        }

        .empty-note {
            color: #6b7280;
            font-size: 0.95rem;
        }
    </style>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="courses-wrap">
        <div class="courses-top">
            <div>
                <h1 class="courses-title">Courses</h1>
                <p class="courses-subtitle">Manage courses and enroll students.</p>
            </div>

            <div class="courses-actions">
                <a href="{{ route('enrollment.create') }}" class="btn-blue"><i class="fas fa-user-plus"></i> Enroll a Student</a>
                <a href="{{ route('courses.create') }}" class="btn-dark"><i class="fas fa-plus"></i> Add Course</a>
            </div>
        </div>

        <table class="course-table">
            <thead>
                <tr>
                    <th style="width: 110px;">Code</th>
                    <th>Course Name</th>
                    <th style="width: 170px;">Students Enrolled</th>
                    <th style="width: 170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td>{{ 'ELEC' . $course->id }}</td>
                        <td>{{ $course->course_name }}</td>
                        <td>{{ $course->students_count }}</td>
                        <td>
                            <a href="{{ route('courses.show', $course->id) }}" class="table-link">View</a>
                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="ajax-delete" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn-link">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-note">No courses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
