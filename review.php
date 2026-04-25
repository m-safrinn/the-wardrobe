<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_ids']) && isset($_GET['email'])) {
    $product_ids = explode(',', $_GET['product_ids']);
    $product_ids = array_unique($product_ids);
    $email = $_GET['email'];
} else {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="Shared/customer.css">
    <title>Write a Review</title>
</head>

<body>
    <div class="review-container">
        <h1>Write a Review</h1>
        <form action="submit_review.php" method="POST">

            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php foreach ($product_ids as $product_id) : ?>
                <input type="hidden" name="product_ids[]" value="<?php echo htmlspecialchars($product_id); ?>">

                <div class="product-review">
                    <div class="rating">
                        <input type="radio" id="star5_<?php echo htmlspecialchars($product_id); ?>" name="rating_<?php echo htmlspecialchars($product_id); ?>" value="5"><label for="star5_<?php echo htmlspecialchars($product_id); ?>"></label>
                        <input type="radio" id="star4_<?php echo htmlspecialchars($product_id); ?>" name="rating_<?php echo htmlspecialchars($product_id); ?>" value="4"><label for="star4_<?php echo htmlspecialchars($product_id); ?>"></label>
                        <input type="radio" id="star3_<?php echo htmlspecialchars($product_id); ?>" name="rating_<?php echo htmlspecialchars($product_id); ?>" value="3"><label for="star3_<?php echo htmlspecialchars($product_id); ?>"></label>
                        <input type="radio" id="star2_<?php echo htmlspecialchars($product_id); ?>" name="rating_<?php echo htmlspecialchars($product_id); ?>" value="2"><label for="star2_<?php echo htmlspecialchars($product_id); ?>"></label>
                        <input type="radio" id="star1_<?php echo htmlspecialchars($product_id); ?>" name="rating_<?php echo htmlspecialchars($product_id); ?>" value="1"><label for="star1_<?php echo htmlspecialchars($product_id); ?>"></label>
                    </div>
                    <label for="comment_<?php echo htmlspecialchars($product_id); ?>">Comment:</label>
                    <textarea id="comment_<?php echo htmlspecialchars($product_id); ?>" name="comment_<?php echo htmlspecialchars($product_id); ?>" rows="4" cols="50" required></textarea>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>

</html>