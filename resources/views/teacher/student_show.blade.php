<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Details</title>
</head>
<body>
    <h1>{{ $student->Fname }} {{ $student->Mname }} {{ $student->Lname }}</h1>
    <p>Email: {{ $student->Email }}</p>
    <p>Contact: {{ $student->Contactno }}</p>
    <p>Address: {{ $student->Address }}</p>
    <p>Degree: {{ optional($student->degree)->name }}</p>
    <p><a href="{{ route('teacher.management') }}">Back to management</a></p>
</body>
</html>
