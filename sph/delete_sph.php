<?php
include '../config.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM invoices WHERE id = $id");
    header("Location: view_invoice.php");
    exit();
} else {
    echo "ID tidak valid.";
}
