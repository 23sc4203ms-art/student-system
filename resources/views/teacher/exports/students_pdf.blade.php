<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Students Export</title>
    <style>
        body{ font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; }
        table{ width:100%; border-collapse: collapse; }
        th, td{ border:1px solid #ccc; padding:6px; text-align:left; }
        th{ background:#f4f4f4; }
    </style>
</head>
<body>
    <h2>Students</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Degree</th>
                <th>Contact</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ optional($s->UserAccount)->username }}</td>
                <td>{{ $s->Email }}</td>
                <td>{{ $s->Fname }}</td>
                <td>{{ $s->Mname }}</td>
                <td>{{ $s->Lname }}</td>
                <td>{{ optional($s->degree)->name }}</td>
                <td>{{ $s->Contactno }}</td>
                <td>{{ $s->created_at ? $s->created_at->toDateTimeString() : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
