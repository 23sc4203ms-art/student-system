<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }

        .container {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .title {
            margin: 0;
            font-size: 28px;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-dark {
            background: #111827;
            color: #ffffff;
        }

        .btn-dark:hover {
            background: #030712;
        }

        .btn-danger {
            background: #dc2626;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .alert {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .teacher-banner {
            border-radius: 8px;
            padding: 10px 12px;
            background: #eef2ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px;
        }

        .label {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .value {
            margin: 6px 0 0;
            font-size: 18px;
            font-weight: 600;
        }

        .panel {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
        }

        .panel h2 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .list {
            margin: 0;
            padding-left: 18px;
            color: #374151;
        }

        .logout-form { margin: 0; }

        @media (max-width: 640px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @include('partials.navbar', ['title' => 'Student Dashboard'])

        <div style="max-width:980px;margin:0 auto;padding:14px 16px;">
            <p class="subtitle">Welcome back, {{ $student->Fname }} {{ $student->Lname }}</p>

            @if(Auth::check() && Auth::user()->Role === 'teacher')
                <div class="teacher-banner" style="margin-top:12px;">
                    <div>This is a teacher view of the student's dashboard.</div>
                    <div><a href="{{ route('teacher.management') }}" class="btn btn-primary">Open Students</a></div>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <section class="grid">
            <article class="card">
                <p class="label">Full Name</p>
                <p class="value">{{ $student->Fname }} {{ $student->Mname }} {{ $student->Lname }}</p>
            </article>

            <article class="card">
                <p class="label">Degree Program</p>
                <p class="value">{{ optional($student->degree)->name ?? 'N/A' }}</p>
            </article>

            <article class="card">
                <p class="label">Email</p>
                <p class="value">{{ $student->Email ?? auth()->user()->email }}</p>
            </article>

            <article class="card">
                <p class="label">Contact No.</p>
                <p class="value">{{ $student->Contactno }}</p>
            </article>
        </section>

        <section class="panel">
            <h2>Quick Access</h2>
            <ul class="list">
                <li>Use <strong>Change Password</strong> to update your account security.</li>
                <li>Visit <strong>Home</strong> to browse available pages and announcements.</li>
                <li>Contact admin if profile details are incorrect.</li>
            </ul>
        </section>
    </div>
</body>
</html>
