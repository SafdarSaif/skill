{{-- resources/views/errors/401.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>401 Unauthorized</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            text-align: center;
            padding-top: 10%;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #ffc107;
        }
        .error-message {
            font-size: 24px;
            margin-top: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">401</div>
        <div class="error-message">Unauthorized Access. You are not allowed to view this page.</div>
        <a href="{{ url('/') }}" class="btn btn-primary mt-4">Go to Homepage</a>
    </div>
</body>
</html>
