<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItemId = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : null;
    $userID = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    if ($cartItemId && $userID) {
        $sql = "DELETE FROM cart_items WHERE cart_item_id = ? AND UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cartItemId, $userID);

        if ($stmt->execute()) {
            $_SESSION['cart_notification'] = "Item removed from cart!";
            exit();
        } else {
            echo "Failed to remove item.";
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
}
