<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';
require_once 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $invoice_number = mysqli_real_escape_string($conn, $_POST['invoice_numbers']);
    $user_email = $_SESSION['email'];

    $user_query = "SELECT id FROM users WHERE email = '$user_email'";
    $result = $conn->query($user_query);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    $items = [];
    $subtotal = 0;

    if (isset($_POST['items'])) {
        $items = json_decode($_POST['items'], true);
        foreach ($items as $item) {
            $qty = $item['qty'];
            $price = $item['price'];
            $discount = $item['discount'];
            $net = ($qty * $price) - ($qty * $price * ($discount / 100));
            $subtotal += $net;
        }
    }

    $tax = $subtotal * 0.11;
    $shipping = isset($_POST['ongkir']) ? $_POST['ongkir'] : 0;
    $grand_total = $subtotal + $tax + $shipping;

    $insert_sql = "INSERT INTO invoices (user_id, client_name, address, invoice_number, subtotal, tax, shipping, grand_total) 
                   VALUES ('$user_id', '$client_name', '$address', '$invoice_number', '$subtotal', '$tax', '$shipping', '$grand_total')";

    if ($conn->query($insert_sql) === TRUE) {
        generate_pdf($client_name, $subtotal, $invoice_number, $items, $tax, $grand_total);
    } else {
        echo "Error: " . $conn->error;
    }
}

function generate_pdf($client_name, $subtotal, $invoice_number, $items, $tax, $grand_total)
{
    try {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetTitle('Invoice');

        // Set font for the document
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Client Name: ' . $client_name, 0, 1);
        $pdf->Cell(0, 10, 'Invoice Number: ' . $invoice_number, 0, 1);
        $pdf->Cell(0, 10, 'Subtotal: Rp. ' . number_format($subtotal, 0, ',', '.'), 0, 1);
        $pdf->Cell(0, 10, 'Tax (11%): Rp. ' . number_format($tax, 0, ',', '.'), 0, 1);
        $pdf->Cell(0, 10, 'Grand Total: Rp. ' . number_format($grand_total, 0, ',', '.'), 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Items:', 0, 1);

        foreach ($items as $item) {
            $pdf->Cell(0, 10, $item['name'] . ' - Qty: ' . $item['qty'] . ' - Price: Rp. ' . number_format($item['price'], 0, ',', '.') . ' - Discount: ' . $item['discount'] . '% - Net: Rp. ' . number_format($item['net'], 0, ',', '.'), 0, 1);
        }

        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $file_name = 'document_' . $client_name . '.pdf';
        $pdf->Output(__DIR__ . '/uploads/' . $file_name, 'F');
        echo 'Document created successfully. You can <a href="uploads/' . $file_name . '" download>download it here</a>';
    } catch (Exception $e) {
        echo 'PDF Generation Error: ' . $e->getMessage();
    }
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
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-fw fa-tachometer-alt">
                    </i> Dashboard</a></li>
            <li class="nav-item">
                <a class="nav-link" href="create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="files.php"><i class="fas fa-fw fa-folder"></i> File
                    Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i>
                    Logout</a></li>
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
                                <input type="text" name="client_name" id="client_name" class="form-control"
                                    placeholder="ex. Mulia Rahmatillah" required>
                            </div>
                        </th>
                        <th>
                            <div class="form-group">
                                <label for="client_name">NIK/NPWP</label>
                                <input type="number" name="client_name" id="client_name" class="form-control"
                                    placeholder="XXXX XXXX XXXX XXXX" required>
                            </div>
                        </th>
                        <th>
                            <div class="form-group">
                                <label for="invoice_numbers">No Invoice</label>
                                <input type="text" name="invoice_numbers" id="invoice_numbers" class="form-control"
                                    placeholder="XX/MM/SSS/INV/2025" required>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
            <div class="form-group" style="width: 627px">
                <label for="address" style="font-weight: bold;">Alamat</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Alamat" required>
            </div>

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addItemModal">Tambah
                Item</button>
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
                            <div class="box-container">
                                <label for="subtotal">Sub Total</label>
                                <p><span id="subtotal">0</span></p>
                            </div>
                        </th>
                        <th>
                            <div class="box-container">
                                <label for="pajak">Pajak (11%)</label>
                                <p><span id="tax">0</span></p>
                            </div>
                        </th>
                        <th>
                            <div class="box-container">
                                <label for="ongkir">Ongkir</label>
                                <input type="number" name="ongkir" id="ongkir" class="form-control" value="0"
                                    oninput="updateGrandTotal()">
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <div class="box-container">
                                <label for="grand_total">Grand Total</label>
                                <p><span id="grand_total">0</span></p>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Create Document</button>
        </form>
    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
        aria-hidden="true">
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
                        <input type="text" id="item_name" class="form-control" placeholder="Item name" value="Pipa"
                            required>
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
                        <label for="item_unit">Satuan</label>
                        <select id="item_unit" class="form-control" required>
                            <option value="piece">Piece</option>
                            <option value="meter">Meter</option>
                            <option value="unit">Unit</option>
                            <option value="set">Set</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item_discount">Discount (%)</label>
                        <input type="number" id="item_discount" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addItemButton">Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/js/sb-admin-2.min.js"></script>
    <script>
        let itemCount = 0;

        function updateGrandTotal() {
            let subtotal = 0;
            $("#items_table_body tr").each(function() {
                const qty = parseFloat($(this).find(".qty").val());
                const price = parseFloat($(this).find(".price").val());
                const discount = parseFloat($(this).find(".discount").val());
                const net = (qty * price) - (qty * price * (discount / 100));
                const total = net;
                subtotal += total;
                $(this).find(".net").text("Rp. " + net.toLocaleString('id-ID'));
                $(this).find(".total").text("Rp. " + total.toLocaleString('id-ID'));
            });

            const tax = subtotal * 0.11;
            const shipping = parseFloat($('#ongkir').val());
            const grandTotal = subtotal + tax + shipping;

            $('#subtotal').text(subtotal.toLocaleString('id-ID'));
            $('#tax').text(tax.toLocaleString('id-ID'));
            $('#grand_total').text(grandTotal.toLocaleString('id-ID'));
        }

        $("#addItemButton").click(function() {
            const itemName = $("#item_name").val();
            const itemQty = parseInt($("#item_qty").val());
            const itemPrice = parseFloat($("#item_price").val());
            const itemDiscount = parseFloat($("#item_discount").val());

            itemCount++;

            const row = `<tr>
                <td>${itemCount}</td>
                <td><input type="text" class="form-control" value="${itemName}" readonly></td>
                <td><input type="number" class="form-control qty" value="${itemQty}" onchange="updateGrandTotal()"></td>
                <td><input type="number" class="form-control price" value="${itemPrice}" onchange="updateGrandTotal()"></td>
                <td><input type="number" class="form-control discount" value="${itemDiscount}" onchange="updateGrandTotal()"></td>
                <td class="net">Rp. 0</td>
                <td class="total">Rp. 0</td>
            </tr>`;

            $("#items_table_body").append(row);
            updateGrandTotal();
            $('#addItemModal').modal('hide');
        });
    </script>
</body>

</html>
