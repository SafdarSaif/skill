<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fee Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            padding: 40px 0;
            margin: 0;
        }

        .receipt-container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .receipt-header h2 {
            margin: 0;
            color: #1e293b;
            font-size: 28px;
        }

        .receipt-header p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .section-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            font-size: 15px;
        }

        table th {
            background-color: #f8fafc;
            color: #1e293b;
            text-align: left;
            width: 30%;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #64748b;
            font-size: 14px;
        }

        .amount {
            color: #16a34a;
            font-weight: bold;
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        {{-- <div class="receipt-header">
            <h2>Fee Receipt</h2>
            <p>Transaction Summary</p>
        </div> --}}

        <div class="receipt-header">
            <img src="assets/img/logo/logo 3.jpg" alt="Institute Logo" style="max-height: 80px; margin-bottom: 10px;">
            <h2>Fee Receipt</h2>
            <p>Transaction Summary</p>
        </div>
        
        <div class="section-title">Payment Details</div>
        <table>
            <tr>
                <th>Transaction ID</th>
                <td>{{ $payment->transaction_id }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ date('d-m-Y H:i:s', strtotime($payment->payment_confirmation_date)) }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td class="status">{{ ucfirst($payment->payment_status) }}</td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td class="amount">Rs. {{ number_format((float) $payment->amount, 2, '.', ',') }}</td>
            </tr>
        </table>

        <div class="section-title">Student Details</div>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $student->email }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $student->mobile }}</td>
            </tr>
            {{-- Uncomment below if address is required
        <tr>
            <th>Address</th>
            <td>{{ $student->address }}, {{ $student->city }}, {{ $student->state }} - {{ $student->pincode }}</td>
        </tr>
        --}}
        </table>

        <div class="section-title">Course Details</div>
        <table>
            <tr>
                <th>Course Name</th>
                <td>{{ $course->name }}</td>
            </tr>
            <tr>
                <th>Duration</th>
                <td>{{ $course->duration }} months</td>
            </tr>
            <tr>
                <th>Price</th>
                <td class="amount">Rs. {{ number_format((float) $course->price, 2, '.', ',') }}</td>
            </tr>
        </table>

        <div class="footer">
            Thank you for choosing us! <br>
            For any assistance, contact our support team at <strong>support@edtechinnovate.com</strong>.
        </div>
    </div>

</body>

</html>
