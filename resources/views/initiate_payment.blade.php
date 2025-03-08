<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easebuzz Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Easebuzz Payment Gateway</h2>
        <form action="{{ url('api/easebuzz/initiate') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="txnid" class="form-label">Transaction ID</label>
                <input type="text" class="form-control" id="txnid" name="txnid" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
    <label for="productinfo" class="form-label">Product Information</label>
    <input type="text" class="form-control" id="productinfo" name="productinfo" required>
</div>
            <!-- <input type="hidden" name="https://pay.easebuzz.in/payment/initiateLink"> -->
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
        
        @if(!empty($result))
            <div class="alert alert-info mt-4">
                <h4>Payment Response</h4>
                <pre>{{ print_r($result, true) }}</pre>
            </div>
        @endif
    </div>
</body>
</html>
