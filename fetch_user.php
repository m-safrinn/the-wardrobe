<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

include 'Connection.php';  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];


$query = "SELECT * FROM user WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
   
    echo "User not found.";
    exit();
}


$stmt->close();
$conn->close();
