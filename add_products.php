<?php
// Sertakan file koneksi
include('config.php');

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Proses upload gambar
    $imagePath = 'uploads/' . basename($image['name']);
    move_uploaded_file($image['tmp_name'], $imagePath);

    // Query untuk menyimpan produk baru ke database
    $sql = "INSERT INTO products (name, price, image_url) VALUES ('$name', '$price', '$imagePath')";

    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman produk setelah berhasil menambah produk
        header("Location: products.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
