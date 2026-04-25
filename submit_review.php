<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['product_ids'])) {
    include 'connection.php';

    $email = $_POST['email'];
    $product_ids = $_POST['product_ids'];

    foreach ($product_ids as $product_id) {
        if (isset($_POST['rating_' . $product_id]) && isset($_POST['comment_' . $product_id])) {
            $rating = $_POST['rating_' . $product_id];
            $comment = $_POST['comment_' . $product_id];

            // Insert review into the database
            $sql = "INSERT INTO reviews (product_id, user_email, rating, comment) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isis", $product_id, $email, $rating, $comment);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();
    $_SESSION['message'] = "Your reviews have been submitted successfully.";
    header("Location: customer.php");
    exit();
} else {
    header("Location: customer.php");
    exit();
}
?>
