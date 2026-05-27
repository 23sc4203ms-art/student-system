<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful</title>
</head>
<body>
    <h1 style="color: green;">Login successful. Redirecting to students landing page...</h1>
    <p>
        Redirecting to the students landing page...
        <a href="{{ $redirectUrl }}">Click here if not redirected</a>
    </p>

    <script>
        setTimeout(function () {
            window.location.href = @json($redirectUrl);
        }, 1200);
    </script>
</body>
</html>
