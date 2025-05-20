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

$html = '
<h2 style="text-align:center;">Laporan</h2>
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
  <tbody>
';

$no = 1;
while ($row = $result->fetch_assoc()) {
  $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama']) . '</td>
        <td>' . number_format($row['nilai_akhir'], 2) . '</td>
        <td>' . htmlspecialchars($row['keputusan']) . '</td>
        <td>' . date('d/m/Y', strtotime($row['tanggal_pengajuan'])) . '</td>
    </tr>';
}

$html .= '
  </tbody>
</table>
';

// Render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF di browser (inline)
$dompdf->stream('laporan-fuzzy.pdf', ['Attachment' => false]);
