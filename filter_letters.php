<?php
session_start();
include 'config.php';

$user_email = $_SESSION['email'];
$user_query = "SELECT id FROM users WHERE email = '$user_email'";
$result = $conn->query($user_query);
$user = $result->fetch_assoc();
$user_id = $user['id'];

$months = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$sql = "SELECT id, invoice_number AS number, client_name, grand_total, status, created_at FROM invoices WHERE user_id = '$user_id'";

if (!empty($search)) {
    $search_terms = explode(' ', $search);
    foreach ($search_terms as $term) {
        $term = $conn->real_escape_string($term);
        $sql .= " AND client_name LIKE '%$term%'";
    }
}
if (!empty($status_filter)) {
    $sql .= " AND status = '$status_filter'";
}
if (!empty($start_date)) {
    $sql .= " AND DATE(created_at) >= '$start_date'";
}
if (!empty($end_date)) {
    $sql .= " AND DATE(created_at) <= '$end_date'";
}
$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $type = strtoupper($row['status']);
    switch ($type) {
        case 'INV':
            $folder = 'invoice';
            $prefix = 'invoice';
            break;
        case 'SPH':
            $folder = 'sph';
            $prefix = 'sph';
            break;
        default:
            $folder = 'other';
            $prefix = 'other';
            break;
    }

    $date = new DateTime($row['created_at']);
    $formatted_date = $date->format('d') . ' ' . $months[(int) $date->format('m')] . ' ' . $date->format('Y');

    echo '<tr>
        <td>' . htmlspecialchars($row['number']) . '</td>
        <td>' . htmlspecialchars($row['client_name']) . '</td>
        <td>Rp. ' . number_format($row['grand_total'], 0, '', '.') . '</td>
        <td>' . $formatted_date . '</td>
        <td>' . $type . '</td>
        <td>
            <a href="' . $folder . '/detail_' . $prefix . '.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">Lihat</a>
            <a href="' . $folder . '/edit_' . $prefix . '.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
            <a href="' . $folder . '/delete_' . $prefix . '.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus surat ini?\')">Hapus</a>
            <a href="' . $folder . '/download_potrait_' . $prefix . '.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Potrait</a>
            <a href="' . $folder . '/download_lands_' . $prefix . '.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Landscape</a>
        </td>
    </tr>';
}
?>