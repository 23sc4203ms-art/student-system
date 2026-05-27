@props(['title' => 'Dashboard'])
<style>
    .partial-navbar { background: linear-gradient(90deg,#0f172a,#0b3b6a); display: flex; align-items: center; box-shadow: 0 4px 16px rgba(11,27,60,0.12); padding:6px 12px; }
    .partial-navbar h1 { color: white; padding: 12px 20px; font-size: 20px; margin:0; flex: 1; }
    .partial-nav-menu { display: flex; list-style: none; gap:8px; margin-left: 0; align-items: center; }
    .partial-nav-menu a { display: block; color: rgba(255,255,255,0.95); padding: 10px 14px; text-decoration: none; font-size: 14px; border-radius:8px; }
    .partial-nav-menu .btn-change { background: #2563eb; color: #fff; padding: 8px 12px; border-radius: 8px; }
    .partial-nav-menu .btn-change:hover { background: #1d4ed8; }
    .partial-nav-menu a:hover, .partial-nav-menu a.active { background: rgba(255,255,255,0.06); }
    .partial-logout { margin-left: 12px; padding: 0 12px; }
    .partial-logout button { background: #ef4444; color: white; border: none; padding: 10px 12px; cursor: pointer; font-size: 14px; border-radius:8px; }
    @media (max-width:640px){ .partial-navbar{ flex-wrap:wrap; } .partial-nav-menu{ margin-top:8px; } }
</style>

<nav class="partial-navbar">
    <h1>{{ $title }}</h1>

    <ul class="partial-nav-menu">
        @if(Auth::check() && Auth::user()->Role === 'teacher')
            <li><a href="{{ route('teacher.dashboard') }}" class="@if(Route::currentRouteName() == 'teacher.dashboard') active @endif">Home</a></li>
            <li><a href="{{ route('teacher.dashboard') }}" class="@if(Route::currentRouteName() == 'teacher.dashboard') active @endif">Dashboard</a></li>
            <li><a href="{{ route('teacher.management') }}" class="@if(Route::currentRouteName() == 'teacher.management') active @endif">Management</a></li>
            <li><a href="{{ route('teacher.degrees') }}" class="@if(Route::currentRouteName() == 'teacher.degrees') active @endif">Degrees</a></li>
            <li><a href="{{ route('teacher.activity-logs') }}" class="@if(Route::currentRouteName() == 'teacher.activity-logs') active @endif">Activity Logs</a></li>
        @else
            <li><a href="{{ route('home') }}" class="@if(Route::currentRouteName() == 'home') active @endif">Home</a></li>
            @if(Auth::check())
                <li><a href="{{ route('student.change-password.show') }}" class="btn-change">Change Password</a></li>
            @endif
        @endif
    </ul>

    <div class="partial-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</nav>
