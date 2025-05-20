<?php
// Koneksi ke database
include "../conn.php";

// Ambil data dari form
$pengajuan_id = $_POST['pengajuan_id'];
$verifikasi_id = $_POST['verifikasi_id'] ?? null;
$poin_riwayat_kriminal = $_POST['poin_riwayat_kriminal'];
$poin_status_hukum = $_POST['poin_status_hukum'];

if ($verifikasi_id) {
    // Update verifikasi_admin
    $sql = "UPDATE verifikasi_admin 
            SET riwayat_kriminal = ?, status_hukum = ?, tanggal_verifikasi = NOW()
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $poin_riwayat_kriminal, $poin_status_hukum, $verifikasi_id);
    mysqli_stmt_execute($stmt);
} else {
    // Insert verifikasi_admin
    $sql = "INSERT INTO verifikasi_admin (pengajuan_id, riwayat_kriminal, status_hukum, tanggal_verifikasi)
            VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $pengajuan_id, $poin_riwayat_kriminal, $poin_status_hukum);
    mysqli_stmt_execute($stmt);

    // Update status_verifikasi di tabel pengajuan
    $update = mysqli_prepare($conn, "UPDATE pengajuan SET status_verifikasi = 'sudah' WHERE id = ?");
    mysqli_stmt_bind_param($update, "i", $pengajuan_id);
    mysqli_stmt_execute($update);
}


// Redirect kembali dengan alert JS
echo "<script>
    alert('Verifikasi Berhasil');
    window.location.href = '../login_admin.php?page=data_pemohon';
</script>";
exit;
