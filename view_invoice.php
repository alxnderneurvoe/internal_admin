<?php

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    // exit();
}

// Include the database configuration file
include 'config.php';

// Fetch all invoices for the logged-in user
$user_email = $_SESSION['email'];

// Get user ID based on email
$user_query = "SELECT id FROM users WHERE email = '$user_email'";
$result = $conn->query($user_query);
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch the invoices for this user
$query = "SELECT * FROM invoices WHERE user_id = '$user_id'";
$invoices = $conn->query($query);

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">
    <title>View Invoice</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="dashboard.php">
            <img src="asset/Logo.png" alt="" style="width: auto; height: 30px;">
        </a>
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i> PT Semesta Sistem Solusindo
        </a>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="files.php">
                    <i class="fas fa-fw fa-folder"></i> File Storage
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product/products.php">
                    <i class="fas fa-fw fa-folder"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-fw fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <h2>All Invoices</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Nilai Kontrak</th>
                    <th>Tgl Pembuatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($invoices->num_rows > 0) {
                    while ($row = $invoices->fetch_assoc()) {
                        $date = new DateTime($row['created_at']);
                        $formatted_date = $date->format('d') . ' ' . $months[(int) $date->format('m')] . ' ' . $date->format('Y');

                        echo "<tr>
                            <td>{$row['invoice_number']}</td>
                            <td>{$row['client_name']}</td>
                            <td>Rp. " . number_format($row['grand_total'], 0, '', '.') . "</td>
                            <td>{$formatted_date}</td>
                            <td>
                                <a href='detail_invoice.php?id={$row['id']}' class='btn btn-info btn-sm mb-1'>Lihat</a>
                                <a href='edit_invoice.php?id={$row['id']}' class='btn btn-warning btn-sm mb-1'>Edit</a>
                                <a href='delete_invoice.php?id={$row['id']}' class='btn btn-danger btn-sm mb-1' onclick='return confirm(\"Yakin ingin menghapus invoice ini?\")'>Hapus</a>
                                <a href='download_invoice.php?id={$row['id']}' class='btn btn-success btn-sm mb-1'>Download</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No invoices found.</td></tr>";
                }
                ?>
                </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>