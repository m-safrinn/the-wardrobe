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
    <link rel="stylesheet" href="Shared/sales-new.css">
</head>

<body>
    <header>
        <?php include 'Shared/navbar.php'; ?>
    </header>
    <section class="ContentSection">



        <?php
        include_once 'connection.php';
        include_once 'productModel.php';

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $categoryId = '1'; // Default to Women's category
        $categoryName = "Women's Sale Products";

        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            if ($categoryId == '2') {
                $categoryName = "Men's Sale Products";
            }
        }

        $displayName = getCategoryName($conn, $categoryId);
        ?>

        <div class="catName">
            <h2><?php echo htmlspecialchars($categoryName); ?></h2>
        </div>

        <section class="section3" id="saleSection" <?php if (isset($_POST['search'])) echo 'style="display:none;"'; ?>>
            <div class="new-arrivals-section" id="new_arrivals">
                <?php
                $products = getProductsByCategory($conn, $categoryId);
                $saleProducts = array_filter($products, function ($product) {
                    return $product['on_sale'] == 1;
                });
                ?>
                <div class="products-container">
                    <?php
                    if (!empty($saleProducts)) {
                        foreach ($saleProducts as $product) {
                            echo renderProduct($product);
                        }
                    } else {
                        echo "<p>No On-Sale Products Found.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="section4">
            <?php include 'Shared/footer.php'; ?>
        </section>
    </section>
</body>

</html>