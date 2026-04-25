<?php
include 'connection.php';

function getCategoryName($conn, $subcategoryid)
{
    $categoryName = "Collection";

    if (!is_numeric($subcategoryid)) {
        die("Invalid category ID.");
    }

    $sql = "SELECT c.category_name AS subcategory_name, p.category_name AS parent_name
            FROM categories c
            LEFT JOIN categories p ON c.parent_id = p.category_id
            WHERE c.category_id = '$subcategoryid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $subcategoryName = $row['subcategory_name'];
        $parentName = $row['parent_name'];

        if (!empty($parentName)) {
            $categoryName = "$parentName - $subcategoryName";
        } else {
            $categoryName = $subcategoryName;
        }
    }

    return $categoryName;
}

function getProductsByCategory($conn, $subcategoryid)
{
    $products = [];

    if (!is_numeric($subcategoryid)) {
        return $products;
    }

    $sql = "SELECT * FROM products WHERE category_id = '$subcategoryid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        $sql = "SELECT * FROM products WHERE subcategory_id = '$subcategoryid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
    }

    return $products;
}

function getNewArrivals($conn, $categoryId)
{
    $newArrivals = [];

    $sql = "SELECT * FROM products WHERE category_id = '$categoryId' AND is_new_arrival = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $newArrivals[] = $row;
        }
    }

    return $newArrivals;
}

function renderProduct($row)
{
    $outOfStockClass = ($row['stock_quantity'] <= 0) ? 'out-of-stock' : '';
    $pointerEventsStyle = ($row['stock_quantity'] <= 0) ? 'style="pointer-events: none;"' : '';

    $html = '
    <a href="product.php?product_id=' . $row['product_id'] . '" class="product-link" ' . $pointerEventsStyle . '> 
        <div class="grid ' . $outOfStockClass . '">
            <div class="grid-art">
                <img src="' . $row['image_url'] . '" class="image1"/>
                <img src="' . $row['image_url2'] . '" class="image2"/>
            </div>';

    $html .= '<h3>' . $row['product_name'] . '</h3>';

    if ($row['on_sale'] && $row['new_price'] < $row['price']) {
        $html .= '
            <h3>
            <s style="font-weight: normal;">Rs.' . $row['price'] . '</s><br>
            Rs.' . $row['new_price'] . '</h3>';
    } else {

        $html .= '
            <h3>
            Rs.' . $row['price'] . '<br><br></h3>';
    }

    // Sale tag
    if ($row['on_sale']) {
        $html .= '<div class="sale-tag">On Sale!</div>';
    }

    // Out of stock tag
    if ($row['stock_quantity'] <= 0) {
        $html .= '<div class="out-of-stock-tag">Out of Stock</div>';
    }

    $html .= '</div>
    </a>';

    return $html;
}

function renderProducts($products)
{
    $html = '<div class="products-row">';
    $entryCount = 0;

    foreach ($products as $row) {
        if ($entryCount > 0 && $entryCount % 4 == 0) {
            $html .= '</div><div class="products-row">';
        }
        $html .= renderProduct($row);
        $entryCount++;
    }

    $html .= '</div>';
    if ($entryCount === 0) {
        $html = "<p>No products found for this category.</p>";
    }

    return $html;
}

function renderNewArrivals($newArrivals)
{
    $html = '';

    if (!empty($newArrivals)) {
        $entryCount = 0;
        foreach ($newArrivals as $row) {
            $html .= renderProduct($row);
            $entryCount++;
            if ($entryCount >= 4) {
                break;
            }
        }
    } else {
        $html = "<p>No new arrivals found for this category.</p>";
    }

    return $html;
}

function getSearchResults($conn, $search)
{
    $searchResults = [];

    $search = $conn->real_escape_string($search);
    $sql = "
        SELECT p.*
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN categories sc ON p.subcategory_id = sc.category_id
        WHERE c.category_name = '$search'
        OR sc.category_name LIKE '%$search%'
        OR p.product_name LIKE '%$search%'
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    return $searchResults;
}

function renderSearchResults($searchResults, $search)
{
    $html = '<div class="catName"><h2>Search Result for: ' . htmlspecialchars($search) . '</h2></div>';

    if (!empty($searchResults)) {
        foreach ($searchResults as $row) {
            $html .= renderProduct($row);
        }
    } else {
        $html .= '<p>No products found under this category or matching the search term</p>';
    }

    return $html;
}
