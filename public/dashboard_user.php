<?php
session_start();


require_once __DIR__ . '/../controllers/CartController.php';

if (isset($_GET['page']) && $_GET['page'] === 'cart' && isset($_GET['action'])) {
    $book_id = intval($_GET['id'] ?? 0);
    if ($_GET['action'] === 'add' && $book_id > 0) {
        CartController::addToCart($book_id);
    } elseif ($_GET['action'] === 'remove') {
        CartController::removeFromCart($book_id);
    }
}


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Ambil parameter halaman
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Path view folder
$viewPath = __DIR__ . '/../view/user/';

// Tentukan halaman mana yang akan di-include
switch ($page) {
    case 'book_detail':
        $file = $viewPath . 'book_detail.php';
        break;

    case 'borrowed_books':
        $file = $viewPath . 'borrowed_books.php';
        break;

    case 'history':
        $file = $viewPath . 'history.php';
        break;

    case 'setting':
        $file = $viewPath . 'setting.php';
        break;

    // 🆕 Tambahkan halaman keranjang
    case 'cart':
        $file = $viewPath . 'cart.php';
        break;

    default:
        $file = $viewPath . 'dashboard.php';
        break;
}

// Cek file ada atau tidak
if (file_exists($file)) {
    include $file;
} else {
    echo "<h3>Halaman tidak ditemukan.</h3>";
}
