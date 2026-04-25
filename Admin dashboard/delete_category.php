<?php
include '../Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    // Check if the category exists
    $sql = "SELECT category_id FROM categories WHERE category_id = $category_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Delete the category
        $delete_sql = "DELETE FROM categories WHERE category_id = $category_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Category deleted successfully.";
        } else {
            echo "Error deleting category: " . $conn->error;
        }
    } else {
        echo "Category not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();

// Redirect back to the categories page
header("Location: category.php");
exit();
