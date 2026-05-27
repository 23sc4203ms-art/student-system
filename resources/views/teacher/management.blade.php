<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Management - Teacher</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root{
            --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --primary:#1565d8; --accent:#2f63d9; --success:#16a34a; --danger:#dc2626;
        }
        body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#eef6ff 0%,#f7fbff 100%); color:#16202a; }
        /* navbar moved to partial */
        .container { max-width: 1100px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px; }
        .title { font-size: 1.25rem; font-weight: 800; color: #0f2f73; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 10px; font-weight: 700; text-decoration: none; border: 0; cursor: pointer; color: #fff; background: var(--accent); box-shadow: 0 6px 18px rgba(47,99,217,0.14); }
        .btn-blue { background: var(--accent); }
        .btn-green { background: var(--success); }
        .btn-red { background: var(--danger); }
        .btn-gray { background: #6b7280; }
        .btn:active{ transform: translateY(1px); }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; overflow: hidden; background: transparent; border-radius:10px; }
        table th, table td { padding: 12px 14px; text-align: left; color: #16202a; }
        table thead th { background: linear-gradient(180deg,#f1f6fd,#f6f9ff); font-weight:700; border-bottom:1px solid rgba(15,23,42,0.06); }
        table tbody tr { border-bottom:1px solid rgba(15,23,42,0.04); }
        table tbody tr:nth-child(odd){ background: rgba(47,99,217,0.02); }
        .row-actions { display: flex; gap: 8px; align-items:center; }
        .empty-note { padding: 18px; color: var(--muted); text-align: center; }
        .alert-success { background: #ecfdf5; color: #065f46; padding: 12px 14px; border-radius: 8px; margin-bottom: 14px; font-weight: 700; }

        /* Search input */
        input[type=search] { padding:10px 12px; border-radius:8px; border:1px solid rgba(15,23,42,0.06); min-width:220px; }

        /* Form controls */
        .form-control { width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(15,23,42,0.08); background:#fbfdff; transition:box-shadow .18s, border-color .18s; }
        .form-control:focus { outline:none; border-color:var(--accent); box-shadow:0 10px 30px rgba(47,99,217,0.12); }
        label { display:block; font-size:13px; color:var(--muted); margin-bottom:6px; }

        /* Modal common */
        #studentViewModal, #studentEditModal, #teacherEditModal { display:none; position:fixed; inset:0; background:rgba(8,15,35,0.45); align-items:center; justify-content:center; z-index:9999; }
        #studentViewModal .modal-panel, #studentEditModal .modal-panel, #teacherEditModal .modal-panel { background:var(--card); width:720px; max-width:95%; border-radius:12px; padding:20px; position:relative; box-shadow:0 20px 40px rgba(2,6,23,0.18); }
        .modal-close { position:absolute; right:12px; top:12px; background:#fff; border:1px solid rgba(15,23,42,0.06); border-radius:999px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:700; }
        .modal-header { display:flex; gap:12px; align-items:center; margin-bottom:12px; }
        .modal-avatar { width:56px; height:56px; border-radius:999px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:18px; box-shadow:0 10px 30px rgba(2,6,23,0.08); }
        .modal-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .modal-grid > div { background:#fbfdff; padding:12px; border-radius:8px; border:1px solid rgba(15,23,42,0.03); }
        .modal-grid strong{ display:block; font-size:12px; color:var(--accent); margin-bottom:6px; }

        @media (max-width:720px){
            .modal-grid{ grid-template-columns:1fr; }
            .nav-menu{ display:none; }
        }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Management'])

    <div class="container">
        @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif
        
        <div class="panel">
            <div class="top">
                <div style="flex:1">
                    <h2 class="title">Students Management</h2>
                </div>
                <div style="flex:2; display:flex; gap:8px; justify-content:flex-end; align-items:center;">
                    <form method="GET" action="{{ route('teacher.management') }}" style="display:flex; gap:8px; align-items:center;">
                        <input type="search" name="search" placeholder="Search..." value="{{ request('search') }}" style="padding:8px 10px; border-radius:6px; border:1px solid #d1d5db;">
                        <button type="submit" class="btn">Search</button>
                        <a href="{{ route('teacher.management') }}" class="btn btn-gray">Clear</a>
                    </form>
                    <a href="{{ route('teacher.students.create') }}" class="btn">+ Add Student</a>
                    <a href="{{ route('teacher.export.students.excel') }}" class="btn btn-green">Export Students (Excel)</a>
                    <a href="{{ route('teacher.export.students.pdf') }}" class="btn btn-blue">Export Students (PDF)</a>
                </div>
            </div>
            @if($students->count())
                <div class="table-wrapper">
                    <table data-model="student">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Degree</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr
                                    data-id="{{ $student->id }}"
                                    data-username="{{ e(optional($student->UserAccount)->username) }}"
                                    data-email="{{ e($student->Email) }}"
                                    data-fname="{{ e($student->Fname) }}"
                                    data-mname="{{ e($student->Mname) }}"
                                    data-lname="{{ e($student->Lname) }}"
                                    data-address="{{ e($student->Address) }}"
                                    data-contactno="{{ e($student->Contactno) }}"
                                    data-degree="{{ e(optional($student->degree)->name) }}"
                                    data-created="{{ $student->created_at->format('M d, Y') }}"
                                >
                                    <td>{{ optional($student->UserAccount)->username ?? 'N/A' }}</td>
                                    <td>{{ $student->Email ?? 'N/A' }}</td>
                                    <td>{{ $student->Fname }}</td>
                                    <td>{{ $student->Lname }}</td>
                                    <td>{{ optional($student->degree)->name ?? 'N/A' }}</td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="row-actions">
                                            <button type="button" class="btn btn-view-student" data-id="{{ $student->id }}">View</button>
                                            <button type="button" class="btn btn-green btn-edit-student" data-id="{{ $student->id }}">Edit</button>
                                            <form method="POST" action="{{ route('teacher.students.destroy', $student->id) }}" class="ajax-delete" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-red">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:12px; display:flex; justify-content:center;">
                    {{ $students->links() }}
                </div>
            @else
                <div class="empty-note">No students found.</div>
            @endif
        </div>

        <div class="panel">
            <div class="top">
                <div>
                    <h2 class="title">Teachers Management</h2>
                </div>
                <div>
                    <a href="{{ route('teacher.teachers.create') }}" class="btn">+ Add Teacher</a>
                    <a href="{{ route('teacher.export.teachers.excel') }}" class="btn btn-green">Export Teachers (Excel)</a>
                    <a href="{{ route('teacher.export.teachers.pdf') }}" class="btn btn-blue">Export Teachers (PDF)</a>
                </div>
            </div>
            @if($teachers->count())
                <div class="table-wrapper">
                    <table data-model="teacher">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr data-id="{{ $teacher->id }}" data-username="{{ e($teacher->username) }}" data-email="{{ e($teacher->email) }}" data-created="{{ $teacher->created_at->format('M d, Y') }}">
                                    <td>{{ $teacher->username }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="row-actions">
                                            <button type="button" class="btn btn-green btn-edit-teacher" data-id="{{ $teacher->id }}">Edit</button>
                                            <form method="POST" action="{{ route('teacher.teachers.destroy', $teacher->id) }}" class="ajax-delete" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-red">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-note">No teachers found.</div>
            @endif
        </div>
    </div>

    <!-- Student View Modal -->
    <div id="studentViewModal">
        <div class="modal-panel">
            <button id="studentViewClose" class="modal-close">✕</button>
            <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px;">
                <div style="width:64px;height:64px;border-radius:999px;background:linear-gradient(135deg,#2f63d9,#6ea8ff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:22px;box-shadow:0 6px 18px rgba(47,99,217,0.2);">S</div>
                <div>
                    <h3 id="sv_fullname" style="margin:0; font-size:20px;">Student</h3>
                    <div id="sv_degree" style="color:var(--accent); font-weight:700; margin-top:6px;">&nbsp;</div>
                </div>
            </div>
            <div class="modal-grid">
                <div><strong>Username</strong><div id="sv_username" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div><strong>Email</strong><div id="sv_email" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div><strong>First Name</strong><div id="sv_fname" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div><strong>Middle Name</strong><div id="sv_mname" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div><strong>Last Name</strong><div id="sv_lname" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div><strong>Contact</strong><div id="sv_contact" style="margin-top:6px;color:#111">&nbsp;</div></div>
                <div style="grid-column:1 / -1;"><strong>Address</strong><div id="sv_address" style="margin-top:6px;color:#111">&nbsp;</div></div>
            </div>
        </div>
    </div>

    <!-- Student Edit Modal -->
    <div id="studentEditModal">
        <div class="modal-panel">
            <button id="studentEditClose" class="modal-close">✕</button>
            <div class="modal-header">
                <div class="modal-avatar" style="background:linear-gradient(135deg,#2f63d9,#6ea8ff);">E</div>
                <div>
                    <h3 style="margin:0; font-size:18px;">Edit Student</h3>
                    <div style="color:var(--muted); margin-top:6px; font-size:13px;">Update student details</div>
                </div>
            </div>
            <form id="studentEditForm" method="POST" class="ajax-submit">
                @csrf
                @method('PUT')
                <div class="modal-grid">
                    <div>
                        <strong>Username</strong>
                        <input class="form-control" type="text" name="username" id="se_username">
                    </div>
                    <div>
                        <strong>Email</strong>
                        <input class="form-control" type="email" name="Email" id="se_email">
                    </div>
                    <div>
                        <strong>First Name</strong>
                        <input class="form-control" type="text" name="Fname" id="se_fname">
                    </div>
                    <div>
                        <strong>Middle Name</strong>
                        <input class="form-control" type="text" name="Mname" id="se_mname">
                    </div>
                    <div>
                        <strong>Last Name</strong>
                        <input class="form-control" type="text" name="Lname" id="se_lname">
                    </div>
                    <div>
                        <strong>Contact No</strong>
                        <input class="form-control" type="text" name="Contactno" id="se_contact">
                    </div>
                    <div style="grid-column:1 / -1;">
                        <strong>Address</strong>
                        <input class="form-control" type="text" name="Address" id="se_address">
                    </div>
                </div>
                <div style="margin-top:14px; display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" id="studentEditCancel" class="btn btn-gray">Cancel</button>
                    <button type="submit" class="btn btn-blue">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Teacher Edit Modal -->
    <div id="teacherEditModal">
        <div class="modal-panel">
            <button id="teacherEditClose" class="modal-close">✕</button>
            <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px;">
                <div style="width:56px;height:56px;border-radius:999px;background:linear-gradient(135deg,#06b6d4,#2f63d9);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:18px;">T</div>
                <div>
                    <h3 style="margin:0; font-size:18px;">Edit Teacher</h3>
                    <div style="color:var(--muted); margin-top:6px; font-size:13px;">Update account details</div>
                </div>
            </div>
            <form id="teacherEditForm" method="POST" class="ajax-submit">
                @csrf
                @method('PUT')
                <div class="modal-grid">
                    <div>
                        <strong>Username</strong>
                        <input class="form-control" type="text" name="username" id="te_username">
                    </div>
                    <div>
                        <strong>Email</strong>
                        <input class="form-control" type="email" name="email" id="te_email">
                    </div>
                    <div>
                        <strong>New Password</strong>
                        <input class="form-control" type="password" name="password" id="te_password">
                    </div>
                    <div>
                        <strong>Confirm Password</strong>
                        <input class="form-control" type="password" name="password_confirmation" id="te_password_confirmation">
                    </div>
                </div>
                <div style="margin-top:14px; display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" id="teacherEditCancel" class="btn btn-gray">Cancel</button>
                    <button type="submit" class="btn btn-blue">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function(){
            function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
            function qsa(sel, ctx){ return Array.from((ctx||document).querySelectorAll(sel)); }

            // Student View Modal
            qsa('.btn-view-student').forEach(btn => btn.addEventListener('click', function(){
                const tr = this.closest('tr');
                if (!tr) return;
                qs('#sv_fullname').textContent = tr.dataset.fname + ' ' + (tr.dataset.mname || '') + ' ' + tr.dataset.lname;
                qs('#sv_degree').textContent = tr.dataset.degree || '';
                qs('#sv_username').textContent = tr.dataset.username || 'N/A';
                qs('#sv_email').textContent = tr.dataset.email || 'N/A';
                qs('#sv_fname').textContent = tr.dataset.fname || '';
                qs('#sv_mname').textContent = tr.dataset.mname || '';
                qs('#sv_lname').textContent = tr.dataset.lname || '';
                qs('#sv_contact').textContent = tr.dataset.contactno || '';
                qs('#sv_address').textContent = tr.dataset.address || '';
                qs('#studentViewModal').style.display = 'flex';
            }));
            qs('#studentViewClose').addEventListener('click', function(){ qs('#studentViewModal').style.display = 'none'; });

            // Student Edit Modal
            qsa('.btn-edit-student').forEach(btn => btn.addEventListener('click', function(){
                const tr = this.closest('tr'); if(!tr) return;
                const id = tr.dataset.id;
                qs('#se_username').value = tr.dataset.username || '';
                qs('#se_email').value = tr.dataset.email || '';
                qs('#se_fname').value = tr.dataset.fname || '';
                qs('#se_mname').value = tr.dataset.mname || '';
                qs('#se_lname').value = tr.dataset.lname || '';
                qs('#se_contact').value = tr.dataset.contactno || '';
                qs('#se_address').value = tr.dataset.address || '';
                const form = qs('#studentEditForm');
                form.action = '{{ url("teacher/management/students") }}' + '/' + id;
                // focus first input
                setTimeout(()=> qs('#se_username').focus(), 120);
                qs('#studentEditModal').style.display = 'flex';
            }));
            qs('#studentEditClose').addEventListener('click', function(){ qs('#studentEditModal').style.display = 'none'; });
            qs('#studentEditCancel').addEventListener('click', function(){ qs('#studentEditModal').style.display = 'none'; });

            // Teacher Edit Modal
            qsa('.btn-edit-teacher').forEach(btn => btn.addEventListener('click', function(){
                const tr = this.closest('tr'); if(!tr) return;
                const id = tr.dataset.id;
                qs('#te_username').value = tr.dataset.username || '';
                qs('#te_email').value = tr.dataset.email || '';
                const form = qs('#teacherEditForm');
                form.action = '/teacher/teachers/' + id;
                qs('#teacherEditModal').style.display = 'flex';
            }));
            qs('#teacherEditClose').addEventListener('click', function(){ qs('#teacherEditModal').style.display = 'none'; });
            qs('#teacherEditCancel').addEventListener('click', function(){ qs('#teacherEditModal').style.display = 'none'; });
        })();
    </script>
</body>
</html>
