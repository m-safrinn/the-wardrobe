<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["Admin"]) || $_SESSION["Admin"] !== true) {
    header("Location: ../home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="index.php" id="home-link"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="product.php" id="products-link"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="category.php" id="category-link"><i class="fas fa-tags"></i> Categories</a></li>
            <li><a href="order.php" id="orders-link"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="view-customers.php" id="customers-link"><i class="fas fa-users"></i> Customers</a></li>
            <li><a href="review.php" id="reviews-link"><i class="fas fa-cogs"></i> Review</a></li>
            <li><a href="reports.php" id="reports-link"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="../home.php"><i class="fas fa-home"></i> Main Page</a></li>

        </ul>
    </div>
    <div class="content">
        <div class="top-nav">
            <div class="search-box">
                <input type="text" placeholder="Search Loading...">
                <button type="button"><i class="fas fa-search"></i></button>
            </div>
            <div class="user-settings">

                <?php
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['user_type']) && isset($_SESSION['user_name'])) {
                    $Username = $_SESSION['user_name'];
                    $UserType = $_SESSION['user_type'];

                    if ($UserType == 'Admin') {
                        echo '<a href="#"><i class="fas fa-user"></i> ' . htmlspecialchars($Username) . '</a>';
                    } else {
                        // Handle other user types or redirection if necessary
                        echo '<a href="#"><i class="fas fa-user"></i> User</a>';
                    }
                } else {
                    // If user is not logged in, you might want to redirect to login page
                    header("Location: login.php");
                    exit();
                }
                ?>

                <a href="../logout.php" class="logout" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "wardrobe");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Count total number of products
        $sql_count = "SELECT COUNT(*) AS total_products FROM products";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_products = $row_count['total_products'];

        $sql = "SELECT COUNT(order_id) AS total_orders FROM orders";
        $result = $conn->query($sql);

        $total_orders = 0;
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_orders = $row['total_orders'];
    }
    
        $sql_customers = "SELECT COUNT(UserID) AS total_customers FROM user WHERE UserType='Customer'";
        $result_customers = $conn->query($sql_customers);

        $total_customers = 0;
        if ($result_customers->num_rows > 0) {
        $row_customers = $result_customers->fetch_assoc();
        $total_customers = $row_customers['total_customers'];
    }

        $sql_revenue = "SELECT SUM(total_amount) AS total_revenue FROM orders";
        $result_revenue = $conn->query($sql_revenue);

        $total_revenue = 0;
        if ($result_revenue->num_rows > 0) {
        $row_revenue = $result_revenue->fetch_assoc();
        $total_revenue = $row_revenue['total_revenue'];
        if ($total_revenue === null) {
            $total_revenue = 0;
        }
    }

        // Close the database connection
        $conn->close();
        ?>
        <div class="main-content" id="main-content">
            <h1>Dashboard</h1>
            <div class="cards">
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Products</h3>
                        <p><?php echo $total_products; ?></p>
                    </div>
                </div>




                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Orders</h3>
                        <p><?php echo $total_orders; ?></p>
                    </div>
                </div>


                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>New Customers</h3>
                        <p><?php echo $total_customers; ?></p>
                    </div>
                </div>


                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Revenue</h3>
                        <p>RS: <?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/script.js"></script>
</body>

</html>