<?php
// update_password.php

session_start();
require_once 'Connection.php'; // Include your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $repeat_new_password = trim($_POST['repeat_new_password']);

    // Validate form data
    if (empty($current_password) || empty($new_password) || empty($repeat_new_password)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: customer.php"); // Redirect back to the customer dashboard
        exit;
    }

    if ($new_password !== $repeat_new_password) {
        $_SESSION['message'] = "New passwords do not match!";
        header("Location: customer.php"); // Redirect back to the customer dashboard
        exit;
    }

    // Fetch the user from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM user WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the current password is correct
    if (!password_verify($current_password, $user['Password'])) {
        $_SESSION['message'] = "Current password is incorrect!";
        header("Location: customer.php"); // Redirect back to the customer dashboard
        exit;
    }

    // Hash the new password
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE user SET Password = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_new_password, $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Password changed successfully!";
    } else {
        $_SESSION['message'] = "Error changing password!";
    }

    header("Location: customer.php");
}
