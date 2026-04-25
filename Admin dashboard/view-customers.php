<?php
session_start();
include '../Connection.php';

// Default values for sorting, searching, filtering, and pagination
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'UserID';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$userType = isset($_GET['userType']) ? $_GET['userType'] : 'Customer';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ensure sorting column is safe
$allowedSortColumns = ['UserID', 'Username', 'Email', 'PhoneNumber'];
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'UserID'; // Default sort
}

// Prepare the query with placeholders
$query = "SELECT UserID, Username, Email, PhoneNumber FROM user 
          WHERE UserType = ? AND (Username LIKE ? OR Email LIKE ?) 
          ORDER BY $sort LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$searchTerm = "%$search%";

// Use variables for limit and offset
$limitParam = $limit;
$offsetParam = $offset;

$stmt->bind_param('sssii', $userType, $searchTerm, $searchTerm, $limitParam, $offsetParam);
$stmt->execute();
$result = $stmt->get_result();

// Prepare total count query
$totalQuery = "SELECT COUNT(*) as total FROM user 
                WHERE UserType = ? AND (Username LIKE ? OR Email LIKE ?)";

$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('sss', $userType, $searchTerm, $searchTerm);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

if (isset($_GET['search']) || isset($_GET['userType']) || isset($_GET['page'])) {
    // AJAX request, only return the search results
    ?>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['UserID']; ?></td>
                <td><?php echo $row['Username']; ?></td>
                <td><?php echo $row['Email']; ?></td>
                <td><?php echo $row['PhoneNumber']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if ($totalPages > 1) { ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="#" class="page-link <?php echo $i == $page ? 'active' : ''; ?>" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php } ?>
        </div>
    <?php } ?>
    <?php
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Customers - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/view-customers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Customer List</h1>

        <form id="search-form">
            <input type="text" id="search-input" name="search" placeholder="Search customers..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <form id="filter-form">
            <select name="userType">
                <option value="Customer" <?php echo $userType == 'Customer' ? 'selected' : ''; ?>>Customer</option>
                <option value="Admin" <?php echo $userType == 'Admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <div id="customer-list">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table content will be dynamically loaded here -->
                </tbody>
            </table>
            <div class="pagination">
                <!-- Pagination links will be dynamically loaded here -->
            </div>
        </div>

        <script>
            $(document).ready(function() {
                function loadCustomerList(page = 1) {
                    var search = $('#search-input').val();
                    var userType = $('select[name="userType"]').val();
                    
                    $.ajax({
                        url: '<?php echo $_SERVER['PHP_SELF'];?>',
                        method: 'GET',
                        data: { search: search, userType: userType, page: page },
                        success: function(html) {
                            $('#customer-list').html(html);
                        }
                    });
                }

                $('#search-form').on('submit', function(e) {
                    e.preventDefault();
                    loadCustomerList();
                });

                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                    loadCustomerList();
                });

                $('#customer-list').on('click', '.page-link', function(e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    loadCustomerList(page);
                });

                // Initial load
                loadCustomerList();
            });
        </script>
    </div>
</body>
</html>
