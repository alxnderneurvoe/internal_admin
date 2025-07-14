<?php
include('../config.php');

$sql = "SELECT * FROM products";
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM products WHERE 1 ORDER BY products.name ASC";

if ($search) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if ($category) {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

$result = $conn->query($sql);
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
    <script src="list.js"></script>
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
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../files.php">
                    <i class="fas fa-fw fa-folder"></i> File Storage
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-fw fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Product List</h2>
        <form method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" class="form-control w-25" name="search" placeholder="Search by product name"
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <select class="form-select w-25 ms-2" name="category" id="category-select" onchange="this.form.submit()">
                <option value="">Select Category</option>
            </select>
        </form>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus-circle"></i> Add Product
        </button>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $platformLinks = '';
                    $platformButtons = [];

                    if ($row['tokopedia_link'])
                        $platformButtons[] = '<a href="' . $row['tokopedia_link'] . '" target="_blank" class="btn btn-tokped btn-sm">Tokopedia</a>';
                    if ($row['shopee_link'])
                        $platformButtons[] = '<a href="' . $row['shopee_link'] . '" target="_blank" class="btn btn-shopee btn-sm">Shopee</a>';
                    if ($row['inaproc_link'])
                        $platformButtons[] = '<a href="' . $row['inaproc_link'] . '" target="_blank" class="btn btn-inaproc btn-sm">Inaproc</a>';
                    if ($row['siplah_link'])
                        $platformButtons[] = '<a href="' . $row['siplah_link'] . '" target="_blank" class="btn btn-siplah btn-sm">Siplah</a>';
                    if ($row['blibli_link'])
                        $platformButtons[] = '<a href="' . $row['blibli_link'] . '" target="_blank" class="btn btn-padi btn-sm">Padi</a>';

                    if (!empty($platformButtons)) {
                        $firstRowLinks = array_slice($platformButtons, 0, 2); // first two buttons
                        $secondRowLinks = array_slice($platformButtons, 2, 3); // next three buttons
            
                        $platformLinks = '<div class="d-flex justify-content-center gap-2 mt-2">';
                        foreach ($firstRowLinks as $button) {
                            $platformLinks .= $button;
                        }
                        $platformLinks .= '</div>';

                        $platformLinks .= '<div class="d-flex justify-content-center gap-2 mt-2">';
                        foreach ($secondRowLinks as $button) {
                            $platformLinks .= $button;
                        }
                        $platformLinks .= '</div>';
                    }

                    echo '
            <div class="col">
                <div class="card border-light shadow-sm">
                <img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/no-image.png') . '" class="card-img-top" alt="' . $row['name'] . '" style="height: auto; width: 90%;">
                    <div class="card-body">
                        <h6 class="card-title text-center" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; height: 3.5em;">
                            ' . $row['name'] . '
                        </h6>
                        <p class="card-text text-center text-muted" style="height: 1em";>Rp. ' . number_format($row['price'], 0, ',', '.') . '</p>
                        <p class="card-text text-center small" style="height: 0.5em";>Satuan : ' . $row['unit'] . '</p>
                        <p class="card-text text-center small">Kategori : ' . $row['category'] . '</p>
                        ' . $platformLinks . '
                    </div>
                    <div class="card-footer text-center">
                        <a href="edit_products.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                        <a href="view_products.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">View</a>
                        <a href="delete_products.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
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

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_products.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Nama Item</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Harga Produk</label>
                            <input type="number" class="form-control" id="productPrice" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="productUnit" class="form-label">Satuan</label>
                            <select class="form-select" id="productUnit" name="unit" required>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="productCategory" name="category" required>
                            </select>
                        </div>
                        <div class="mb-3" id="variant-section">
                            <label class="form-label">Varian Produk</label>
                            <div id="variant-container">
                                <div class="variant-row d-flex gap-2">
                                    <input type="text" name="variant_name[]" class="form-control"
                                        placeholder="Nama Varian">
                                    <input type="number" name="variant_price[]" class="form-control"
                                        placeholder="Harga Varian">
                                    <button type="button" class="btn btn-danger remove-variant">&times;</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="addVariant">Tambah Varian</button>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" id="productImage" name="image">
                        </div>
                        <div class="mb-3">
                            <label for="tokopediaLink" class="form-label">Tokopedia Link</label>
                            <input type="url" class="form-control" id="tokopediaLink" name="tokopedia_link">
                        </div>
                        <div class="mb-3">
                            <label for="shopeeLink" class="form-label">Shopee Link</label>
                            <input type="url" class="form-control" id="shopeeLink" name="shopee_link">
                        </div>
                        <div class="mb-3">
                            <label for="inaprocLink" class="form-label">Inaproc Link</label>
                            <input type="url" class="form-control" id="inaprocLink" name="inaproc_link">
                        </div>
                        <div class="mb-3">
                            <label for="siplahLink" class="form-label">Siplah Blibli Link</label>
                            <input type="url" class="form-control" id="siplahLink" name="siplah_link">
                        </div>
                        <div class="mb-3">
                            <label for="blibliLink" class="form-label">Padi UMKM Link</label>
                            <input type="url" class="form-control" id="blibliLink" name="blibli_link">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadCategories();
            loadUnit();
            populateCategories();
        });

        document.getElementById('addVariant').addEventListener('click', function () {
            let variantContainer = document.getElementById('variant-container');
            let newVariant = document.createElement('div');
            newVariant.classList.add('variant-row', 'd-flex', 'gap-2', 'mt-2');
            newVariant.innerHTML = `
            <input type="text" name="variant_name[]" class="form-control" placeholder="Nama Varian">
            <input type="number" name="variant_price[]" class="form-control" placeholder="Harga Varian">
            <button type="button" class="btn btn-danger remove-variant">&times;</button>
        `;
            variantContainer.appendChild(newVariant);
        });

        document.getElementById('variant-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.parentElement.remove();
            }
        });
    </script>
    <script src="list.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .btn-tokped {
            background-color: rgb(13, 129, 0);
            border-color: rgb(13, 129, 0);
            color: white;
        }

        .btn-shopee {
            background-color: #FF5722;
            border-color: #FF5722;
            color: white;
        }

        .btn-inaproc {
            background-color: rgb(174, 0, 0);
            border-color: rgb(174, 0, 0);
            color: white;
        }

        .btn-siplah {
            background-color: rgb(8, 112, 196);
            border-color: rgb(8, 112, 196);
            color: white;
        }

        .btn-padi {
            background-color: #009ea9;
            border-color: #009ea9;
            color: white;
        }

        .dropzone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dropzone.dragover {
            background-color: #f8f9fa;
        }

        .preview img {
            max-width: 100px;
            margin-top: 10px;
        }
    </style>
</body>

</html>

<?php
$conn->close();
?>