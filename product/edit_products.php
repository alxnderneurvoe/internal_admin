<?php
include('../config.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    echo "No product ID specified!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $unit = $_POST['unit'];
    $tokopedia_link = $_POST['tokopedia_link'];
    $shopee_link = $_POST['shopee_link'];
    $inaproc_link = $_POST['inaproc_link'];
    $siplah_link = $_POST['siplah_link'];
    $blibli_link = $_POST['blibli_link'];
    $category = $_POST['category'];


    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_path = '../uploads/' . $image_name;


        move_uploaded_file($image_tmp_name, $image_path);


        $sql = "UPDATE products SET 
                name = '$name', 
                price = '$price', 
                unit = '$unit', 
                tokopedia_link = '$tokopedia_link', 
                shopee_link = '$shopee_link', 
                inaproc_link = '$inaproc_link', 
                siplah_link = '$siplah_link', 
                blibli_link = '$blibli_link', 
                image_url = '$image_path',
                category = '$category' 
                WHERE id = $product_id";
    } else {
        $sql = "UPDATE products SET 
                name = '$name', 
                price = '$price', 
                unit = '$unit', 
                tokopedia_link = '$tokopedia_link', 
                shopee_link = '$shopee_link', 
                inaproc_link = '$inaproc_link', 
                siplah_link = '$siplah_link', 
                blibli_link = '$blibli_link',
                category = '$category' 
                WHERE id = $product_id";
    }

    echo $sql;
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
        header("Location: products.php");
    } else {
        echo "Error updating product: " . $conn->error;
    }

}

$categories = [];
$categoryQuery = "SELECT * FROM products";
$categoryResult = $conn->query($categoryQuery);
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

$units = [];
$unitQuery = "SELECT * FROM products";
$unitResult = $conn->query($unitQuery);
while ($row = $unitResult->fetch_assoc()) {
    $units[] = $row;
}

$units = [
    ['id' => '', 'unit' => 'Pilih Satuan'],
    ['id' => 'Batang', 'unit' => 'Batang'],
    ['id' => 'Pcs', 'unit' => 'Pcs'],
    ['id' => 'Meter', 'unit' => 'Meter'],
    ['id' => 'Unit', 'unit' => 'Unit'],
    ['id' => 'Paket', 'unit' => 'Paket'],
    ['id' => 'Roll-50m', 'unit' => 'Roll-50m'],
    ['id' => 'Roll-100m', 'unit' => 'Roll-100m'],
];

$categories = [
    ['id' => '', 'category' => 'Pilih Kategori'],
    ['id' => 'Aksesoris Perangkat Jaringan', 'category' => 'Aksesoris Perangkat Jaringan'],
    ['id' => 'Alat Peraga Edukatif', 'category' => 'Alat Peraga Edukatif'],
    ['id' => 'Buku Pendidikan', 'category' => 'Buku Pendidikan'],
    ['id' => 'Furnitur Kantor/Sekolah', 'category' => 'Furnitur Kantor/Sekolah'],
    ['id' => 'Laptop/PC/AiO', 'category' => 'Laptop/PC/AiO'],
    ['id' => 'Laptop', 'category' => 'Laptop'],
    ['id' => 'Meja dan Kursi Guru', 'category' => 'Meja dan Kursi Guru'],
    ['id' => 'Meja dan Kursi Siswa', 'category' => 'Meja dan Kursi Siswa'],
    ['id' => 'Meja dan Kursi Paud', 'category' => 'Meja dan Kursi Paud'],
    ['id' => 'Mesin Welding Pipe', 'category' => 'Mesin Welding Pipe'],
    ['id' => 'Pipa dan Fitting HDPE', 'category' => 'Pipa dan Fitting HDPE'],
    ['id' => 'Pipa dan Fitting Limbah', 'category' => 'Pipa dan Fitting Limbah'],
    ['id' => 'Pipa dan Fitting PPR', 'category' => 'Pipa dan Fitting PPR'],
    ['id' => 'Pipa dan Fitting PVC', 'category' => 'Pipa dan Fitting PVC']
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
        <div class="container mt-3 text-center">
            <a href="products.php" class="btn btn-secondary">Back to Products</a>
        </div>
        <p></p>
        <h2 class="mb-4 text-center text-primary">Edit Product</h2>

        <form action="edit_products.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="name"
                    value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="productPrice" name="price"
                    value="<?php echo $product['price']; ?>" required>
            </div>
            <select class="form-select" id="productUnit" name="unit" required>
                <?php foreach ($units as $unit): ?>
                    <option value="<?= htmlspecialchars($unit['id']) ?>" <?= ($unit['id'] == $product['unit']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($unit['unit']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="mb-3">
                <label for="productCategory" class="form-label">Kategori</label>
                <select class="form-select" id="productCategory" name="category" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo ($cat['id'] == $product['category']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tokopediaLink" class="form-label">Tokopedia Link</label>
                <input type="url" class="form-control" id="tokopediaLink" name="tokopedia_link"
                    value="<?php echo $product['tokopedia_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="shopeeLink" class="form-label">Shopee Link</label>
                <input type="url" class="form-control" id="shopeeLink" name="shopee_link"
                    value="<?php echo $product['shopee_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="inaprocLink" class="form-label">Inaproc Link</label>
                <input type="url" class="form-control" id="inaprocLink" name="inaproc_link"
                    value="<?php echo $product['inaproc_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="siplahLink" class="form-label">Siplah Link</label>
                <input type="url" class="form-control" id="siplahLink" name="siplah_link"
                    value="<?php echo $product['siplah_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="blibliLink" class="form-label">Blibli Link</label>
                <input type="url" class="form-control" id="blibliLink" name="blibli_link"
                    value="<?php echo $product['blibli_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="image"
                    accept=".jpg, .jpeg, .png, .webp">
                <img src="<?php echo $product['image_url']; ?>" alt="Product Image" class="img-thumbnail mt-2"
                    style="width: 150px;">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Product</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadCategories();
            loadUnit();
            populateCategories();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>