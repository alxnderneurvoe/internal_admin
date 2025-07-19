<?php
include '../config.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM invoices WHERE id = $id");
$invoice = $result->fetch_assoc();
if (!$invoice) {
    die("Invoice tidak ditemukan.");
}

// Ambil item invoice
$items_query = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $id");
$items = [];
while ($item = $items_query->fetch_assoc()) {
    $items[] = $item;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <title>Detail Penawaran</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="../dashboard.php">
            <img src="../asset/Logo.png" alt="Logo" style="height: 30px;">
        </a>
        <a class="navbar-brand" href="../dashboard.php">PT Semesta Sistem Solusindo</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../create_letter.php">Create Letter</a></li>
            <li class="nav-item"><a class="nav-link" href="../files.php">File Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="../product/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="container mt-4">
        <h2 class="mb-4">Detail Penawaran</h2>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-only-top">
                    <tr>
                        <th style="width: 150px;">No Penawaran</th>
                        <td>: <?= htmlspecialchars($invoice['invoice_number']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>: <?= htmlspecialchars($invoice['client_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: <?= htmlspecialchars($invoice['address']) ?></td>
                    </tr>
                    <tr>
                        <th>Subtotal</th>
                        <td>: Rp. <?= number_format($invoice['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>PPN 11%</th>
                        <td>: Rp. <?= number_format($invoice['tax'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Grand Total</th>
                        <td>: <span class="text-black font-weight-bold">Rp.
                                <?= number_format($invoice['grand_total'], 0, ',', '.') ?></span></td>
                    </tr>
                </table>
            </div>
        </div>

        <h5>Daftar Item</h5>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($items as $item):
                    $total = ($item['qty'] * $item['price']) * (1 - $item['discount'] / 100);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['spec'] ?? '-') ?></td>
                        <td><?= $item['qty'] . ' ' . htmlspecialchars($item['unit']) ?></td>
                        <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td><?= $item['discount'] ?>%</td>
                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../view_letter.php" class="btn btn-secondary mt-3" style="margin-bottom: 45px;">← Kembali</a>
    </div>
</body>

</html>