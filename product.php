<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_notification'] = "Login to continue";
    header("Location: login.php");
    exit();
}
include 'connection.php';
function sanitizeInput($data)
{
    global $conn;
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($data ?? ''))));
}

if (isset($_GET['product_id'])) {
    $product_id = sanitizeInput($_GET['product_id']);
    $sql = "
    SELECT 
        p.product_name, 
        p.price, 
        p.new_price, 
        c1.category_name AS category_name, 
        c2.category_name AS subcategory_name, 
        p.stock_quantity, 
        p.is_new_arrival, 
        p.on_sale, 
        p.image_url, 
        p.image_url2, 
        p.image_url3,
        p.sizes 
    FROM 
        products p
    LEFT JOIN 
        categories c1 ON p.category_id = c1.category_id
    LEFT JOIN 
        categories c2 ON p.subcategory_id = c2.category_id
    WHERE 
        p.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<p>Product not found.</p>";
        exit;
    }


    $stmt->close(); // Fetch reviews for the product
    $sql_reviews = "
SELECT 
    r.rating, 
    r.comment,
    r.user_email,
    r.created_at 
FROM 
    reviews r
WHERE 
    r.product_id = ?";

    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->bind_param("i", $product_id);
    $stmt_reviews->execute();
    $result_reviews = $stmt_reviews->get_result();

    $reviews = [];
    if ($result_reviews->num_rows > 0) {
        while ($row = $result_reviews->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    $stmt_reviews->close();
} else {
    echo "<p>No Product ID provided.</p>";
    exit;
}
$conn->close();
?>
<link rel="stylesheet" href="product.css">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="shortcut icon" href="IMG/log.jpg">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
    <link rel="stylesheet" href="Shared/product.css">
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/home.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <div class="product-details">
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <div class="price-section">
                <p class="price">
                    <?php if ($product['on_sale'] && $product['new_price'] > 0) : ?>
                        <span class="old-price">Rs <?php echo htmlspecialchars($product['price']); ?></span>
                        Rs <span class="new-price"><?php echo htmlspecialchars($product['new_price']); ?></span>
                        <span class="sale-label">SALE</span>
                    <?php else : ?>
                        Rs <span class="regular-price"><?php echo htmlspecialchars($product['price']); ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <p>Tax included.</p>
            <ul>
                <li>100% Cotton.</li>
            </ul>
            <p>Model wears - US 2</p>
            <p>Height - 5.4"</p>
            <div class="size-selection">
                <label for="size">SIZE: </label>
                <?php
                $sizesString = $product['sizes'] ?? '';
                $availableSizes = explode(',', $sizesString);
                $allSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                foreach ($allSizes as $size) {
                    if (in_array($size, $availableSizes)) {
                        echo "<button class='size-button'data-size='$size'>$size</button>";
                    } else {
                        echo "<button class='size-button disabled' disabled>$size</button>";
                    }
                }
                ?>
                <a href="#" class="sizing-guide">Sizing guide</a>
            </div>
            <div class="quantity-selection">
                <button class="quantity-button" onclick="updateQuantity(-1)">-</button>
                <span class="quantity" id="quantity">1</span>
                <button class="quantity-button" onclick="updateQuantity(1)">+</button>
            </div>
            <button class="add-to-cart" onclick="addToCart(<?php echo htmlspecialchars($product_id); ?>)">ADD TO CART</button>
        </div>
        <div class="product-images">
            <div class="main-image">
                <img id="mainImage" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image 1">
            </div>
            <div class="thumbnail-images">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image 1" onclick="changeImage('<?php echo htmlspecialchars($product['image_url']); ?>')">
                <?php if (!empty($product['image_url2'])) : ?>
                    <img src="<?php echo htmlspecialchars($product['image_url2']); ?>" alt="Product Image 2" onclick="changeImage('<?php echo htmlspecialchars($product['image_url2']); ?>')">
                <?php endif; ?>
                <?php if (!empty($product['image_url3'])) : ?>
                    <img src="<?php echo htmlspecialchars($product['image_url3']); ?>" alt="Product Image 3" onclick="changeImage('<?php echo htmlspecialchars($product['image_url3']); ?>')">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function changeImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
        }

        function updateQuantity(amount) {
            var quantityElement = document.getElementById('quantity');
            var newQuantity = parseInt(quantityElement.textContent) + amount;
            if (newQuantity > 0) {
                quantityElement.textContent = newQuantity;
            }
        }

        function addToCart(productId) {
            var selectedSize = document.querySelector('.size-button.active');
            if (!selectedSize) {
                showToast("Please select a size");
                <?php $_SESSION['cart_notification'] = "Please select a size."; ?>
                return;
            }
            var size = selectedSize.getAttribute('data-size');
            var quantity = document.getElementById('quantity').textContent;

            var formData = new FormData();
            formData.append('product_id', productId);
            formData.append('size', size);
            formData.append('quantity', quantity);

            fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    window.location.href = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.querySelectorAll('.size-button').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.size-button').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });
    </script>


    <div class="tab-container">

        <div class="reviews-section">
            <h2>Product Reviews</h2>
            <?php if (empty($reviews)) : ?>
                <p>No reviews yet.</p>
            <?php else : ?>
                <ul class="reviews-list">
                    <?php foreach ($reviews as $review) : ?>
                        <li class="review-item">
                            <div class="review-left">
                                <div class="stars"><?php echo str_repeat('&#9733;', htmlspecialchars($review['rating'])); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($review['user_email']); ?></div>
                                <div class="review-date"><?php echo date("M j, Y", strtotime($review['created_at'])); ?></div>
                            </div>
                            <div class="review-right">
                                <p class="review-title">
                                <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <section class="section 4">
        <?php include 'Shared/footer.php'; ?>
    </section>
</body>

</html>