<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $price = (int)$_POST['price'];
    $unit = $conn->real_escape_string($_POST['unit']);
    $category = $conn->real_escape_string($_POST['category']);
    $image_url = '';

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_url = $target_file;
    }

    $tokopedia_link = $conn->real_escape_string($_POST['tokopedia_link']);
    $shopee_link = $conn->real_escape_string($_POST['shopee_link']);
    $inaproc_link = $conn->real_escape_string($_POST['inaproc_link']);
    $siplah_link = $conn->real_escape_string($_POST['siplah_link']);
    $blibli_link = $conn->real_escape_string($_POST['blibli_link']);

    $sql = "INSERT INTO products (name, price, unit, category, image_url, tokopedia_link, shopee_link, inaproc_link, siplah_link, blibli_link)
            VALUES ('$name', '$price', '$unit', '$category', '$image_url', '$tokopedia_link', '$shopee_link', '$inaproc_link', '$siplah_link', '$blibli_link')";

    if ($conn->query($sql) === TRUE) {
        header("Location: list_products.php?success=Product added successfully");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center text-primary">Add New Product</h2>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">
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
                <select class="form-select" id="productUnit" name="unit" required></select>
            </div>
            <div class="mb-3">
                <label for="productCategory" class="form-label">Kategori</label>
                <select class="form-select" id="productCategory" name="category" required></select>
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
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>
    </div>

    <script src="list.js"></script>
</body>
</html>
