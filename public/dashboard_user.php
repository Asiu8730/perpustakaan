<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();


}
?>
<?php include __DIR__ . '/../view/user/dashboard.php'; ?>
