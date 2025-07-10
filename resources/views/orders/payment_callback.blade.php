<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 80px; }
        .success { color: #27ae60; }
        .failed { color: #e74c3c; }
    </style>
</head>
<body>
    @if ($status === 'success')
        <h1 class="success">Payment Successful!</h1>
        <p>Your order #{{ $order->id }} has been paid.</p>
    @else
        <h1 class="failed">Payment Failed</h1>
        <p>Unfortunately, your payment for order #{{ $order->id }} was not successful.</p>
    @endif
    <a href="/">Back to Home</a>
</body>
</html>