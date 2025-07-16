<?php
include 'config.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}
$id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'];
    $address = $_POST['address'];
    $shipping = floatval($_POST['shipping']);

    $conn->query("UPDATE invoices SET client_name='$client_name', address='$address', shipping='$shipping' WHERE id=$id");
    header("Location: view_invoice.php?id=$id");
    exit();
}

$result = $conn->query("SELECT * FROM invoices WHERE id = $id");
$invoice = $result->fetch_assoc();
?>

<h2>Edit Invoice</h2>
<form method="POST">
    <label>Nama:</label><input name="client_name" value="<?= $invoice['client_name'] ?>"><br>
    <label>Alamat:</label><textarea name="address"><?= $invoice['address'] ?></textarea><br>
    <label>Ongkir:</label><input name="shipping" value="<?= $invoice['shipping'] ?>"><br>
    <button type="submit">Simpan</button>
</form>
