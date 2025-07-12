<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

$email = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE email = '$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_id = $user['id'];

$files_sql = "SELECT * FROM files";
$files_result = $conn->query($files_sql);

$sql = "SELECT COUNT(*) AS total_invoices FROM invoices";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_invoices = $row['total_invoices'];
} else {
    $total_invoices = 0;
}

$sql = "SELECT COUNT(*) AS total_files FROM files";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_files = $row['total_files'];
} else {
    $total_files = 0;
}

$sql = "SELECT COUNT(*) AS total_product FROM products";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_product = $row['total_product'];
} else {
    $total_product = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/style.css"> <!-- External CSS File -->
</head>

<body>
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

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="content-wrapper">

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h3>Welcome, <?php echo $email; ?></h3>

            <!-- Dashboard Statistics -->
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total
                                        Invoices</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_invoices; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="view_invoice.php" class="btn btn-primary btn-lg">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total
                                        Earnings</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. 0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Files
                                        Uploaded</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $total_files; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="files.php" class="btn btn-primary btn-lg">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text- text-uppercase mb-1">Produk</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $total_product; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="product/products.php" class="btn btn-primary btn-lg">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- File Storage Section -->
            <h3>File Storage</h3>
            <div class="row">
                <?php while ($file = $files_result->fetch_assoc()) { ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary"><?php echo $file['file_name']; ?></h6>
                            </div>
                            <div class="card-body">
                                <p>Uploaded: <?php echo $file['upload_date']; ?></p>
                                <p><a href="<?php echo $file['file_path']; ?>" target="_blank">Download</a></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>

</html>