<?php
include "../conn.php";

$username = $_POST['username'];
$password = $_POST['password'];
$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

$stmt = $conn->prepare("INSERT INTO user (username, password, nama_lengkap, email, no_hp) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $password, $nama_lengkap, $email, $no_hp);

if ($stmt->execute()) {
    echo "Registrasi berhasil. <a href='../index.php?login=true'>Login di sini</a>";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}
