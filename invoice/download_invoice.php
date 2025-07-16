<?php
require 'vendor/autoload.php';
require 'config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

function terbilang($angka)
{
    $angka = abs($angka);
    $bilangan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    $hasil = "";
    if ($angka < 12) {
        $hasil = " " . $bilangan[$angka];
    } else if ($angka < 20) {
        $hasil = terbilang($angka - 10) . " Belas ";
    } else if ($angka < 100) {
        $hasil = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
    } else if ($angka < 200) {
        $hasil = " Seratus " . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $hasil = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $hasil = " Seribu " . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $hasil = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $hasil = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
    }
    return trim(preg_replace('/\s+/', ' ', $hasil));
}

if (!isset($_GET['id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = intval($_GET['id']);
$query = $conn->query("SELECT * FROM invoices WHERE id = $invoice_id");
if (!$query || $query->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $query->fetch_assoc();

$items_query = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");
$items = [];
while ($item = $items_query->fetch_assoc()) {
    $items[] = $item;
}

$tanggal = new DateTime($invoice['created_at']);
$formatted_date = $tanggal->format('d') . ' ' . date('F', mktime(0, 0, 0, $tanggal->format('m'))) . ' ' . $tanggal->format('Y');
$romawi = bulanRomawi($tanggal->format('n'));
$terbilang = terbilang($invoice['grand_total']) . ' Rupiah';

$basePath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$kopPath = $basePath . '/asset/kop.png';
$ttdPath = $basePath . '/asset/ttd.png';

$html = '<html><head><style>
@page {
    margin: 0cm;
}
body {
    margin: 0cm;
    font-family: Arial, sans-serif;
    font-size: 12px;
    background-image: url("' . $kopPath . '");
    background-size: 100% 100%;
    background-size: cover;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: top left;
    padding: 120px 50px 60px 60px;
    // top btom left right
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #000;
    padding: 6px;
    text-align: left;
}
.no-border td {
    border: none;
    padding: 3px 0;
}
.note {
    margin-top: 20px;
    font-size: 11px;
}
</style></head><body>';

$html .= '<table class="no-border">
<tr><td>Nomor</td><td>: ' . $invoice['invoice_number'] . '</td></tr>
<tr><td>Perihal</td><td>: Penawaran Harga</td></tr>
</table>

<p>Kepada Yth.<br><strong>' . $invoice['client_name'] . '</strong><br>' . $invoice['address'] . '</p>';

$html .= '<table><thead><tr><th>No</th><th>Nama Barang</th><th>Qty</th><th>Harga</th><th>Total Harga</th></tr></thead><tbody>';

$no = 1;
foreach ($items as $item) {
    $total = $item['qty'] * $item['price'] * (1 - $item['discount'] / 100);
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $item['name'] . '</td>
        <td>' . $item['qty'] . ' ' . $item['unit'] . '</td>
        <td>' . number_format($item['price'], 0, '', '.') . '</td>
        <td>' . number_format($total, 0, '', '.') . '</td>
    </tr>';
}

$html .= '<tr><td colspan="4" style="text-align:right;">Subtotal</td><td>Rp. ' . number_format($invoice['subtotal'], 0, '', '.') . '</td></tr>';
$html .= '<tr><td colspan="4" style="text-align:right;">PPN 11%</td><td>Rp. ' . number_format($invoice['tax'], 0, '', '.') . '</td></tr>';
$html .= '<tr><td colspan="4" style="text-align:right; font-weight: bold;">Grand Total</td><td>Rp. ' . number_format($invoice['grand_total'], 0, '', '.') . '</td></tr>';
$html .= '</tbody></table>';

$html .= '<p><em>Terbilang : <strong>' . $terbilang . '</strong></em></p>';

$html .= '<div class="note">
<p>Catatan :</p>
<ol>
<li>Penawaran ini berlaku 10 hari setelah penawaran dibuat</li>
<li>Harga dapat berubah sewaktu-waktu</li>
<li>Harga belum termasuk ongkir</li>
<li>Pembayaran dapat dilakukan sejak invoice diterbitkan</li>
<li>Untuk pemesanan lebih lanjut hubungi <strong>PT SEMESTA SISTEM SOLUSINDO</strong> <br>+62 811-1933-077 (Rahmad Saputra)</li>
</ol>
</div>';

$html .= '<p style="text-align:right;">Jakarta, ' . $formatted_date . '<br><br>
<img src="' . $ttdPath . '" width="150"><br>
<strong>Rahmad Saputra</strong><br>Direktur Utama</p>';

$html .= '</body></html>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Invoice_' . $invoice['invoice_number'] . '.pdf', ['Attachment' => 0]);
exit();