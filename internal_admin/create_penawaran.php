<?php
    // Start the session
    session_start();

    // Check if the user is logged in
    if (! isset($_SESSION['email'])) {
        header('Location: login.php');
        exit();
    }

    // Include the database configuration file
    include 'config.php';

    // Include TCPDF library
    require_once 'vendor/autoload.php';

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and assign form data to variables
        $client_name   = mysqli_real_escape_string($conn, $_POST['client_name']);
        $amount        = mysqli_real_escape_string($conn, $_POST['amount']);
        $document_type = $_POST['document_type']; // Invoice or Penawaran
        $user_email    = $_SESSION['email'];

        // Get user ID from email
        $user_query = "SELECT id FROM users WHERE email = '$user_email'";
        $result     = $conn->query($user_query);
        $user       = $result->fetch_assoc();
        $user_id    = $user['id'];

        // Define the query for inserting data based on document type
        if ($document_type == 'invoice') {
            // For Invoice, we need an invoice number
            $invoice_number     = mysqli_real_escape_string($conn, $_POST['invoice_number']);
            $insert_invoice_sql = "INSERT INTO invoices (user_id, client_name, amount, invoice_number)
                               VALUES ('$user_id', '$client_name', '$amount', '$invoice_number')";
        } else {
            // For Penawaran, we need a validity period
            $validity_period    = mysqli_real_escape_string($conn, $_POST['validity_period']);
            $insert_invoice_sql = "INSERT INTO penawaran (user_id, client_name, amount, validity_period)
                               VALUES ('$user_id', '$client_name', '$amount', '$validity_period')";
        }

        // Execute the query and check for success
        if ($conn->query($insert_invoice_sql) === true) {
            // Generate the PDF document
            generate_pdf($client_name, $amount, $invoice_number ?? null, $validity_period ?? null, $document_type);
        } else {
            echo "Error: " . $conn->error;
        }
    }

    function generate_pdf($client_name, $amount, $invoice_number = null, $validity_period = null, $document_type)
    {
        // Create new PDF document
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Set document information
        $pdf->SetTitle('Invoice or Penawaran');

        // Add title
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, $document_type === 'invoice' ? 'Invoice' : 'Penawaran', 0, 1, 'C');

        // Add Client Name and Amount
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Client Name: ' . $client_name, 0, 1);
        $pdf->Cell(0, 10, 'Amount: ' . $amount, 0, 1);

        if ($document_type === 'invoice') {
            // If it's an invoice, include the invoice number
            $pdf->Cell(0, 10, 'Invoice Number: ' . $invoice_number, 0, 1);
        } else {
            // If it's a Penawaran, include the validity period
            $pdf->Cell(0, 10, 'Validity Period (days): ' . $validity_period, 0, 1);
        }

        if (! file_exists('uploads')) {
            mkdir('uploads', 0777, true); // Create uploads directory if it doesn't exist
        }

        // Output PDF document to the browser or save it to a file
        $file_name = 'document_' . $client_name . '.pdf';
        $pdf->Output(__DIR__ . '/uploads/' . $file_name . 'F');

        // Optionally, provide the link to download the generated file
        echo 'Document created successfully. You can <a href="uploads/' . $file_name . '" download>download it here</a>';
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice or Penawaran</title>
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="nav-item">
                <a class="nav-link" href="create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>        <li class="nav-item"><a class="nav-link" href="files.php"><i class="fas fa-fw fa-folder"></i> File Storage</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

<!-- Form to create an Invoice or Penawaran -->
<div class="container">
    <h2>Create Invoice or Penawaran</h2>
    <form action="create_letter.php" method="post">
        <!-- Client Name -->
        <div class="form-group">
            <label for="client_name">Client Name</label>
            <input type="text" name="client_name" id="client_name" class="form-control" placeholder="Client Name" required>
        </div>

        <!-- Amount -->
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" required>
        </div>

        <!-- Document Type (Invoice or Penawaran) -->
        <div class="form-group">
            <label for="document_type">Document Type</label>
            <select name="document_type" id="document_type" class="form-control" required onchange="toggleFields()">
                <option value="invoice">Invoice</option>
                <option value="penawaran">Penawaran</option>
            </select>
        </div>

        <!-- Invoice Number (Only show if Invoice is selected) -->
        <div class="form-group" id="invoice_number_group" style="display: none;">
            <label for="invoice_number">Invoice Number</label>
            <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="Invoice Number">
        </div>

        <!-- Validity Period (Only show if Penawaran is selected) -->
        <div class="form-group" id="validity_period_group" style="display: none;">
            <label for="validity_period">Validity Period (days)</label>
            <input type="number" name="validity_period" id="validity_period" class="form-control" placeholder="Validity Period">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Document</button>
    </form>
</div>

<!-- JavaScript to Toggle Fields -->
<script>
    function toggleFields() {
        const documentType = document.getElementById('document_type').value;
        const invoiceNumberGroup = document.getElementById('invoice_number_group');
        const validityPeriodGroup = document.getElementById('validity_period_group');

        if (documentType === 'invoice') {
            invoiceNumberGroup.style.display = 'block';
            validityPeriodGroup.style.display = 'none';
        } else {
            invoiceNumberGroup.style.display = 'none';
            validityPeriodGroup.style.display = 'block';
        }
    }
    // Initialize fields based on the selected document type
    toggleFields();
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
