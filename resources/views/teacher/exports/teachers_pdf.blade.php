<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teachers Export</title>
    <style>
        body{ font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; }
        table{ width:100%; border-collapse: collapse; }
        th, td{ border:1px solid #ccc; padding:6px; text-align:left; }
        th{ background:#f4f4f4; }
    </style>
</head>
<body>
    <h2>Teachers</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->username }}</td>
                <td>{{ $t->email }}</td>
                <td>{{ $t->created_at ? $t->created_at->toDateTimeString() : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
