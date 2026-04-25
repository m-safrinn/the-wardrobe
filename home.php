<?php
session_start();
if (isset($_SESSION['login_success_message'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['login_success_message'] . "');</script>";
    unset($_SESSION['login_success_message']);
}

if (isset($_SESSION['logout'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['logout'] . "');</script>";
    unset($_SESSION['logout']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="shortcut icon" href="IMG/log.jpg">
    <title>The Wardrobe</title>
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/home.css">
    <link rel="stylesheet" href="Shared/slideshow.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>

    <!-- Slideshow -->
    <div class="custom-slideshow-container" id="slideshow">
        <div class="custom-mySlides custom-fade">
            <img src="IMG/slide1.jpeg" alt="Slide 1">
        </div>
        <div class="custom-mySlides custom-fade">
            <img src="IMG\slide2.jpeg" alt="Slide 2">
        </div>
        <div class="custom-mySlides custom-fade">
            <img src="IMG\slide3.jpeg" alt="Slide 3">
        </div>
        <div class="custom-mySlides custom-fade">
            <img src="IMG\slide4.jpeg" alt="Slide 3">
        </div>
    </div>
    <br>
    <div class="custom-dot-container">
        <span class="custom-dot" onclick="currentSlide(1)"></span>
        <span class="custom-dot" onclick="currentSlide(2)"></span>
        <span class="custom-dot" onclick="currentSlide(3)"></span>
        <span class="custom-dot" onclick="currentSlide(4)"></span>
    </div>
    <script src="Shared/slide.js"></script>

    <!-- New Arrivals -->
    <section class="section3" id="productsSection">
        <div class="new-arrivals-section" id="new_arrivals">
            <h2>Women's New Arrivals</h2>
            <div class="products-container">
                <div class="products">
                    <?php
                    include 'productModel.php';
                    include 'connection.php';
                    $womenNewArrivals = getNewArrivals($conn, 1);
                    echo renderNewArrivals($womenNewArrivals);
                    ?>
                </div>
            </div>

            <h2>Men's New Arrivals</h2>
            <div class="products-container">
                <div class="products">
                    <?php
                    $menNewArrivals = getNewArrivals($conn, 2);
                    echo renderNewArrivals($menNewArrivals);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- shop by category -->
    <div class="categories">
        <h2>Shop By Category</h2>
        <div class="category-item large-vertical">
            <img src="IMG/models/m0.jpg" alt="Women">
            <div class="category-info">
                <p>WOMEN</p>
                <a href="collection.php?id=1" class="btn">SHOP NOW</a>
            </div>
        </div>
        <div class="category-item large-vertical">
            <img src="IMG/models/m9.jpg" alt="Men">
            <div class="category-info">
                <p>MEN</p>
                <a href="collection.php?id=2" class="btn">SHOP NOW</a>
            </div>
        </div>
        <div class="category-item small">
            <img src="IMG/models/m8.jpg" alt="Tops">
            <div class="category-info">
                <p>Tops</p>
                <a href="collection.php?id=4" class="btn">SHOP NOW</a>
            </div>
        </div>
        <div class="category-item small">
            <img src="IMG/models/m3.jpg" alt="Dresses">
            <div class="category-info">
                <p>DRESSES</p>
                <a href="collection.php?id=3" class="btn">SHOP NOW</a>
            </div>
        </div>
        <div class="category-item medium-horizontal">
            <img src="IMG/models/m5.jpg" alt="Casual">
            <div class="category-info">
                <p>CASUAL</p>
                <a href="collection.php?id=10" class="btn">SHOP NOW</a>
            </div>
        </div>
        <div class="category-item horizontal">
            <img src="IMG/models/m6.jpg" alt="Formal">
            <div class="category-info">
                <p>FORMAL</p>
                <a href="collection.php?id=11" class="btn">SHOP NOW</a>
            </div>
        </div>
    </div>
    <section class="section 4">
        <?php include 'Shared/footer.php'; ?>
    </section>

</body>

</html>