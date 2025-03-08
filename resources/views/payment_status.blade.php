<!DOCTYPE html>
<html>
<head>
    <title>Payment Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>{{ $message }}</h2>
        <pre>{{ print_r($response, true) }}</pre>
        <a href="/" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>