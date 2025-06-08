<?php

$uid = $_SESSION['user_id'] ?? null;
if (!isset($_SESSION['user_id']) || !$uid)
  header("Location: login_admin.php?page=data_pemohon");

if (!$uid) {
  die("ID pengajuan tidak ditemukan.");
}

// Ambil data utama
$q_pengajuan = mysqli_query($conn, "SELECT p.*, u.* 
    FROM pengajuan p 
    JOIN user u ON p.user_id = u.id 
    WHERE p.user_id = '$uid'");
$pengajuan = mysqli_fetch_assoc($q_pengajuan);
$pid = $pengajuan['id'] ?? null; 
// debug($pengajuan);
if ($pengajuan != NULL) {
  // Ambil data keluarga
  $q_keluarga_ayah = mysqli_query($conn, "SELECT * FROM keluarga WHERE hubungan = 'ayah' AND pengajuan_id = '$pid'");
  $q_keluarga_ibu = mysqli_query($conn, "SELECT * FROM keluarga WHERE hubungan = 'ibu' AND pengajuan_id = '$pid'");
  $keluarga_ayah = mysqli_fetch_assoc($q_keluarga_ayah);
  $keluarga_ibu = mysqli_fetch_assoc($q_keluarga_ibu);

  // debug($keluarga_ayah); d
  // debug($keluarga_ibu); d
  
  // Ambil data pendidikan
  $q_pendidikan = mysqli_query($conn, "SELECT * FROM pendidikan WHERE pengajuan_id = '$pid'");
  $pendidikan = mysqli_fetch_assoc($q_pendidikan);
  // debug($pendidikan); d
  
  // Ambil data pidana
  $q_pidana = mysqli_query($conn, "SELECT * FROM pidana WHERE pengajuan_id = '$pid'");
  $pidana = mysqli_fetch_assoc($q_pidana);
  // debug($pidana); d
  
  // Ambil data fisik
  $q_fisik = mysqli_query($conn, "SELECT * FROM fisik WHERE pengajuan_id = '$pid'");
  $fisik = mysqli_fetch_assoc($q_fisik);
  // debug($fisik); d
  
  // Ambil data lampiran
  $q_lampiran = mysqli_query($conn, "SELECT * FROM lampiran WHERE pengajuan_id = '$pid'");
  $lampiran = mysqli_fetch_assoc($q_lampiran);
  // debug($lampiran);
  
  // Ambil data keterangan
  $q_keterangan = mysqli_query($conn, "SELECT * FROM keterangan WHERE pengajuan_id = '$pid'");
  $keterangan = mysqli_fetch_assoc($q_keterangan);
  // debug($keterangan); d
  
  // Ambil verifikasi admin (jika ada)
  $q_verifikasi = mysqli_query($conn, "SELECT * FROM verifikasi_admin WHERE pengajuan_id = '$pid'");
  $verifikasi = mysqli_fetch_assoc($q_verifikasi);
  // debug($verifikasi);
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sistem Pembuatan SKCK Online Polsek</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      overflow-x: hidden;
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar styling */
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 230px;
      background: #343a40;
      color: #ced4da;
      display: flex;
      flex-direction: column;
      padding: 1rem 1rem 2rem;
    }

    #sidebar .user-name {
      font-weight: 600;
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 2rem;
      border-bottom: 1px solid #495057;
      padding-bottom: 0.5rem;
    }

    #sidebar .nav-link {
      color: #adb5bd;
      font-weight: 500;
      margin-bottom: 0.5rem;
      border-radius: 0.3rem;
    }

    #sidebar .nav-link:hover,
    #sidebar .nav-link.active {
      background-color: #495057;
      color: #fff;
    }

    /* Top header */
    #top-header {
      margin-left: 230px;
      background-color: #0d6efd;
      color: white;
      padding: 1rem 2rem;
      font-weight: 600;
      font-size: 1.25rem;
      box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
      position: sticky;
      top: 0;
      z-index: 1040;
    }

    /* Content container */
    #content {
      margin-left: 230px;
      padding: 1.5rem 2rem 3rem;
      background-color: #f8f9fa;
      min-height: calc(100vh - 56px);
    }

    .form-step {
      display: none;
    }

    .form-step.active {
      display: block;
    }

    .nav-tabs .nav-link.active {
      background-color: #0d6efd;
      color: white;
    }

    .required::after {
      content: "*";
      color: red;
      margin-left: 3px;
    }

    .custom-upload-label {
      cursor: pointer;
    }

    @media (max-width: 768px) {
      #sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        padding: 0.5rem 1rem;
        align-items: center;
      }

      #sidebar .user-name {
        margin-bottom: 0;
        margin-right: 1rem;
        border-bottom: none;
        font-size: 1rem;
      }

      #sidebar .nav {
        flex-direction: row;
        gap: 0.5rem;
        flex-wrap: wrap;
      }

      #content {
        margin-left: 0;
        padding: 1rem;
      }

      #top-header {
        margin-left: 0;
        padding: 1rem;
        font-size: 1.1rem;
      }

      .small-news-list {
        justify-content: center;
      }

      .small-news {
        max-width: 100%;
        min-width: auto;
        flex-basis: 100%;
      }
    }
  </style>
</head>

