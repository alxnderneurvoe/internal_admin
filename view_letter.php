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

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$sql = "SELECT id, invoice_number AS number, client_name, grand_total, status, created_at, 'invoice' AS type FROM invoices WHERE user_id = '$user_id'";

if (!empty($search)) {
    $sql .= " AND client_name LIKE '%$search%'";
}
if (!empty($status_filter)) {
    $sql .= " AND status = '$status_filter'";
}
if (!empty($start_date)) {
    $sql .= " AND DATE(created_at) >= '$start_date'";
}
if (!empty($end_date)) {
    $sql .= " AND DATE(created_at) <= '$end_date'";
}

$sql .= " ORDER BY created_at DESC";
$invoices = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Letters</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
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
        <form id="filter-form" class="form-inline mb-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Cari pelanggan...">
            <select name="status" class="form-control mr-2">
                <option value="">Semua</option>
                <option value="INV">Invoice</option>
                <option value="SPH">SPH</option>
            </select>
            <input type="date" name="start_date" class="form-control mr-2">
            <input type="date" name="end_date" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="view_letter.php" class="btn btn-secondary ml-2">Reset</a>
        </form>
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
            <tbody id="letters-table-body">
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
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    function loadLetters() {
                        $.ajax({
                            url: 'filter_letters.php',
                            type: 'GET',
                            data: $('#filter-form').serialize(),
                            success: function (data) {
                                $('#letters-table-body').html(data);
                            }
                        });
                    }

                    // Load default data saat halaman dibuka
                    $(document).ready(function () {
                        loadLetters();

                        $('#filter-form').on('submit', function (e) {
                            e.preventDefault();
                            loadLetters();
                        });
                    });
                </script>

            </tbody>
        </table>
    </div>
</body>

</html>