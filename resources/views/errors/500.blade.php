{{-- resources/views/errors/500.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Error - 500</title>
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
            color: #dc3545;
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
        <div class="error-code">500</div>
        <div class="error-message">Oops! Something went wrong on our end.</div>
        <a href="{{ url('/') }}" class="btn btn-primary mt-4">Go to Homepage</a>
    </div>
</body>
</html>
