<?php
include "../conn.php";

$username = $_POST['username'];
$password = $_POST['password'];
$nama_lengkap = $_POST['nama_lengkap'];

$stmt = $conn->prepare("INSERT INTO admin (username, password, nama_lengkap) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $nama_lengkap);

if ($stmt->execute()) {
    echo "Registrasi berhasil. <a href='../login_admin.php?login=true'>Login di sini</a>";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}
