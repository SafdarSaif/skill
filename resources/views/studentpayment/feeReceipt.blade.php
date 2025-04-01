<!DOCTYPE html>
<html>
<head>
    <title>Fee Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 100%;
            border: 1px solid #ddd;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Fee Receipt</h2>

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
                <td>{{ ucfirst($payment->payment_status) }}</td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td>{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </table>

        <h3>Student Details</h3>
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
            <tr>
                <th>Address</th>
                <td>{{ $student->address }}, {{ $student->city }}, {{ $student->state }} - {{ $student->pincode }}</td>
            </tr>
        </table>

        <h3>Course Details</h3>
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
                <td>{{ number_format($course->price, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            Thank you for choosing us! For any assistance, contact our support team.
        </div>
    </div>

</body>
</html>
