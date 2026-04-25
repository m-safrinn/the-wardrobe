<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $cart_item_id = $data['cart_item_id'];
    $action = $data['action']; // 'update' or 'delete'

    if ($action === 'update') {
        $quantity = $data['quantity'];

        // Fetch product details to calculate new price
        $sql_fetch_product = "SELECT p.price, p.on_sale, p.new_price FROM products p
        JOIN cart_items ci ON p.product_id = ci.product_id
        WHERE ci.cart_item_id = ?";
        $stmt_fetch_product = $conn->prepare($sql_fetch_product);
        $stmt_fetch_product->bind_param("i", $cart_item_id);
        $stmt_fetch_product->execute();
        $result_product = $stmt_fetch_product->get_result();

        if ($result_product->num_rows > 0) {
            $row_product = $result_product->fetch_assoc();
            if ($row_product['on_sale'] == true) {
                $unit_price = $row_product['new_price'];
            } else {
                $unit_price = $row_product['price'];
            }
            $new_price = $unit_price * $quantity;
            // Update cart item with new quantity and calculated price
            $sql_update_cart = "UPDATE cart_items SET quantity = ?, price = ? WHERE cart_item_id = ?";
            $stmt_update_cart = $conn->prepare($sql_update_cart);
            $stmt_update_cart->bind_param("idi", $quantity, $new_price, $cart_item_id);

            if ($stmt_update_cart->execute()) {
                echo json_encode(['success' => true, 'new_price' => $new_price]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update cart item']);
            }

            $stmt_update_cart->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch product details']);
        }

        $stmt_fetch_product->close();
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM cart_items WHERE cart_item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cart_item_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

    $conn->close();
}
