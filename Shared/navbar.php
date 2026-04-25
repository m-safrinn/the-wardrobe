<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS cart_count FROM cart_items WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $cart_data = $result->fetch_assoc();
        $cart_count = $cart_data['cart_count'];
    }
    $stmt->close();
}
?>
<section class="section1">
    <div class="navbar">
        <div class="logo">
            <a href="home.php" class="logo"> <img src="IMG/loggoo.jpg" alt="The Wardrobe"> </a>
        </div>
        <div class="search-bar">
            <form action="collection.php" method="POST">
                <input type="text" placeholder="What are you looking for?" name="search" class="search-box">
                <input type="hidden" id="categoryInput" name="category_id">
            </form>
        </div>
        <?php
        if (isset($_SESSION['user_type']) && isset($_SESSION['user_name'])) {
            $Username = $_SESSION['user_name'];
            $UserType = $_SESSION['user_type'];

            if ($UserType == 'Admin') {
                $dashboardLink = 'Admin dashboard/index.php';
            } else {
                $dashboardLink = 'customer.php';
            }
        ?>
            <div class="auth-buttons">
                <a href="<?php echo $dashboardLink; ?>" class="username">
                    <img src="IMG/users1.png" alt="User Icon"><?php echo $Username; ?>
                </a>
            </div>
        <?php
        } else {
        ?>
            <div class="auth-buttons">
                <a href="login.php" class="underline-effect">Login</a>
            </div>
        <?php
        }
        ?>

        <div class="cart-icon" id="cartIcon">
            <img src="IMG/bag2.png" alt="cart-icon">
            <span class="cart-badge" id="cartBadge"><?php echo $cart_count; ?></span>
        </div>

        <div id="toastNotification" class="toast-notification"></div>


</section>

<section class="section2">
    <div class="navbar">
        <nav>
            <ul class="nav-links">
                <li class="dropdown">
                    <a href="NewIn.php" class="underline-effect">NEW IN</a>
                    <div class="dropdown-content">
                        <a href="NewIn.php?category_id=1" class="underline-eff">WOMENS</a>
                        <a href="NewIn.php?category_id=2" class="underline-eff">MENS</a>
                    </div>
                </li>
                <!-- category update -->
                <?php
                function fetchParentCategories($conn, $parent_id = 0)
                {
                    $sql = "SELECT category_id, category_name FROM categories WHERE parent_id = $parent_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<ul>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<li class='dropdown'>";
                            echo "<a href='collection.php?id=" . $row["category_id"] . "' class='underline-effect'>" . strtoupper($row["category_name"]) . "</a>";

                            $childSql = "SELECT category_id, category_name FROM categories WHERE parent_id = " . $row["category_id"];
                            $childResult = $conn->query($childSql);

                            if ($childResult->num_rows > 0) {
                                echo "<div class='dropdown-content'>";
                                while ($childRow = $childResult->fetch_assoc()) {
                                    echo "<a href='collection.php?id=" . $childRow["category_id"] . "' class='underline-effect'>" . $childRow["category_name"] . "</a>";
                                }
                                echo "</div>";
                            }

                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                }
                fetchParentCategories($conn);
                ?>
                <li class="dropdown">
                    <a href="size.php" class="underline-effect">SIZE GUIDE</a>
                </li>
                <li class="dropdown">
                    <a href="sales.php" class="underline-effect">BIG SALE</a>
                    <div class="dropdown-content">
                        <a href="sales.php?category_id=1" class="underline-eff">WOMENS</a>
                        <a href="sales.php?category_id=2" class="underline-eff">MENS</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</section>
<section class="sidebar">
    <script src="Shared/main.js"></script>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="cart-content">
            <?php
            if (isset($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];
                $sql = "SELECT ci.cart_item_id, p.product_name, p.price, p.new_price,p.on_sale, ci.size, ci.quantity,p.image_url
                        FROM cart_items ci
                        JOIN products p ON ci.product_id = p.product_id
                        WHERE ci.UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();

                $cartItems = [];
                while ($row = $result->fetch_assoc()) {
                    $cartItems[] = $row;
                }

                $stmt->close();
                $conn->close();
            } else {
                $cartItems = [];
            }
            ?>
            <?php if (empty($cartItems)) : ?>
                <div class="emptyCart">
                    <div class="cart-logo">
                        <img src="IMG/emptycart.png">
                    </div>
                    <p>Your cart is currently empty.</p>
                    <a href="login.php" class="btn Cshopping-btn">Start Shopping</a>
                </div>
            <?php else : ?>
                <div class="cart">
                    <h1>Cart</h1>
                    <?php foreach ($cartItems as $item) : ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            </div>
                            <div class="cart-item-details">
                                <h2><?php echo htmlspecialchars($item['product_name']); ?></h2>
                                <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                            </div>
                            <div class="cart-item-quantity">
                                <p>QTY:<?php echo htmlspecialchars($item['quantity']); ?></p>
                            </div>
                            <div class="cart-item-price">
                                <p>Rs. <?php echo htmlspecialchars($item['quantity'] * ($item['on_sale'] && $item['new_price'] < $item['price'] ? $item['new_price'] : $item['price'])); ?></p>
                            </div>
                            <div class="cart-item-actions">
                                <form method="post" id="removeCartItemForm">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <button type="submit" class="remove-button" onclick="removeCartItem(event)">
                                        <span class="material-icons-round">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="cart-buttons">
                        <a href="cart.php" class="out-button">View Cart</a><br>
                        <a href="checkout.php" class="out-button">Proceed to Checkout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div id="overlay" class="overlay"></div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.querySelector(".navbar");

        window.addEventListener("scroll", function() {
            if (window.scrollY > 100) {
                navbar.classList.add("shrink");
            } else {
                navbar.classList.remove("shrink");
            }
        });
    });

    function removeCartItem(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        const formData = new FormData(form);
        fetch('removeFromCart.php', {
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

    function showToast(message) {
        var toast = document.getElementById('toastNotification');
        toast.textContent = message;
        toast.style.display = 'block';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 3000);
    }

    <?php if (isset($_SESSION['cart_notification']) && $_SESSION['cart_notification']) : ?>
        showToast("<?php echo $_SESSION['cart_notification']; ?>");
        <?php unset($_SESSION['cart_notification']); ?>
    <?php endif; ?>
</script>