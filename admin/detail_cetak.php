<?php

include "conn.php";

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
?>


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
            min-height: 100vh;
            overflow-x: hidden;
            font-family: "Times New Roman", Times, serif;
            margin: 0 auto; /* Center the content */
            /* max-width: 300mm; A4 width */
            min-height: 297mm; /* A4 height */
            padding: 20mm; /* Standard A4 margins */
            line-height: 1.5;
            background: white;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background: #343a40;
            color: #ced4da;
            display: flex;
            flex-direction: column;
            padding: 1rem 0;
        }

        #sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #sidebar .nav-link.active,
        #sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        #sidebar .sidebar-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: #fff;
            margin-bottom: 1.5rem;
            padding-left: 1.25rem;
        }

        #content {
            margin-left: 220px;
            padding: 2rem 2.5rem;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: relative;
                width: 100%;
                height: auto;
                flex-direction: row;
                padding: 0.75rem 1rem;
                overflow-x: auto;
                white-space: nowrap;
            }

            #sidebar .sidebar-title {
                padding-left: 0;
                margin-right: 1rem;
            }

            #sidebar .nav-link {
                display: inline-block;
                padding: 0.5rem 1rem;
                margin-right: 0.75rem;
            }

            #content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        #detailModal1 .modal-body {
            overflow-y: scroll;
            max-height: 55vh;
        }

        /* CONTENT */

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
      margin-top: 30px;
    }
    .field {
      margin-bottom: 10px;
    }
    .signature {
      text-align: left;
      margin-top: 50px;
    }
    .underline {
      text-decoration: underline;
    }
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column flex-md-column flex-row flex-wrap px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link ">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link">Laporan Pemohon SKCK</a>
            <a href="login_admin.php?page=cetak" class="nav-link active">Cetak</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>

    <main id="content" role="main" tabindex="-1"><div class="center">
    <div style="display: flex; justify-content: space-between;">
      <div style="flex: 1;">
        <div class="header">POLRI DAERAH JAWA TIMUR</div>
        <div class="header">RESORT JOMBANG</div>
        <div class="header">SEKTOR TEMBELANG</div>
        <div>Jalan Raya Pesantren 248 Tembelang - Jombang 61548</div>
      </div>
      <div style="flex: 1;">
        <!-- Right column empty -->
      </div>
    </div>

    <h2 class="underline" style="margin-top: 30px;">SURAT KETERANGAN CATATAN KEPOLISIAN</h2>
    <h4><i>POLICE RECORD</i></h4>
  </div>

  <div class="section">
    <p style="margin-bottom: 0;">Diterangkan bersama ini bahwa:</p>
    <p style="margin-top: 0;"><i>This is to certify that:</i></p>

    <div class="field">
      <table style="width: 100%; line-height: 0.8">
        <tr>
          <td style="width: 250px">Nama</td>
          <td>: <?= $pengajuan['nama_lengkap'] ?></td>
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
          <td>: <?php echo $pengajuan['jenis_kelamin'] == 'L'?'Laki-laki':'Perempuan' ?></td>
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
          <td>: <?= $pengajuan['kebangsaan'] ?></td>
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
          <td>: <?= $pengajuan['agama'] ?></td>
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
          <td>: <?= $pengajuan['tempat_lahir'] ?>/<?= date('d-M-Y', strtotime($pengajuan['tanggal_lahir'])) ?></td>
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
          <td>: <?php echo $keterangan['alamat_mudah_dihubungi'] ?></td>
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
          <td>: <?php echo $keterangan['negara_dikunjungi'] ?></td>
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
          <td>: <?= $pengajuan['no_ktp'] ?></td>
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
    <p style="margin-top: 0;"><i>As of screening through the issue hereof by virtue of:</i></p>

    <ul style="list-style: none; padding-left: 20px;">
      <li style="margin-bottom: 15px;">
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
          <td>: <?php echo $verifikasi['berada_indonesia_dari'] ?></td>
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
          <td>: <?php echo $verifikasi['berada_indonesia_sampai'] ?></td>
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
          <td>: <?php echo $pengajuan['jenis_keperluan'] ?></td>
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
          <td>: <?php echo $verifikasi['berlaku_dari'] ?></td>
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
          <td>: <?php echo $verifikasi['berlaku_sampai'] ?></td>
        </tr>
        <tr>
          <td><i>to</i></td>
          <td></td>
        </tr>
      </table>
    </div>
  </div>

    <div style="display: flex; justify-content: space-between;">
        <div style="flex: 1;">
        <!-- Left column empty -->
        </div>
        <div style="flex: 1;">
        <div class="signature">
            <table style="width: 100%; line-height: 0.8">
              <tr>
                <td style="width: 100px">Dikeluarkan di</td>
                <td>: <?php echo $verifikasi['dicetak_di'] ?></td>
              </tr>
              <tr>
                <td><i>Issued in</i></td>
                <td></td>
              </tr>
            </table>

            <table style="width: 100%; line-height: 0.8; margin-bottom: 1rem;">
              <tr>
                <td style="width: 100px">Pada tanggal</td>
                <td>: <?php echo $verifikasi['tanggal_cetak'] ?></td>
              </tr>
              <tr>
                <td><i>On</i></td>
                <td></td>
              </tr>
            </table>

            <hr style="margin: 2px 0;">
            <p style="margin: 0px 0;"><strong>KEPALA KEPOLISIAN SEKTOR TEMBELANG</strong></p>
            <br><br><br>
            <p style="margin: 0px 0;"><strong class="underline">F A D I L A H, S.H, M.H.</strong></p>
            <hr style="margin: 2px 0;">
            <p style="margin: 0px 0;">AJUN KOMISARIS POLISI NRP 81100173</p>
        </div>
        </div>
    </div>

  <p style="margin-top: 30px;"><i>* Coret yang tidak perlu</i></p>

    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>