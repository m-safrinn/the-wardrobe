<?php
session_start();
include '../Connection.php';

// Fetch reviews data
$query = "
    SELECT r.review_id, r.product_id, r.user_email, r.rating, r.comment, r.created_at, p.product_name as product_name
    FROM reviews r
    JOIN products p ON r.product_id = p.product_id
    ORDER BY r.created_at DESC
";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/reviews.css">
</head>
<body>

<div class="container">
    <h1>Customer Reviews</h1>

    <table class="reviews-table">
        <thead>
            <tr>
                <th>Review ID</th>
                <th>Product</th>
                <th>User Email</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['review_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['comment']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
