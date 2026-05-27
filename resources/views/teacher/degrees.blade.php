<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Degrees - Teacher</title>
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
        .title { font-size: 1.4rem; font-weight: 800; color: #0f2f73; }
        .subtitle { margin-top: 4px; color: var(--muted); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 10px; font-weight: 700; text-decoration: none; border: 0; cursor: pointer; color: #fff; background: var(--accent); box-shadow: 0 6px 18px rgba(47,99,217,0.14); }
        .btn-blue { background: var(--accent); }
        .btn-dark { background: #101726; }
        .btn-green { background: var(--success); }
        .btn-red { background: var(--danger); }
        .degree-table { width: 100%; border-collapse: collapse; overflow: hidden; background: transparent; }
        .degree-table th, .degree-table td { padding: 12px 14px; text-align: left; color: #16202a; }
        .degree-table thead th { background: linear-gradient(180deg,#f1f6fd,#f6f9ff); font-weight:700; border-bottom:1px solid rgba(15,23,42,0.06); }
        .degree-table tbody tr { border-bottom:1px solid rgba(15,23,42,0.04); }
        .row-actions { display: flex; gap: 8px; align-items:center; }
        .empty-note { padding: 18px; color: var(--muted); text-align: center; }
        .alert-success { background: #ecfdf5; color: #065f46; padding: 12px 14px; border-radius: 8px; margin-bottom: 14px; font-weight: 700; }

        .form-control { width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(15,23,42,0.08); background:#fbfdff; transition:box-shadow .18s, border-color .18s; }
        .form-control:focus { outline:none; border-color:var(--accent); box-shadow:0 10px 30px rgba(47,99,217,0.12); }

        /* Modal common */
        #degreeAddModal, #degreeEditModal, #degreeViewModal { display:none; position:fixed; inset:0; background:rgba(8,15,35,0.45); align-items:center; justify-content:center; z-index:9999; }
        .modal-panel { background:var(--card); width:560px; max-width:95%; border-radius:12px; padding:20px; position:relative; box-shadow:0 20px 40px rgba(2,6,23,0.18); }
        .modal-close { position:absolute; right:12px; top:12px; background:#fff; border:1px solid rgba(15,23,42,0.06); border-radius:999px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:700; }
        .modal-header{ display:flex; gap:12px; align-items:center; margin-bottom:12px; }
        .modal-avatar{ width:48px;height:48px;border-radius:999px;background:linear-gradient(135deg,#06b6d4,#2f63d9);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800; }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Degrees'])

    <div class="container">
        @if(Auth::check() && Auth::user()->Role === 'teacher')
            <div class="panel" style="margin-bottom:14px;">
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="font-weight:800;color:#0f2f73;font-size:1.25rem;">Welcome, {{ Auth::user()->username }}!</div>
                    <div style="color:var(--muted);">This is a teacher dashboard. Use the navigation menu above to manage students and teachers, view degrees, and check activity logs.</div>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="panel">
            <div class="top">
                <div>
                    <div class="title">Degrees</div>
                    <div class="subtitle">Manage the degree list used in the student form.</div>
                </div>

                <div class="actions">
                    <button id="btnAddDegree" class="btn btn-blue">+ Add Degree</button>
                </div>
            </div>

            <table class="degree-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">ID</th>
                        <th>Degree Name</th>
                        <th style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($degrees as $degree)
                        <tr data-id="{{ $degree->id }}" data-name="{{ e($degree->name) }}">
                            <td>{{ $degree->id }}</td>
                            <td>{{ $degree->name }}</td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="btn btn-blue btn-view-degree" data-id="{{ $degree->id }}">View</button>
                                    <button type="button" class="btn btn-green btn-edit-degree" data-id="{{ $degree->id }}">Edit</button>
                                    <form method="POST" action="{{ route('teacher.degrees.destroy', $degree->id) }}" class="ajax-delete" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-note">No degree records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Degree Add Modal -->
    <div id="degreeAddModal">
        <div class="modal-panel">
            <button id="degreeAddClose" class="modal-close">✕</button>
            <div class="modal-header">
                <div class="modal-avatar">+</div>
                <div>
                    <h3 style="margin:0">Add Degree</h3>
                    <div style="color:var(--muted); font-size:13px;">Create a new degree</div>
                </div>
            </div>
            <form id="degreeAddForm" method="POST" action="{{ route('teacher.degrees.store') }}" class="ajax-submit">
                @csrf
                <div style="margin-bottom:8px">
                    <label>Degree Name</label>
                    <input class="form-control" type="text" name="name" id="da_name" required>
                </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" id="degreeAddCancel" class="btn btn-gray">Cancel</button>
                    <button type="submit" class="btn btn-blue">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Degree Edit Modal -->
    <div id="degreeEditModal">
        <div class="modal-panel">
            <button id="degreeEditClose" class="modal-close">✕</button>
            <div class="modal-header">
                <div class="modal-avatar">✎</div>
                <div>
                    <h3 style="margin:0">Edit Degree</h3>
                    <div style="color:var(--muted); font-size:13px;">Update existing degree</div>
                </div>
            </div>
            <form id="degreeEditForm" method="POST" class="ajax-submit">
                @csrf
                @method('PUT')
                <div style="margin-bottom:8px">
                    <label>Degree Name</label>
                    <input class="form-control" type="text" name="name" id="de_name" required>
                </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" id="degreeEditCancel" class="btn btn-gray">Cancel</button>
                    <button type="submit" class="btn btn-blue">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Degree View Modal -->
    <div id="degreeViewModal">
        <div class="modal-panel">
            <button id="degreeViewClose" class="modal-close">✕</button>
            <div class="modal-header">
                <div class="modal-avatar">D</div>
                <div>
                    <h3 id="dv_name" style="margin:0">Degree</h3>
                    <div style="color:var(--muted); font-size:13px;">Details</div>
                </div>
            </div>
            <div style="margin-top:8px">
                <strong>Degree Name</strong>
                <div id="dv_name_val" style="margin-top:6px;color:#111">&nbsp;</div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
            function qsa(sel, ctx){ return Array.from((ctx||document).querySelectorAll(sel)); }

            // Add degree modal
            qs('#btnAddDegree').addEventListener('click', function(){ qs('#degreeAddModal').style.display='flex'; setTimeout(()=>qs('#da_name').focus(),120); });
            qs('#degreeAddClose').addEventListener('click', ()=> qs('#degreeAddModal').style.display='none');
            qs('#degreeAddCancel').addEventListener('click', ()=> qs('#degreeAddModal').style.display='none');

            // Edit degree
            qsa('.btn-edit-degree').forEach(b=>b.addEventListener('click', function(){
                const tr = this.closest('tr'); if(!tr) return;
                const id = tr.dataset.id;
                const name = tr.dataset.name || '';
                qs('#de_name').value = name;
                const form = qs('#degreeEditForm');
                form.action = '{{ url("teacher/management/degrees") }}' + '/' + id;
                qs('#degreeEditModal').style.display='flex'; setTimeout(()=>qs('#de_name').focus(),120);
            }));
            qs('#degreeEditClose').addEventListener('click', ()=> qs('#degreeEditModal').style.display='none');
            qs('#degreeEditCancel').addEventListener('click', ()=> qs('#degreeEditModal').style.display='none');

            // View degree
            qsa('.btn-view-degree').forEach(b=>b.addEventListener('click', function(){
                const tr = this.closest('tr'); if(!tr) return;
                const name = tr.dataset.name || '';
                qs('#dv_name').textContent = name;
                qs('#dv_name_val').textContent = name;
                qs('#degreeViewModal').style.display='flex';
            }));
            qs('#degreeViewClose').addEventListener('click', ()=> qs('#degreeViewModal').style.display='none');
        })();
    </script>
</body>
</html>
