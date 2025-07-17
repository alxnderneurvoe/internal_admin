<?php
include '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID invoice tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE invoices SET client_name=?, address=? WHERE id=?");
    $stmt->bind_param("ssi", $client_name, $address, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Invoice berhasil diupdate.'); window.location='../view_letter.php';</script>";
    } else {
        echo "Gagal update.";
    }
    exit();
}

$stmt = $conn->prepare("SELECT * FROM invoices WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<form method="POST">
    <label>Nama Klien</label>
    <input name="client_name" class="form-control" value="<?= htmlspecialchars($data['client_name']) ?>">
    <label>Alamat</label>
    <textarea name="address" class="form-control"><?= htmlspecialchars($data['address']) ?></textarea>
    <button class="btn btn-success mt-2">Simpan</button>
</form>
<a href="../view_letter.php" class="btn btn-secondary mt-2">Kembali</a>