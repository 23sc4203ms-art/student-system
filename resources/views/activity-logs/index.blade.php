@extends('format.layout')

@section('title', 'Activity Logs')

@section('content')
    <style>
        .logs-wrap {
            max-width: 980px;
            margin: 0 auto;
            background: #f6f7fb;
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .logs-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .logs-title {
            margin: 0;
            color: #0f2f73;
            font-size: 2rem;
            font-weight: 800;
        }

        .logs-sub {
            margin: 8px 0 14px;
            color: #4b5563;
            border-top: 1px solid #d6dce8;
            padding-top: 8px;
            font-size: 1.02rem;
        }

        .btn-back {
            background: #101726;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .logs-table th,
        .logs-table td {
            border: 1px solid #dde3ef;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.96rem;
            color: #202938;
            vertical-align: top;
        }

        .logs-table th {
            background: #e9eef7;
            font-weight: 700;
        }

        .action-edit,
        .action-delete {
            font-weight: 800;
        }

        .action-edit { color: #0f172a; }
        .action-delete { color: #b91c1c; }

        .change-link {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
        }

        .empty-note {
            color: #6b7280;
            font-size: 0.95rem;
        }
    </style>

    <div class="logs-wrap">
        <div class="logs-top">
            <h1 class="logs-title">Activity Logs</h1>
            <a href="{{ route('home') }}" class="btn-back">Back to Dashboard</a>
        </div>
        <p class="logs-sub">History of edited and deleted records.</p>

        @if($logs->count())
            <table class="logs-table">
                <thead>
                    <tr>
                        <th style="width: 180px;">Date</th>
                        <th style="width: 90px;">Action</th>
                        <th style="width: 100px;">Type</th>
                        <th style="width: 100px;">Record ID</th>
                        <th>Description</th>
                        <th style="width: 90px;">Changes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="{{ strtoupper($log->action) === 'DELETE' ? 'action-delete' : 'action-edit' }}">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td>{{ $log->subject_type }}</td>
                            <td>{{ $log->record_id }}</td>
                            <td>{{ $log->description }}</td>
                            <td><a class="change-link" href="{{ route('activity-logs.show', $log->id) }}">▶ View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">No activity logs yet.</p>
        @endif
    </div>
@endsection
