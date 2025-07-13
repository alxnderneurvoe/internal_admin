<?php
include('../config.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM `products` WHERE 1";

if (!empty($search)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($category)) {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

$sql .= " ORDER BY products.name ASC";

$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
</head>
<body>
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="../dashboard.php">
            <img src="../asset/Logo.png" alt="" style="width: auto; height: 30px;">
        </a>
        <a class="navbar-brand" href="../dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i> PT Semesta Sistem Solusindo
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../create_letter.php"><i class="fas fa-fw fa-file-invoice"></i> Create Letter</a></li>
            <li class="nav-item"><a class="nav-link" href="../files.php"><i class="fas fa-fw fa-folder"></i> File Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="../product/products.php"><i class="fas fa-fw fa-folder"></i> Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-fw fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Product List</h2>
        <form method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" class="form-control w-25" name="search" placeholder="Search by product name" value="<?php echo htmlspecialchars($search); ?>">
            <select class="form-select w-25 ms-2" name="category" id="category-select" onchange="this.form.submit()">
                <option value="">Select Category</option>
            </select>
        </form>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-outline-primary" id="toggleViewBtn">Switch to Table View</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus-circle"></i> Add Product</button>
        </div>

        <!-- Grid View -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="gridView">
            <?php
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                $platformButtons = [];
                if ($row['tokopedia_link']) $platformButtons[] = '<a href="' . $row['tokopedia_link'] . '" target="_blank" class="btn btn-tokped btn-sm">Tokopedia</a>';
                if ($row['shopee_link']) $platformButtons[] = '<a href="' . $row['shopee_link'] . '" target="_blank" class="btn btn-shopee btn-sm">Shopee</a>';
                if ($row['inaproc_link']) $platformButtons[] = '<a href="' . $row['inaproc_link'] . '" target="_blank" class="btn btn-inaproc btn-sm">Inaproc</a>';
                if ($row['siplah_link']) $platformButtons[] = '<a href="' . $row['siplah_link'] . '" target="_blank" class="btn btn-siplah btn-sm">Siplah</a>';
                if ($row['blibli_link']) $platformButtons[] = '<a href="' . $row['blibli_link'] . '" target="_blank" class="btn btn-padi btn-sm">Padi</a>';
                $platformLinks = implode(' ', $platformButtons);
                echo '<div class="col">
                        <div class="card border-light shadow-sm" style="height: 510px;">
                            <img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/no-image.png') . '" class="card-img-top" alt="' . $row['name'] . '" style="height: auto; width: 90%;">
                            <div class="card-body">
                                <h6 class="card-title text-center" style="overflow: hidden;">' . $row['name'] . '</h6>
                                <p class="card-text text-center text-muted">Rp. ' . number_format($row['price'], 0, ',', '.') . '</p>
                                <p class="card-text text-center small">Satuan : ' . $row['unit'] . '</p>
                                <p class="card-text text-center small">Kategori : ' . $row['category'] . '</p>
                                <div class="text-center">' . $platformLinks . '</div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="edit_products.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="view_products.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">View</a>
                                <a href="delete_products.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                            </div>
                        </div>
                      </div>';
            }
            ?>
        </div>

        <!-- Table View -->
        <div id="tableView" style="display: none;">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Platforms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/no-image.png') . '" style="max-height: 50px;"></td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>Rp. ' . number_format($row['price'], 0, ',', '.') . '</td>';
                            echo '<td>' . $row['unit'] . '</td>';
                            echo '<td>' . $row['category'] . '</td>';

                            $links = [];
                            if ($row['tokopedia_link']) $links[] = '<a href="' . $row['tokopedia_link'] . '" target="_blank" class="btn btn-tokped btn-sm">Tokopedia</a>';
                            if ($row['shopee_link']) $links[] = '<a href="' . $row['shopee_link'] . '" target="_blank" class="btn btn-shopee btn-sm">Shopee</a>';
                            if ($row['inaproc_link']) $links[] = '<a href="' . $row['inaproc_link'] . '" target="_blank" class="btn btn-inaproc btn-sm">Inaproc</a>';
                            if ($row['siplah_link']) $links[] = '<a href="' . $row['siplah_link'] . '" target="_blank" class="btn btn-siplah btn-sm">Siplah</a>';
                            if ($row['blibli_link']) $links[] = '<a href="' . $row['blibli_link'] . '" target="_blank" class="btn btn-padi btn-sm">Padi</a>';
                            echo '<td>' . implode(' ', $links) . '</td>';

                            echo '<td>
                                <a href="edit_products.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="view_products.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">View</a>
                                <a href="delete_products.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                            </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No products available</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById('toggleViewBtn');
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            let isGrid = true;
            toggleBtn.addEventListener('click', () => {
                isGrid = !isGrid;
                gridView.style.display = isGrid ? 'flex' : 'none';
                tableView.style.display = isGrid ? 'none' : 'block';
                toggleBtn.textContent = isGrid ? 'Switch to Table View' : 'Switch to Grid View';
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>