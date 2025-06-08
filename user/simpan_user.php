<?php
include "../conn.php";

$username = $_POST['username'];
$password = $_POST['password'];
$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];
$agama = $_POST['agama'];
$kebangsaan = $_POST['kebangsaan'];
$noktp = $_POST['noktp'];
$tempat_lahir = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];

$stmt = $conn->prepare("INSERT INTO user (username, password, nama_lengkap, email, no_hp, agama, kebangsaan, no_ktp, tempat_lahir, tanggal_lahir, jenis_kelamin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $username, $password, $nama_lengkap, $email, $no_hp, $agama, $kebangsaan, $noktp, $tempat_lahir, $tanggal_lahir, $jenis_kelamin);

if ($stmt->execute()) {
    echo "<script>
        alert('Terimakasih, Anda sudah berhasil melakukan registrasi!');
        window.location.href='../index.php?login=true';
    </script>";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}
