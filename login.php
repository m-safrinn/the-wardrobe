<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_type'])) {
    header("Location: home.php");
    exit();
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Shared/home.css">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <title>The Wardrobe</title>
    <link rel="shortcut icon" href="IMG/loggoo.png">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <script src="main.js"></script>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="cart-content">
            <div class="cart-logo">
                <img src="IMG/emptycart.png">
            </div>
            <p>Your cart is currently empty.</p>
            <a href="login.php" class="btn Cshopping-btn">Start Shopping</a>
        </div>
    </div>
    <div id="overlay" class="overlay"></div>

    <section class="section3" id="login-container">
        <div class="form" id="formLogin">
            <form action="user.php" method="post">
                <h2>Login</h2>
                <div class="form-text">
                    <span> If you have an account with us, please log in.</span>
                </div>
                <input type="text" placeholder="Username" name="username" required><br>
                <input type="password" placeholder="Password" name="password" required autocomplete="off"><br>
                <button type="submit" name="btnlogin" value="Login" class="btn">
                    <span>Login&nbsp;</span>
                    <i class="material-icons-round">login</i>

                </button>
                <div class="form-text">
                    <span>Don’t have an account? </span>
                    <a href="register.php"><u>Create an account</u></a>
                </div>
            </form>
        </div>
    </section>
    <?php include 'Shared/footer.php'; ?>
</body>

</html>