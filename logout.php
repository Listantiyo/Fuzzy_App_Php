<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    session_start();
    session_destroy();
    header("Location: login_admin.php?login=true");
}else{
    session_start();
    session_destroy();
    header("Location: index.php?login=true");
}
