<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Clear errors and form data from session
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
include 'connection.php';
$user_id = $_SESSION['user_id'];

// Fetch user email and username
$sql_user = "SELECT Email FROM user WHERE UserID = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_email = "";
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_email = $row_user['Email'];
}

$stmt_user->close();

// Fetch cart items
$sql = "
    SELECT 
        ci.cart_item_id,
        p.product_name,
        p.price,
        p.new_price,
        p.on_sale,
        ci.size, 
        ci.quantity, 
        ci.price AS Subtotal,
        p.image_url
    FROM 
        cart_items ci
    JOIN 
        products p ON ci.product_id = p.product_id
    WHERE 
        ci.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_amount = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine unit price
        $unit_price = $row['on_sale'] ? $row['new_price'] : $row['price'];
        $row['unit_price'] = $unit_price;
        $row['Subtotal'] = $unit_price * $row['quantity'];
        $cart_items[] = $row;
        $total_amount += $row['Subtotal'];
    }
} else {
    $_SESSION['cart_notification'] = "Your cart is empty!";
    header("Location: home.php");
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
    <title>Checkout</title>
    <script src="main.js"></script>
    <link rel="stylesheet" href="Shared/checkout.css">
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/home.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <section class="container">
        <div class="cart-summary">
            <h2>Cart Summary</h2>
            <table class="cart-details-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Size</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['size']); ?></td>
                            <td>Rs <?php echo htmlspecialchars(number_format($item['unit_price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>Rs <?php echo htmlspecialchars(number_format($item['Subtotal'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>Rs <?php echo htmlspecialchars(number_format($total_amount, 2)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="ShippingSection">
            <form action="process_checkout.php" method="POST" class="shipping-form">
                <h2>Shipping Information</h2>

                <div class="form-group error-container">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control <?php echo isset($errors['name']) ? 'error' : ''; ?>" required value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>">
                    <?php if (isset($errors['name'])) echo '<div class="error-message">' . $errors['name'] . '</div>'; ?>
                </div>

                <div class="form-group error-container">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control <?php echo isset($errors['address']) ? 'error' : ''; ?>" required value="<?php echo isset($form_data['address']) ? htmlspecialchars($form_data['address']) : ''; ?>">
                    <?php if (isset($errors['address'])) echo '<div class="error-message">' . $errors['address'] . '</div>'; ?>
                </div>

                <div class="form-group error-container">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-control <?php echo isset($errors['phone']) ? 'error' : ''; ?>" required value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>">
                    <?php if (isset($errors['phone'])) echo '<div class="error-message">' . $errors['phone'] . '</div>'; ?>
                </div>

                <div class="form-group error-container">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email_display" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" required disabled>
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($user_email); ?>">
                    <?php if (isset($errors['email'])) echo '<div class="error-message">' . $errors['email'] . '</div>'; ?>
                </div>

                <div class="form-group error-container">
                    <label for="payment">Payment</label>
                    <input id="payment" name="payment" class="form-control" value="Cash on Delivery" required disabled>
                </div>

                <div class="form-group">
                    <button type="submit" id="checkout-button" class="btn btn-primary">Confirm Order</button>
                </div>
            </form>
        </div>
    </section>
</body>

</html>