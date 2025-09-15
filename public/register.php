<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $result = AuthController::register($username, $email, $password);
    if ($result === true) {
        header("Location: login.php");
        exit();
    } else {
        $message = $result;
    }
}
?>

<?php include __DIR__ . '/../view/auth/register_form.php'; ?>