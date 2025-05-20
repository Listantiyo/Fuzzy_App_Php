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
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column flex-md-column flex-row flex-wrap px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link active">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link">Laporan Pemohon SKCK</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>

    <main id="content" role="main" tabindex="-1">
        <header class="mb-4">
            <h1 class="h3 fw-bold text-primary">Detail Pemohon <?php echo $pengajuan['username'] ?></h1>
        </header>

        <!-- Modal Template for Detail Data Pengaju -->


        <div class="">
            <!-- Kategori 1: Jenis Pengajuan -->
            <h6>Kategori 1: Jenis Pengajuan</h6>
            <div class="border p-2 rounded mb-2">
                <strong>Jenis Pendaftaran:</strong> <?php echo $pengajuan['jenis_pendaftaran'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Jenis Keperluan:</strong> <?php echo $pengajuan['jenis_keperluan'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Detail Keperluan:</strong> <?php echo $pengajuan['detail_keperluan'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Tingkat Wewenang:</strong> <?php echo $pengajuan['tingkat_wewenang'] ?>
            </div>

            <!-- Kategori 2: Data Orang Tua -->
            <h6>Kategori 2: Data Orang Tua</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Ayah</h6>
                    <div class="border p-2 rounded mb-2">
                        <strong>Nama Ayah:</strong> <?php echo $keluarga_ayah['nama'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Umur Ayah:</strong> <?php echo $keluarga_ayah['umur'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Agama Ayah:</strong> <?php echo $keluarga_ayah['agama'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Warga Ayah:</strong> <?php echo $keluarga_ayah['warganegara'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Pekerjaan Ayah:</strong> <?php echo $keluarga_ayah['pekerjaan'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Serumah Ayah:</strong> <?php echo $keluarga_ayah['serumah'] ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>Ibu</h6>
                    <div class="border p-2 rounded mb-2">
                        <strong>Nama Ibu:</strong> <?php echo $keluarga_ibu['nama'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Umur Ibu:</strong> <?php echo $keluarga_ibu['umur'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Agama Ibu:</strong> <?php echo $keluarga_ibu['agama'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Warga Ibu:</strong> <?php echo $keluarga_ibu['warganegara'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Pekerjaan Ibu:</strong> <?php echo $keluarga_ibu['pekerjaan'] ?>
                    </div>
                    <div class="border p-2 rounded mb-2">
                        <strong>Serumah Ibu:</strong> <?php echo $keluarga_ibu['serumah'] ?>
                    </div>
                </div>
            </div>

            <!-- Kategori 3: Pendidikan -->
            <h6>Kategori 3: Pendidikan</h6>
            <div class="border p-2 rounded mb-2">
                <strong>Sekolah Di:</strong> <?php echo ucwords($pendidikan['lokasi_sekolah']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Tingkat Pendidikan:</strong> <?php echo ucwords($pendidikan['tingkat']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Nama Institusi:</strong> <?php echo ucwords($pendidikan['nama_institusi']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Tahun Lulus:</strong> <?php echo $pendidikan['tahun_lulus'] ?>
            </div>

            <!-- Kategori 4: Riwayat Hukum -->
            <h6>Kategori 4: Riwayat Hukum</h6>
            <div class="border p-2 rounded mb-2">
                <strong>Tersangkut Pidana:</strong> <?php echo ucwords($pidana['pernah_terlibat']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Perkara Apa:</strong> <?php echo $pidana['detail_perkara'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Keputusan Vonis:</strong> <?php echo $pidana['keputusan'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Sedang Proses Pidana:</strong> <?php echo ucwords($pidana['sedang_proses']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Kasus Apa:</strong> <?php echo $pidana['kasus_sedang_diproses'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Proses Hukum:</strong> <?php echo $pidana['sampai_mana'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Melanggar Hukum Norma:</strong> <?php echo ucwords($pidana['pelanggaran_norma']) ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Pelanggaran Apa:</strong> <?php echo $pidana['detail_norma'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Proses Pelanggaran:</strong> <?php echo $pidana['proses_norma'] ?>
            </div>

            <!-- Kategori 5: Ciri Fisik -->
            <h6>Kategori 5: Ciri Fisik</h6>
            <div class="border p-2 rounded mb-2">
                <strong>Tinggi Badan:</strong> <?php echo $fisik['tinggi'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Berat Badan:</strong> <?php echo $fisik['berat'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Tanda Istimewa:</strong> <?php echo $fisik['tanda_istimewa'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Warna Kulit:</strong> <?php echo $fisik['warna_kulit'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Jenis Rambut:</strong> <?php echo $fisik['jenis_rambut'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Bentuk Muka:</strong> <?php echo $fisik['bentuk_muka'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Rumus Sidik Jari:</strong> <?php echo ucwords($fisik['punya_sidik_jari']) ?>
            </div>

            <!-- Kategori 6: Foto -->
            <h6>Kategori 6: Foto</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Foto Diri:</strong><br>
                    <img src="<?php echo $pengajuan['foto_diri'] ?>" alt="Foto Diri" width="300"
                        class="img-thumbnail">
                </div>
                <div class="col-md-4">
                    <strong>Foto KK:</strong><br>
                    <img src="<?php echo $lampiran['foto_kk'] ?>" alt="Foto KK" width="300"
                        class="img-thumbnail">
                </div>
                <div class="col-md-4">
                    <strong>Foto Akte Ijazah:</strong><br>
                    <img src="<?php echo $lampiran['foto_akta_ijazah'] ?>" alt="Foto Akte Ijazah"
                        width="300" class="img-thumbnail">
                </div>
            </div>

            <!-- Kategori 7: Lainnya -->
            <h6>Kategori 7: Lainnya</h6>
            <!-- <div class="border p-2 rounded mb-2">
                <strong>Riwayat Pekerjaan / Negara dikunjungi:</strong> 
                <input class="form-check-input" type="checkbox" value="<?php echo $keterangan['riwayat_pekerjaan'] ?>"/> 
            </div> -->
            <div class="border p-2 rounded mb-2">
                <strong>Pekerjaan / Negara:</strong> <?php echo $keterangan['negara_dikunjungi'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Kesenangan Hobi:</strong> <?php echo $keterangan['hobi'] ?>
            </div>
            <div class="border p-2 rounded mb-2">
                <strong>Alamat Kontak:</strong> <?php echo $keterangan['alamat_mudah_dihubungi'] ?>
            </div>
        </div>
        <div class=" d-block text-start">
            <!-- Form input poin riwayat kriminal dan status hukum -->
            <form action="admin/verifikasi_pengajuan.php" method="POST">
                <input type="hidden" name="pengajuan_id" value="<?= $pid ?>">
                <input type="hidden" name="verifikasi_id" value="<?php echo $verifikasi == null ? '' : $verifikasi['id'] ?>">

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="poin_riwayat_kriminal" class="form-label">Poin Riwayat
                            Kriminal</label>
                        <input type="number" class="form-control" id="poin_riwayat_kriminal"
                            name="poin_riwayat_kriminal" value="<?php echo $verifikasi == null ? '' : $verifikasi['riwayat_kriminal']?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="poin_status_hukum" class="form-label">Poin Status Hukum</label>
                        <input type="number" class="form-control" id="poin_status_hukum"
                            name="poin_status_hukum" value="<?php echo $verifikasi == null ? '' : $verifikasi['status_hukum'] ?>" required>
                    </div>
                </div>
                <?php if(!isset($_GET['detail']) || !$_GET['detail'] == true):?>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Verifikasi</button>
                </div>
                <?php endif?>
            </form>
        </div>

    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>