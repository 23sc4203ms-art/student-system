<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Student</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root{
            --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --primary:#1565d8; --accent:#2f63d9; --success:#16a34a; --danger:#dc2626;
        }
        body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#eef6ff 0%,#f7fbff 100%); color:#16202a; }
        .container { max-width: 980px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .title { font-size: 1.6rem; font-weight: 800; color: #0f2f73; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 700; color: #202938; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #dde3ef; border-radius: 8px; font-size: 14px; }
        textarea { resize: vertical; min-height: 80px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 10px 30px rgba(47,99,217,0.12); }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 10px; font-weight: 700; text-decoration: none; border: 0; cursor: pointer; color: #fff; }
        .btn-blue { background: var(--accent); }
        .btn-blue:hover { opacity: 0.95; }
        .btn-gray { background: #6b7280; }
        .btn-gray:hover { opacity: 0.95; }
        .btn-group { margin-top: 20px; display: flex; gap: 10px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-weight: 700; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
        @media (max-width:640px){ .form-grid { grid-template-columns: 1fr; } }

        /* Enhanced form design */
        .breadcrumb { font-size: 13px; color: #6b7280; margin: 12px 0 18px; }
        .breadcrumb a { color: #0f2f73; text-decoration: none; margin-right: 8px; }

        .panel .panel-header { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; }
        .panel .panel-header .title { margin:0; font-size:1.4rem; color:#0f2f73; }
        .panel .panel-header .subtitle { color: var(--muted); font-size: 13px; margin-top:4px; }

        label { display: block; margin-bottom: 6px; font-weight: 700; color: #202938; font-size: 13px; text-transform: uppercase; letter-spacing: .04em; }
        input, select, textarea { width: 100%; padding: 12px 14px; border: 1px solid #e6eefc; border-radius: 10px; font-size: 14px; background: #fbfdff; }

        .input-note { font-size: 12px; color: #6b7280; margin-top: 6px; }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; font-weight: 700; text-decoration: none; border: 0; cursor: pointer; color: #fff; box-shadow: 0 6px 18px rgba(15,46,112,0.12); }
        .btn-blue { background: var(--accent); }
        .btn-blue:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(47,99,217,0.16); }
        .btn-gray { background: #6b7280; }
        .btn-gray:hover { transform: translateY(-1px); }

        .btn-right { margin-left: auto; }

        .required { color: #dc2626; margin-left: 6px; font-weight: 700; }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Teacher Dashboard'])

    <div class="container">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="title">Add New Student</h2>
                    <div class="subtitle">Create a student account — required fields are marked</div>
                </div>
                <div>
                    <a href="{{ route('teacher.management') }}" class="btn btn-gray">Back to Management</a>
                </div>
            </div>

            <div class="breadcrumb"><a href="{{ route('teacher.dashboard') }}">Teacher Dashboard</a> / <a href="{{ route('teacher.management') }}">Management</a> / Add Student</div>

            @if($errors->any())
                <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.students.store') }}" class="ajax-submit">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="Fname">First Name *</label>
                        <input type="text" id="Fname" name="Fname" value="{{ old('Fname') }}" required>
                        @if($errors->has('Fname'))<span class="error">{{ $errors->first('Fname') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label for="Mname">Middle Name</label>
                        <input type="text" id="Mname" name="Mname" value="{{ old('Mname') }}">
                        @if($errors->has('Mname'))<span class="error">{{ $errors->first('Mname') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label for="Lname">Last Name *</label>
                        <input type="text" id="Lname" name="Lname" value="{{ old('Lname') }}" required>
                        @if($errors->has('Lname'))<span class="error">{{ $errors->first('Lname') }}</span>@endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="Address">Address *</label>
                    <textarea id="Address" name="Address" required>{{ old('Address') }}</textarea>
                    @if($errors->has('Address'))<span class="error">{{ $errors->first('Address') }}</span>@endif
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="degree_id">Degree *</label>
                        <select id="degree_id" name="degree_id" required>
                            <option value="">-- Select a Degree --</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree->id }}" @if(old('degree_id') == $degree->id) selected @endif>{{ $degree->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('degree_id'))<span class="error">{{ $errors->first('degree_id') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label for="Contactno">Contact Number *</label>
                        <input type="text" id="Contactno" name="Contactno" value="{{ old('Contactno') }}" required>
                        @if($errors->has('Contactno'))<span class="error">{{ $errors->first('Contactno') }}</span>@endif
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="Email">Email *</label>
                        <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required>
                        @if($errors->has('Email'))<span class="error">{{ $errors->first('Email') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
                        @if($errors->has('username'))<span class="error">{{ $errors->first('username') }}</span>@endif
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                        @if($errors->has('password'))<span class="error">{{ $errors->first('password') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-blue">Create Student</button>
                    <a href="{{ route('teacher.management') }}" class="btn btn-gray">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
