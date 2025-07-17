<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $category = $conn->real_escape_string($_POST['category']);
    $tokopedia_link = $conn->real_escape_string($_POST['tokopedia_link'] ?? '');
    $shopee_link = $conn->real_escape_string($_POST['shopee_link'] ?? '');
    $inaproc_link = $conn->real_escape_string($_POST['inaproc_link'] ?? '');
    $siplah_link = $conn->real_escape_string($_POST['siplah_link'] ?? '');
    $blibli_link = $conn->real_escape_string($_POST['blibli_link'] ?? '');
    $spec = $conn->real_escape_string($_POST['spec'] ?? '');
    
    // Handle file upload
    $image_url = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        }
    }

    // Insert product data
    $sql = "INSERT INTO products (name, price, unit, category, image_url, tokopedia_link, shopee_link, inaproc_link, siplah_link, blibli_link, spec) 
            VALUES ('$name', '$price', '$unit', '$category', '$image_url', '$tokopedia_link', '$shopee_link', '$inaproc_link', '$siplah_link', '$blibli_link', '$spec')";
    
    if ($conn->query($sql) === TRUE) {
        $product_id = $conn->insert_id;

        // Handle product variants
        if (!empty($_POST['variant_name']) && is_array($_POST['variant_name'])) {
            foreach ($_POST['variant_name'] as $key => $variant_name) {
                $variant_name = $conn->real_escape_string($variant_name);
                $variant_price = $conn->real_escape_string($_POST['variant_price'][$key]);
                
                if (!empty($variant_name) && is_numeric($variant_price)) {
                    $sql_variant = "INSERT INTO product_variants (product_id, variant_name, variant_price) 
                                    VALUES ('$product_id', '$variant_name', '$variant_price')";
                    $conn->query($sql_variant);
                }
            }
        }
        
        header("Location: products.php?success=Product added successfully");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>