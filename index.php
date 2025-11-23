<?php
// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login → ke dashboard guest
if (!isset($_SESSION['role'])) {
    header("Location: public/dashboard_guest.php");
    exit();
}

// Jika role user → ke dashboard user
if ($_SESSION['role'] === 'user') {
    header("Location: public/dashboard_user.php");
    exit();
}

// Jika role admin → ke dashboard admin
if ($_SESSION['role'] === 'admin') {
    header("Location: public/dashboard_admin.php");
    exit();
}

// Jika role tidak dikenali → fallback ke guest
header("Location: public/dashboard_guest.php");
exit();
?>
