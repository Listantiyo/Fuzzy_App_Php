<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    if (isset($_GET['login']) && $_GET['login'] == 'true') {
        include "user/login.php"; // tampilkan form login
    } else {
        include "landing-page.php"; // tampilkan landing page
    }
    exit;
}

// Sudah login
$page = $_GET['page'] ?? 'home';
$route_user = "user/" . $page . ".php";

if (file_exists($route_user)) {
    include $route_user;
} else {
    echo "Halaman tidak ditemukan.";
}
