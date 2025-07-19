<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

$id = intval($_GET['id'] ?? 0);
if (!$id)
    die("ID tidak valid.");

// Ambil invoice
$invoice_result = $conn->query("SELECT * FROM invoices WHERE id = $id");
$invoice = $invoice_result->fetch_assoc();
if (!$invoice)
    die("Data tidak ditemukan.");

// Ambil item
$items_result = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $id");
$items = [];
while ($row = $items_result->fetch_assoc())
    $items[] = $row;

// Ambil produk
$product_options = '';
$product_query = "SELECT id, name, price FROM products ORDER BY name ASC";
$product_result = $conn->query($product_query);
while ($product = $product_result->fetch_assoc()) {
    $product_options .= '<option value="' . $product['id'] . '" data-name="' . htmlspecialchars($product['name']) . '" data-price="' . $product['price'] . '">' . htmlspecialchars($product['name']) . '</option>';
}

// Update jika POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'];
    $client_nik = $_POST['client_nik'];
    $address = $_POST['address'];
    $delivery_time = $_POST['delivery_time'];
    $delivery_unit = $_POST['delivery_unit'];
    $shipping = floatval($_POST['ongkir']);
    $items = json_decode($_POST['items'], true);

    $subtotal = 0;
    foreach ($items as &$item) {
        $qty = $item['qty'];
        $price = $item['price'];
        $discount = $item['discount'];
        $net = ($qty * $price) - ($qty * $price * ($discount / 100));
        $item['net'] = $net;
        $subtotal += $net;
    }
    unset($item);

    $tax = $subtotal * 0.11;
    $grand_total = $subtotal + $tax + $shipping;

    $stmt = $conn->prepare("UPDATE invoices SET client_name=?, address=?, client_nik=?, delivery_time=?, delivery_unit=?, subtotal=?, tax=?, shipping=?, grand_total=? WHERE id=?");
    $stmt->bind_param("sssssssddi", $client_name, $address, $client_nik, $delivery_time, $delivery_unit, $subtotal, $tax, $shipping, $grand_total, $id);
    $stmt->execute();

    // Update items: hapus lama, insert baru
    $conn->query("DELETE FROM invoice_items WHERE invoice_id = $id");
    foreach ($items as $item) {
        $conn->query("INSERT INTO invoice_items (invoice_id, id_product, name, qty, price, discount, unit, net) VALUES (
            $id, '{$item['id']}', '{$item['name']}', {$item['qty']}, {$item['price']}, {$item['discount']}, '{$item['unit']}', {$item['net']})");
    }

    echo "<script>alert('Berhasil diperbarui.'); window.location='../view_letter.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../asset/Logo.png">
    <title>Edit Penawaran</title>
    <link href="asset/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand navbar-light bg-light shadow mb-4">
        <a class="navbar-brand" href="../dashboard.php">
            <img src="../asset/Logo.png" alt="Logo" style="height: 30px;">
        </a>
        <a class="navbar-brand" href="../dashboard.php">PT Semesta Sistem Solusindo</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../create_letter.php">Create Letter</a></li>
            <li class="nav-item"><a class="nav-link" href="../files.php">File Storage</a></li>
            <li class="nav-item"><a class="nav-link" href="../product/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="container mt-4">
        <h2>Edit Penawaran</h2>
        <form method="POST" id="invoiceForm">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="client_name" class="form-control"
                        value="<?= htmlspecialchars($invoice['client_name']) ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>NIK/NPWP</label>
                    <input type="text" name="client_nik" class="form-control"
                        value="<?= htmlspecialchars($invoice['client_nik']) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>No Invoice</label>
                    <input type="text" class="form-control" value="<?= $invoice['invoice_number'] ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-9">
                    <label>Alamat</label>
                    <textarea name="address" class="form-control"
                        rows="2"><?= htmlspecialchars($invoice['address']) ?></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>Waktu Pengiriman</label>
                    <div class="input-group">
                        <input type="text" name="delivery_time" class="form-control"
                            value="<?= $invoice['delivery_time'] ?>">
                        <select name="delivery_unit" class="form-control">
                            <option value="hari" <?= $invoice['delivery_unit'] == 'hari' ? 'selected' : '' ?>>Hari</option>
                            <option value="minggu" <?= $invoice['delivery_unit'] == 'minggu' ? 'selected' : '' ?>>Minggu
                            </option>
                            <option value="bulan" <?= $invoice['delivery_unit'] == 'bulan' ? 'selected' : '' ?>>Bulan
                            </option>
                        </select>
                    </div>
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
                            <th style="width: 5%">Aksi</th>
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
            <button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 45px;">Update
                Penawaran</button>
        </form>
    </div>

    <!-- Modal Tambah Item -->
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
                    <button type="button" class="btn btn-primary" id="addItemBtn">Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sb-admin-2@4.0.3/dist/js/sb-admin-2.min.js"></script>
    <script>
        let items = <?= json_encode($items) ?>;
        function renderItems() {
            $('#items_table_body').empty();
            items.forEach((item, i) => {
                const net = (item.qty * item.price) - (item.qty * item.price * item.discount / 100);
                $('#items_table_body').append(`
            <tr>
                <td>${i + 1}</td>
                <td>${item.name}</td>
                <td><input type="number" class="form-control" value="${item.qty}" onchange="items[${i}].qty=this.value; updateGrandTotal()"></td>
                <td><input type="number" class="form-control" value="${item.price}" onchange="items[${i}].price=this.value; updateGrandTotal()"></td>
                <td><input type="number" class="form-control" value="${item.discount}" onchange="items[${i}].discount=this.value; updateGrandTotal()"></td>
                <td class="net">Rp. ${net.toLocaleString('id-ID')}</td>
                <td class="total">Rp. ${net.toLocaleString('id-ID')}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${i})">&times;</button>
                </td>
            </tr>`);
            });
            updateGrandTotal();
        }
        $('#item_name').on('change', function () {
            const price = $('option:selected', this).data('price');
            $('#item_price').val(price);
        });

        function removeItem(index) {
            items.splice(index, 1); // Hapus dari array
            renderItems(); // Render ulang
        }

        function updateGrandTotal() {
            let subtotal = 0;
            items.forEach(item => {
                const net = (item.qty * item.price) - (item.qty * item.price * item.discount / 100);
                subtotal += net;
            });
            const tax = subtotal * 0.11;
            const shipping = parseFloat($('#ongkir').val() || 0);
            const grandTotal = subtotal + tax + shipping; // ✅ definisikan grandTotal

            $('#subtotal').text("Rp. " + subtotal.toLocaleString('id-ID'));
            $('#tax').text("Rp. " + tax.toLocaleString('id-ID'));
            $('#grand_total').text("Rp. " + grandTotal.toLocaleString('id-ID'));
        }

        $('#addItemBtn').click(() => {
            const name = $('#item_name option:selected').text();
            const id = $('#item_name').val();
            const qty = parseInt($('#item_qty').val());
            const price = parseFloat($('#item_price').val());
            const discount = parseFloat($('#item_discount').val());
            const unit = $('#item_unit').val();
            items.push({ id, name, qty, price, discount, unit });
            $('#addItemModal').modal('hide');
            renderItems();
        });

        $('#invoiceForm').submit(function () {
            $('#items_input').val(JSON.stringify(items));
        });

        renderItems();
    </script>
</body>

</html>