<?php
include('../config.php');

$csvFile = fopen('produk.csv', 'r');

if (!$csvFile) {
    die("Gagal membuka file CSV.");
}

// Lewati baris header
fgetcsv($csvFile);

while (($row = fgetcsv($csvFile, 1000, ',')) !== FALSE) {
    $name = $row[0];
    $category = $row[1];
    $unit = $row[2];
    $price = (int)$row[3];
    $inaproc_link = $row[4];

    // Cek apakah produk dengan nama yang sama sudah ada
    $check = $conn->prepare("SELECT id FROM products WHERE name = ?");
    $check->bind_param("s", $name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO products (name, category, unit, price, inaproc_link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $name, $category, $unit, $price, $inaproc_link);
        $stmt->execute();
    }
}

fclose($csvFile);
echo "Import produk selesai!";
?>
