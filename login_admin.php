<?php
session_start();
include "conn.php";
include "fungsi.php";

if (!isset($_SESSION['admin_id'])) {
    if (isset($_GET['login']) && $_GET['login'] == 'true') {
        include "admin/login.php"; // tampilkan form login
    } else {
        include "landing-page.php"; // tampilkan landing page
    }
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
$route_user = "admin/" . $page . ".php";

if (file_exists($route_user)) {
    include $route_user;
} else {
    echo "Halaman tidak ditemukan.";
}

