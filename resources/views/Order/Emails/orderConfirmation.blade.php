<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Thank you for your order, {{ $user->name }}!</h2>
    <p>Your order has been successfully placed.</p>
    <p><strong>Order ID:</strong> {{ $orderId }}</p>
    <p>We’ll deliver your items by tomorrow. You can view your orders in your dashboard.</p>
    <br>
    <p>Regards,<br>Your Store Team</p>
</body>
</html>
