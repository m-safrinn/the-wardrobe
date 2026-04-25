<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

include 'connection.php';
include 'fetch_user.php';
include 'fetch_order.php';


if (isset($_SESSION['success'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['message'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="Shared/customer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <section class="navbar-custom">
        <div class="welcome">
            <a href="home.php"> Welcome <span><?php echo htmlspecialchars($user['Username']); ?></span>
            </a>
        </div>


        <div class="links">
            <a href="#account">Account</a> |
            <a href="#orders">Order History</a> |
            <a href="#logout" id="logout-link">Logout</a>

        </div>
    </section>

    <section class="container light-style flex-grow-1 container-p-y main-content">
        <div class="tab-content" id="myTabContent">
            <!-- Account Tab -->
            <div class="tab-pane fade show active" id="account" role="tabpanel">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-general" role="tab">Profile</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password" role="tab">Change password</a>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="tab-content">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="account-general" role="tabpanel">
                                <div class="card-body">
                                    <form action="update_customer.php" method="post">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control mb-1" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">E-mail</label>
                                            <input type="email" class="form-control mb-1" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['PhoneNumber']); ?>" required>
                                        </div>

                                        <div class="sub-button">
                                            <button type="submit" class="custom-btn">Save changes</button>
                                            <button type="button" class="subcustom-btn" onclick="window.location.href='customer.php'">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade" id="account-change-password" role="tabpanel">
                                <div class="card-body pb-2">
                                    <form action="update_password.php" method="post">
                                        <div class="form-group">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" class="form-control" name="current_password" placeholder="Enter your current password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="new_password" placeholder="Enter your new password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Repeat New Password</label>
                                            <input type="password" class="form-control" name="repeat_new_password" placeholder="Repeat your new password" required>
                                        </div>
                                        <div class="sub-button">
                                            <button type="submit" class="custom-btn">Change Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="orders" role="tabpanel">
                <div class="order-container">
                    <h1>My Orders</h1>
                    <?php if (!empty($orders)) : ?>
                        <div class="order-grid">
                            <?php foreach ($orders as $order) : ?>
                                <div class="order-card">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    <p><strong>Products:</strong> <?php echo htmlspecialchars($order['product_names']); ?></p>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                    <p><strong>Total Amount:</strong> Rs <?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>
                                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                                    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                                    <?php if ($order['status'] == 'Delivered') : ?>
                                        <form action="review.php" method="GET">
                                            <input type="hidden" name="product_ids" value="<?php echo htmlspecialchars($order['product_ids']); ?>">
                                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($order['email']); ?>">
                                            <button type="submit">Add a Review</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p>No orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="logout" role="tabpanel">
                <div class="logout-container">

                    <h3>Logout</h3>
                    <p>You are about to log out. Click the button below to confirm.</p>
                    <a href="logout.php"><button>Logout</button></a>
                </div>
            </div>
        </div>
    </section>
    <section class="section 4">
        <?php include 'Shared/footer.php'; ?>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.links a').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                $('.tab-pane').removeClass('show active');
                $(target).addClass('show active');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['message'])) : ?>
                alert("<?php echo $_SESSION['message']; ?>");
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>