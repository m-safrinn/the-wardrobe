<?php
session_start();
// Check if the user is an admin (this logic depends on how you handle user roles)
if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: login.php");
    exit();
}

include '../connection.php';

// Fetch all orders with product names
$sql = "
    SELECT 
        o.order_id, 
        o.name, 
        o.address, 
        o.phone, 
        o.email, 
        o.total_amount, 
        o.created_at, 
        o.status,
        GROUP_CONCAT(p.product_name SEPARATOR ', ') AS product_names
    FROM 
        orders o
    JOIN 
        order_items oi ON o.order_id = oi.order_id
    JOIN 
        products p ON oi.product_id = p.product_id
    GROUP BY 
        o.order_id, o.name, o.address, o.phone, o.email, o.total_amount, o.created_at, o.status";

$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="shortcut icon" href="IMG/log.jpg">
    <title>Admin Orders</title>
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/order.css">
</head>

<body>

    <div class="order-container">
        <h1>All Orders</h1>
        <div class="order-grid">
            <?php foreach ($orders as $order) : ?>
                <div class="order-card">
                    <p><strong>Products:</strong> <?php echo htmlspecialchars($order['product_names']); ?></p>
                    <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Total Amount:</strong> Rs <?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

                    <?php if ($order['status'] == 'Pending') : ?>
                        <form action="process_order.php" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit" name="action" value="deliver">Deliver Product</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>