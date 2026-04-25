<?php
include '../Connection.php';
$category_id = $_POST["category_id"];
$result = mysqli_query($conn, "SELECT * FROM categories where parent_id = $category_id");
?>
<option value="" disabled selected>Select Sub Category</option>
<?php
while ($row = mysqli_fetch_array($result)) {
?>
    <option value="<?php echo $row["category_id"]; ?>"><?php echo $row["category_name"]; ?></option>
<?php
}
?>