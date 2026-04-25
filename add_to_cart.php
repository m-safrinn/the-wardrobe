<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($data)
    {
        global $conn;
        return htmlspecialchars(stripslashes(trim($conn->real_escape_string($data ?? ''))));
    }

    $product_id = sanitizeInput($_POST['product_id']);
    $size = sanitizeInput($_POST['size']);
    $quantity = sanitizeInput($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    // Fetch the product details
    $sql = "SELECT price, new_price, on_sale, stock_quantity FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        if ($product['on_sale'] && $product['new_price'] > 0) {
            $price_per_unit = $product['new_price'];
        } else {
            $price_per_unit = $product['price'];
        }

        if ($quantity > $product['stock_quantity']) {
            $_SESSION['cart_notification'] = "The requested quantity is not available in stock.";
            header("Location: product_page.php?product_id=" . $product_id);
            exit();
        } else {
            $total_price = $quantity * $price_per_unit;

            $sql = "INSERT INTO cart_items (UserID, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisid", $user_id, $product_id, $size, $quantity, $total_price);

            if ($stmt->execute()) {
                $_SESSION['cart_notification'] = "Item added to cart!";
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Product not found.";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
