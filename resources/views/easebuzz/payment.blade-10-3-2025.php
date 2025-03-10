<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easebuzz Payment</title>
</head>
<body onload="document.getElementById('easebuzz_payment_form').submit();">
    <h3>Redirecting to Easebuzz...</h3>
    <form id="easebuzz_payment_form" method="POST" action="{{ $baseUrl }}/payment/initiateLink">
        @foreach ($postdata as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>

</html>
