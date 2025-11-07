<?php
session_start();
require_once __DIR__ . '/../controllers/CartController.php';

// =====================
// 🟩 Tambahan untuk AJAX notifikasi keranjang
// =====================
if (isset($_GET['page']) && $_GET['page'] === 'cart' && isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    
    // Jika request datang dari fetch() → kirim JSON, bukan redirect
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');

        $result = CartController::addToCart($book_id);
        echo json_encode($result);
        exit;
    }

    // Jika bukan AJAX → jalankan alur biasa
    CartController::addToCart($book_id);
}

// =====================
// 🟦 Logika login dan routing utama
// =====================
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

    case 'borrowed':
        $file = $viewPath . 'borrowed_books.php';
        break;

    case 'history':
        $file = $viewPath . 'history.php';
        break;

    case 'setting':
        $file = $viewPath . 'setting.php';
        break;

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
?>
