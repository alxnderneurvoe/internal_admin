<?php
    session_start();
    if (! isset($_SESSION['email'])) {
        header('Location: login.php');
        // exit();
    }

    include 'config.php';
    require_once 'vendor/autoload.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $client_name   = mysqli_real_escape_string($conn, $_POST['client_name']);
        $amount        = mysqli_real_escape_string($conn, $_POST['amount']);
        $document_type = $_POST['document_type']; 
        $user_email    = $_SESSION['email'];

        $user_query = "SELECT id FROM users WHERE email = '$user_email'";
        $result     = $conn->query($user_query);
        $user       = $result->fetch_assoc();
        $user_id    = $user['id'];
        
        if ($document_type == 'invoice') {
            $invoice_number     = mysqli_real_escape_string($conn, $_POST['invoice_number']);
            $insert_invoice_sql = "INSERT INTO invoices (user_id, client_name, amount, invoice_number)
                               VALUES ('$user_id', '$client_name', '$amount', '$invoice_number')";
        } else {
            $validity_period    = mysqli_real_escape_string($conn, $_POST['validity_period']);
            $insert_invoice_sql = "INSERT INTO penawaran (user_id, client_name, amount, validity_period)
                               VALUES ('$user_id', '$client_name', '$amount', '$validity_period')";
        }

        
        if ($conn->query($insert_invoice_sql) === true) {
            generate_pdf($client_name, $amount, $invoice_number ?? null, $validity_period ?? null, $document_type);
        } else {
            echo "Error: " . $conn->error;
        }
    }

    function generate_pdf($client_name, $amount, $invoice_number = null, $validity_period = null, $document_type)
    {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetTitle('Invoice or Penawaran');
        
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, $document_type === 'invoice' ? 'Invoice' : 'Penawaran', 0, 1, 'C');
        
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Client Name: ' . $client_name, 0, 1);
        $pdf->Cell(0, 10, 'Amount: ' . $amount, 0, 1);

        if ($document_type === 'invoice') {
            $pdf->Cell(0, 10, 'Invoice Number: ' . $invoice_number, 0, 1);
        } else {
            $pdf->Cell(0, 10, 'Validity Period (days): ' . $validity_period, 0, 1);
        }

        if (! file_exists('uploads')) {
            mkdir('uploads', 0777, true); 
        }
        
        $file_name = 'document_' . $client_name . '.pdf';
        $pdf->Output(__DIR__ . '/uploads/' . $file_name . 'F');
        
        echo 'Document created successfully. You can <a href="uploads/' . $file_name . '" download>download it here</a>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">
    <title>Create Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.table tbody tr td {
        vertical-align: middle;
    } </style>
</head>

