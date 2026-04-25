<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'Connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user with product names
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
        GROUP_CONCAT(p.product_name SEPARATOR ', ') AS product_names,
        GROUP_CONCAT(p.product_id SEPARATOR ',') AS product_ids
    FROM 
        orders o
    JOIN 
        order_items oi ON o.order_id = oi.order_id
    JOIN 
        products p ON oi.product_id = p.product_id
    WHERE 
        o.user_id = ?
    GROUP BY 
        o.order_id, o.name, o.address, o.phone, o.email, o.total_amount, o.created_at, o.status";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$stmt->close();
$conn->close();
