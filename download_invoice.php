<?php
include 'config.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM invoices WHERE id = $id");
$invoice = $result->fetch_assoc();
if (!$invoice) {
    die("Data tidak ditemukan.");
}

header("Content-Disposition: attachment; filename=invoice_{$invoice['invoice_number']}.pdf");
header("Content-Type: doc/pdf");

echo "<h1>Invoice: {$invoice['invoice_number']}</h1>";
echo "<p>Nama: {$invoice['client_name']}</p>";
echo "<p>Alamat: {$invoice['address']}</p>";
echo "<p>Total: Rp. " . number_format($invoice['grand_total'], 0, ',', '.') . "</p>";
?>
