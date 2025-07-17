<?php
require '../vendor/autoload.php';
require '../config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function bulanRomawi($bulan)
{
    $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
    return $romawi[(int) $bulan];
}

function terbilang($angka)
{
    $angka = abs($angka);
    $bilangan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    $hasil = "";
    
    if ($angka < 12) {
        $hasil = " " . $bilangan[$angka];
    } elseif ($angka < 20) {
        $hasil = terbilang($angka - 10) . " Belas ";
    } elseif ($angka < 100) {
        $hasil = terbilang(intval($angka / 10)) . " Puluh " . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $hasil = " Seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $hasil = terbilang(intval($angka / 100)) . " Ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $hasil = " Seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $hasil = terbilang(intval($angka / 1000)) . " Ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $hasil = terbilang(intval($angka / 1000000)) . " Juta " . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        $hasil = terbilang(intval($angka / 1000000000)) . " Miliar " . terbilang($angka % 1000000000);
    }

    return trim(preg_replace('/\s+/', ' ', $hasil));
}

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_GET['id']);
$query = $conn->query("SELECT * FROM invoices WHERE id = $id");
if (!$query || $query->num_rows === 0) {
    die("Data tidak ditemukan.");
}
$invoice = $query->fetch_assoc();

$items_query = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $id");
$items = [];
while ($item = $items_query->fetch_assoc()) {
    $items[] = $item;
}

$tanggal = new DateTime($invoice['created_at']);
$formatted_date = $tanggal->format('d') . ' ' . date('F', mktime(0, 0, 0, $tanggal->format('m'))) . ' ' . $tanggal->format('Y');
$romawi = bulanRomawi($tanggal->format('n'));
$terbilang = terbilang($invoice['grand_total']) . ' Rupiah';

$basePath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$kopPath = $basePath . '/../asset/kop_lands.jpg';
$ttdPath = $basePath . '/../asset/ttd.png';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$html = '
<html>
<head>
    <style>
        @page {
            margin: 1px;
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
            padding: 20px 50px 60px 60px;
            // top btom left right
        }
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }
        .no-border td {
            border: none;
            padding: 2px 0;
        }
        .note {
            font-size: 10px;
            margin-top: 20px;
        }
        img.product-img {
            max-height: 60px;
            max-width: 100px;
        }
    </style>
</head>
<body>
    <br>
    <table class="no-border" width="200px" style="padding-left:650px; text-align: left;">
        <tr><td width="15%" style="text-align: left;">No Surat</td><td style="text-align: left;">: ' . $invoice['invoice_number'] . '</td></tr>
        <tr><td width="15%" style="text-align: left;">Tanggal</td><td style="text-align: left;">: ' . $formatted_date . '</td></tr>
        <tr><td width="15%" style="text-align: left;">Kepada</td><td style="text-align: left;">: <strong>' . $invoice['client_name'] . '</strong></td></tr>
        <tr><td width="15%" style="text-align: left;">Perihal</td><td style="text-align: left;">: Surat Penawaran Harga</td></tr>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Spesifikasi</th>
                <th>Gambar</th>
                <th>Link</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
foreach ($items as $item) {
    $total = $item['qty'] * $item['price'];
    $product_img = '-';
    $inaproc_link = '-';
    $product_spec = '-';

    $product_id = intval($item['id_product']);
    if ($product_id > 0) {
        $product_result = $conn->query("SELECT image_url, inaproc_link, spec FROM products WHERE id = $product_id LIMIT 1");
        if ($product_result && $product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            // Inaproc link
            $inaproc_link = $product['inaproc_link'] ?: '-';
            // Spesifikasi
            $product_spec = $product['spec'] ?: '-';   
            // Gambar
            $imgPath = $product['image_url'];
            $fullPath = realpath($imgPath);
            if (file_exists($fullPath)) {
                $imageData = base64_encode(file_get_contents($fullPath));
                $mimeType = mime_content_type($fullPath);
                $product_img = '<img src="data:' . $mimeType . ';base64,' . $imageData . '" class="product-img">';
            }
        }
    }

    $html .= '
        <tr>
            <td width="3%">' . $no++ . '</td>
            <td width="20%">' . htmlspecialchars($item['name']) . '</td>
            <td width="30%">' . htmlspecialchars($product_spec) . '</td>
            <td width="20%">' . $product_img . '</td>
            <td width="15%">' . ($inaproc_link !== '-' ? '<a href="' . htmlspecialchars($inaproc_link) . '" target="_blank">Link Item</a>' : '-') . '</td>
            <td width="10%">' . $item['qty'] . ' ' . htmlspecialchars($item['unit']) . '</td>
            <td width="12%">Rp ' . number_format($item['price'], 0, '', '.') . '</td>
            <td width="13%" style="text-align: left;">Rp ' . number_format($total, 0, '', '.') . '</td>
        </tr>';
}

$html .= '
            <tr><td colspan="7" style="text-align:right;">Subtotal</td><td style="text-align: left;">Rp ' . number_format($invoice['subtotal'], 0, '', '.') . '</td></tr>
            <tr><td colspan="7" style="text-align:right;">PPN (11%)</td><td style="text-align: left;">Rp ' . number_format($invoice['tax'], 0, '', '.') . '</td></tr>';

if ($invoice['shipping'] > 0) {
    $html .= '<tr><td colspan="7" style="text-align:right;">Ongkir</td><td style="text-align: left;">Rp ' . number_format($invoice['shipping'], 0, '', '.') . '</td></tr>';
}

$html .= '<tr><td colspan="7" style="text-align:right;"><strong>Grand Total</strong></td><td style="text-align: left;"><strong>Rp ' . number_format($invoice['grand_total'], 0, '', '.') . '</strong></td></tr>
        </tbody>
    </table>
    <p><em>Terbilang: <strong>' . $terbilang . '</strong></em></p>';

$html .= '<div class="note">
    <p>Catatan:</p>
    <ol>
        <li>Penawaran berlaku 10 hari setelah dibuat.</li>
        <li>Harga dapat berubah sewaktu-waktu.</li>
        <li>' . ($invoice['shipping'] > 0 ? 'Harga sudah termasuk ongkir.' : 'Harga belum termasuk ongkir.') . '</li>';
if ($invoice['delivery_time'] > 0) {
    $html .= '<li>Estimasi pengiriman: ' . $invoice['delivery_time'] . ' ' . $invoice['delivery_unit'] . ' setelah pembayaran.</li>';
}
$html .= '<li>Pembayaran dapat dilakukan sejak invoice diterbitkan.</li>
        <li>Hubungi: <strong>PT SEMESTA SISTEM SOLUSINDO</strong><br>+62 811-1933-077 (Rahmad Saputra)</li>
    </ol>
</div>';

$html .= '<p style="text-align:right;">Jakarta, ' . $formatted_date . '<br><br>
<img src="' . $ttdPath . '" width="120"><br>
<strong>Rahmad Saputra</strong><br>Direktur Utama</p>
</body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('SPH_' . $invoice['invoice_number'] . '.pdf', ['Attachment' => 0]);
exit;
