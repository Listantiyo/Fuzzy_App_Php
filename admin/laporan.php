<?php

require 'conn.php'; // koneksi $conn

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
  pg.dibuat_pada AS tanggal_pengajuan,
  user.no_hp
FROM fuzzy_hasil
JOIN pengajuan pg ON pg.id = fuzzy_hasil.pengajuan_id
LEFT JOIN user ON user.id = fuzzy_hasil.user_id
ORDER BY fuzzy_hasil.created_at DESC
";

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Pemohon SKCK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            min-height: 100vh;
            overflow-x: hidden;
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
            cursor: pointer;
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

        .table-actions button {
            margin-right: 0.3rem;
        }

        /* Modal content scroll */
        .modal-dialog-scrollable .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link ">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link active">Laporan Pemohon SKCK</a>
            <a href="login_admin.php?page=cetak" class="nav-link">Cetak</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>
    <main id="content" role="main" tabindex="-1">
        <header class="mb-4">
            <h1 class="h3 fw-bold text-primary">Hasil Pengajuan</h1>
        </header>
        <a target="_blank" class="btn btn-secondary btn-sm mb-2" href="admin/cetak_fuzzy.php?<?php echo time()?>" aria-label="Cetak PDF Fuzzy">
            🖨 Cetak PDF
        </a>
        <section class="bg-white rounded shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Nilai Akhir</th>
                            <th scope="col">Keputusan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $diterima = "Diterima";
                        $evaluasi = "Butuh Evaluasi";
                        $ditolak = "Ditolak";

                        $badge = "";
                        $keputusan = "";
                        $ix = 0;
                        while ($row = $result->fetch_assoc()):
                        $ix++;
                            if($row['keputusan'] == $diterima){
                                $badge = 'success';
                                $keputusan = $row['keputusan'];
                            } 
                            if($row['keputusan'] == $evaluasi){
                                $badge = 'warning';
                                $keputusan = "Butuh Evaluasi / Dalam Proses";
                            } 
                            if($row['keputusan'] == $ditolak){
                                $badge = 'danger';
                                $keputusan = $row['keputusan'];
                            } 
                        ?>
                        <tr>
                            <td class="text-center"><?= $ix?></td>
                            <td><?= $row['nama'] ?></td>
                            <td class="text-center"><?= $row['tanggal_pengajuan'] ?></td>
                            <td class="text-center"><?= $row['nilai_akhir'] ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?= $badge?>"><?= $keputusan?></span>
                            </td>
                            <td class="table-actions text-center">
                                <a href="login_admin.php?page=detail_pemohon&detail=true&pid=<?= $row['pengajuan_id']?>" class="btn btn-info btn-sm" >
                                    🔍 Detail
                                </a>
                                <a href="https://wa.me/<?= $row['no_hp'] ?>" target="_blank" class="btn btn-success btn-sm">
                                    💬 WhatsApp
                                </a>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modals Detail -->
        <!-- Detail Modal 1 -->
        

        <!-- Detail Modal 2 -->
        
        <!-- Detail Modal 3 -->
        <div class="modal fade" id="detailModal3" tabindex="-1" aria-labelledby="detailModalLabel3" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel3">
                            Detail Data Pengaju: Andi Pratama
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Data Pemohon</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Nama Lengkap</dt>
                            <dd class="col-sm-8">Andi Pratama</dd>

                            <dt class="col-sm-4">Tempat, Tanggal Lahir</dt>
                            <dd class="col-sm-8">Surabaya, 20 November 1990</dd>

                            <dt class="col-sm-4">Alamat</dt>
                            <dd class="col-sm-8">Jl. Kenanga No.23 Surabaya</dd>

                            <dt class="col-sm-4">Tujuan SKCK</dt>
                            <dd class="col-sm-8">Melamar Pekerjaan</dd>

                            <dt class="col-sm-4">Riwayat Hukum</dt>
                            <dd class="col-sm-8">Tidak Pernah</dd>

                            <dt class="col-sm-4">File Upload</dt>
                            <!-- <dd class="col-sm-8">
                  <a href="#" download title="Download dokumen pengajuan"
                    >dokumen-pengajuan-andi.pdf</a
                  >
                </dd> -->
                        </dl>
                        <hr />
                        <h6>Proses Perhitungan Fuzzy</h6>
                        <p><strong>Nilai Akhir:</strong> 45.9</p>
                        <p><strong>Keputusan:</strong> Ditolak</p>
                        <pre><code>

            Fuzzifikasi Usia: Muda=0.1, Tua=0.9
Fuzzifikasi Catatan Hukum: Bersih=1, Bermasalah=0
Fuzzifikasi Skor: Rendah=0.9, Tinggi=0.1
Rule evaluasi dan defuzzifikasi dilakukan dengan metode Tsukamoto... </code></pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printPDF(nama) {
            alert(
                "Simulasi cetak PDF untuk " +
                nama +
                ". Fitur cetak dapat diintegrasikan dengan library seperti jsPDF."
            );
        }
    </script>
</body>

</html>