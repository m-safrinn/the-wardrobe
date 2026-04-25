<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'Connection.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($data)
    {
        global $conn;
        return htmlspecialchars(stripslashes(trim($conn->real_escape_string($data ?? ''))));
    }

    // Validate name
    if (empty($_POST['name'])) {
        $errors['name'] = "Name is required.";
    } else {
        $name = sanitizeInput($_POST['name']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors['name'] = "Name should not contain numbers or symbols";
        }
    }

    // Validate address
    if (empty($_POST['address'])) {
        $errors['address'] = "Address is required.";
    } else {
        $address = sanitizeInput($_POST['address']);
    }

    // Validate phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Phone number is required.";
    } else {
        $phone = sanitizeInput($_POST['phone']);
        if (!preg_match("/^\+?[0-9]{10,15}$/", $phone)) {
            $errors['phone'] = "Invalid phone number format.";
        }
    }

    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } else {
        $email = sanitizeInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
    }

    $user_id = $_SESSION['user_id'];

    if (empty($errors)) {
        // Fetch cart items for the logged-in user
        $sql = "
            SELECT 
                ci.product_id, 
                ci.size, 
                ci.quantity, 
                p.price, 
                p.new_price,
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

        $order_items = [];
        $total_amount = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Determine unit price based on sale status
                $unit_price = $row['on_sale'] ? $row['new_price'] : $row['price'];
                $subtotal = $unit_price * $row['quantity'];

                $order_items[] = [
                    'product_id' => $row['product_id'],
                    'size' => $row['size'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $unit_price,
                    'subtotal' => $subtotal
                ];

                $total_amount += $subtotal;
            }
        } else {
            echo "Your cart is empty.";
            exit();
        }
        $stmt->close();

        $conn->begin_transaction();

        $sql = "INSERT INTO orders (user_id, name, address, phone, email, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssd", $user_id, $name, $address, $phone, $email, $total_amount);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            $sql = "INSERT INTO order_items (order_id, product_id, size, quantity, unit_price, ItemTot) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            foreach ($order_items as $item) {
                $stmt->bind_param("iisidd", $order_id, $item['product_id'], $item['size'], $item['quantity'], $item['unit_price'], $item['subtotal']);
                $stmt->execute();

                // Deduct quantity from products table
                $update_sql = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
                $update_stmt->execute();
            }

            $delete_sql = "DELETE FROM cart_items WHERE UserID = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();

            $conn->commit();
            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'thewardrobearvs@gmail.com'; // Replace with your email
                $mail->Password   = 'rbxxakuqegvoemhm';    // Replace with your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('thewardrobearvs@gmail.com', 'TheWardrobe');
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation';
                $mail->Body    = "
                Dear $name,<br><br>
                Thank you for your order!<br>
                Your order details are as follows:<br><br>
                Order ID: $order_id<br>
                Name: $name<br>
                Address: $address<br>
                Phone: $phone<br>
                Email: $email<br>
                Total Amount: Rs $total_amount<br><br>
                Regards,<br>
                TheWardrobe Team
            ";

                $mail->send();
                echo 'Order confirmation email has been sent';
            } catch (Exception $e) {
                echo "Order confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            header("Location: confirmation.php?order_id=$order_id");
            exit();
        } else {
            echo "Error: " . $conn->error;
            $conn->rollback();
        }
        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: checkout.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
