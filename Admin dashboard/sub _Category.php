<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Category</title>
    <link rel="stylesheet" href="assets/css/category-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<div class="category-form">
<h1>Sub Categories</h1>

        <form id="categoryForm" method="POST" action="category.php">

        <label for="subcategoryName">Sub Category Name:</label>
        <input type="text" id="subcategoryName" name="subcategoryName" required>
        <button type="submit">Add Category</button>
    </form>
    
  
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        <?php
          
            $conn = new mysqli("localhost", "root", "", "wardrobe");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<p>Form submitted</p>";
                $categoryName = $_POST['categoryName'];
            
                echo "<p>Received data - Category: $categoryName</p>";
            
                $sql = "INSERT INTO categories (category_name) VALUES ('$categoryName')";
            
                if ($conn->query($sql) === TRUE) {
                    echo "New category added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

          
            $sql = "SELECT category_id, category_name FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["category_id"] . "</td>
                        <td>" . $row["category_name"] . "</td>
                        <td><button>Edit</button> <button>Delete</button></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No categories found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>


    </table>
        </div>
    </div>
</body>
</html>

