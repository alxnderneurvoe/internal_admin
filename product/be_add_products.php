<?php
// Sertakan file koneksi
include('../config.php');

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $unit = $_POST['unit'];
    $tokopedia_link = $_POST['tokopedia_link'];
    $shopee_link = $_POST['shopee_link'];
    $inaproc_link = $_POST['inaproc_link'];
    $siplah_link = $_POST['siplah_link'];
    $blibli_link = $_POST['blibli_link'];
    $category = $_POST['category'];
    
    // Proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = '../uploads/' . basename($image['name']);

        // Pindahkan file gambar ke folder upload
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Query untuk menyimpan produk baru ke database
            $sql = "INSERT INTO products (name, price, unit, tokopedia_link, shopee_link, inaproc_link, siplah_link, blibli_link, image_url, category) 
                    VALUES ('$name', '$price', '$unit', '$tokopedia_link', '$shopee_link', '$inaproc_link', '$siplah_link', '$blibli_link', '$imagePath', '$category')";
        } else {
            echo "Error uploading the image.";
            exit;
        }
    } else {
        // Jika tidak ada gambar yang diupload
        $sql = "INSERT INTO products (name, price, unit, tokopedia_link, shopee_link, inaproc_link, siplah_link, blibli_link, category) 
                VALUES ('$name', '$price', '$unit', '$tokopedia_link', '$shopee_link', '$inaproc_link', '$siplah_link', '$blibli_link', '$category')";
    }

    // Eksekusi query
    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman produk setelah berhasil menambah produk
        header("Location: products.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
