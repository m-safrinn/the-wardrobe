<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    $_SESSION['cart_notification'] = "Login to continue";
    exit();
}

include 'connection.php';

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$sql = "
    SELECT 
        ci.cart_item_id,
        p.product_name,
        p.price,
        p.new_price,
        ci.size, 
        ci.quantity, 
        ci.price AS item_price,
        p.image_url,
        p.on_sale 
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
        $cart_items[] = $row;
        $total_amount += $row['item_price'];
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
    <script src="main.js"></script>
    <link rel="stylesheet" href="Shared/cart.css">
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/home.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <section class="CartSection">
        <div class="cart-container">
            <h1>Your Cart</h1>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <?php foreach ($cart_items as $item) : ?>
                        <tr data-id="<?php echo $item['cart_item_id']; ?>">
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                <div class="product-info">
                                    <p><?php echo htmlspecialchars($item['product_name']); ?></p>
                                    <p>SIZE: <?php echo htmlspecialchars($item['size']); ?></p>
                                </div>
                            </td>
                            <td>
                                <div class="quantity">
                                    <button class="quantity-button" onclick="updateQuantity(<?php echo $item['cart_item_id']; ?>, -1)">-</button>
                                    <span data-id="<?php echo $item['cart_item_id']; ?>"><?php echo $item['quantity']; ?></span>
                                    <button class="quantity-button" onclick="updateQuantity(<?php echo $item['cart_item_id']; ?>, 1)">+</button>
                                    <button class="delete-button" onclick="deleteItem(<?php echo $item['cart_item_id']; ?>)">&#128465;</button>
                                </div>
                            </td>
                            <td class="unit-price" data-id="<?php echo $item['cart_item_id']; ?>">
                                Rs <?php echo $item['on_sale'] ? htmlspecialchars($item['new_price']) : htmlspecialchars($item['price']); ?>
                            </td>
                            <td class="subtotal" data-id="<?php echo $item['cart_item_id']; ?>">
                                Rs. <span><?php echo htmlspecialchars($item['item_price']); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" class="total">
                            <h2><strong>Total Amount</strong></h2>
                        </td>
                        <td class="total-amount">
                            Rs <?php echo number_format($total_amount, 2); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="checkout-section">
                <a href="checkout.php"> <button id="checkout-button">Checkout</button></a>
            </div>
        </div>
    </section>

    <script>
        function updateQuantity(cartItemId, change) {
            var quantityElement = document.querySelector(`.quantity span[data-id='${cartItemId}']`);
            var newQuantity = parseInt(quantityElement.textContent) + change;
            if (newQuantity > 0) {
                quantityElement.textContent = newQuantity;
                updateTotal(cartItemId, newQuantity);
                updateCartItem(cartItemId, newQuantity);
            }
        }

        function updateTotal(cartItemId, newQuantity) {
            var unitPriceElement = document.querySelector(`.unit-price[data-id='${cartItemId}']`);
            var priceText = unitPriceElement.textContent.replace('Rs ', '');
            var price = parseFloat(priceText);

            var newTotal = price * newQuantity;
            var subtotalElement = document.querySelector(`.subtotal[data-id='${cartItemId}'] span`);
            subtotalElement.textContent = newTotal.toFixed(2);

            calculateTotalPrice();
        }

        function calculateTotalPrice() {
            var totalElements = document.querySelectorAll('.subtotal span');
            var totalPrice = 0;
            totalElements.forEach(function(element) {
                totalPrice += parseFloat(element.textContent);
            });

            document.querySelector('.total-amount').textContent = 'Rs ' + totalPrice.toFixed(2);
            document.getElementById('checkout-button').textContent = 'Checkout • Rs ' + totalPrice.toFixed(2);
        }

        function deleteItem(cartItemId) {
            var row = document.querySelector(`tr[data-id='${cartItemId}']`);
            row.remove();
            removeCartItem(cartItemId);
            calculateTotalPrice();
        }

        function updateCartItem(cartItemId, newQuantity) {
            fetch('cart_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        quantity: newQuantity,
                        action: 'update'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to update cart item');
                    } else {
                        var subtotalElement = document.querySelector(`.subtotal[data-id='${cartItemId}'] span`);
                        subtotalElement.textContent = data.new_price.toFixed(2);
                        calculateTotalPrice();
                    }
                });
        }

        function removeCartItem(cartItemId) {
            fetch('cart_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        action: 'delete'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to remove cart item');
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', calculateTotalPrice);
    </script>
</body>

</html>