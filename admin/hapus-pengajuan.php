<?php 

require_once '../conn.php';
$id_pengajuan = $_GET['pid'] ?? null;

if(!$id_pengajuan){
    echo "<script>
    alert('Data tidak ditemukan!');
    window.location.href = '../login_admin.php?page=data_pemohon';
    </script>";
    exit;
}
function hapusPengajuan($conn, $id_pengajuan) {
    mysqli_begin_transaction($conn);

    try {
        // Ambil nama file gambar dari pengajuan
        $sql = "SELECT foto_diri FROM pengajuan WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $foto_diri);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Ambil nama file gambar dari lampiran
        $sql = "SELECT foto_kk, foto_akta_ijazah FROM lampiran WHERE pengajuan_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $foto_kk, $foto_akta_ijazah);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Hapus file-file gambar jika ada
        $folder_upload = "../"; // sesuaikan folder upload kamu

        if ($foto_diri && file_exists($folder_upload . $foto_diri)) {
            unlink($folder_upload . $foto_diri);
        }
        if ($foto_kk && file_exists($folder_upload . $foto_kk)) {
            unlink($folder_upload . $foto_kk);
        }
        if ($foto_akta_ijazah && file_exists($folder_upload . $foto_akta_ijazah)) {
            unlink($folder_upload . $foto_akta_ijazah);
        }

        // Hapus data di tabel terkait
        $tables = ['keluarga', 'pendidikan', 'pidana', 'fisik', 'lampiran', 'keterangan', 'verifikasi_admin', 'fuzzy_hasil'];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE pengajuan_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Hapus data pengajuan
        $sql = "DELETE FROM pengajuan WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        
        echo "<script>
        alert('Data berhasil dihapus!');
        window.location.href = '../login_admin.php?page=data_pemohon';
        </script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        
        echo "<script>
        alert('Data gagal dihapus!');
        window.location.href = '../login_admin.php?page=data_pemohon';
        </script>";
        exit;
    }
}

hapusPengajuan($conn, $id_pengajuan);
