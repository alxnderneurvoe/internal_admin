<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}
function bulanRomawi($bulan)
{
    $romawi = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];
    return $romawi[(int) $bulan];
}

include '../config.php';
require_once '../vendor/autoload.php';

$query = $conn->query("SELECT MAX(id) as last_id FROM invoices");
$row = $query->fetch_assoc();
$lastId = $row['last_id'] ?? 0;
$newId = $lastId + 1;

// Buat nomor invoice
$bulanRomawi = bulanRomawi(date('n')); // Ambil bulan sekarang
$tahun = '2025'; // Atau date('Y') jika dinamis
$status = 'INV';

$nomorInvoice = $newId . '/' . $bulanRomawi . '/SSS/INV/' . $tahun;
// Fetch product options from the database
$product_options = '';
$product_query = "SELECT id, name, price FROM products ORDER BY name ASC";
$product_result = $conn->query($product_query);
while ($product = $product_result->fetch_assoc()) {
    $product_options .= '<option value="' . $product['id'] . '" data-name="' . htmlspecialchars($product['name']) . '" data-price="' . $product['price'] . '">' . htmlspecialchars($product['name']) . '</option>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_nik = mysqli_real_escape_string($conn, $_POST['client_nik']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $user_email = $_SESSION['email'];
    $rek = isset($_POST['rek']) && $_POST['rek'] == '1' ? 1 : 0;


    $user_query = "SELECT id FROM users WHERE email = '$user_email'";
    $result = $conn->query($user_query);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    $items = [];
    $subtotal = 0;

    if (isset($_POST['items'])) {
        $items = json_decode($_POST['items'], true);
        foreach ($items as &$item) {
            $qty = $item['qty'];
            $price = $item['price'];
            $discount = $item['discount'];
            $net = ($qty * $price) - ($qty * $price * ($discount / 100));
            $item['net'] = $net;
            $subtotal += $net;
        }
        unset($item);
    }

    $tax = $subtotal * 0.11;
    $shipping = isset($_POST['ongkir']) ? $_POST['ongkir'] : 0;
    $grand_total = $subtotal + $tax + $shipping;

    $insert_sql = "INSERT INTO invoices (user_id, invoice_number, client_name, address, subtotal, tax, shipping, grand_total, status, rek) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("isssddddsi", $user_id, $nomorInvoice, $client_name, $address, $subtotal, $tax, $shipping, $grand_total, $status, $rek);
    $stmt->execute();


    if ($stmt->affected_rows > 0) {
        echo '<div class="alert alert-success">Invoice berhasil disimpan ke database. Nomor Invoice: <strong>' . $nomorInvoice . '</strong></div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $invoice_id = $conn->insert_id; // ID invoice yang baru dibuat
    foreach ($items as $item) {
        $item_name = mysqli_real_escape_string($conn, $item['name']);
        $item_qty = $item['qty'];
        $item_price = $item['price'];
        $item_discount = $item['discount'];
        $item_unit = mysqli_real_escape_string($conn, $item['unit']);
        $item_net = $item['net'];

        $conn->query("INSERT INTO invoice_items (invoice_id, name, qty, price, discount, unit, net) 
        VALUES ('$invoice_id', '$item_name', '$item_qty', '$item_price', '$item_discount', '$item_unit', '$item_net')");
    }

}

// Fungsi terbilang sederhana
function terbilang($angka)
{
    $angka = abs($angka);
    $bilangan = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $hasil = "";
    if ($angka < 12) {
        $hasil = " " . $bilangan[$angka];
    } else if ($angka < 20) {
        $hasil = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $hasil = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $hasil = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $hasil = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $hasil = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $hasil = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $hasil = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    }
    return trim($hasil);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <title>Create Invoice</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="../dashboard.php">
            <img src="../asset/Logo.png" alt="" style="width: auto; height: 30px;">
        </a>
        <a class="navbar-brand" href="../dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i> PT Semesta Sistem Solusindo
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php"><i class="fas fa-fw fa-tachometer-alt">
                    </i> Dashboard</a></li>
            <li class="nav-item">
                <a class="nav-link" href="../create_letter.php">
                    <i class="fas fa-fw fa-file-invoice"></i> Create Letter
                </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="../files.php"><i class="fas fa-fw fa-folder"></i> File
                    Storage</a></li>
            <li class="nav-item">
                <a class="nav-link" href="../product/products.php">
                    <i class="fas fa-fw fa-folder"></i> Products
                </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-fw fa-sign-out-alt"></i>
                    Logout</a></li>
        </ul>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Create Invoice</h2>
        <form action="create_invoice.php" method="post" id="invoiceForm">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="client_name">Nama Pelanggan</label>
                    <input type="text" name="client_name" id="client_name" class="form-control"
                        placeholder="ex. Mulia Rahmatillah" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="client_nik">NIK/NPWP</label>
                    <input type="text" name="client_nik" id="client_nik" class="form-control"
                        placeholder="XXXX XXXX XXXX XXXX">
                </div>
                <div class="form-group col-md-3">
                    <label for="client_nik">No Invoice</label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-control"
                        value="<?= $nomorInvoice ?>" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-10">
                    <label for=" address" style="font-weight: bold;">Alamat</label>
                    <textarea name="address" id="address" class="form-control" placeholder="Alamat" required rows="2"
                        style="resize: vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label for="rek">Rekening</label>
                    <select name="rek" id="rek" class="form-control" required>
                        <option value="1">Kantor</option>
                        <option value="0">Pribadi</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#addItemModal">Tambah
                Item</button>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Item</th>
                            <th style="width: 10%">Qty</th>
                            <th style="width: 15%">Harga</th>
                            <th style="width: 10%">Disc.</th>
                            <th style="width: 15%">Net</th>
                            <th style="width: 20%">Total</th>
                        </tr>
                    </thead>
                    <tbody id="items_table_body"></tbody>
                </table>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card p-2">
                        <label for="subtotal">Sub Total</label>
                        <p class="font-weight-bold mb-0"><span id="subtotal">0</span></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-2">
                        <label for="tax">Pajak (11%)</label>
                        <p class="font-weight-bold mb-0"><span id="tax">0</span></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-2">
                        <label for="ongkir">Ongkir</label>
                        <input type="number" name="ongkir" id="ongkir" class="form-control" value="0"
                            oninput="updateGrandTotal()">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-2">
                        <label for="grand_total">Grand Total</label>
                        <p class="font-weight-bold mb-0"><span id="grand_total">0</span></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="items" id="items_input">
            <button type="submit" class="btn btn-primary btn-block">Create Document</button>
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
                        <select id="item_name" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php echo $product_options; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item_qty">Quantity</label>
                        <input type="number" id="item_qty" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="item_price">Price</label>
                        <input type="number" id="item_price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="item_unit">Satuan</label>
                        <select id="item_unit" class="form-control" required>
                            <option value="piece">Pcs</option>
                            <option value="meter">Meter</option>
                            <option value="unit">Unit</option>
                            <option value="unit">Batang</option>
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
        let items = [];

        function updateGrandTotal() {
            let subtotal = 0;
            $("#items_table_body tr").each(function (index) {
                const qty = parseFloat($(this).find(".qty").val());
                const price = parseFloat($(this).find(".price").val());
                const discount = parseFloat($(this).find(".discount").val());
                const net = (qty * price) - (qty * price * (discount / 100));
                const total = net;
                subtotal += total;
                $(this).find(".net").text("Rp. " + net.toLocaleString('id-ID'));
                $(this).find(".total").text("Rp. " + total.toLocaleString('id-ID'));

                // Update items array
                if (items[index]) {
                    items[index].qty = qty;
                    items[index].price = price;
                    items[index].discount = discount;
                    items[index].net = net;
                }
            });

            const tax = subtotal * 0.11;
            const shipping = parseFloat($('#ongkir').val());
            const grandTotal = subtotal + tax + shipping;

            $('#subtotal').text(subtotal.toLocaleString('id-ID'));
            $('#tax').text(tax.toLocaleString('id-ID'));
            $('#grand_total').text(grandTotal.toLocaleString('id-ID'));
        }

        // Harga otomatis terisi saat produk dipilih
        $('#item_name').on('change', function () {
            var selected = $(this).find('option:selected');
            var price = selected.data('price') || 0;
            $('#item_price').val(price);
        });

        $("#addItemButton").click(function () {
            const itemId = $("#item_name").val();
            const itemName = $("#item_name option:selected").data('name');
            const itemQty = parseInt($("#item_qty").val());
            const itemPrice = parseFloat($("#item_price").val());
            const itemDiscount = parseFloat($("#item_discount").val());
            const itemUnit = $("#item_unit").val();
            const net = (itemQty * itemPrice) - (itemQty * itemPrice * (itemDiscount / 100));

            itemCount++;
            items.push({
                id: itemId,
                name: itemName,
                qty: itemQty,
                price: itemPrice,
                discount: itemDiscount,
                unit: itemUnit,
                net: net
            });

            const row = `<tr>
                <td>${itemCount}</td>
                <td><input type="text" class="form-control" value="${itemName}" readonly></td>
                <td><input type="number" class="form-control qty" value="${itemQty}" onchange="updateGrandTotal()"></td>
                <td><input type="number" class="form-control price" value="${itemPrice}" onchange="updateGrandTotal()"></td>
                <td><input type="number" class="form-control discount" value="${itemDiscount}" onchange="updateGrandTotal()"></td>
                <td class="net">Rp. ${net.toLocaleString('id-ID')}</td>
                <td class="total">Rp. ${net.toLocaleString('id-ID')}</td>
            </tr>`;

            $("#items_table_body").append(row);
            updateGrandTotal();
            $('#addItemModal').modal('hide');
        });

        // Serialize items before submitting the form
        $("#invoiceForm").submit(function (e) {
            $("#items_input").val(JSON.stringify(items));
        });
    </script>
</body>

</html>