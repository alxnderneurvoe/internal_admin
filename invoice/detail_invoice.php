<?php
include '../config.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM invoices WHERE id = $id");
$invoice = $result->fetch_assoc();
if (!$invoice) {
    die("Invoice tidak ditemukan.");
}
?>

<h2>Detail Invoice</h2>
<p><strong>No Invoice:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?></p>
<p><strong>Nama:</strong> <?= htmlspecialchars($invoice['client_name']) ?></p>
<p><strong>Alamat:</strong> <?= htmlspecialchars($invoice['address']) ?></p>
<p><strong>Subtotal:</strong> Rp. <?= number_format($invoice['subtotal'], 0, ',', '.') ?></p>
<p><strong>Pajak:</strong> Rp. <?= number_format($invoice['tax'], 0, ',', '.') ?></p>
<p><strong>Grand Total:</strong> Rp. <?= number_format($invoice['grand_total'], 0, ',', '.') ?></p>
<a href="../view_letter.php">Kembali</a>
