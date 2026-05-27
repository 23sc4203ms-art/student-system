@extends('format.layout')

@section('title', 'Students Page')

@section('content')
    <style>
        :root{ --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --accent:#2f63d9; --danger:#dc2626; --success:#16a34a; }
        .container { max-width: 1100px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .top { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:12px; }
        .title { font-size:1.25rem; font-weight:800; color:#0f2f73; }
        .subtitle { color:var(--muted); margin-top:4px; }
        .actions { display:flex; gap:8px; align-items:center; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:10px; font-weight:700; color:#fff; text-decoration:none; border:0; cursor:pointer; }
        .btn-blue{ background:var(--accent); box-shadow:0 6px 18px rgba(47,99,217,0.14);} .btn-green{ background:var(--success);} .btn-gray{ background:#6b7280; }
        .table-wrapper{ overflow-x:auto; }
        table{ width:100%; border-collapse:collapse; }
        thead th{ background: linear-gradient(180deg,#f1f6fd,#f6f9ff); font-weight:700; border-bottom:1px solid rgba(15,23,42,0.06); padding:12px 14px; text-align:left; }
        tbody td{ padding:12px 14px; color:#16202a; }
        tbody tr{ border-bottom:1px solid rgba(15,23,42,0.04); }
        .row-actions{ display:flex; gap:8px; align-items:center; }
        .empty-note{ padding:18px; color:var(--muted); text-align:center; }
        .alert-success{ background:#ecfdf5; color:#065f46; padding:12px 14px; border-radius:8px; margin-bottom:14px; font-weight:700; }
        @media (max-width:720px){ .actions{ width:100%; justify-content:space-between; } }
    </style>

    <div class="container">
        @if(Auth::check() && Auth::user()->Role === 'teacher')
            <div class="panel" style="margin-bottom:14px;">
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="font-weight:800;color:#0f2f73;font-size:1.1rem;">Teacher Dashboard</div>
                    <div style="color:var(--muted);">This is the teacher dashboard.</div>
                </div>
            </div>
        @endif
        <div class="panel">
            <div class="top">
                <div>
                    <div class="title">Students Management</div>
                    <div class="subtitle">Manage student records and enrollments.</div>
                </div>

                <div class="actions">
                    <a href="{{ route('students.create') }}" class="btn btn-blue" id="openAddStudent"><i class="fas fa-plus-circle"></i> Add Student</a>
                    <a href="{{ route('courses.index') }}" class="btn btn-gray" style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); box-shadow:0 6px 18px rgba(59,130,246,0.14);"><i class="fas fa-book"></i> Courses</a>
                </div>
            </div>

            @if(session('message'))
                <div class="alert-success">{{ session('message') }}</div>
            @endif

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($students->count())
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Full Name</th>
                   
                      
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Degree</th>
                        <th style="width: 230px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr data-id="{{ $student->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $student->Fname }} {{ $student->Mname }} {{ $student->Lname }}</strong></td>
                            <td>{{ $student->Email }}</td>
                            <td>{{ $student->Contactno }}</td>
                            <td>{{ $student->degree?->name ?? 'degree_title' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('students.show', $student->id) }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button class="btn-edit" data-id="{{ $student->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-delete" data-id="{{ $student->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            @if ($students->lastPage() > 1)
                <ul class="pagination-list">
                    <li>
                        @if ($students->onFirstPage())
                            <span class="pagination-text disabled" aria-disabled="true">&lsaquo;</span>
                        @else
                            <a class="pagination-link" href="{{ $students->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                        @endif
                    </li>

                    @for ($page = 1; $page <= $students->lastPage(); $page++)
                        @if ($page == $students->currentPage())
                            <li><span class="pagination-text active">{{ $page }}</span></li>
                        @elseif (abs($page - $students->currentPage()) <= 1 || $page == 1 || $page == $students->lastPage())
                            <li><a class="pagination-link" href="{{ $students->url($page) }}">{{ $page }}</a></li>
                        @elseif ($page == 2 && $students->currentPage() > 4)
                            <li><span class="pagination-text disabled">…</span></li>
                        @elseif ($page == $students->lastPage() - 1 && $students->currentPage() < $students->lastPage() - 3)
                            <li><span class="pagination-text disabled">…</span></li>
                        @endif
                    @endfor

                    <li>
                        @if ($students->hasMorePages())
                            <a class="pagination-link" href="{{ $students->nextPageUrl() }}" rel="next">&rsaquo;</a>
                        @else
                            <span class="pagination-text disabled" aria-disabled="true">&rsaquo;</span>
                        @endif
                    </li>
                </ul>
            @endif
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h2>No Students Found</h2>
            <p>There are currently no students in the system. Click "Add New Student" to get started.</p>
        </div>
    @endif

    <!-- jQuery + students JS for AJAX CRUD -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/js/students.js"></script>

@endsection

