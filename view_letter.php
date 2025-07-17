<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Include database configuration
include 'config.php';

$user_email = $_SESSION['email'];
$user_query = "SELECT id FROM users WHERE email = '$user_email'";
$result = $conn->query($user_query);
$user = $result->fetch_assoc();
$user_id = $user['id'];

$months = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

// Fetch data from invoices and SPH
$invoices = $conn->query("SELECT id, invoice_number AS number, client_name, grand_total, status, created_at, 'invoice' AS type FROM invoices WHERE user_id = '$user_id'");

$letters = [];
while ($row = $invoices->fetch_assoc()) {
    $letters[] = $row;
}

// Sort by created_at descending
usort($letters, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Letters</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="dashboard.php">
            <img src="asset/Logo.png" alt="Logo" style="height: 30px;">
        </a>
        <a class="navbar-brand" href="dashboard.php">PT Semesta Sistem Solusindo</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="create_letter.php">Create Letter</a></li>
            <li class="nav-item"><a class="nav-link" href="files.php">File Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="product/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>All Letters</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Nama Pelanggan</th>
                    <th>Nilai</th>
                    <th>Tgl Pembuatan</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($letters as $row): ?>
                    <?php
                    $type = strtoupper($row['status']);

                    switch ($type) {
                        case 'INV':
                            $folder = 'invoice';
                            $prefix = 'invoice';
                            break;
                        case 'SPH':
                            $folder = 'sph';
                            $prefix = 'sph';
                            break;
                        default:
                            $folder = 'other';
                            $prefix = 'other';
                            break;
                    }

                    $date = new DateTime($row['created_at']);
                    $formatted_date = $date->format('d') . ' ' . $months[(int) $date->format('m')] . ' ' . $date->format('Y');
                    ?>
                    <tr>
                        <td><?= $row['number'] ?></td>
                        <td><?= $row['client_name'] ?></td>
                        <td>Rp. <?= number_format($row['grand_total'], 0, '', '.') ?></td>
                        <td><?= $formatted_date ?></td>
                        <td><?= strtoupper($row['status']) ?></td>
                        <td>
                            <a href="<?= $folder ?>/detail_<?= $prefix ?>.php?id=<?= $row['id'] ?>"
                                class="btn btn-info btn-sm ">Lihat</a>
                            <a href="<?= $folder ?>/edit_<?= $prefix ?>.php?id=<?= $row['id'] ?>"
                                class="btn btn-warning btn-sm  ">Edit</a>
                            <a href="<?= $folder ?>/delete_<?= $prefix ?>.php?id=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm  "
                                onclick="return confirm('Yakin ingin menghapus surat ini?')">Hapus</a>
                            <a href="<?= $folder ?>/download_potrait_<?= $prefix ?>.php?id=<?= $row['id'] ?>"
                                class="btn btn-success btn-sm  ">Potrait</a>
                            <a href="<?= $folder ?>/download_lands_<?= $prefix ?>.php?id=<?= $row['id'] ?>"
                                class="btn btn-success btn-sm  ">Landscape</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>