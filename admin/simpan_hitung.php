<?php
require '../conn.php'; // Pastikan $conn adalah objek mysqli
require '../fungsi_fuzzy.php'; // Pastikan $conn adalah objek mysqli

// Ambil data dari POST
$user_id = $_POST['user_id'];
$pengajuan_id = $_POST['pengajuan_id'];
$nama = $_POST['nama'];
$kriminal = $_POST['kriminal'];
$status = $_POST['status'];

// Hitung fuzzy (asumsi fungsi hitung_fuzzy sudah di-include)
$hasil = hitung_fuzzy($kriminal, $status);
$nilai_akhir = $hasil['skor'];
$keputusan = $hasil['kategori'];

// Cek apakah data sudah ada
$sql = "SELECT COUNT(*) as cnt FROM fuzzy_hasil WHERE pengajuan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pengajuan_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['cnt'] > 0) {
    // Update data
    $sqlUpdate = "UPDATE fuzzy_hasil SET user_id=?, nama=?, kriminal=?, status=?, nilai_akhir=?, keputusan=? WHERE pengajuan_id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("isddssi", $user_id, $nama, $kriminal, $status, $nilai_akhir, $keputusan, $pengajuan_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    $sudah = 'sudah';
    $sqlUpdate = "UPDATE pengajuan SET status_hitung = ? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate); 
    $stmtUpdate->bind_param("si", $sudah, $pengajuan_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();
} else {
    // Insert data baru
    $sqlInsert = "INSERT INTO fuzzy_hasil (user_id, pengajuan_id, nama, kriminal, status, nilai_akhir, keputusan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iisddds", $user_id, $pengajuan_id, $nama, $kriminal, $status, $nilai_akhir, $keputusan);
    $stmtInsert->execute();
    $stmtInsert->close();

    $sudah = 'sudah';
    $sqlUpdate = "UPDATE pengajuan SET status_hitung = ? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate); 
    $stmtUpdate->bind_param("si", $sudah, $pengajuan_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}

$stmt->close();

// Redirect balik
header("Location: ../login_admin.php?page=hitung_fuzzy&sukses=1&kriminal=$kriminal&status=$status&nama=$nama");
exit;
