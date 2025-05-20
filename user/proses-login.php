<?php
session_start();
include "../conn.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        header("Location: ../index.php");
        exit;
    }
}

echo "<script>
    alert('Username atau Password salah!');
    window.location.href = '../index.php?login=true';
</script>";
