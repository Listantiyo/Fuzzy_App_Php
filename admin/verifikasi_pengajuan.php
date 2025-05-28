<?php
// Koneksi ke database
include "../conn.php";
$pengajuan_id = $_POST['pengajuan_id'];

// Get additional form data
$jenis_pendaftaran = $_POST['jenis_pendaftaran'];
$jenis_keperluan = $_POST['jenis_keperluan']; 
$detail_keperluan = $_POST['detail_keperluan'];
$tingkat_wewenang = $_POST['tingkat_wewenang'];

// Update pengajuan table with additional data
$update_pengajuan = mysqli_prepare($conn, "UPDATE pengajuan SET 
    jenis_pendaftaran = ?,
    jenis_keperluan = ?,
    detail_keperluan = ?,
    tingkat_wewenang = ?
    WHERE id = ?");
mysqli_stmt_bind_param($update_pengajuan, "ssssi", 
    $jenis_pendaftaran,
    $jenis_keperluan,
    $detail_keperluan, 
    $tingkat_wewenang,
    $pengajuan_id
);
mysqli_stmt_execute($update_pengajuan);

// Get father's information from POST
$nama_ayah = $_POST['nama_ayah'];
$umur_ayah = $_POST['umur_ayah'];
$agama_ayah = $_POST['agama_ayah'];
$warga_ayah = $_POST['warga_ayah'];
$pekerjaan_ayah = $_POST['pekerjaan_ayah'];
$serumah_ayah = $_POST['serumah_ayah'];

// Update pengajuan table with father's information
$update_ayah = mysqli_prepare($conn, "UPDATE keluarga SET 
    nama = ?,
    umur = ?,
    agama = ?,
    warganegara = ?,
    pekerjaan = ?,
    serumah = ?
    WHERE pengajuan_id = ? AND hubungan ='ayah'");
mysqli_stmt_bind_param($update_ayah, "ssssssi", 
    $nama_ayah,
    $umur_ayah,
    $agama_ayah,
    $warga_ayah,
    $pekerjaan_ayah,
    $serumah_ayah,
    $pengajuan_id
);
mysqli_stmt_execute($update_ayah);

// Get mother's information from POST
$nama_ibu = $_POST['nama_ibu'];
$umur_ibu = $_POST['umur_ibu'];
$agama_ibu = $_POST['agama_ibu'];
$warga_ibu = $_POST['warga_ibu'];
$pekerjaan_ibu = $_POST['pekerjaan_ibu'];
$serumah_ibu = $_POST['serumah_ibu'];

// Update pengajuan table with mother's information
$update_ibu = mysqli_prepare($conn, "UPDATE keluarga SET 
    nama = ?,
    umur = ?,
    agama = ?,
    warganegara = ?,
    pekerjaan = ?,
    serumah = ?
    WHERE pengajuan_id = ? AND hubungan ='ibu'");
mysqli_stmt_bind_param($update_ibu, "ssssssi", 
    $nama_ibu,
    $umur_ibu,
    $agama_ibu,
    $warga_ibu,
    $pekerjaan_ibu,
    $serumah_ibu,
    $pengajuan_id
);
mysqli_stmt_execute($update_ibu);


// Get education information from POST
$lokasi_sekolah = $_POST['lokasi_sekolah'];
$tingkat = $_POST['tingkat'];
$nama_institusi = $_POST['nama_institusi'];
$tahun_lulus = $_POST['tahun_lulus'];

// Update pengajuan table with education information
$update_pendidikan = mysqli_prepare($conn, "UPDATE pendidikan SET 
    lokasi_sekolah = ?,
    tingkat = ?,
    nama_institusi = ?,
    tahun_lulus = ?
    WHERE pengajuan_id = ?");
mysqli_stmt_bind_param($update_pendidikan, "ssssi", 
    $lokasi_sekolah,
    $tingkat,
    $nama_institusi,
    $tahun_lulus,
    $pengajuan_id
);
mysqli_stmt_execute($update_pendidikan);


// Get criminal history details from POST
$pernah_terlibat = $_POST['pernah_terlibat'];
$detail_perkara = $_POST['detail_perkara'];
$keputusan = $_POST['keputusan'];
$sedang_proses = $_POST['sedang_proses'];
$kasus_sedang_diproses = $_POST['kasus_sedang_diproses'];
$sampai_mana = $_POST['sampai_mana'];
$pelanggaran_norma = $_POST['pelanggaran_norma'];
$detail_norma = $_POST['detail_norma'];
$proses_norma = $_POST['proses_norma'];

// Update riwayat_kriminal table with criminal history details
$update_riwayat = mysqli_prepare($conn, "UPDATE pidana SET 
    pernah_terlibat = ?,
    detail_perkara = ?,
    keputusan = ?,
    sedang_proses = ?,
    kasus_sedang_diproses = ?,
    sampai_mana = ?,
    pelanggaran_norma = ?,
    detail_norma = ?,
    proses_norma = ?
    WHERE pengajuan_id = ?");
mysqli_stmt_bind_param($update_riwayat, "sssssssssi", 
    $pernah_terlibat,
    $detail_perkara,
    $keputusan,
    $sedang_proses,
    $kasus_sedang_diproses,
    $sampai_mana,
    $pelanggaran_norma,
    $detail_norma,
    $proses_norma,
    $pengajuan_id
);
mysqli_stmt_execute($update_riwayat);

// Get physical characteristics from POST
$tinggi = $_POST['tinggi'];
$berat = $_POST['berat'];
$tanda_istimewa = $_POST['tanda_istimewa'];
$warna_kulit = $_POST['warna_kulit'];
$jenis_rambut = $_POST['jenis_rambut'];
$bentuk_muka = $_POST['bentuk_muka'];
$punya_sidik_jari = $_POST['punya_sidik_jari'];

// Update ciri_fisik table with physical characteristics
$update_ciri_fisik = mysqli_prepare($conn, "UPDATE fisik SET 
    tinggi = ?,
    berat = ?,
    tanda_istimewa = ?,
    warna_kulit = ?,
    jenis_rambut = ?,
    bentuk_muka = ?,
    punya_sidik_jari = ?
    WHERE pengajuan_id = ?");
mysqli_stmt_bind_param($update_ciri_fisik, "sssssssi", 
    $tinggi,
    $berat,
    $tanda_istimewa,
    $warna_kulit,
    $jenis_rambut,
    $bentuk_muka,
    $punya_sidik_jari,
    $pengajuan_id
);
mysqli_stmt_execute($update_ciri_fisik);

// Get additional personal information from POST
$negara_dikunjungi = $_POST['negara_dikunjungi'];
$hobi = $_POST['hobi'];
$alamat_mudah_dihubungi = $_POST['alamat_mudah_dihubungi'];

// Update personal information in pengajuan table
$update_personal = mysqli_prepare($conn, "UPDATE keterangan SET 
    negara_dikunjungi = ?,
    hobi = ?,
    alamat_mudah_dihubungi = ?
    WHERE id = ?");
mysqli_stmt_bind_param($update_personal, "sssi", 
    $negara_dikunjungi,
    $hobi,
    $alamat_mudah_dihubungi,
    $pengajuan_id
);
mysqli_stmt_execute($update_personal);

// Get date range and purpose information
$berada_indonesia_dari = $_POST['berada_indonesia_dari'];
$berada_indonesia_sampai = $_POST['berada_indonesia_sampai'];
$berlaku_dari = $_POST['berlaku_dari'];
$berlaku_sampai = $_POST['berlaku_sampai'];
$dicetak_di = $_POST['dicetak_di'];
$tanggal_cetak = $_POST['tanggal_cetak'];

// Ambil data dari form
$verifikasi_id = $_POST['verifikasi_id'] ?? null;
$poin_riwayat_kriminal = $_POST['poin_riwayat_kriminal'];
$poin_status_hukum = $_POST['poin_status_hukum'];

if ($verifikasi_id) {
    // Update verifikasi_admin
    $sql = "UPDATE verifikasi_admin 
            SET 
            riwayat_kriminal = ?, 
            status_hukum = ?, 
            tanggal_verifikasi = NOW()
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $poin_riwayat_kriminal, $poin_status_hukum, $verifikasi_id);
    mysqli_stmt_execute($stmt);

    // Update date range and purpose information
    $update_dates = mysqli_prepare($conn, "UPDATE verifikasi_admin SET 
        berada_indonesia_dari = ?,
        berada_indonesia_sampai = ?,
        berlaku_dari = ?,
        berlaku_sampai = ?,
        dicetak_di = ?,
        tanggal_cetak = ?
        WHERE pengajuan_id = ?");
    mysqli_stmt_bind_param($update_dates, "ssssssi",
    $berada_indonesia_dari,
    $berada_indonesia_sampai,
        $berlaku_dari,
        $berlaku_sampai,
        $dicetak_di,
        $tanggal_cetak,
        $pengajuan_id
        );
    mysqli_stmt_execute($update_dates);
} else {
    // Insert verifikasi_admin
    $sql = "INSERT INTO verifikasi_admin (
                pengajuan_id, 
                riwayat_kriminal, 
                status_hukum, 
                tanggal_verifikasi,

                berada_indonesia_dari,
                berada_indonesia_sampai,
                berlaku_dari,
                berlaku_sampai,
                dicetak_di,
                tanggal_cetak
            )
            VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiissssss", 
    $pengajuan_id, 
    $poin_riwayat_kriminal, 
        $poin_status_hukum,
        $berada_indonesia_dari,
        $berada_indonesia_sampai,
        $berlaku_dari,
        $berlaku_sampai,
        $dicetak_di,
        $tanggal_cetak
    );
    mysqli_stmt_execute($stmt);

    $insert_dates = mysqli_prepare($conn, "INSERT INTO verifikasi_admin (
        berada_indonesia_dari,
        berada_indonesia_sampai,
        berlaku_dari,
        berlaku_sampai,
        dicetak_di,
        tanggal_cetak,
        pengajuan_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    mysqli_stmt_bind_param($insert_dates, "ssssssi",
        $berada_indonesia_dari,
        $berada_indonesia_sampai,
        $berlaku_dari,
        $berlaku_sampai,
        $dicetak_di,
        $tanggal_cetak,
        $pengajuan_id
    );
    
    // mysqli_stmt_execute($insert_dates);
    

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
