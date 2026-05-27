<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root{
            --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --primary:#1565d8; --accent:#2f63d9; --success:#16a34a; --danger:#dc2626;
        }
        body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#eef6ff 0%,#f7fbff 100%); color:#16202a; }
        .navbar { background: linear-gradient(90deg,#0f172a,#0b3b6a); display: flex; align-items: center; box-shadow: 0 4px 16px rgba(11,27,60,0.12); padding:6px 12px; }
        .navbar h1 { color: white; padding: 12px 20px; font-size: 20px; flex: 1; margin:0; }
        .nav-menu { display: flex; list-style: none; gap:8px; }
        .nav-menu a { display: block; color: rgba(255,255,255,0.9); padding: 10px 14px; text-decoration: none; font-size: 14px; border-radius:8px; }
        .nav-menu a:hover, .nav-menu a.active { background: rgba(255,255,255,0.06); }
        .logout-btn { margin-left: auto; padding: 0 15px; }
        .logout-btn button { background: var(--danger); color: white; border: none; padding: 10px 12px; cursor: pointer; font-size: 14px; border-radius:8px; }
        .container { max-width: 1100px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px; }
        .title { font-size: 1.4rem; font-weight: 800; color: #0f2f73; }
        .subtitle { margin-top: 4px; color: var(--muted); }
        .welcome-text { color: #4b5563; line-height: 1.6; }
        .alert-success { background: #ecfdf5; color: #065f46; padding: 12px 14px; border-radius: 8px; margin-bottom: 14px; font-weight: 700; }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Teacher Dashboard'])

    <div class="container">
        @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <div class="panel">
            <h2 class="title">THIS IS TEACHER DASHBOARD, {{ Auth::user()->username }}!</h2>
            <p class="subtitle">You are logged in as a Teacher</p>
            <p class="welcome-text">Use the navigation menu above to manage students and teachers, view degrees, and check activity logs.</p>
        </div>
    </div>
</body>
</html>
