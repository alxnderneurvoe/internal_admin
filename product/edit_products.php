<?php
include('../config.php');

// Cek apakah ada parameter 'id' di URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Ambil data produk berdasarkan ID
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    // Jika produk ditemukan
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Jika tidak ditemukan produk dengan ID tersebut
        echo "Product not found!";
        exit;
    }
} else {
    echo "No product ID specified!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form edit
    $name = $_POST['name'];
    $price = $_POST['price'];
    $unit = $_POST['unit'];
    $tokopedia_link = $_POST['tokopedia_link'];
    $shopee_link = $_POST['shopee_link'];
    $inaproc_link = $_POST['inaproc_link'];
    $siplah_link = $_POST['siplah_link'];
    $blibli_link = $_POST['blibli_link'];

    // Periksa apakah gambar baru diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_path = 'images/' . $image_name;

        // Pindahkan gambar ke folder yang diinginkan
        move_uploaded_file($image_tmp_name, $image_path);

        // Update produk dengan gambar baru
        $sql = "UPDATE products SET 
                name = '$name', 
                price = '$price', 
                unit = '$unit', 
                tokopedia_link = '$tokopedia_link', 
                shopee_link = '$shopee_link', 
                inaproc_link = '$inaproc_link', 
                siplah_link = '$siplah_link', 
                blibli_link = '$blibli_link', 
                image_url = '$image_path'
                WHERE id = $product_id";
    } else {
        // Jika tidak ada gambar baru, hanya update data lainnya
        $sql = "UPDATE products SET 
                name = '$name', 
                price = '$price', 
                unit = '$unit', 
                tokopedia_link = '$tokopedia_link', 
                shopee_link = '$shopee_link', 
                inaproc_link = '$inaproc_link', 
                siplah_link = '$siplah_link', 
                blibli_link = '$blibli_link'
                WHERE id = $product_id";
    }

    // Jalankan query untuk update
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
        header("Location: products.php"); // Redirect ke halaman daftar produk
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
        <h2 class="mb-4 text-center text-primary">Edit Product</h2>

        <form action="edit_products.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="productPrice" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="productUnit" class="form-label">Unit</label>
                <input type="text" class="form-control" id="productUnit" name="unit" value="<?php echo $product['unit']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tokopediaLink" class="form-label">Tokopedia Link</label>
                <input type="url" class="form-control" id="tokopediaLink" name="tokopedia_link" value="<?php echo $product['tokopedia_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="shopeeLink" class="form-label">Shopee Link</label>
                <input type="url" class="form-control" id="shopeeLink" name="shopee_link" value="<?php echo $product['shopee_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="inaprocLink" class="form-label">Inaproc Link</label>
                <input type="url" class="form-control" id="inaprocLink" name="inaproc_link" value="<?php echo $product['inaproc_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="siplahLink" class="form-label">Siplah Link</label>
                <input type="url" class="form-control" id="siplahLink" name="siplah_link" value="<?php echo $product['siplah_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="blibliLink" class="form-label">Blibli Link</label>
                <input type="url" class="form-control" id="blibliLink" name="blibli_link" value="<?php echo $product['blibli_link']; ?>">
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="image">
                <img src="<?php echo $product['image_url']; ?>" alt="Product Image" class="img-thumbnail mt-2" style="width: 150px;">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Product</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
