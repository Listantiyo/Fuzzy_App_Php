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

$pid = $_GET['pid'] ?? null;
if(!isset($_GET['pid']) || !$pid) header("Location: login_admin.php?page=data_pemohon");

if (!$pid) {
    die("ID pengajuan tidak ditemukan.");
}

// Ambil data utama
$q_pengajuan = mysqli_query($conn, "SELECT p.*, u.* 
    FROM pengajuan p 
    JOIN user u ON p.user_id = u.id 
    WHERE p.id = '$pid'");
$pengajuan = mysqli_fetch_assoc($q_pengajuan);

// Ambil data keluarga
$q_keluarga_ayah = mysqli_query($conn, "SELECT * FROM keluarga WHERE hubungan = 'ayah' AND pengajuan_id = '$pid'");
$keluarga_ayah = mysqli_fetch_assoc($q_keluarga_ayah);
$q_keluarga_ibu = mysqli_query($conn, "SELECT * FROM keluarga WHERE hubungan = 'ibu' AND pengajuan_id = '$pid'");
$keluarga_ibu = mysqli_fetch_assoc($q_keluarga_ibu);

// Ambil data pendidikan
$q_pendidikan = mysqli_query($conn, "SELECT * FROM pendidikan WHERE pengajuan_id = '$pid'");
$pendidikan = mysqli_fetch_assoc($q_pendidikan);

// Ambil data pidana
$q_pidana = mysqli_query($conn, "SELECT * FROM pidana WHERE pengajuan_id = '$pid'");
$pidana = mysqli_fetch_assoc($q_pidana);

// Ambil data fisik
$q_fisik = mysqli_query($conn, "SELECT * FROM fisik WHERE pengajuan_id = '$pid'");
$fisik = mysqli_fetch_assoc($q_fisik);

// Ambil data lampiran
$q_lampiran = mysqli_query($conn, "SELECT * FROM lampiran WHERE pengajuan_id = '$pid'");
$lampiran = mysqli_fetch_assoc($q_lampiran);

// Ambil data keterangan
$q_keterangan = mysqli_query($conn, "SELECT * FROM keterangan WHERE pengajuan_id = '$pid'");
$keterangan = mysqli_fetch_assoc($q_keterangan);

// Ambil verifikasi admin (jika ada)
$q_verifikasi = mysqli_query($conn, "SELECT * FROM verifikasi_admin WHERE pengajuan_id = '$pid'");
$verifikasi = mysqli_fetch_assoc($q_verifikasi);
// debug($verifikasi);
// Ambil hasil fuzzy (jika ada)
// $q_fuzzy = mysqli_query($conn, "SELECT * FROM fuzzy_hasil WHERE pengajuan_id = '$pid'");
// $fuzzy = mysqli_fetch_assoc($q_fuzzy);

$jenisKL = $pengajuan['jenis_kelamin'] == 'L'?'Laki-laki':'Perempuan';

$html = '
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pemohon SKCK </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
      <style>
    body {
      font-family: "Times New Roman", Times, serif;
      margin: 0 auto; /* Center the content */
      max-width: 230mm; /* A4 width */
      min-height: 297mm; /* A4 height */
      padding: 0 1mm; /* Standard A4 margins */
      line-height: 1.2;
      background: white;
      font-size:10px !important;
    }
    @media print {
      body {
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 20mm;
      }
    }
    .center {
      text-align: center;
    }
    .header {
      font-weight: bold;
      text-transform: uppercase;
    }
    .section {
      margin-top: 10px;
    }
    .field {
      margin-bottom: 5px;
    }
    .signature {
      text-align: left;
      margin-top: 20px;
    }
    .underline {
      text-decoration: underline;
    }
    .text-center{
      text-align:center;
    }
    .mt-05{
      margin-top:5px;
    }
  </style>
</head>

<body>
  <div class="center" style="margin-bottom:1px;padding-bottom:0px;">
    <table style="width: 100%;">
      <tr>
        <td style="width: 50%;">
          <div class="header center">POLRI DAERAH JAWA TIMUR</div>
          <div class="header center">RESORT JOMBANG</div>
          <div class="header center">SEKTOR TEMBELANG</div>
          <div class="center">Jalan Raya Pesantren 248 Tembelang - Jombang 61548</div>
        </td>
        <td style="width: 50%;">
          <!-- Right column kosong -->
        </td>
      </tr>
    </table>


    <h2 class="underline center" style="margin-top: 9px; margin-bottom:1px;">SURAT KETERANGAN CATATAN KEPOLISIAN</h2>
    <h4 class="center" style="margin-top:0; padding-top:0;"><i>POLICE RECORD</i></h4>
  </div>

  <div class="section" style="margin-top:0;padding-top:0;">
    <p style="margin-bottom: 0;">Diterangkan bersama ini bahwa:</p>
    <p style="margin-top: 0;"><i>This is to certify that:</i></p>

    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Nama</td>
          <td>:'.$pengajuan['nama_lengkap'].'</td>
        </tr>
        <tr>
          <td><i>Name</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Jenis Kelamin</td>
          <td>:'.$jenisKL.'</td>
        </tr>
        <tr>
          <td><i>Sex</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Kebangsaan</td>
          <td>: '.$pengajuan['kebangsaan'] .'</td>
        </tr>
        <tr>
          <td><i>Nationality</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Agama</td>
          <td>: '.$pengajuan['agama'] .'</td>
        </tr>
        <tr>
          <td><i>Religion</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Tempat dan tgl lahir</td>
          <td>: '. $pengajuan['tempat_lahir'] .'/'. date('d-M-Y', strtotime($pengajuan['tanggal_lahir'])) .'</td>
        </tr>
        <tr>
          <td><i>Place and date of birth</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Tempat tinggal sekarang</td>
          <td>: '. $keterangan['alamat_mudah_dihubungi'] .'</td>
        </tr>
        <tr>
          <td><i>Current address</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Pekerjaan</td>
          <td>: '. $keterangan['negara_dikunjungi'] .'</td>
        </tr>
        <tr>
          <td><i>Occupation</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Nomor KTP</td>
          <td>: '. $pengajuan['no_ktp'] .'</td>
        </tr>
        <tr>
          <td><i>Citizens card number</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Nomor Paspor/KITAS/KITAP*</td>
          <td>: </td>
        </tr>
        <tr>
          <td><i>Passport/KITAS/KITAP number</i></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Rumus sidik jari</td>
          <td>: </td>
        </tr>
        <tr>
          <td><i>Fingerprints Formula</i></td>
          <td></td>
        </tr>
      </table>
    </div>
  </div>

  <div class="section">
    <p style="margin-bottom: 0;">Setelah diadakan penelitian hingga saat dikeluarkan surat keterangan ini yang didasarkan kepada:</p>
    <p style="margin-top: 0; margin-bottom: 0px;"><i>As of screening through the issue hereof by virtue of:</i></p>

    <ul style="list-style: none; padding-left: 20px;">
      <li style="margin-bottom: 5px;">
        <table style="width: 100%; line-height: 1.2;">
          <tr>
            <td style="width: 10px; vertical-align: top;">a.</td>
            <td>
              <div>Catatan Kepolisian yang ada</div>
              <div><i>Existing Police record</i></div>
            </td>
          </tr>
        </table>
      </li>
      <li>
        <table style="width: 100%; line-height: 1.2;">
          <tr>
            <td style="width: 10px; vertical-align: top;">b.</td>
            <td>
              <div>Surat keterangan dari Kepala Desa / Lurah</div>
              <div><i>Information from local Authorities</i></div>
            </td>
          </tr>
        </table>
      </li>
    </ul>

    <b style="margin-bottom: 0; padding-left: 20px;">bahwa nama tersebut di atas tidak memiliki catatan atau keterlibatan dalam kegiatan kriminal apapun</b><br>
    <b style="margin-top: 0; padding-left: 20px;"><i>the bearer hereof proves not to be involved in any criminal cases</i></b>

    <div class="field" style="padding-left: 20px; margin-top: 20px;">
      <table style="width: 100%; line-height: 0.8;">
        <tr>
          <td style="width: 260px">selama ia berada di Indonesia dari</td>
          <td>: '. $verifikasi['berada_indonesia_dari'] .'</td>
        </tr>
        <tr>
          <td><i>during his/her stay in Indonesia from</i></td>
          <td></td>
        </tr>
      </table>
    </div>

    <div class="field" style="padding-left: 20px;">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 260px">sampai dengan</td>
          <td>: '. $verifikasi['berada_indonesia_sampai'] .'</td>
        </tr>
        <tr>
          <td><i>to</i></td>
          <td></td>
        </tr>
      </table>
    </div>

    <p class="center" style="margin-bottom: 0;">Keterangan ini diberikan berhubungan dengan permohonan:</p>
    <p class="center" style="margin-top: 0;"><i>This certificate is issued at the request to the applicant</i></p>

    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Untuk keperluan/menuju*</td>
          <td>: '.  $pengajuan['jenis_keperluan'] .'</td>
        </tr>
        <tr>
          <td><i>For the purpose</i></td>
          <td></td>
        </tr>
      </table>
    </div>

    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Berlaku dari tanggal</td>
          <td>: '. $verifikasi['berlaku_dari'] .'</td>
        </tr>
        <tr>
          <td><i>Valid from</i></td>
          <td></td>
        </tr>
      </table>
    </div>

    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">sampai dengan</td>
          <td>: '. $verifikasi['berlaku_sampai'] .'</td>
        </tr>
        <tr>
          <td><i>to</i></td>
          <td></td>
        </tr>
      </table>
    </div>
  </div>

    <table style="width: 100%;">
  <tr>
    <!-- Left column kosong -->
    <td style="width: 50%;">&nbsp;</td>

    <!-- Right column (tanda tangan dan informasi) -->
    <td style="width: 50%;">
      <div class="signature">
        <table style="width: 100%; line-height: 0.8;">
          <tr>
            <td style="width: 100px;">Dikeluarkan di</td>
            <td>: '.$verifikasi['dicetak_di'] .'</td>
          </tr>
          <tr>
            <td><i>Issued in</i></td>
            <td></td>
          </tr>
        </table>

        <table style="width: 100%; line-height: 0.8; margin-bottom: 1rem;">
          <tr>
            <td style="width: 100px;">Pada tanggal</td>
            <td>: '. $verifikasi['tanggal_cetak'].'</td>
          </tr>
          <tr>
            <td><i>On</i></td>
            <td></td>
          </tr>
        </table>

        <hr style="margin: 2px 0;">
        <p style="margin: 0;"><strong>KEPALA KEPOLISIAN SEKTOR TEMBELANG</strong></p>
        <br><br>
        <p style="margin: 0;"><strong class="underline">F A D I L A H, S.H, M.H.</strong></p>
        <hr style="margin: 2px 0;">
        <p style="margin: 0;">AJUN KOMISARIS POLISI NRP 81100173</p>
      </div>
    </td>
  </tr>
</table>


  <p style="margin-top: 30px;"><i>* Coret yang tidak perlu</i></p>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

';

// Render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF di browser (inline)
$dompdf->stream('cetak.pdf', ['Attachment' => false]);
?>