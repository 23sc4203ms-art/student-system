@extends('format.layout')

@section('title', 'Users')

@section('content')
    <style>
        .users-wrap {
            max-width: 760px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .users-title {
            margin: 0;
            color: #0f2f73;
            font-size: 2rem;
            font-weight: 800;
        }

        .users-sub {
            margin: 8px 0 14px;
            color: #4b5563;
            border-top: 1px solid #d6dce8;
            padding-top: 8px;
            font-size: 1.02rem;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .users-table th,
        .users-table td {
            border: 1px solid #dde3ef;
            padding: 11px 12px;
            text-align: left;
            font-size: 1rem;
            color: #202938;
        }

        .users-table th {
            background: #e9eef7;
            font-weight: 700;
        }

        .empty-note {
            color: #6b7280;
            font-size: 0.95rem;
        }
    </style>

    <div class="users-wrap">
        <h1 class="users-title">User Management</h1>
        <p class="users-sub">Manage user accounts and profiles.</p>

        @if($users->count())
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 90px;">Role</th>
                        <th style="width: 100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>User</td>
                            <td>Active</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">No users found.</p>
        @endif
    </div>
@endsection
