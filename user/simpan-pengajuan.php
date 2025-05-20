<?php
include '../conn.php';
include '../fungsi.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    die("User tidak login.");
}


// Upload helper
function uploadFile($fieldName, $folder = 'uploads')
{
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] == 0) {
        $filename = time() . '_' . basename($_FILES[$fieldName]['name']);
        $target = $folder . '/' . strtolower($filename);
        move_uploaded_file($_FILES[$fieldName]['tmp_name'], '../' . $target);
        return $target;
    }
    return null;
}

// 1. Simpan ke tabel `pengajuan`
$foto_diri = uploadFile('fotoDiri');
$stmt = $conn->prepare("INSERT INTO pengajuan (user_id, jenis_pendaftaran, jenis_keperluan, detail_keperluan, tingkat_wewenang, foto_diri) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $user_id, $_POST['jenisPendaftaran'], $_POST['jenisKeperluan'], $_POST['detailKeperluan'], $_POST['tingkatWewenang'], $foto_diri);
$stmt->execute();
$pengajuan_id = $stmt->insert_id;

// 2. Simpan ke tabel `keluarga` (ayah & ibu)
$keluarga = [
    ['ayah', $_POST['namaAyah'], $_POST['umurAyah'], $_POST['agamaAyah'], $_POST['wargaAyah'], $_POST['pekerjaanAyah'], $_POST['serumahAyah']],
    ['ibu', $_POST['namaIbu'], $_POST['umurIbu'], $_POST['agamaIbu'], $_POST['wargaIbu'], $_POST['pekerjaanIbu'], $_POST['serumahIbu']],
];
$stmt = $conn->prepare("INSERT INTO keluarga (pengajuan_id, hubungan, nama, umur, agama, warganegara, pekerjaan, serumah) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($keluarga as $k) {
    $stmt->bind_param("ississss", $pengajuan_id, $k[0], $k[1], $k[2], $k[3], $k[4], $k[5], $k[6]);
    $stmt->execute();
}

// 3. Simpan ke `pendidikan`
$stmt = $conn->prepare("INSERT INTO pendidikan (pengajuan_id, lokasi_sekolah, tingkat, nama_institusi, tahun_lulus) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isssi", $pengajuan_id, $_POST['sekolahDi'], $_POST['tingkatPendidikan'], $_POST['namaInstitusi'], $_POST['tahunLulus']);
$stmt->execute();

// 4. Simpan ke `pidana`
$stmt = $conn->prepare("INSERT INTO pidana (pengajuan_id, pernah_terlibat, detail_perkara, keputusan, sedang_proses, kasus_sedang_diproses, sampai_mana, pelanggaran_norma, detail_norma, proses_norma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssss", $pengajuan_id, $_POST['tersangkutPidana'], $_POST['perkaraApa'], $_POST['keputusanVonis'], $_POST['prosesPidana'], $_POST['kasusApa'], $_POST['prosesHukum'], $_POST['melanggarHukum'], $_POST['pelanggaranApa'], $_POST['prosesPelanggaran']);
$stmt->execute();

// 5. Simpan ke `fisik`
$stmt = $conn->prepare("INSERT INTO fisik (pengajuan_id, tinggi, berat, tanda_istimewa, warna_kulit, bentuk_muka, jenis_rambut, punya_sidik_jari) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiisssss", $pengajuan_id, $_POST['tinggiBadan'], $_POST['beratBadan'], $_POST['tandaIstimewa'], $_POST['warnaKulit'], $_POST['bentukMuka'], $_POST['jenisRambut'], $_POST['rumusSidikJari']);
$stmt->execute();

// 6. Simpan ke `lampiran`
$foto_kk = uploadFile('fotoKK');
$foto_ijazah = uploadFile('fotoAkteIjazah');
$stmt = $conn->prepare("INSERT INTO lampiran (pengajuan_id, foto_kk, foto_akta_ijazah) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $pengajuan_id, $foto_kk, $foto_ijazah);
$stmt->execute();

// 7. Simpan ke `keterangan`
$stmt = $conn->prepare("INSERT INTO keterangan (pengajuan_id, riwayat_pekerjaan, negara_dikunjungi, hobi, alamat_mudah_dihubungi) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $pengajuan_id, $_POST['riwayatPekerjaan'], $_POST['riwayatPekerjaanAtauNegara'], $_POST['kesenanganHobi'], $_POST['alamatKontak']);
$stmt->execute();

// Sukses

echo "<script>
    alert('Simpan Berhasil');
    window.location.href = '../index.php?page=pengajuan';
</script>";
exit;
// header("Location: ../index.php?page=pengajuan&status=berhasil");

