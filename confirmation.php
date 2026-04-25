<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

if (!isset($_GET['order_id'])) {
    $_SESSION['cart_notification'] = "Your order is empty!";
    header("Location: home.php");
    exit();
}

$order_id = intval($_GET['order_id']);

$sql = "
         SELECT 
        o.order_id, 
        o.name, 
        o.address, 
        o.phone, 
        o.email, 
        o.total_amount, 
        o.created_at, 
        oi.product_id, 
        p.product_name, 
        oi.size, 
        oi.quantity, 
        oi.unit_price,
        oi.ItemTot 
    FROM 
        orders o
    JOIN 
        order_items oi ON o.order_id = oi.order_id
    JOIN 
        products p ON oi.product_id = p.product_id
    WHERE 
        o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$order_details = [];
$order_info = null;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!$order_info) {
            $order_info = $row;
        }
        $order_details[] = $row;
    }
} else {
    echo "Order not found.";
    exit();
}

$stmt->close();
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
    <title>Confirmation</title>
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/Order.css">
    <link rel="stylesheet" href="Shared/home.css">
    <link rel="stylesheet" href="slideshow.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <div class="container">
        <div class="confirmation-container">
            <div class="order-confirmation">
                <span class="material-icons-round">check_circle</span>
                <h1>We’ve received your order</h1>
            </div>
            <p>Thank you for your purchase!</p>
            <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order_info['order_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order_info['name']); ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order_info['address']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order_info['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order_info['email']); ?></p>
            <p><strong>Total Amount:</strong> Rs <?php echo htmlspecialchars(number_format($order_info['total_amount'], 2)); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order_info['created_at']); ?></p>
        </div>
        <div class="order-details">
            <h2>Order Details</h2>
            <table class="order-details-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['size']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>Rs <?php echo htmlspecialchars(number_format($item['unit_price'], 2)); ?></td>
                            <td>Rs <?php echo htmlspecialchars(number_format($item['ItemTot'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>Rs <?php echo htmlspecialchars(number_format($order_info['total_amount'], 2)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>