<?php
include('../config.php');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id == 0) {
    die("Invalid product ID");
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found");
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$variants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="list.js"></script>
    <link rel="stylesheet" href="../asset/style.css">
</head>

<body>
    <div class="container mt-3 text-center">
        <a href="products.php" class="btn btn-secondary">Back to Products</a>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Product Details</h2>
        <div class="card">
            <img src="<?php echo !empty($product['image_url']) ? $product['image_url'] : '../asset/no-image.png'; ?>"
                class="card-img-top" style="height: auto; width: 130px;" alt="<?php echo $product['name']; ?>">
            <div class="card-body">
                <h2 class="card-title" style="
                                    display: -webkit-box;
                                    /* -webkit-line-clamp: 2; */
                                    -webkit-box-orient: vertical;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    line-height: 1.2em;
                                    height: 1.35em;
                                "> <?php echo $product['name']; ?> </h2>

                <p class="card-text">Harga : Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                <p class="card-text">Satuan : <?php echo $product['unit']; ?></p>
                <p class="card-text">Kategori : <?php echo $product['category']; ?></p>

                <h6>Variants:</h6>
                <?php if (!empty($variants)) { ?>
                    <ul>
                        <?php foreach ($variants as $variant) { ?>
                            <li><?php echo $variant['variant_name']; ?> - Rp.
                                <?php echo number_format($variant['variant_price'], 0, ',', '.'); ?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p>No variants available.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php
$conn->close();
?>