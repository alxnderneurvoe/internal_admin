<?php
include('../config.php');

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

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
    <meta name="viewport" content="width=1024">
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
            <li class="nav-item"><a class="nav-link" href="../dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i>
                    Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../create_letter.php"><i
                        class="fas fa-fw fa-file-invoice"></i> Create Letter</a></li>
            <li class="nav-item"><a class="nav-link" href="../files.php"><i class="fas fa-fw fa-folder"></i> File
                    Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="../product/products.php"><i class="fas fa-fw fa-folder"></i>
                    Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-fw fa-sign-out-alt"></i>
                    Logout</a></li>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Product List</h2>
        <form method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" class="form-control w-25" name="search" placeholder="Search by product name"
                value="<?php echo htmlspecialchars($search); ?>">
            <select class="form-select w-25 ms-2" name="category" id="category-select" onchange="this.form.submit()"></select>
        </form>
        <div class="container">
            <h2>Import Produk Massal dari CSV</h2>
            <form method="POST" enctype="multipart/form-data" class="mt-4" action="upload_import.php">
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Pilih file CSV</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                </div>
                <button type="submit" name="import" class="btn btn-primary">Import
                    Sekarang</button>
                <p></p>
            </form>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-outline-primary" id="toggleViewBtn">Switch to Table View</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"><i
                    class="fas fa-plus-circle"></i> Add Product</button>
        </div>

        <!-- Grid View -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4" id="gridView">
            <?php
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
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
                $platformLinks = implode(' ', $platformButtons);
                echo '<div class="col">
                        <div class="card border-light shadow-sm" style="height: 510px;">
