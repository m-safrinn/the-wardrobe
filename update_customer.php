<?php
// update_customer.php

session_start();
include 'Connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Basic validation
    if (empty($username) || empty($email) || empty($phone)) {
        $_SESSION['error'] = 'All fields are required.';
        header("Location: customer.php");
        exit();
    }

    // Sanitize and validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header("Location: customer.php");
        exit();
    }

    // Update user details
    $query = "UPDATE user SET Username = ?, Email = ?, PhoneNumber = ? WHERE UserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        // Handle SQL preparation error
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("sssi", $username, $email, $phone, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Update successful
        $_SESSION['success'] = 'Your details have been updated successfully.';
    } else {
        // No rows updated
        $_SESSION['success'] = 'No changes were made.';
    }

    $stmt->close();
    $conn->close();

    header("Location: customer.php");
    exit();
} else {
    header("Location: customer.php");
    exit();
}
