<?php
session_start();
// Check if the user is an admin (this logic depends on how you handle user roles)
if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: login.php");
    exit();
}

include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    if ($action == 'deliver') {
        // Update the order status to 'Delivered'
        $sql = "UPDATE orders SET status = 'Delivered' WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Order #$order_id marked as delivered.";
        } else {
            $_SESSION['message'] = "Failed to update the order status.";
        }

        $stmt->close();
    }
}

$conn->close();
header("Location: order.php");
exit();
