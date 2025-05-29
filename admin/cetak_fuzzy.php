<?php
require_once '../vendor/autoload.php'; // Composer autoload
require '../conn.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial'); // Atur font default

$dompdf = new Dompdf($options);

// Ambil data dari database
$sql = "
SELECT 
  fuzzy_hasil.id,
  fuzzy_hasil.user_id,
  fuzzy_hasil.pengajuan_id,
  fuzzy_hasil.nama,
  fuzzy_hasil.kriminal,
  fuzzy_hasil.status,
  fuzzy_hasil.nilai_akhir,
  fuzzy_hasil.keputusan,
  fuzzy_hasil.created_at,
  pg.dibuat_pada AS tanggal_pengajuan
FROM fuzzy_hasil
JOIN pengajuan pg ON pg.id = fuzzy_hasil.pengajuan_id
ORDER BY fuzzy_hasil.created_at DESC
";

$result = $conn->query($sql);

$html = '';

$perPage = 20;
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$totalPages = ceil(count($rows) / $perPage);

for ($page = 0; $page < $totalPages; $page++) {
    // Awal halaman (bungkus dengan div posisi relatif)
    $html .= '
    <div style="page-break-after: always; position: relative; min-height: 1000px;"> <!-- Tinggi min sesuai A4 -->
        <table width="100%" style="border: none; font-size:12px;">
            <tr>
                <td width="50%" style="text-align: center;">
                    <h3 style="margin:0;">POLRI DAERAH JAWA TIMUR</h3>
                    <h3 style="margin:0;">RESORT JOMBANG</h3>
                    <h3 style="margin:0;">SEKTOR TEMBELANG</h3>
                    <p style="margin:0;">Jalan Raya Pesantren 248 Tembelang - Jombang 61548</p>
                </td>
                <td width="50%"></td>
            </tr>
        </table>

        <h2 style="text-align:center; margin-bottom: 20px;">Laporan</h2>

        <table border="1" cellspacing="0" cellpadding="6" width="100%" style="border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nilai Akhir</th>
                    <th>Keputusan</th>
                    <th>Tanggal Pengajuan</th>
                </tr>
            </thead>
            <tbody>';

    $start = $page * $perPage;
    $end = min($start + $perPage, count($rows));
    for ($i = $start; $i < $end; $i++) {
        $row = $rows[$i];
        $html .= '<tr>
                <td>' . ($i + 1) . '</td>
                <td>' . htmlspecialchars($row['nama']) . '</td>
                <td>' . number_format($row['nilai_akhir'], 2) . '</td>
                <td>' . htmlspecialchars($row['keputusan']) . '</td>
                <td>' . date('d/m/Y', strtotime($row['tanggal_pengajuan'])) . '</td>
            </tr>';
    }

    $html .= '</tbody></table>';

    // Tanda tangan di pojok kanan bawah
    $html .= '
        <div style="position: absolute; bottom: 40px; right: 40px; text-align: center; font-size: 12px;">
            <p>KEPALA KEPOLISIAN SEKTOR TEMBELANG</p>
            <br><br><br>
            <p><strong>F A D I L A H, S.H, M.H.</strong><br>
            AJUN KOMISARIS POLISI<br>
            NRP 81100173</p>
        </div>
    </div>'; // penutup halaman
}

// Render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF di browser (inline)
$dompdf->stream('laporan-fuzzy.pdf', ['Attachment' => false]);

