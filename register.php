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

    <section class="section3" id="reg-container">
        <div class="form" id="formReg">
            <form method="post" action="user.php">
                <h2>Create an account</h2>
                <div class="form-text">
                    <span> Enter your information below to proceed. If you already have an account, please log in instead.</span>
                </div>

                <input type="text" placeholder="Username" name='username' required autocomplete="off"><br>
                <input type="email" placeholder="Email" name='email' required><br>
                <input type="text" placeholder="Phone Number" name='phone' required autocomplete="off"><br>
                <input type="hidden" name="type" value="Customer">
                <input type="password" placeholder="Password" name="password" required autocomplete="off" minlength="8"></br>
                <input type="password" placeholder="Confirm Password" name='confirm' required autocomplete="off" minlength="8"><br>
                <button type="submit" name="btnreg" value="Register" class="btn">
                    <span>Register&nbsp;</span>
                    <i class="material-icons-round">how_to_reg</i>
                </button><br>
                <div class="form-text">
                    <span>Already have an account?</span>
                    <a href="login.php"><u>login</u></a>
                </div>
            </form>
        </div>
    </section>


    <footer class="footer">
        <div class="brand">
            <img src="IMG/loggoo.jpg" alt="The Wardrobe" width="200px">
            <p>Discover your signature style at The Wardrobe, where fashion meets convenience. Browse our curated collection of trendy apparel and timeless classics, ensuring you'll find the perfect ensemble for any occasion</p>
        </div>
        <div class="info">
            <h3>INFO</h3>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>

            </ul>
        </div>
        <div class="socials">
            <h3>SOCIALS</h3>
            <ul>
                <li><img src="IMG/facebook.png"> <a href="https://www.facebook.com/people/The-Wardrobe/61560890816353/">Facebook</a></li>
                <li><img src="IMG/insta.png"><a href="https://www.instagram.com/zack.jose01?igsh=dGdhNjhza2I3MGx4&utm_source=qr">Instagram</a></li>
                <li><img src="IMG/pinterest.png"><a href="https://www.pinterest.com/TheOfficialWardrobe/">Pinterest</a></li>

            </ul>
        </div>

    </footer>
    <div class="footer-bottom">
        <p>© 2024 The Wardrobe Sri Lanka.</p>
    </div>
</body>

</html>