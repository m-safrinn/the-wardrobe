<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <title>The Wardrobe</title>
    <link rel="shortcut icon" href="IMG/loggoo.png">
    <link rel="stylesheet" href="Shared/home.css">
    <link rel="stylesheet" href="Shared/collection.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <section class="searchresult" id="search">
        <div class="products">
            <?php
            include_once 'productModel.php';
            if (isset($_POST['search'])) {
                include 'connection.php';
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $search = $_POST['search'];
                echo '<script>window.onload = function() { SearchActive(); }</script>';
                $searchResults = getSearchResults($conn, $search);
                echo renderSearchResults($searchResults, $search);
            }
            ?>
        </div>
    </section>
    <section class="section3" id="productsSection" <?php if (isset($_POST['search'])) echo 'style="display:none;"'; ?>>
        <div class="new-arrivals-section" id="new_arrivals">
            <?php
            include_once 'connection.php';
            include_once 'productModel.php';
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if (isset($_GET['id'])) {
                $subcategoryid = $_GET['id'];
                $categoryName = getCategoryName($conn, $subcategoryid);
                echo '<div class="catName"><h2>' . $categoryName . '</h2></div>';
            } else {
                echo "<p>No category ID specified.</p>";
            }
            ?>
            <div class="products-container">
                <div class="products">
                    <?php
                    if (isset($_GET['id'])) {
                        $subcategoryid = $_GET['id'];
                        $products = getProductsByCategory($conn, $subcategoryid);
                        echo renderProducts($products);
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section class="section 4">
        <?php include 'Shared/footer.php'; ?>
    </section>
</body>

</html>