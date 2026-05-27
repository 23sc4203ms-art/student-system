<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Logs - Teacher</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root{
            --bg:#f1f6fb; --card:#ffffff; --muted:#6b7280; --primary:#1565d8; --accent:#2f63d9; --success:#16a34a; --danger:#dc2626;
        }
        body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: linear-gradient(180deg,#eef6ff 0%,#f7fbff 100%); color:#16202a; }
        /* navbar moved to partial */
        .container { max-width: 1100px; margin: 28px auto; padding: 0 20px; }
        .panel { background: var(--card); border-radius: 12px; padding: 18px; box-shadow: 0 10px 30px rgba(16,24,40,0.06); margin-bottom: 18px; border: 1px solid rgba(15,23,42,0.04); }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
        .title { font-size: 1.4rem; font-weight: 800; color: #0f2f73; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; overflow: hidden; background: #fff; }
        table th, table td { border: 1px solid #dde3ef; padding: 11px 12px; text-align: left; color: #202938; }
        table th { background: #e9eef7; font-weight: 700; }
        .empty-note { padding: 18px; color: #6b7280; text-align: center; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination .page-item span { padding: 8px 12px; margin: 0 4px; border: 1px solid #d8deea; text-decoration: none; color: #2f63d9; border-radius: 4px; display: inline-block; }
        .pagination a:hover { background: #2f63d9; color: white; }
        .pagination .active { background: #2f63d9; color: white; border-color: #2f63d9; }
    </style>
</head>
<body>
    @include('partials.navbar', ['title' => 'Activity Logs'])

    <div class="container">
        <div class="panel">
            <div class="top">
                <h2 class="title">Activity Logs</h2>
            </div>
            @if($logs->count())
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Subject Type</th>
                                <th>Record ID</th>
                                <th>Description</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->subject_type }}</td>
                                    <td>{{ $log->record_id }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="empty-note">No activity logs found.</div>
            @endif
        </div>
    </div>
</body>
</html>
</html>
