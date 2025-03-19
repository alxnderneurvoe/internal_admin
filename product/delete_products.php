<?php
include('../config.php');

// Ambil ID produk dari URL
$id = $_GET['id'];

// Query untuk menghapus produk berdasarkan ID
$sql = "DELETE FROM products WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: products.php"); // Redirect ke daftar produk setelah berhasil menghapus
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
