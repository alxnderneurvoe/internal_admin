<?php
include('../config.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM products WHERE 1";

if ($search) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if ($category) {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

$sql .= " ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center text-primary">Product List</h2>

        <form method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" class="form-control w-25" name="search" placeholder="Search by product name"
                value="<?php echo htmlspecialchars($search); ?>">
            <select class="form-select w-25 ms-2" name="category" id="category-select" onchange="this.form.submit()">
                <option value="">Select Category</option>
            </select>
        </form>

        <a href="add_product.php" class="btn btn-success mb-3">
            <i class="fas fa-plus-circle"></i> Add Product
        </a>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col">
                        <div class="card border-light shadow-sm">
                            <img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/Logo.png') . '" class="card-img-top" alt="' . $row['name'] . '">
                            <div class="card-body">
                                <h6 class="card-title text-center" style="overflow: hidden; text-overflow: ellipsis;">' . $row['name'] . '</h6>
                                <p class="card-text text-center text-muted">Rp. ' . number_format($row['price'], 0, ',', '.') . '</p>
                                <p class="card-text text-center small">Satuan: ' . $row['unit'] . '</p>
                                <p class="card-text text-center small">Kategori: ' . $row['category'] . '</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="edit_product.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_product.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo "<p class='text-center'>No products available</p>";
            }
            ?>
        </div>
    </div>

    <script src="list.js"></script>
</body>
</html>

<?php $conn->close(); ?>