<img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/no-image.png') . '" class="card-img-top mx-auto d-block" alt="' . $row['name'] . '" style="width: 70%; height: 200px;">
                            <div class="card-body">
                                <h6 class="card-title text-center" style="
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;     
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    line-height: 1.2em;
                                    height: 2.5em;
                                ">
                                    ' . htmlspecialchars($row['name']) . '
                                </h6>
                                <p class="card-text text-center text-muted" style="font-size: 1em;">Rp. ' . number_format($row['price'], 0, ',', '.') . '</p>
                                <p class="card-text text-center small" style="font-size: 0.9em;">Kategori : ' . $row['category'] . '</p>
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
                            $links = [];
                            if ($row['tokopedia_link'])
                                $links[] = '<a href="' . $row['tokopedia_link'] . '" target="_blank" class="btn btn-tokped btn-sm">Tokopedia</a>';
                            if ($row['shopee_link'])
                                $links[] = '<a href="' . $row['shopee_link'] . '" target="_blank" class="btn btn-shopee btn-sm">Shopee</a>';
                            if ($row['inaproc_link'])
                                $links[] = '<a href="' . $row['inaproc_link'] . '" target="_blank" class="btn btn-inaproc btn-sm">Inaproc</a>';
                            if ($row['siplah_link'])
                                $links[] = '<a href="' . $row['siplah_link'] . '" target="_blank" class="btn btn-siplah btn-sm">Siplah</a>';
                            if ($row['blibli_link'])
                                $links[] = '<a href="' . $row['blibli_link'] . '" target="_blank" class="btn btn-padi btn-sm">Padi</a>';
                            echo '<tr>';
                            echo '<td style="vertical-align: middle;"><img src="' . (!empty($row['image_url']) ? $row['image_url'] : '../asset/no-image.png') . '" style="max-height: 130px;"></td>';
                            echo '<td width="25%" style="vertical-align: middle;">' . $row['name'] . '</td>';
                            echo '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; vertical-align: middle;">Rp. ' . number_format($row['price'], 0, ',', '.') . '</td>';
                            echo '<td style="vertical-align: middle;">' . $row['unit'] . '</td>';
                            echo '<td style="vertical-align: middle;">' . $row['category'] . '</td>';
                            echo '<td style="vertical-align: middle; max-width: 240px;">
                                    <div class="d-flex justify-content-center flex-wrap gap-2">' . implode('', $links) . '</div>
                                 </td>';
                            echo '<td style="vertical-align: middle;">
                                    <div class="d-flex justify-content-center flex-wrap gap-2" style="margin-bottom: 10px;">
                                        <a href="edit_products.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="view_products.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">View</a>
                                        <a href="delete_products.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                                    </div>
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

    <!-- Modal Add Product -->
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
                            <input type="file" class="form-control" id="productImage" name="image"
                                accept=".jpg, .jpeg, .png, .webp">
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
                        <div class="mb-3">
                            <label for="spec" class="form-label">Spesifikasi</label>
                            <textarea name="spec" id="spec" class="form-control" placeholder="Spesifikasi" rows="2"
                                style="resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const Category = [
            { id: "", name: "Pilih Kategori" },
            { id: "Aksesoris Pendukung Komputer", name: "Aksesoris Pendukung Komputer" },
            { id: "Aksesoris Perangkat Jaringan", name: "Aksesoris Perangkat Jaringan" },
            { id: "Alat Peraga - Hortukultura", name: "Alat Peraga - Hortukultura" },
            { id: "Alat Peraga - Kapal Penangkap Ikan", name: "Alat Peraga - Kapal Penangkap Ikan" },
            { id: "Alat Peraga - Keahlian Kriya Kreatif Kayu dan Rotan", name: "Alat Peraga - Keahlian Kriya Kreatif Kayu dan Rotan" },
            { id: "Alat Peraga - Keahlian Tata Busana", name: "Alat Peraga - Keahlian Tata Busana" },
            { id: "Alat Peraga - Kesehatan Hewan", name: "Alat Peraga - Kesehatan Hewan" },
            { id: "Alat Peraga - Mek. Pertanian", name: "Alat Peraga - Mek. Pertanian" },
            { id: "Alat Peraga - Olah Hasil  Ikan", name: "Alat Peraga - Olah Hasil  Ikan" },
            { id: "Alat Peraga - Pengolahan Hasil Pertanian", name: "Alat Peraga - Pengolahan Hasil Pertanian" },
            { id: "Alat Peraga - Perbenihan Tanaman", name: "Alat Peraga - Perbenihan Tanaman" },
            { id: "Alat Peraga - Perhotelan", name: "Alat Peraga - Perhotelan" },
            { id: "Alat Peraga - Perikanan Air Tawar", name: "Alat Peraga - Perikanan Air Tawar" },
            { id: "Alat Peraga - Teknik Audio Video", name: "Alat Peraga - Teknik Audio Video" },
            { id: "Alat Peraga Edukatif", name: "Alat Peraga Edukatif" },
            { id: "Buku Pendidikan", name: "Buku Pendidikan" },
            { id: "Furnitur Kantor/Sekolah", name: "Furnitur Kantor/Sekolah" },
            { id: "Lampu Jalan/PJUTS", name: "Lampu Jalan/PJUTS" },
            { id: "Laptop/PC/AiO", name: "Laptop/PC/AiO" },
            { id: "Meja dan Kursi Guru", name: "Meja dan Kursi Guru" },
            { id: "Meja dan Kursi Paud", name: "Meja dan Kursi Paud" },
            { id: "Meja dan Kursi Siswa", name: "Meja dan Kursi Siswa" },
            { id: "Mesin Industri", name: "Mesin Industri" },
            { id: "Mesin Welding Pipe", name: "Mesin Welding Pipe" },
            { id: "Paket SR Perpipaan", name: "Paket SR Perpipaan" },
            { id: "Pipa dan Fitting HDPE", name: "Pipa dan Fitting HDPE" },
            { id: "Pipa dan Fitting Limbah", name: "Pipa dan Fitting Limbah" },
            { id: "Pipa dan Fitting PPR", name: "Pipa dan Fitting PPR" },
            { id: "Pipa dan Fitting PVC", name: "Pipa dan Fitting PVC" }
        ];

        const unit = [
            { id: "", name: "Pilih Satuan" },
            { id: "Batang", name: "Batang" },
            { id: "Pcs", name: "Pcs" },
            { id: "Meter", name: "Meter" },
            { id: "Unit", name: "Unit" },
            { id: "Paket", name: "Paket" },
            { id: "Set", name: "Set" },
            { id: "Roll-50m", name: "Roll-50m" },
            { id: "Roll-100m", name: "Roll-100m" },
        ];

        function loadCategoryFilter() {
            const select = document.getElementById("category-select");
            Category.forEach(cat => {
                const option = document.createElement("option");
                option.value = cat.id;
                option.textContent = cat.name;

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('category') === cat.id) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        }

        function loadCategory() {
            const CategorySelect = document.getElementById("productCategory");
            Category.forEach(cat => {
                const option = document.createElement("option");
                option.value = cat.id;
                option.textContent = cat.name;
                CategorySelect.appendChild(option);
            });
        }

        function loadUnit() {
            const unitSelect = document.getElementById("productUnit");
            unit.forEach(u => {
                const option = document.createElement("option");
                option.value = u.id;
                option.textContent = u.name;
                unitSelect.appendChild(option);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            loadCategory();
            loadUnit();
            loadCategoryFilter();
        });
    </script>

    <script>
        // Toggle grid/table view
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

            // Script varian produk
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
        });
    </script>
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
    </style>
</body>

</html>

<?php
$conn->close();
?>