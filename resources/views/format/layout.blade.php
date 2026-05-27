<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(circle at 10% 10%, rgba(56, 189, 248, 0.16), transparent 35%),
                radial-gradient(circle at 90% 15%, rgba(99, 102, 241, 0.14), transparent 40%),
                linear-gradient(180deg, #eef2ff 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        nav {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
            padding: 0 18px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.24);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 2px;
            flex-wrap: wrap;
        }

        nav ul li a {
            color: #e2e8f0;
            padding: 13px 14px;
            text-decoration: none;
            display: block;
            font-weight: 700;
            font-size: 0.93rem;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
            border-radius: 10px 10px 0 0;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #ffffff;
            background: rgba(99, 102, 241, 0.25);
            border-bottom-color: #93c5fd;
        }

        .content {
            padding: 26px 16px 34px;
            flex: 1;
            max-width: 1180px;
            margin: 0 auto;
            width: 100%;
        }

        footer {
            position: relative;
            width: 100vw;
            left: 50%;
            margin-left: -50vw;
            background: #020617;
            color: #e2e8f0;
            text-align: center;
            padding: 16px;
            box-shadow: 0 -6px 20px rgba(2, 6, 23, 0.2);
            margin-top: auto;
        }

        footer p {
            margin: 0;
            font-weight: 600;
            font-size: 0.92rem;
        }

        h1 {
            color: #0f2f73;
            margin-bottom: 18px;
            font-size: 2.1rem;
            text-shadow: none;
        }

        h2 {
            color: #1e3a8a;
            margin-bottom: 12px;
        }

        .page-shell {
            max-width: 920px;
            margin: 0 auto;
            background: rgba(248, 250, 252, 0.93);
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(4px);
        }

        .page-subtitle {
            color: #475569;
            margin: 6px 0 14px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 1rem;
        }

        @media (max-width: 760px) {
            nav ul {
                justify-content: flex-start;
            }

            nav ul li a {
                font-size: 0.88rem;
                padding: 12px 10px;
            }
        }
    </style>
</head>
<body>

    @section('header')
        <nav>
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="{{ route('students') }}" class="{{ request()->routeIs('students') || request()->routeIs('students.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') || request()->routeIs('enrollment.*') ? 'active' : '' }}"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="fas fa-users-cog"></i> Users</a></li>
                <li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Posts</a></li>
                <li><a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"><i class="fas fa-history"></i> Activity Logs</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> About</a></li>
                @auth
                    @php $role = auth()->user()->Role ?? 'student'; @endphp
                    @if(in_array($role, ['admin','teacher']))
                        <li><a href="{{ route('teacher.export.students.excel') }}"><i class="fas fa-file-excel"></i> Export Excel</a></li>
                        <li><a href="{{ route('teacher.export.students.pdf') }}"><i class="fas fa-file-pdf"></i> Export PDF</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="color: #e2e8f0; font-weight:700; font-size:0.93rem; border: none; background: transparent; cursor: pointer;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    @endif
                @endauth
            </ul>
        </nav>
    @show

    <div class="content">
        @yield('content')
    </div>

    @section('footer')
    <footer>
        <p>&copy; 2026 JMM Project. All rights reserved.</p>
    </footer>
    @show
    <script>
        (function(){
            function getCsrf(){ var m = document.querySelector('meta[name="csrf-token"]'); return m?m.getAttribute('content'):null; }
            function send(url, method, body){
                var headers = { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrf() };
                var opts = { method: method, headers: headers };
                if (body instanceof FormData) opts.body = body; else if (body) { headers['Content-Type']='application/json'; opts.body = JSON.stringify(body);} 
                return fetch(url, opts);
            }
            document.addEventListener('click', function(e){
                var el = e.target.closest('[data-ajax-delete]'); if(!el) return;
                e.preventDefault(); var url = el.getAttribute('href')||el.getAttribute('data-url'); if(!url) return; var method = (el.getAttribute('data-method')||'DELETE').toUpperCase(); if(!confirm('Are you sure you want to delete this item?')) return; send(url, method).then(function(r){ if(r.ok) location.reload(); else r.text().then(t=>alert('Delete failed: '+t)); }).catch(()=>alert('Request failed'));
            }, false);
            document.addEventListener('submit', function(e){
                var form = e.target.closest('form.ajax-delete'); if(!form) return; e.preventDefault(); if(!confirm('Are you sure you want to delete this item?')) return; var action = form.action; var m = form.querySelector('input[name="_method"]'); var method = (m?m.value:form.method||'POST').toUpperCase(); var fd = new FormData(form); send(action, method, fd).then(function(r){ if(r.ok) location.reload(); else r.text().then(t=>alert('Delete failed: '+t)); }).catch(()=>alert('Request failed'));
            }, false);
        })();
    </script>
</body>
</html>