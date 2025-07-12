<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

// Fetch user data
$email = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE email = '$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch files uploaded by the user
$files_sql = "SELECT * FROM files WHERE user_id = '$user_id'";
$files_result = $conn->query($files_sql);

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $upload_dir = 'uploads/';  // Directory to store uploaded files
    $success_message = '';
    $error_message = '';

    // Loop through each file
    foreach ($_FILES['files']['name'] as $key => $file_name) {
        $file_tmp_name = $_FILES['files']['tmp_name'][$key];
        $file_size = $_FILES['files']['size'][$key];
        $file_error = $_FILES['files']['error'][$key];
        $file_path = $upload_dir . basename($file_name);

        // Check for upload errors
        if ($file_error === UPLOAD_ERR_OK) {
            // Check file size (5MB limit)
            if ($file_size <= 5242880) {
                // Check if file already exists
                if (!file_exists($file_path)) {
                    // Move uploaded file to the server
                    if (move_uploaded_file($file_tmp_name, $file_path)) {
                        // Insert file data into the database
                        $upload_date = date('Y-m-d H:i:s');
                        $insert_sql = "INSERT INTO files (user_id, file_name, file_path, upload_date) 
                                       VALUES ('$user_id', '$file_name', '$file_path', '$upload_date')";
                        if ($conn->query($insert_sql)) {
                            $success_message .= "File '$file_name' uploaded successfully!<br>";
                            header('Location: files.php');
                            exit();
                        } else {
                            $error_message .= "Error saving file data for '$file_name' in the database.<br>";
                        }
                    } else {
                        $error_message .= "Error moving uploaded file '$file_name'.<br>";
                    }
                } else {
                    $error_message .= "File '$file_name' already exists.<br>";
                }
            } else {
                $error_message .= "File '$file_name' exceeds the 5MB size limit.<br>";
            }
        } else {
            $error_message .= "Error during file upload for '$file_name'.<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>File Storage</title>
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- External CSS File -->
</head>

<body>

    <!-- Top Navigation Bar (List Bar) -->
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

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="content-wrapper">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- File Upload Form -->
            <h3>Upload New Files</h3>
            <form action="files.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="files">Choose Files</label>
                    <style>
                        /* Menambahkan tinggi dan padding pada input file */
                        #files {
                            padding: 10px;
                            height: 50px;
                        }
                    </style>
                    <!-- Add 'multiple' attribute to allow selecting multiple files -->
                    <input type="file" class="form-control" id="files" name="files[]" multiple required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
                <hr class="double">
            </form>

            <!-- Display Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success mt-3">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- File Storage Section -->
            <h3>Uploaded Files</h3>

            <!-- Filter Section -->
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search by file name"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="start_date"
                            value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" name="end_date"
                            value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <?php
                // Get the search, start_date, and end_date parameters from the GET request
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                // Build the SQL query with filters
                $query = "SELECT * FROM files WHERE 1"; // Base query
                
                // Add search filter if provided
                if (!empty($search)) {
                    $query .= " AND file_name LIKE '%" . $conn->real_escape_string($search) . "%'";
                }

                // Add date range filter if provided
                if (!empty($start_date)) {
                    $query .= " AND upload_date >= '" . $conn->real_escape_string($start_date) . "'";
                }
                if (!empty($end_date)) {
                    $query .= " AND upload_date <= '" . $conn->real_escape_string($end_date) . "'";
                }

                // Execute the query
                $files_result = $conn->query($query);

                // Display the files
                while ($file = $files_result->fetch_assoc()) {
                    ?>
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

    <!-- jQuery, Bootstrap, SB Admin 2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/js/sb-admin-2.min.js"></script>

    <script src="scripts.js"></script> <!-- External JS File -->
</body>

</html>