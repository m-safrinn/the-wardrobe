<?php
session_start();
include '../Connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Products</title>
    <link rel="stylesheet" href="assets/css/product-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

    <div class="product-form">
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i>
    </a>
        <h2 id="addProductFormTitle">Add Product</h2>
        <form id="addProductForm" method="POST" action="product.php" enctype="multipart/form-data">
            <input type="hidden" id="productid" name="productid">
            <input type="hidden" id="action" name="action" value="">
            <input type="hidden" id="existing_image1" name="existing_image1">
            <input type="hidden" id="existing_image2" name="existing_image2">
            <input type="hidden" id="existing_image3" name="existing_image3">

            <label for="productname">Product Name:</label>
            <input type="text" id="productname" name="productname" required><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required><br>

            <label for="new_price">New Price:</label>
            <input type="number" id="new_price" name="new_price" step="0.01"><br>

            <label for="category">Category:</label>
            <select class="form-control" id="parent-dropdown" name="category" required>
                <option value="" disabled selected>Select Category</option>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM categories WHERE parent_id = 0");
                while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['category_id']; ?>"><?php echo $row["category_name"]; ?></option>
                <?php
                }
                ?>
            </select><br>

            <label for="subcategory">Sub Category:</label>
            <select class="form-control" id="sub-category-dropdown" name="subcategory" required>
                <option value="" disabled selected>Select Sub Category</option>
            </select><br>

            <label for="sizes">Sizes:</label>
            <div id="size-checkboxes">
            <?php
            $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
            foreach ($sizes as $size) {
            echo "<label><input type='checkbox' name='sizes[]' value='$size'> $size</label>";
         }
         ?>
         </div>
         <br>
            <label for="stockquantity">Stock Quantity:</label>
            <input type="number" id="stockquantity" name="stockquantity" required><br>
            <label for="image1">Image 1:</label>
            <input type="file" id="image1" name="image1" accept="image/*"><br>

            <label for="image2">Image 2:</label>
            <input type="file" id="image2" name="image2" accept="image/*"><br>

            <label for="image3">Image 3:</label>
            <input type="file" id="image3" name="image3" accept="image/*"><br>

            <label for="is_new_arrival">New Arrival:</label>
            <input type="checkbox" id="is_new_arrival" name="is_new_arrival"><br>

            <label for="on_sale">On Sale:</label>
            <input type="checkbox" id="on_sale" name="on_sale"><br>

            <input type="submit" id="addProductButton" value="Add Product">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#parent-dropdown').on('change', function() {
                var category_id = this.value;
                $.ajax({
                    url: "get-subcategories.php",
                    type: "POST",
                    data: {
                        category_id: category_id
                    },
                    cache: false,
                    success: function(result) {
                        $("#sub-category-dropdown").html(result);
                    }
                });
            });
        });
    </script>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>New Price</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Sizes</th>
                <th>Stock</th>
                <th>Image 1</th>
                <th>Image 2</th>
                <th>Image 3</th>
                <th>New Arrival</th>
                <th>On Sale</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            function sanitizeInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            function uploadImage($file)
            {
                if ($file["error"] == UPLOAD_ERR_NO_FILE) {
                    return null;
                }

                $targetDir = __DIR__ . '/../IMG/products/';

                // Ensure the directory exists, if not, create it
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                //unique file name
                $uniqueName = uniqid() . '-' . basename($file["name"]);
                $targetFile = $targetDir . $uniqueName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if image file is a valid image
                $check = getimagesize($file["tmp_name"]);
                if ($check === false) {
                    return null;
                }

                // Check if file size (limit to 5MB)
                if ($file["size"] > 5000000) {
                    return null;
                }


                $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($imageFileType, $allowedFormats)) {
                    return null;
                }


                if (move_uploaded_file($file["tmp_name"], $targetFile)) {

                    return "IMG/products/" . $uniqueName;
                } else {
                    return null;
                }
            }



            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST["productname"], $_POST["price"], $_POST["category"], $_POST["subcategory"], $_POST["sizes"], $_POST["stockquantity"])) {

                    $productname = sanitizeInput($_POST["productname"]);
                    $price = sanitizeInput($_POST["price"]);
                    $new_price = isset($_POST["new_price"]) ? sanitizeInput($_POST["new_price"]) : null; // Handle optional new price
                    $category_id = sanitizeInput($_POST["category"]);
                    $subcategory_id = sanitizeInput($_POST["subcategory"]);
                    $stockquantity = sanitizeInput($_POST["stockquantity"]);

                    $is_new_arrival = isset($_POST["is_new_arrival"]) ? 1 : 0;
                    $on_sale = isset($_POST["on_sale"]) ? 1 : 0;


                    $existing_image1 = isset($_POST['existing_image1']) ? sanitizeInput($_POST['existing_image1']) : null;
                    $existing_image2 = isset($_POST['existing_image2']) ? sanitizeInput($_POST['existing_image2']) : null;
                    $existing_image3 = isset($_POST['existing_image3']) ? sanitizeInput($_POST['existing_image3']) : null;


                    $image_url1 = $existing_image1;
                    $image_url2 = $existing_image2;
                    $image_url3 = $existing_image3;

                    $selectedSizes = isset($_POST['sizes']) ? $_POST['sizes'] : [];
                    $size = implode(',', $selectedSizes);

                    if (isset($_FILES["image1"]) && $_FILES["image1"]["error"] !== UPLOAD_ERR_NO_FILE) {
                        $new_image_url1 = uploadImage($_FILES["image1"]);
                        if ($new_image_url1 === null) {
                            echo "<div class='alert error'>Error: Failed to upload image 1 or invalid file format.</div>";
                            exit;
                        }

                        $image_url1 = $new_image_url1;
                    }

                    if (isset($_FILES["image2"]) && $_FILES["image2"]["error"] !== UPLOAD_ERR_NO_FILE) {
                        $new_image_url2 = uploadImage($_FILES["image2"]);
                        if ($new_image_url2 === null) {
                            echo "<div class='alert error'>Error: Failed to upload image 2 or invalid file format.</div>";
                            exit;
                        }

                        $image_url2 = $new_image_url2;
                    }

                    if (isset($_FILES["image3"]) && $_FILES["image3"]["error"] !== UPLOAD_ERR_NO_FILE) {
                        $new_image_url3 = uploadImage($_FILES["image3"]);
                        if ($new_image_url3 === null) {
                            echo "<div class='alert error'>Error: Failed to upload image 3 or invalid file format.</div>";
                            exit;
                        }

                        $image_url3 = $new_image_url3;
                    }
                    $productid = isset($_POST['productid']) ? sanitizeInput($_POST['productid']) : null;
                    if ($productid) {

                        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, new_price=?, category_id=?, subcategory_id=?, stock_quantity=?, sizes=?, image_url=?, image_url2=?, image_url3=?, is_new_arrival=?, on_sale=? WHERE product_id=?");
                        $stmt->bind_param("sddiiissssiii", $productname, $price, $new_price, $category_id, $subcategory_id, $stockquantity, $size, $image_url1, $image_url2, $image_url3, $is_new_arrival, $on_sale, $productid);
                    } else {

                        $stmt = $conn->prepare("INSERT INTO products (product_name, price, new_price, category_id, subcategory_id, stock_quantity, sizes, image_url, image_url2, image_url3, is_new_arrival, on_sale) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sddiiissssii", $productname, $price, $new_price, $category_id, $subcategory_id, $stockquantity, $size, $image_url1, $image_url2, $image_url3, $is_new_arrival, $on_sale);
                    }

                    if ($stmt->execute()) {
                        echo "<div class='alert success'>Product " . ($productid ? "updated" : "added") . " successfully</div>";
                    } else {
                        echo "<div class='alert error'>Error: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                } else {
                    echo "<div class='alert error'>Error: Required fields are not set.</div>";
                }
            }

            //delete product
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"]) && $_GET["action"] === "delete") {
                if (isset($_GET["productid"])) {
                    $productid = sanitizeInput($_GET["productid"]);

                    $sql = "DELETE FROM products WHERE product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $productid);

                    if ($stmt->execute()) {
                        echo "<div class='alert success'>Product deleted successfully</div>";
                    } else {
                        echo "<div class='alert error'>Error deleting product: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                } else {
                    echo "<div class='alert error'>Error: Product ID not provided.</div>";
                }
            }

            $sql = "
                SELECT 
                    p.product_id, 
                    p.product_name, 
                    p.price,
                    P.new_price,
                    c1.category_name AS category_name, 
                    c2.category_name AS subcategory_name, 
                    P.sizes,
                    p.stock_quantity, 
                    p.image_url,
                    p.image_url2,
                    p.image_url3,
                    P.is_new_arrival,
                    P.on_sale
                FROM 
                    products p
                LEFT JOIN 
                    categories c1 ON p.category_id = c1.category_id
                LEFT JOIN 
                    categories c2 ON p.subcategory_id = c2.category_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>" . htmlspecialchars($row["product_id"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["product_name"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["price"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["new_price"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["category_name"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["subcategory_name"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["sizes"] ?? "") . "</td>
                    <td>" . htmlspecialchars($row["stock_quantity"] ?? "") . "</td>
                    <td>";
                    if (!empty($row["image_url"])) {
                        echo "<img src='../" . htmlspecialchars($row["image_url"]) . "' alt='Product Image 1' style='max-width: 100px; max-height: 100px;'>";
                    }
                    echo "</td>
                    <td>";
                    if (!empty($row["image_url2"])) {
                        echo "<img src='../" . htmlspecialchars($row["image_url2"]) . "' alt='Product Image 2' style='max-width: 100px; max-height: 100px;'>";
                    }
                    echo "</td>
                    <td>";
                    if (!empty($row["image_url3"])) {
                        echo "<img src='../" . htmlspecialchars($row["image_url3"]) . "' alt='Product Image 3' style='max-width: 100px; max-height: 100px;'>";
                    }
                    echo "</td>
                    <td>" . ($row["is_new_arrival"] ? 'Yes' : 'No') . "</td>
                    <td>" . ($row["on_sale"] ? 'Yes' : 'No') . "</td>
                    <td> 
                        <button onclick='editProduct(" . json_encode($row) . ")'>Edit</button>
                        <a href='javascript:void(0);' onclick='confirmDelete(" . $row['product_id'] . ")'><button>Delete</button></a>
                    </td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No products found.</td></tr>";
            }
            $conn->close();
            ?>

        </tbody>
    </table>

    <script>
        function confirmDelete(productid) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = 'product.php?action=delete&productid=' + encodeURIComponent(productid);
            }
        }

        function editProduct(product) {
            document.getElementById('productid').value = product.product_id;
            document.getElementById('productname').value = product.product_name;
            document.getElementById('price').value = product.price;
            document.getElementById('new_price').value = product.new_price;
            document.getElementById('category').value = product.category_name;
            document.getElementById('subcategory').value = product.subcategory_name;
            document.getElementById('sizes').value = product.sizes;
            document.getElementById('stockquantity').value = product.stock_quantity;
            document.getElementById('is_new_arrival').checked = product.is_new_arrival;
            document.getElementById('on_sale').checked = product.on_sale;
            document.getElementById('action').value = 'edit';
            document.getElementById('existing_image1').value = product.image_url;
            document.getElementById('existing_image2').value = product.image_url2;
            document.getElementById('existing_image3').value = product.image_url3;
            document.getElementById('addProductFormTitle').innerText = "Edit Product";
            document.getElementById('addProductButton').value = "Update Product";
        }


        document.querySelector('#products-link').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Function to load page content via AJAX
    function loadPage(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                document.querySelector('#content').innerHTML = xhr.responseText;
            } else {
                console.error('Failed to load page:', xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request error');
        };
        xhr.send();
    }

    // Call the loadPage function with the URL of the page to load
    loadPage('product.php');
});

    </script>

</body>

</html>