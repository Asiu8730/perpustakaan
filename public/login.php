<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $message = AuthController::login($username, $password);
}
?>

<?php include __DIR__ . '/../view/auth/login_form.php'; ?>