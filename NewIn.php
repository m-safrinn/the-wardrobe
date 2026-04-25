<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Encode Sans Condensed' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <title>New Arrivals-The Wardrobe</title>
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
        $categoryName = "Women's New Arrivals";

        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            if ($categoryId == '2') {
                $categoryName = "Men's New Arrivals";
            }
        }

        $displayName = getCategoryName($conn, $categoryId);
        ?>

        <div class="catName">
            <h2><?php echo htmlspecialchars($categoryName); ?></h2>
        </div>

        <section class="section3" <?php if (isset($_POST['search'])) echo 'style="display:none;"'; ?>>
            <div class="new-arrivals-section">
                <?php
                $products = getProductsByCategory($conn, $categoryId);
                $newArrivalProducts = array_filter($products, function ($product) {
                    return $product['is_new_arrival'] == 1;
                });
                ?>
                <div class="products-container">
                    <?php
                    if (!empty($newArrivalProducts)) {
                        foreach ($newArrivalProducts as $product) {
                            echo renderProduct($product);
                        }
                    } else {
                        echo "<p>No New Arrivals Found.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </section>
    <section class="section4">
        <?php include 'Shared/footer.php'; ?>
    </section>

</body>

</html>