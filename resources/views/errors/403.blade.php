<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .error-box .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #dc3545;
        }
        .error-box .error-icon {
            font-size: 70px;
            color: #dc3545;
        }
        .error-box .error-message {
            font-size: 22px;
            color: #6c757d;
        }
        .btn-primary {
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-icon mb-3">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-message mb-4">
            You do not have permission to access this page.
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Go Back
        </a>
    </div>
</body>
</html>