<body>

  <nav id="sidebar" class="shadow">
    <div class="user-name">Selamat datang, Budi</div>
    <nav class="nav flex-column">
      <a href="index.php?page=home" class="nav-link">Home</a>
      <a href="index.php?page=pengajuan" class="nav-link active">Pengajuan SKCK</a>
      <a href="logout.php" class="nav-link text-danger">Logout</a>
    </nav>
  </nav>

  <header id="top-header">
    Sistem Pembuatan SKCK Online Polsek
  </header>

  <!-- Content -->
  <main id="content">
    <div class="container py-5">
      <h2 class="mb-4 text-center">Form Pengajuan SKCK Bertahap</h2>
      <?php if ($pengajuan != NULL && !isset($_GET['edit'])): ?>
        <div class="text-center mb-4">
          <a href="index.php?page=pengajuan&edit=true" class="btn btn-primary">Edit Your Response</a>
        </div>
      <?php else: ?>
        <!-- Form -->
        <form id="skckForm" method="POST" action="user/simpan-pengajuan.php" enctype="multipart/form-data" novalidate>
          <?php if ($pengajuan != NULL):?>
            <input type="hidden" value="<?= $pid?>" name="pengajuan_id">
          <?php endif?>
          <div class="row">
            <ul class="col-md-4 nav nav-pills flex-column mb-4" id="formTabs" role="tablist">
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link active w-100 text-start" id="step1-tab" data-bs-toggle="tab"
                  data-bs-target="#step1" type="button" role="tab" aria-controls="step1" aria-selected="true">1. Jenis
                  Pendaftaran</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2"
                  type="button" role="tab" aria-controls="step2" aria-selected="false">2. Jenis Keperluan</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step3-tab" data-bs-toggle="tab" data-bs-target="#step3"
                  type="button" role="tab" aria-controls="step3" aria-selected="false">3. Data Pribadi</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step4-tab" data-bs-toggle="tab" data-bs-target="#step4"
                  type="button" role="tab" aria-controls="step4" aria-selected="false">4. Hubungan Keluarga</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step5-tab" data-bs-toggle="tab" data-bs-target="#step5"
                  type="button" role="tab" aria-controls="step5" aria-selected="false">5. Riwayat Pendidikan</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step6-tab" data-bs-toggle="tab" data-bs-target="#step6"
                  type="button" role="tab" aria-controls="step6" aria-selected="false">6. Perkara Pidana</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step7-tab" data-bs-toggle="tab" data-bs-target="#step7"
                  type="button" role="tab" aria-controls="step7" aria-selected="false">7. Ciri Fisik</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step8-tab" data-bs-toggle="tab" data-bs-target="#step8"
                  type="button" role="tab" aria-controls="step8" aria-selected="false">8. Lampiran</button>
              </li>
              <li class="nav-item mb-2" role="presentation">
                <button class="nav-link w-100 text-start" id="step9-tab" data-bs-toggle="tab" data-bs-target="#step9"
                  type="button" role="tab" aria-controls="step9" aria-selected="false">9. Keterangan</button>
              </li>
            </ul>

            <div class="col-md-7 tab-content">
              <!-- Step 1 -->
              <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                <div class="mb-3">
                  <label for="jenisPendaftaran" class="form-label required">Jenis Pendaftaran</label>
                  <select class="form-select" id="jenisPendaftaran" name="jenisPendaftaran" required>
                    <option value="" <?php echo (!isset($pengajuan['jenis_pendaftaran'])) ? 'selected' : ''; ?> disabled>Pilih jenis pendaftaran</option>
                    <option value="Buat Baru" <?php echo (isset($pengajuan['jenis_pendaftaran']) && $pengajuan['jenis_pendaftaran'] == 'Buat Baru') ? 'selected' : ''; ?>>Buat Baru</option>
                    <option value="Perpanjangan" <?php echo (isset($pengajuan['jenis_pendaftaran']) && $pengajuan['jenis_pendaftaran'] == 'Perpanjangan') ? 'selected' : ''; ?>>Perpanjangan</option>
                    <option value="Penggantian" <?php echo (isset($pengajuan['jenis_pendaftaran']) && $pengajuan['jenis_pendaftaran'] == 'Penggantian') ? 'selected' : ''; ?>>Penggantian</option>
                  </select>
                  <div class="invalid-feedback">Harap memilih jenis pendaftaran.</div>
                </div>
              </div>

              <!-- Step 2 -->
              <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                <div class="mb-3">
                  <label for="jenisKeperluan" class="form-label required">Jenis Keperluan (Kategori)</label>
                  <select class="form-select" id="jenisKeperluan" name="jenisKeperluan" required>
                    <option value="" <?php echo (!isset($pengajuan['jenis_keperluan'])) ? 'selected' : ''; ?> disabled>Pilih jenis keperluan</option>
                    <option value="Kepentingan Kerja" <?php echo (isset($pengajuan['jenis_keperluan']) && $pengajuan['jenis_keperluan'] == 'Kepentingan Kerja') ? 'selected' : ''; ?>>Kepentingan Kerja</option>
                    <option value="Kepentingan Sekolah" <?php echo (isset($pengajuan['jenis_keperluan']) && $pengajuan['jenis_keperluan'] == 'Kepentingan Sekolah') ? 'selected' : ''; ?>>Kepentingan Sekolah</option>
                    <option value="Kepentingan Organisasi" <?php echo (isset($pengajuan['jenis_keperluan']) && $pengajuan['jenis_keperluan'] == 'Kepentingan Organisasi') ? 'selected' : ''; ?>>Kepentingan Organisasi</option>
                    <option value="Lainnya" <?php echo (isset($pengajuan['jenis_keperluan']) && $pengajuan['jenis_keperluan'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Harap memilih jenis keperluan.</div>
                </div>
                <div class="mb-3">
                  <label for="detailKeperluan" class="form-label required">Detail Keperluan</label>
                  <input type="text" class="form-control" id="detailKeperluan" name="detailKeperluan"
                    placeholder="Masukkan detail keperluan" value="<?php echo isset($pengajuan['detail_keperluan']) ? $pengajuan['detail_keperluan'] : ''; ?>" required />
                  <div class="invalid-feedback">Harap mengisi detail keperluan.</div>
                </div>
                <div class="mb-3">
                  <label for="tingkatWewenang" class="form-label">Tingkat Wewenang Diperlukan</label>
                  <input type="text" class="form-control" id="tingkatWewenang" name="tingkatWewenang" value="<?php echo isset($pengajuan['tingkat_wewenang']) ? $pengajuan['tingkat_wewenang'] : ''; ?>" readonly />
                </div>
              </div>

              <!-- Step 3 -->
              <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                <div class="mb-3">
                  <label class="form-label required">Upload Foto Diri</label>
                  <div class="alert alert-info">
                    <small>
                      <ul class="mb-0">
                        <li>Background foto harus berwarna merah</li>
                        <li>Ukuran foto 4x6</li>
                        <li>Ukuran file maksimal 200KB</li>
                      </ul>
                    </small>
                  </div>
                  <?php if(isset($pengajuan['foto_diri']) && !empty($pengajuan['foto_diri'])): ?>
                    <div class="mb-2">
                      <img src="./<?php echo $pengajuan['foto_diri']; ?>" alt="Foto Diri" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" class="form-control" id="fotoDiri" name="fotoDiri" accept="image/*" 
                    <?php echo (!isset($pengajuan['foto_diri']) ? 'required' : ''); ?>
                    onchange="handleFileSize(this, 200)" />
                  <div class="invalid-feedback">Harap upload foto diri sesuai ketentuan.</div>
                  <?php if(isset($pengajuan['foto_diri'])): ?>
                    <input type="hidden" name="foto_diri_existing" value="<?php echo $pengajuan['foto_diri']; ?>">
                    <small class="text-muted">Upload foto baru untuk mengganti foto yang ada</small>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Step 4 DONE --> 
              <div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
                <h5>Data Ayah</h5>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="namaAyah" class="form-label required">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaAyah" name="namaAyah" value="<?php echo isset($keluarga_ayah['nama']) ? $keluarga_ayah['nama'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi nama lengkap ayah.</div>
                  </div>
                  <div class="col-md-2">
                    <label for="umurAyah" class="form-label required">Umur</label>
                    <input type="number" min="0" class="form-control" id="umurAyah" name="umurAyah" value="<?php echo isset($keluarga_ayah['umur']) ? $keluarga_ayah['umur'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi umur ayah.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="agamaAyah" class="form-label required">Agama</label>
                    <select class="form-select" id="agamaAyah" name="agamaAyah" required>
                      <option value="" disabled selected>Pilih agama</option>
                      <option value="Islam" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'islam') ? 'selected' : ''; ?>>Islam</option>
                      <option value="Kristen" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'kristen') ? 'selected' : ''; ?>>Kristen</option>
                      <option value="Katolik" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'katolik') ? 'selected' : ''; ?>>Katolik</option>
                      <option value="Hindu" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'hindu') ? 'selected' : ''; ?>>Hindu</option>
                      <option value="Buddha" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'buddha') ? 'selected' : ''; ?>>Buddha</option>
                      <option value="Konghucu" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                      <option value="Lainnya" <?php echo (isset($keluarga_ayah['agama']) && strtolower($keluarga_ayah['agama']) == 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                    <div class="invalid-feedback">Harap isi agama ayah.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="wargaAyah" class="form-label required">Warganegara</label>
                    <select class="form-select" id="wargaAyah" name="wargaAyah" required>
                      <option value="" disabled selected>Pilih</option>
                      <option value="WNI" <?php echo (isset($keluarga_ayah['warganegara']) && $keluarga_ayah['warganegara'] == 'WNI') ? 'selected' : ''; ?>>WNI</option>
                      <option value="WNA" <?php echo (isset($keluarga_ayah['warganegara']) && $keluarga_ayah['warganegara'] == 'WNA') ? 'selected' : ''; ?>>WNA</option>
                    </select>
                    <div class="invalid-feedback">Harap pilih warganegara ayah.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="pekerjaanAyah" class="form-label required">Pekerjaan</label>
                    <input type="text" class="form-control" id="pekerjaanAyah" name="pekerjaanAyah" value="<?php echo isset($keluarga_ayah['pekerjaan']) ? $keluarga_ayah['pekerjaan'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi pekerjaan ayah.</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label required">Apakah Serumah</label><br />
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="serumahAyah" id="serumahAyahYa" value="Ya"
                        <?php echo (isset($keluarga_ayah['serumah']) && strtolower($keluarga_ayah['serumah']) == 'ya') ? 'checked' : ''; ?> required />
                      <label class="form-check-label" for="serumahAyahYa">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="serumahAyah" id="serumahAyahTidak" value="Tidak"
                        <?php echo (isset($keluarga_ayah['serumah']) && strtolower($keluarga_ayah['serumah']) == 'tidak') ? 'checked' : ''; ?> required />
                      <label class="form-check-label" for="serumahAyahTidak">Tidak</label>
                    </div>
                    <div class="invalid-feedback d-block">Harap pilih status serumah ayah.</div>
                  </div>
                </div>
                <hr class="my-4" />
                <h5>Data Ibu</h5>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="namaIbu" class="form-label required">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaIbu" name="namaIbu" value="<?php echo isset($keluarga_ibu['nama']) ? $keluarga_ibu['nama'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi nama lengkap ibu.</div>
                  </div>
                  <div class="col-md-2">
                    <label for="umurIbu" class="form-label required">Umur</label>
                    <input type="number" min="0" class="form-control" id="umurIbu" name="umurIbu" value="<?php echo isset($keluarga_ibu['umur']) ? $keluarga_ibu['umur'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi umur ibu.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="agamaIbu" class="form-label required">Agama</label>
                    <select class="form-select" id="agamaIbu" name="agamaIbu" required>
                      <option value="" disabled selected>Pilih agama</option>
                      <option value="Islam" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'islam') ? 'selected' : ''; ?>>Islam</option>
                      <option value="Kristen" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'kristen') ? 'selected' : ''; ?>>Kristen</option>
                      <option value="Katolik" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'katolik') ? 'selected' : ''; ?>>Katolik</option>
                      <option value="Hindu" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'hindu') ? 'selected' : ''; ?>>Hindu</option>
                      <option value="Buddha" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'buddha') ? 'selected' : ''; ?>>Buddha</option>
                      <option value="Konghucu" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                      <option value="Lainnya" <?php echo (isset($keluarga_ibu['agama']) && strtolower($keluarga_ibu['agama']) == 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                    <div class="invalid-feedback">Harap isi agama ibu.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="wargaIbu" class="form-label required">Warganegara</label>
                    <select class="form-select" id="wargaIbu" name="wargaIbu" required>
                      <option value="" disabled selected>Pilih</option>
                      <option value="WNI" <?php echo (isset($keluarga_ibu['warganegara']) && $keluarga_ibu['warganegara'] == 'WNI') ? 'selected' : ''; ?>>WNI</option>
                      <option value="WNA" <?php echo (isset($keluarga_ibu['warganegara']) && $keluarga_ibu['warganegara'] == 'WNA') ? 'selected' : ''; ?>>WNA</option>
                    </select>
                    <div class="invalid-feedback">Harap pilih warganegara ibu.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="pekerjaanIbu" class="form-label required">Pekerjaan</label>
                    <input type="text" class="form-control" id="pekerjaanIbu" name="pekerjaanIbu" value="<?php echo isset($keluarga_ibu['pekerjaan']) ? $keluarga_ibu['pekerjaan'] : ''; ?>" required />
                    <div class="invalid-feedback">Harap isi pekerjaan ibu.</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label required">Apakah Serumah</label><br />
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="serumahIbu" id="serumahIbuYa" value="Ya"
                        <?php echo (isset($keluarga_ibu['serumah']) && strtolower($keluarga_ibu['serumah']) == 'ya') ? 'checked' : ''; ?> required />
                      <label class="form-check-label" for="serumahIbuYa">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="serumahIbu" id="serumahIbuTidak" value="Tidak"
                        <?php echo (isset($keluarga_ibu['serumah']) && strtolower($keluarga_ibu['serumah']) == 'tidak') ? 'checked' : ''; ?> required />
                      <label class="form-check-label" for="serumahIbuTidak">Tidak</label>
                    </div>
                    <div class="invalid-feedback d-block">Harap pilih status serumah ibu.</div>
                  </div>
                </div>
              </div>

              <!-- Step 5 DONE -->
              <div class="tab-pane fade" id="step5" role="tabpanel" aria-labelledby="step5-tab">
                <div class="mb-3">
                  <label class="form-label required">Sekolah di</label>
                  <select class="form-select" id="sekolahDi" name="sekolahDi" required>
                    <option value="" disabled <?php echo (!isset($pendidikan['lokasi_sekolah'])) ? 'selected' : ''; ?>>Pilih</option>
                    <option value="Dalam Negeri" <?php echo (isset($pendidikan['lokasi_sekolah']) && strtolower($pendidikan['lokasi_sekolah']) == 'dalam negeri') ? 'selected' : ''; ?>>Dalam Negeri</option>
                    <option value="Luar Negeri" <?php echo (isset($pendidikan['lokasi_sekolah']) && strtolower($pendidikan['lokasi_sekolah']) == 'luar negeri') ? 'selected' : ''; ?>>Luar Negeri</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih sekolah di.</div>
                </div>
                <div class="mb-3">
                  <label for="tingkatPendidikan" class="form-label required">Tingkat Pendidikan</label>
                  <select class="form-select" id="tingkatPendidikan" name="tingkatPendidikan" required>
                    <option value="" disabled <?php echo (!isset($pendidikan['tingkat'])) ? 'selected' : ''; ?>>Pilih tingkat pendidikan</option>
                    <option value="SD" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'SD') ? 'selected' : ''; ?>>SD</option>
                    <option value="SMP" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'SMP') ? 'selected' : ''; ?>>SMP</option>
                    <option value="SMA" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'SMA') ? 'selected' : ''; ?>>SMA</option>
                    <option value="SMK" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'SMK') ? 'selected' : ''; ?>>SMK</option>
                    <option value="D1" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'D1') ? 'selected' : ''; ?>>D1</option>
                    <option value="D2" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'D2') ? 'selected' : ''; ?>>D2</option>
                    <option value="D3" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'D3') ? 'selected' : ''; ?>>D3</option>
                    <option value="D4" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'D4') ? 'selected' : ''; ?>>D4</option>
                    <option value="S1" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'S1') ? 'selected' : ''; ?>>S1</option>
                    <option value="S2" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'S2') ? 'selected' : ''; ?>>S2</option>
                    <option value="S3" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'S3') ? 'selected' : ''; ?>>S3</option>
                    <option value="Lainnya" <?php echo (isset($pendidikan['tingkat']) && $pendidikan['tingkat'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Harap isi tingkat pendidikan.</div>
                </div>
                <div class="mb-3">
                  <label for="namaInstitusi" class="form-label required">Nama Institusi</label>
                  <input type="text" class="form-control" id="namaInstitusi" name="namaInstitusi" value="<?php echo isset($pendidikan['nama_institusi']) ? $pendidikan['nama_institusi'] : ''; ?>" required />
                  <div class="invalid-feedback">Harap isi nama institusi.</div>
                </div>
                <div class="mb-3">
                  <label for="tahunLulus" class="form-label required">Tahun Lulus</label>
                  <input type="number" min="1900" max="2099" step="1" class="form-control" id="tahunLulus" name="tahunLulus" value="<?php echo isset($pendidikan['tahun_lulus']) ? $pendidikan['tahun_lulus'] : ''; ?>" required />
                  <div class="invalid-feedback">Harap isi tahun lulus dengan benar.</div>
                </div>
              </div>

              <!-- Step 6 DONE -->
              <div class="tab-pane fade" id="step6" role="tabpanel" aria-labelledby="step6-tab">
                <div class="mb-3">
                  <label class="form-label required">Apakah tersangkut perkara pidana</label>
                  <select class="form-select" id="tersangkutPidana" name="tersangkutPidana" required>
                    <option value="" disabled selected>Pilih</option>
                    <option value="Ya" <?php echo (isset($pidana['pernah_terlibat']) && strtolower($pidana['pernah_terlibat']) == 'ya') ? 'selected' : ''; ?>>Pernah</option>
                    <option value="Tidak" <?php echo (isset($pidana['pernah_terlibat']) && strtolower($pidana['pernah_terlibat']) == 'tidak') ? 'selected' : ''; ?>>Tidak Pernah</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih jawaban.</div>
                </div>
                <div class="mb-3">
                  <label for="perkaraApa" class="form-label">Perkara Apa?</label>
                  <input type="text" class="form-control" id="perkaraApa" name="perkaraApa" value="<?php echo isset($pidana['detail_perkara']) ? $pidana['detail_perkara'] : ''; ?>" <?php echo (!isset($pidana['pernah_terlibat']) || strtolower($pidana['pernah_terlibat']) != 'ya') ? 'disabled' : ''; ?> />
                </div>
                <div class="mb-3">
                  <label for="keputusanVonis" class="form-label">Keputusan dan Vonis Hakim</label>
                  <input type="text" class="form-control" id="keputusanVonis" name="keputusanVonis" value="<?php echo isset($pidana['keputusan']) ? $pidana['keputusan'] : ''; ?>" <?php echo (!isset($pidana['pernah_terlibat']) || strtolower($pidana['pernah_terlibat']) != 'ya') ? 'disabled' : ''; ?> />
                </div>

                <div class="mb-3">
                  <label class="form-label required">Apakah sedang dalam proses perkara pidana?</label>
                  <select class="form-select" id="prosesPidana" name="prosesPidana" required>
                    <option value="" disabled selected>Pilih</option>
                    <option value="Ya" <?php echo (isset($pidana['sedang_proses']) && strtolower($pidana['sedang_proses']) == 'ya') ? 'selected' : ''; ?>>Ya</option>
                    <option value="Tidak" <?php echo (isset($pidana['sedang_proses']) && strtolower($pidana['sedang_proses']) == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih jawaban.</div>
                </div>
                <div class="mb-3">
                  <label for="kasusApa" class="form-label">Kasus Apa?</label>
                  <input type="text" class="form-control" id="kasusApa" name="kasusApa" value="<?php echo isset($pidana['kasus_sedang_diproses']) ? $pidana['kasus_sedang_diproses'] : ''; ?>" <?php echo (!isset($pidana['sedang_proses']) || strtolower($pidana['sedang_proses']) != 'ya') ? 'disabled' : ''; ?> />
                </div>
                <div class="mb-3">
                  <label for="prosesHukum" class="form-label">Sampai Mana Proses Hukumnya?</label>
                  <input type="text" class="form-control" id="prosesHukum" name="prosesHukum" value="<?php echo isset($pidana['sampai_mana']) ? $pidana['sampai_mana'] : ''; ?>" <?php echo (!isset($pidana['sedang_proses']) || strtolower($pidana['sedang_proses']) != 'ya') ? 'disabled' : ''; ?> />
                </div>

                <div class="mb-3">
                  <label class="form-label required">Apakah pernah melanggar hukum dan norma sosial?</label>
                  <select class="form-select" id="melanggarHukum" name="melanggarHukum" required>
                    <option value="" disabled selected>Pilih</option>
                    <option value="Ya" <?php echo (isset($pidana['pelanggaran_norma']) && strtolower($pidana['pelanggaran_norma']) == 'ya') ? 'selected' : ''; ?>>Pernah</option>
                    <option value="Tidak" <?php echo (isset($pidana['pelanggaran_norma']) && strtolower($pidana['pelanggaran_norma']) == 'tidak') ? 'selected' : ''; ?>>Tidak Pernah</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih jawaban.</div>
                </div>
                <div class="mb-3">
                  <label for="pelanggaranApa" class="form-label">Pelanggaran Hukum atau Norma Sosial Apa?</label>
                  <input type="text" class="form-control" id="pelanggaranApa" name="pelanggaranApa" value="<?php echo isset($pidana['detail_norma']) ? $pidana['detail_norma'] : ''; ?>" <?php echo (!isset($pidana['pelanggaran_norma']) || strtolower($pidana['pelanggaran_norma']) != 'ya') ? 'disabled' : ''; ?> />
                </div>
                <div class="mb-3">
                  <label for="prosesPelanggaran" class="form-label">Sampai Sejauh Mana Prosesnya?</label>
                  <input type="text" class="form-control" id="prosesPelanggaran" name="prosesPelanggaran" value="<?php echo isset($pidana['proses_norma']) ? $pidana['proses_norma'] : ''; ?>" <?php echo (!isset($pidana['pelanggaran_norma']) || strtolower($pidana['pelanggaran_norma']) != 'ya') ? 'disabled' : ''; ?> />
                </div>
              </div>

              <!-- Step 7 DONE -->
              <div class="tab-pane fade" id="step7" role="tabpanel" aria-labelledby="step7-tab">
                <div class="mb-3">
                  <label for="tinggiBadan" class="form-label required">Tinggi Badan (cm)</label>
                  <input type="number" min="0" class="form-control" id="tinggiBadan" name="tinggiBadan" value="<?php echo isset($fisik['tinggi']) ? $fisik['tinggi'] : ''; ?>" required />
                  <div class="invalid-feedback">Harap isi tinggi badan.</div>
                </div>
                <div class="mb-3">
                  <label for="beratBadan" class="form-label required">Berat Badan (kg)</label>
                  <input type="number" min="0" class="form-control" id="beratBadan" name="beratBadan" value="<?php echo isset($fisik['berat']) ? $fisik['berat'] : ''; ?>" required />
                  <div class="invalid-feedback">Harap isi berat badan.</div>
                </div>
                <div class="mb-3">
                  <label for="tandaIstimewa" class="form-label">Tanda Istimewa</label>
                  <input type="text" class="form-control" id="tandaIstimewa" name="tandaIstimewa" value="<?php echo isset($fisik['tanda_istimewa']) ? $fisik['tanda_istimewa'] : ''; ?>" />
                </div>
                <div class="mb-3">
                  <label for="warnaKulit" class="form-label required">Warna Kulit</label>
                  <select class="form-select" id="warnaKulit" name="warnaKulit" required>
                    <option value="" disabled selected>Pilih warna kulit</option>
                    <option value="Hitam" <?php echo (isset($fisik['warna_kulit']) && $fisik['warna_kulit'] == 'Hitam') ? 'selected' : ''; ?>>Hitam</option>
                    <option value="Putih" <?php echo (isset($fisik['warna_kulit']) && $fisik['warna_kulit'] == 'Putih') ? 'selected' : ''; ?>>Putih</option>
                    <option value="Sawo Matang" <?php echo (isset($fisik['warna_kulit']) && $fisik['warna_kulit'] == 'Sawo Matang') ? 'selected' : ''; ?>>Sawo Matang</option>
                    <option value="Kuning Langsat" <?php echo (isset($fisik['warna_kulit']) && $fisik['warna_kulit'] == 'Kuning Langsat') ? 'selected' : ''; ?>>Kuning Langsat</option>
                    <option value="Lainnya" <?php echo (isset($fisik['warna_kulit']) && $fisik['warna_kulit'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih warna kulit.</div>
                </div>
                <div class="mb-3">
                  <label for="jenisRambut" class="form-label required">Jenis Rambut</label>
                  <select class="form-select" id="jenisRambut" name="jenisRambut" required>
                    <option value="" disabled selected>Pilih jenis rambut</option>
                    <option value="Lurus" <?php echo (isset($fisik['jenis_rambut']) && $fisik['jenis_rambut'] == 'Lurus') ? 'selected' : ''; ?>>Lurus</option>
                    <option value="Ikal" <?php echo (isset($fisik['jenis_rambut']) && $fisik['jenis_rambut'] == 'Ikal') ? 'selected' : ''; ?>>Ikal</option>
                    <option value="Keriting" <?php echo (isset($fisik['jenis_rambut']) && $fisik['jenis_rambut'] == 'Keriting') ? 'selected' : ''; ?>>Keriting</option>
                    <option value="Gimbal" <?php echo (isset($fisik['jenis_rambut']) && $fisik['jenis_rambut'] == 'Gimbal') ? 'selected' : ''; ?>>Gimbal</option>
                    <option value="Lainnya" <?php echo (isset($fisik['jenis_rambut']) && $fisik['jenis_rambut'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih jenis rambut.</div>
                </div>
                <div class="mb-3">
                  <label for="bentukMuka" class="form-label required">Bentuk Muka</label>
                  <select class="form-select" id="bentukMuka" name="bentukMuka" required>
                    <option value="" disabled selected>Pilih bentuk muka</option>
                    <option value="Oval" <?php echo (isset($fisik['bentuk_muka']) && $fisik['bentuk_muka'] == 'Oval') ? 'selected' : ''; ?>>Oval</option>
                    <option value="Bulat" <?php echo (isset($fisik['bentuk_muka']) && $fisik['bentuk_muka'] == 'Bulat') ? 'selected' : ''; ?>>Bulat</option>
                    <option value="Kotak" <?php echo (isset($fisik['bentuk_muka']) && $fisik['bentuk_muka'] == 'Kotak') ? 'selected' : ''; ?>>Kotak</option>
                    <option value="Lonjong" <?php echo (isset($fisik['bentuk_muka']) && $fisik['bentuk_muka'] == 'Lonjong') ? 'selected' : ''; ?>>Lonjong</option>
                    <option value="Lainnya" <?php echo (isset($fisik['bentuk_muka']) && $fisik['bentuk_muka'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Harap pilih bentuk muka.</div>
                </div>
                <div class="mb-3">
                  <label class="form-label required">Apakah Punya Rumus Sidik Jari?</label><br />
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rumusSidikJari" id="rumusPunya" value="Ya"
                      <?php echo (isset($fisik['punya_sidik_jari']) && strtolower($fisik['punya_sidik_jari']) == 'ya') ? 'checked' : ''; ?> required />
                    <label class="form-check-label" for="rumusPunya">Punya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rumusSidikJari" id="rumusTidakPunya" value="Tidak"
                      <?php echo (isset($fisik['punya_sidik_jari']) && strtolower($fisik['punya_sidik_jari']) == 'tidak') ? 'checked' : ''; ?> required />
                    <label class="form-check-label" for="rumusTidakPunya">Tidak Punya</label>
                  </div>
                  <div class="invalid-feedback d-block">Harap pilih salah satu.</div>
                </div>
              </div>

              <!-- Step 8 -->
              <div class="tab-pane fade" id="step8" role="tabpanel" aria-labelledby="step8-tab">
                <div class="mb-3">
                  <label for="fotoKK" class="form-label required">Foto KK</label>
                  <?php if(isset($lampiran['foto_kk']) && !empty($lampiran['foto_kk'])): ?>
                    <div class="mb-2">
                      <img src="./<?php echo $lampiran['foto_kk']; ?>" alt="Foto KK" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" class="form-control" id="fotoKK" name="fotoKK" accept="image/*" 
                    <?php echo (!isset($lampiran['foto_kk']) ? 'required' : ''); ?> />
                  <div class="invalid-feedback">Harap upload foto KK.</div>
                  <?php if(isset($lampiran['foto_kk'])): ?>
                    <input type="hidden" name="foto_kk_existing" value="<?php echo $lampiran['foto_kk']; ?>">
                    <small class="text-muted">Upload foto baru untuk mengganti foto yang ada</small>
                  <?php endif; ?>
                </div>
                <div class="mb-3">
                  <label for="fotoAkteIjazah" class="form-label required">Foto Akte / Ijazah</label>
                  <?php if(isset($lampiran['foto_akta_ijazah']) && !empty($lampiran['foto_akta_ijazah'])): ?>
                    <div class="mb-2">
                      <img src="./<?php echo $lampiran['foto_akta_ijazah']; ?>" alt="Foto Akte/Ijazah" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" class="form-control" id="fotoAkteIjazah" name="fotoAkteIjazah" accept="image/*"
                    <?php echo (!isset($lampiran['foto_akta_ijazah']) ? 'required' : ''); ?> />
                  <div class="invalid-feedback">Harap upload foto akte atau ijazah.</div>
                  <?php if(isset($lampiran['foto_akta_ijazah'])): ?>
                    <input type="hidden" name="foto_akta_ijazah_existing" value="<?php echo $lampiran['foto_akta_ijazah']; ?>">
                    <small class="text-muted">Upload foto baru untuk mengganti foto yang ada</small>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Step 9 DONE -->
              <div class="tab-pane fade" id="step9" role="tabpanel" aria-labelledby="step9-tab">
                <div class="mb-3 form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="riwayatPekerjaan" name="riwayatPekerjaan" 
                    <?php echo (isset($keterangan['riwayat_pekerjaan']) && $keterangan['riwayat_pekerjaan'] == 'on') ? 'checked' : ''; ?> />
                  <label class="form-check-label" for="riwayatPekerjaan">Riwayat pekerjaan/negara</label>
                </div>
                <div class="mb-3">
                  <label for="riwayatPekerjaanAtauNegara" class="form-label">Riwayat pekerjaan/negara</label>
                  <textarea class="form-control" id="riwayatPekerjaanAtauNegara" name="riwayatPekerjaanAtauNegara" 
                    rows="3"><?php echo isset($keterangan['negara_dikunjungi']) ? $keterangan['negara_dikunjungi'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="kesenanganHobi" class="form-label">Kesenangan/Hobi</label>
                  <textarea class="form-control" id="kesenanganHobi" name="kesenanganHobi" 
                    rows="3"><?php echo isset($keterangan['hobi']) ? $keterangan['hobi'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="alamatKontak" class="form-label">Alamat yang Mudah Dihubungi</label>
                  <textarea class="form-control" id="alamatKontak" name="alamatKontak" 
                    rows="3"><?php echo isset($keterangan['alamat_mudah_dihubungi']) ? $keterangan['alamat_mudah_dihubungi'] : ''; ?></textarea>
                </div>
              </div>
            </div>
          </div>
          <!-- Nav tabs for steps -->

          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary" id="prevBtn" disabled>Previous</button>
            <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
            <button type="submit" class="btn btn-success d-none" id="submitBtn">Submit</button>
          </div>
        </form>
      <?php endif; ?>
    </div>

  </main>
  <!-- End -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Add event listener to tersangkutPidana select
    document.getElementById('tersangkutPidana').addEventListener('change', function () {
      const perkaraApa = document.getElementById('perkaraApa');
      const keputusanVonis = document.getElementById('keputusanVonis');

      if (this.value === 'Ya') {
        // Enable and make required if "Ya" selected
        perkaraApa.disabled = false;
        perkaraApa.required = true;
        perkaraApa.value = '';

        keputusanVonis.disabled = false;
        keputusanVonis.required = true;
        keputusanVonis.value = '';
      } else {
        // Disable and set value to "-" if "Tidak" selected
        perkaraApa.disabled = true;
        perkaraApa.required = false;
        perkaraApa.value = '-';

        keputusanVonis.disabled = true;
        keputusanVonis.required = false;
        keputusanVonis.value = '-';
      }
    });

    document.getElementById('prosesPidana').addEventListener('change', function () {
      const kasusApa = document.getElementById('kasusApa');
      const prosesHukum = document.getElementById('prosesHukum');

      if (this.value === 'Ya') {
        // Enable and make required if "Ya" selected
        kasusApa.disabled = false;
        kasusApa.required = true;
        kasusApa.value = '';

        prosesHukum.disabled = false;
        prosesHukum.required = true;
        prosesHukum.value = '';
      } else {
        // Disable and set value to "-" if "Tidak" selected
        kasusApa.disabled = true;
        kasusApa.required = false;
        kasusApa.value = '-';

        prosesHukum.disabled = true;
        prosesHukum.required = false;
        prosesHukum.value = '-';
      }
    });

    document.getElementById('melanggarHukum').addEventListener('change', function () {
      const pelanggaranApa = document.getElementById('pelanggaranApa');
      const prosesPelanggaran = document.getElementById('prosesPelanggaran');

      if (this.value === 'Ya') {
        // Enable and make required if "Ya" selected
        pelanggaranApa.disabled = false;
        pelanggaranApa.required = true;
        pelanggaranApa.value = '';

        prosesPelanggaran.disabled = false;
        prosesPelanggaran.required = true;
        prosesPelanggaran.value = '';
      } else {
        // Disable and set value to "-" if "Tidak" selected
        pelanggaranApa.disabled = true;
        pelanggaranApa.required = false;
        pelanggaranApa.value = '-';

        prosesPelanggaran.disabled = true;
        prosesPelanggaran.required = false;
        prosesPelanggaran.value = '-';
      }
    });
  </script>

  <script>
    // Validate file size in KB
    function handleFileSize(input, maxSizeKB) {
      if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024; // Convert to KB
        if (fileSize > maxSizeKB) {
          alert(`File size must be less than ${maxSizeKB}KB. Current size: ${fileSize.toFixed(2)}KB`);
          input.value = ''; // Clear the input
        }
      }
    }
  </script>

  <script>
    (() => {
      const form = document.getElementById('skckForm');
      const tabsTrigger = [...document.querySelectorAll('#formTabs button.nav-link')];
      const nextBtn = document.getElementById('nextBtn');
      const prevBtn = document.getElementById('prevBtn');
      const submitBtn = document.getElementById('submitBtn');
      const totalSteps = tabsTrigger.length;
      let currentStep = 0;

      // Map jenis keperluan ke tingkat wewenang
      const tingkatWewenangMap = {
        'Kepentingan Kerja': 'Kantor Polisi Tingkat Kabupaten/Kota',
        'Kepentingan Sekolah': 'Kantor Polisi Tingkat Kecamatan',
        'Kepentingan Organisasi': 'Kantor Polisi Tingkat Kecamatan',
        'Lainnya': 'Tergantung Kebijakan'
      };

      const jenisKeperluanSelect = document.getElementById('jenisKeperluan');
      const tingkatWewenangInput = document.getElementById('tingkatWewenang');

      jenisKeperluanSelect.addEventListener('change', () => {
        const selected = jenisKeperluanSelect.value;
        tingkatWewenangInput.value = tingkatWewenangMap[selected] || '';
      });

      function showStep(step) {
        tabsTrigger.forEach((tab, idx) => {
          if (idx === step) {
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            const pane = document.querySelector(tab.getAttribute('data-bs-target'));
            pane.classList.add('show', 'active');
          } else {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
            const pane = document.querySelector(tab.getAttribute('data-bs-target'));
            pane.classList.remove('show', 'active');
          }
        });

        prevBtn.disabled = (step === 0);
        if (step === totalSteps - 1) {
          nextBtn.classList.add('d-none');
          submitBtn.classList.remove('d-none');
        } else {
          nextBtn.classList.remove('d-none');
          submitBtn.classList.add('d-none');
        }
      }

      nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        currentStep++;
        showStep(currentStep);
      });

      prevBtn.addEventListener('click', () => {
        currentStep--;
        showStep(currentStep);
      });

      //   form.addEventListener('submit', e => {
      //     e.preventDefault();
      //     if(!validateStep(currentStep)) return;
      //     // Here you can add code for form submission or data handling
      //     alert('Form Pengajuan SKCK berhasil disubmit!');
      //     form.reset();
      //     currentStep = 0;
      //     showStep(currentStep);
      //   });

      // Validation for current step
      function validateStep(step) {
        const pane = document.querySelector(tabsTrigger[step].getAttribute('data-bs-target'));
        const inputs = pane.querySelectorAll('input, select, textarea');
        let valid = true;
        inputs.forEach(input => {
          if (input.hasAttribute('required')) {
            if (input.type === 'file') {
              if (input.files.length === 0) valid = false;
            } else if (input.type === 'radio') {
              const radioName = input.name;
              const radios = pane.querySelectorAll(`input[name="${radioName}"]`);
              if (![...radios].some(r => r.checked)) valid = false;
            } else if (!input.value) {
              valid = false;
            }
          }
          if (!valid) {
            input.classList.add('is-invalid');
          } else {
            input.classList.remove('is-invalid');
          }
        });
        return valid;
      }

      // Initialize
      showStep(currentStep);
    })();
  </script>
</body>

</html>