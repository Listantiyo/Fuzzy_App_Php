<?php

include "conn.php";

$pid = $_GET['pid'] ?? null;
if (!isset($_GET['pid']) || !$pid)
    header("Location: login_admin.php?page=data_pemohon");

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
            <a href="login_admin.php?page=cetak" class="nav-link">Cetak</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>

    <form action="admin/verifikasi_pengajuan.php" method="POST">
        <main id="content" role="main" tabindex="-1">
            <header class="mb-4">
                <h1 class="h3 fw-bold text-primary">Detail Pemohon <?php echo $pengajuan['username'] ?></h1>
            </header>

            <!-- Modal Template for Detail Data Pengaju -->


            <div class="">
                <!-- Kategori 1: Jenis Pengajuan -->
                <h6 class="fw-bold text-primary mb-3">Kategori 1: Jenis Pengajuan</h6>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Jenis Pendaftaran:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenis_pendaftaran"
                            value="<?php echo $pengajuan['jenis_pendaftaran'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Jenis Keperluan:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenis_keperluan"
                            value="<?php echo $pengajuan['jenis_keperluan'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Detail Keperluan:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_keperluan"
                            value="<?php echo $pengajuan['detail_keperluan'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tingkat Wewenang:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tingkat_wewenang"
                            value="<?php echo $pengajuan['tingkat_wewenang'] ?>" />
                    </div>
                </div>

                <!-- Kategori 2: Data Orang Tua -->
                <h6 class="fw-bold text-primary mb-3">Kategori 2: Data Orang Tua</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Ayah</h6>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Nama Ayah:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama_ayah"
                                    value="<?php echo $keluarga_ayah['nama'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Umur Ayah:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="umur_ayah"
                                    value="<?php echo $keluarga_ayah['umur'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Agama Ayah:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="agama_ayah"
                                    value="<?php echo $keluarga_ayah['agama'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Warga Ayah:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="warga_ayah"
                                    value="<?php echo $keluarga_ayah['warganegara'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Pekerjaan Ayah:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pekerjaan_ayah"
                                    value="<?php echo $keluarga_ayah['pekerjaan'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Serumah Ayah:</strong>
                            <div class="col-sm-8">
                                <select class="form-select" name="serumah_ayah">
                                    <option value="ya" <?php echo $keluarga_ayah['serumah'] == 'ya' ? 'selected' : '' ?>>
                                        Ya</option>
                                    <option value="tidak" <?php echo $keluarga_ayah['serumah'] == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Ibu</h6>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Nama Ibu:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama_ibu"
                                    value="<?php echo $keluarga_ibu['nama'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Umur Ibu:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="umur_ibu"
                                    value="<?php echo $keluarga_ibu['umur'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Agama Ibu:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="agama_ibu"
                                    value="<?php echo $keluarga_ibu['agama'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Warga Ibu:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="warga_ibu"
                                    value="<?php echo $keluarga_ibu['warganegara'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Pekerjaan Ibu:</strong>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pekerjaan_ibu"
                                    value="<?php echo $keluarga_ibu['pekerjaan'] ?>" />
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-2 row align-items-center">
                            <strong class="col-sm-4">Serumah Ibu:</strong>
                            <div class="col-sm-8">
                                <select class="form-select" name="serumah_ibu">
                                    <option value="ya" <?php echo $keluarga_ibu['serumah'] == 'ya' ? 'selected' : '' ?>>
                                        Ya</option>
                                    <option value="tidak" <?php echo $keluarga_ibu['serumah'] == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kategori 3: Pendidikan -->
                <h6 class="fw-bold text-primary mb-3">Kategori 3: Pendidikan</h6>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Sekolah Di:</strong>
                    <div class="col-sm-9">
                        <select class="form-select" name="lokasi_sekolah">
                            <option value="dalam negeri" <?php echo strtolower($pendidikan['lokasi_sekolah']) == 'dalam negeri' ? 'selected' : '' ?>>Dalam Negeri</option>
                            <option value="luar negeri" <?php echo strtolower($pendidikan['lokasi_sekolah']) == 'luar negeri' ? 'selected' : '' ?>>Luar Negeri</option>
                        </select>
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tingkat Pendidikan:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tingkat"
                            value="<?php echo ucwords($pendidikan['tingkat']) ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Nama Institusi:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama_institusi"
                            value="<?php echo ucwords($pendidikan['nama_institusi']) ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tahun Lulus:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tahun_lulus"
                            value="<?php echo $pendidikan['tahun_lulus'] ?>" />
                    </div>
                </div>

                <!-- Kategori 4: Riwayat Hukum -->
                <h6 class="fw-bold text-primary mb-3">Kategori 4: Riwayat Hukum</h6>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tersangkut Pidana:</strong>
                    <div class="col-sm-9">
                        <select class="form-select" name="pernah_terlibat">
                            <option value="ya" <?php echo strtolower($pidana['pernah_terlibat']) == 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?php echo strtolower($pidana['pernah_terlibat']) == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Perkara Apa:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_perkara"
                            value="<?php echo $pidana['detail_perkara'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Keputusan Vonis:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="keputusan"
                            value="<?php echo $pidana['keputusan'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Sedang Proses Pidana:</strong>
                    <div class="col-sm-9">
                        <select class="form-select" name="sedang_proses">
                            <option value="ya" <?php echo strtolower($pidana['sedang_proses']) == 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?php echo strtolower($pidana['sedang_proses']) == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Kasus Apa:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="kasus_sedang_diproses"
                            value="<?php echo $pidana['kasus_sedang_diproses'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Proses Hukum:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="sampai_mana"
                            value="<?php echo $pidana['sampai_mana'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Melanggar Hukum Norma:</strong>
                    <div class="col-sm-9">
                        <select class="form-select" name="pelanggaran_norma">
                            <option value="ya" <?php echo strtolower($pidana['pelanggaran_norma']) == 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?php echo strtolower($pidana['pelanggaran_norma']) == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Pelanggaran Apa:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_norma"
                            value="<?php echo $pidana['detail_norma'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Proses Pelanggaran:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="proses_norma"
                            value="<?php echo $pidana['proses_norma'] ?>" />
                    </div>
                </div>

                <!-- Kategori 5: Ciri Fisik -->
                <h6 class="fw-bold text-primary mb-3">Kategori 5: Ciri Fisik</h6>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tinggi Badan:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tinggi" value="<?php echo $fisik['tinggi'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Berat Badan:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="berat" value="<?php echo $fisik['berat'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Tanda Istimewa:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tanda_istimewa"
                            value="<?php echo $fisik['tanda_istimewa'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Warna Kulit:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="warna_kulit"
                            value="<?php echo $fisik['warna_kulit'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Jenis Rambut:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jenis_rambut"
                            value="<?php echo $fisik['jenis_rambut'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Bentuk Muka:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bentuk_muka"
                            value="<?php echo $fisik['bentuk_muka'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Rumus Sidik Jari:</strong>
                    <div class="col-sm-9">
                        <select class="form-select" name="punya_sidik_jari">
                            <option value="ya" <?php echo strtolower($fisik['punya_sidik_jari']) == 'ya' ? 'selected' : '' ?>>Ya</option>
                            <option value="tidak" <?php echo strtolower($fisik['punya_sidik_jari']) == 'tidak' ? 'selected' : '' ?>>Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Kategori 6: Foto -->
                <h6 class="fw-bold text-primary mb-3">Kategori 6: Foto</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Foto Diri:</strong><br>
                        <img src="<?php echo $pengajuan['foto_diri'] ?>" alt="Foto Diri" width="300"
                            class="img-thumbnail">
                    </div>
                    <div class="col-md-4">
                        <strong>Foto KK:</strong><br>
                        <img src="<?php echo $lampiran['foto_kk'] ?>" alt="Foto KK" width="300" class="img-thumbnail">
                    </div>
                    <div class="col-md-4">
                        <strong>Foto Akte Ijazah:</strong><br>
                        <img src="<?php echo $lampiran['foto_akta_ijazah'] ?>" alt="Foto Akte Ijazah" width="300"
                            class="img-thumbnail">
                    </div>
                </div>

                <!-- Kategori 7: Lainnya -->
<h6 class="fw-bold text-primary mb-3">Kategori 7: Lainnya</h6>
                <!-- <div class="border p-2 rounded mb-2">
                <strong>Riwayat Pekerjaan / Negara dikunjungi:</strong> 
                <input class="form-check-input" type="checkbox" value="<?php echo $keterangan['riwayat_pekerjaan'] ?>"/> 
            </div> -->
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Pekerjaan / Negara:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="negara_dikunjungi"
                            value="<?php echo $keterangan['negara_dikunjungi'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Kesenangan Hobi:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="hobi" value="<?php echo $keterangan['hobi'] ?>" />
                    </div>
                </div>
                <div class="border p-2 rounded mb-2 row align-items-center">
                    <strong class="col-sm-3">Alamat Sekarang:</strong>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="alamat_mudah_dihubungi"
                            value="<?php echo $keterangan['alamat_mudah_dihubungi'] ?>" />
                    </div>
                </div>
            </div>
            <div class=" d-block text-start">
                <!-- Form input poin riwayat kriminal dan status hukum -->
                <input type="hidden" name="pengajuan_id" value="<?= $pid ?>">
                <input type="hidden" name="verifikasi_id"
                    value="<?php echo $verifikasi == null ? '' : $verifikasi['id'] ?>">

                    <h6 class="fw-bold text-primary mb-3">Verifikasi</h6>
                <!-- <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="poin_riwayat_kriminal" class="form-label">Poin Riwayat Kriminal</label>
                        <input type="number" class="form-control" id="poin_riwayat_kriminal" name="poin_riwayat_kriminal"
                            value="<?php //echo $verifikasi == null ? '' : $verifikasi['riwayat_kriminal'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="poin_status_hukum" class="form-label">Poin Status Hukum</label>
                        <input type="number" class="form-control" id="poin_status_hukum" name="poin_status_hukum"
                            value="<?php //echo $verifikasi == null ? '' : $verifikasi['status_hukum'] ?>" required>
                    </div>
                </div> -->
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="berada_indonesia_dari" class="form-label">Berada di Indonesia Dari</label>
                        <input type="text" class="form-control" id="berada_indonesia_dari" name="berada_indonesia_dari" value="<?php echo $verifikasi == null ? '' : $verifikasi['berada_indonesia_dari'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="berada_indonesia_sampai" class="form-label">Sampai</label>
                        <input type="text" class="form-control" id="berada_indonesia_sampai" name="berada_indonesia_sampai" value="<?php echo $verifikasi == null ? '' : $verifikasi['berada_indonesia_sampai'] ?>" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="berlaku_dari" class="form-label">Berlaku Dari Tanggal</label>
                        <input type="text" class="form-control" id="berlaku_dari" name="berlaku_dari" value="<?php echo $verifikasi == null ? '' : $verifikasi['berlaku_dari'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="berlaku_sampai" class="form-label">Sampai Dengan</label>
                        <input type="text" class="form-control" id="berlaku_sampai" name="berlaku_sampai" value="<?php echo $verifikasi == null ? '' : $verifikasi['berlaku_sampai'] ?>" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label for="dicetak_di" class="form-label">Dicetak di</label>
                        <input type="text" class="form-control" id="dicetak_di" name="dicetak_di" value="<?php echo $verifikasi == null ? '' : $verifikasi['dicetak_di'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_cetak" class="form-label">Pada Tanggal</label>
                        <input type="text" class="form-control" id="tanggal_cetak" name="tanggal_cetak" value="<?php echo $verifikasi == null ? '' : $verifikasi['tanggal_cetak'] ?>" required>
                    </div>
                </div>
                <?php if (!isset($_GET['detail']) || !$_GET['detail'] == true): ?>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Verifikasi</button>
                    </div>
                <?php endif ?>
            </div>

        </main>
    </form>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>