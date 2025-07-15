<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <title>Select Document Type</title>
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <!-- Logo on the left -->
        <a class="navbar-brand" href="dashboard.php">
            <img src="asset/Logo.png" alt="" style="width: auto; height: 30px;">
        </a>
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i> PT Semesta Sistem Solusindo
        </a>

        <!-- Navbar items -->
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

    <!-- Main Content -->
    <div class="container">
        <h2>Select Document Type</h2>
        <p>Please select the type of document you want to create:</p>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body text-center">
                        <h5 class="card-title">Invoice</h5>
                        <p class="card-text">Create a new invoice.</p>
                        <a href="create_invoice.php" class="btn btn-primary">Create Invoice</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body text-center">
                        <h5 class="card-title">Penawaran</h5>
                        <p class="card-text">Create a new penawaran (offer).</p>
                        <a href="create_penawaran.php" class="btn btn-success">Create Penawaran</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>