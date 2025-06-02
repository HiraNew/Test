<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Low Stock Alert</title>
</head>
<body>
    <h3>⚠️ Low Stock Alert</h3>
    <p>The following product is running low on stock:</p>
    <ul>
        <li><strong>Product Name:</strong> {{ $product->name }}</li>
        <li><strong>Remaining Quantity:</strong> {{ $product->quantity }}</li>
        <li><strong>Product ID:</strong> {{ $product->id }}</li>
    </ul>
    <p>Please restock it soon to avoid going out of stock.</p>
</body>
</html>
