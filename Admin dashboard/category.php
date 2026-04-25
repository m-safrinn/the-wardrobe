<?php
session_start();
include '../Connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Category</title>
    <link rel="stylesheet" href="assets/css/category-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>

<body>

    <div class="category-form">
    <a href="index.php" class="back-link" style="float: right;">
    <i class="fas fa-arrow-left"></i>
</a>
        <h1>Categories</h1>
        <form id="categoryForm" method="POST" action="category.php">
            <select name="category_id" id="parent-dropdown" required>
                <option value="" selected disabled>Select a Parent Category</option>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM categories WHERE parent_id = 0");
                while ($row = mysqli_fetch_array($result)) {
                    echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                }
                ?>
            </select>
            <label for="categoryName">Category Name:</label>
            <input type="text" id="categoryName" name="categoryName" required>
            <button type="submit">Add Category</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p>Form submitted</p>";
            $parent_id = $_POST["category_id"];
            $categoryName = $_POST['categoryName'];
            echo "<p>Received data - Parent: $parent_id</p>";
            echo "<p>Received data - Category Name: $categoryName</p>";
            $stmt = $conn->prepare("INSERT INTO categories (parent_id, category_name) VALUES (?, ?)");
            $stmt->bind_param("is", $parent_id, $categoryName);

            if ($stmt->execute()) {
                echo "New category added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }


        function fetchCategories($conn, $parent_id = 0, $level = 0)
        {
            $sql = "SELECT category_id, category_name FROM categories WHERE parent_id = $parent_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . str_repeat('&nbsp;', $level * 8) . $row["category_id"] . "</td>";
                    if ($level == 0) {

                        echo "<td><strong>" . $row["category_name"] . "</strong></td>";
                    } else {
                        echo "<td>" . str_repeat('&nbsp;', $level * 8) . $row["category_name"] . "</td>";
                    }

                    if ($level > 0) {
                        echo "<td><form method='post' action='delete_category.php' onsubmit='return confirm(\"Are you sure you want to delete this category?\");'>
                                    <input type='hidden' name='category_id' value='" . $row["category_id"] . "' />
                                    <button type='submit'>Delete</button>
                                </form></td>";
                    } else {
                        echo "<td></td>";
                    }
                    echo "</tr>";

                    fetchCategories($conn, $row["category_id"], $level + 1);
                }
            }
        }

        echo "<table border='1'>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>";
        fetchCategories($conn);
        echo "</table>";

        $conn->close();
        ?>
    </div>
    </div>
</body>

</html>