<body>
    <style>
        .navbar{
            margin-left: 0px;
            }
            .table tbody tr td{
                vertical-align: middle;
            }
    </style>

    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="dashboard.php">
            <img src="asset/Logo.png" alt="" style="width: auto; height: 30px;">
        </a>
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i> PT Semesta Sistem Solusindo
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt">
                </i> Dashboard</a></li>
            <li class="nav-item">
                <a class="nav-link" href="create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>        
            <li class="nav-item"><a class="nav-link" href="files.php"><i class="fas fa-fw fa-folder"></i> File Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Create Invoice</h2>
        <form action="create_letter.php" method="post">
            <table>
                <tbody>
                    <tr>
                        <th> 
                            <div class="form-group">
                                <label for="client_name">Nama Pelanggan</label>
                                <input type="text" name="client_name" id="client_name" class="form-control" placeholder="ex. Mulia Rahmatillah" required>
                            </div>
                        </th>
                        <th>
                            <div class="form-group">
                                <label for="client_name">NIK/NPWP</label>
                                <input type="number" name="client_name" id="client_name" class="form-control" placeholder="XXXX XXXX XXXX XXXX" 
                                    required oninput="formatNIKNPWP(event)">
                            </div>
                        </th>
                        <th>
                            <div class="form-group">
                                <label for="invoice_numbers">No Invoice</label>
                                <input type="text" name="invoice_numbers" id="invoice_numbers" class="form-control" placeholder="XX/MM/SSS/INV/2025" required>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
            <div class="form-group" style="width: 627px">
                <label for="address" style="font-weight: bold;">Alamat</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Alamat" required>
            </div>
        
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addItemModal">Tambah Item</button>
            <p></p>
            <div>
                <table class="table table-bordered" style=" width: 900px; padding-left: -200px;">
                    <thead>
                        <tr>
                            <th style="width: 1%">No</th>
                            <th style="width: 25%">Item</th>
                            <th style="width: 15%">Qty</th>
                            <th style="width: 18%">Harga</th>
                            <th style="width: 1%">Disc.</th>
                            <th style="width: 20%">Net</th>
                            <th style="width: 25%">Total</th>
                        </tr>
                    </thead>
                    <tbody id="items_table_body"></tbody>
                </table>
            </div>
    <table>
        <tbody>
            <tr>
                <th>
                    <label for="subtotal">Sub Total</label>
                    <p><span id="subtotal">0</span></p>
                </th>
                <th>
                    <div class="form-group">
                        <label for="pajak">Pajak (11%)</label>
                        <p><span id="tax">0</span></p>
                    </div> 
                </th>
                <th>
                    <div class="form-group">
                        <label for="ongkir">Ongkir</label>
                        <input type="number" name="ongkir" id="ongkir" class="form-control" value="0" oninput="updateGrandTotal()">
                    </div> 
                </th>
            </tr>
            <tr>
                <th colspan="3">
                    <div class="form-group">
                        <label for="grand_total">Grand Total</label>
                        <input type="number" name="grand_total" id="grand_total" class="form-control" style="background-color: grey; opacity: calc(0.3); color: black;" readonly oninput="updateGrandTotal()">
                    </div> 
                </th>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Create Document</button>
    </form>
    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
            <div class="form-group">
            <label for="item_name">Item</label>
            <input type="text" id="item_name" class="form-control" placeholder="Item name" value="Pipa" required>
            </div>
            <div class="form-group">
            <label for="item_qty">Quantity</label>
            <input type="number" id="item_qty" class="form-control" value="10" required>
            </div>
            <div class="form-group">
            <label for="item_price">Price</label>
            <input type="number" id="item_price" class="form-control" value="10000" required>
            </div>
            <div class="form-group">
            <label for="item_discount">Discount (%)</label>
            <input type="number" id="item_discount" class="form-control" value="0">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="save_item">Save Item</button>
        </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('save_item').addEventListener('click', function() {
            var itemName = document.getElementById('item_name').value;
            var qty = document.getElementById('item_qty').value;
            var price = document.getElementById('item_price').value;
            var discount = document.getElementById('item_discount').value;

            if (!itemName || !qty || !price) {
            alert("Please fill in all fields");
            return; 
            }

            var tableBody = document.getElementById('items_table_body');
            var newRow = tableBody.insertRow();

            var cell1 = newRow.insertCell(0); 
            var cell2 = newRow.insertCell(1); 
            var cell3 = newRow.insertCell(2); 
            var cell4 = newRow.insertCell(3);
            var cell5 = newRow.insertCell(4);
            var cell6 = newRow.insertCell(5);
            var cell7 = newRow.insertCell(6);

            var rowIndex = tableBody.rows.length;
            cell1.innerHTML = rowIndex; 
            cell2.innerHTML = itemName; 
            cell3.innerHTML = qty; 
            cell4.innerHTML = price; 
            cell5.innerHTML = discount; 
            var net = (qty * price) - (qty * price * (discount / 100)); 
            var total = net; 
            cell6.innerHTML = net.toFixed(0);
            cell7.innerHTML = total.toFixed(0); 

            $('#addItemModal').modal('hide');

            document.getElementById('item_name').value = '';
            document.getElementById('item_qty').value = '';
            document.getElementById('item_price').value = '';
            document.getElementById('item_discount').value = '0';

            updateTotal();
    });

    function updateTotal() {
            var tableBody = document.getElementById('items_table_body');
            var rows = tableBody.rows;
            var subtotal = 0;

            for (var i = 0; i < rows.length; i++) {
                subtotal += parseFloat(rows[i].cells[6].innerText);  // Total price per item (net price)
            }

            var taxRate = 0.11;  // 10% pajak
            var tax = subtotal * taxRate;

            var grandtotal = subtotal + tax + shipping;

            document.getElementById('subtotal').innerText = subtotal.toFixed(0);
            document.getElementById('tax').innerText = tax.toFixed(0);
            document.getElementById('grandtotal').innerText = grandtotal.toFixed(0);
        }
    </script>

</body>
</html>

