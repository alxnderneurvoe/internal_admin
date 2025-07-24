<?php
if (isset($_POST['import']) && isset($_FILES['csv_file'])) {
    include '../config.php';

    $tmpName = $_FILES['csv_file']['tmp_name'];
    $fileExt = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);

    if ($fileExt !== 'csv') {
        die("File yang diupload harus berekstensi .csv");
    }

    $csv = fopen($tmpName, 'r');

    // Lewati header
    fgetcsv($csv);

    $success = 0;
    $skipped = 0;

    while (($row = fgetcsv($csv, 1000, ',')) !== false) {
        $name = $row[0] ?? '';
        $category = $row[1] ?? '';
        $unit = $row[2] ?? '';
        $price = (int) ($row[3] ?? 0);
        $inaproc_link = $row[4] ?? '';
        $siplah_link = $row[5] ?? '';
        $tokopedia_link = $row[6] ?? '';
        $shopee_link = $row[7] ?? '';
        $blibli_link = $row[8] ?? '';
        $spec = $row[9] ?? '';

        if (trim($name) == '') {
            $skipped++;
            continue;
        }

        // Cek apakah produk sudah ada
        $check = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            // Insert produk baru
            $stmt = $conn->prepare("INSERT INTO products (name, price, unit, category, inaproc_link, tokopedia_link, shopee_link, siplah_link, blibli_link, spec) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssssss", $name, $price, $unit, $category, $inaproc_link, $tokopedia_link, $shopee_link, $siplah_link, $blibli_link);
            $stmt->execute();
            $success++;
        } else {
            $skipped++;
        }
    }

    fclose($csv);
    echo "<div class='alert alert-success mt-3'>Import selesai: $success produk ditambahkan, $skipped dilewati (duplikat/kosong).</div>";
    header("Location: products.php");
    exit();
}
?>