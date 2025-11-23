<?php
session_start();
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/CategoriesController.php';

$page = $_GET['page'] ?? 'dashboard';
$viewPath = __DIR__ . '/../view/guest/';

switch ($page) {
    case 'dashboard':
        $file = $viewPath . 'dashboard.php';
        break;

    case 'book_detail':
        $file = $viewPath . 'book_detail.php';
        break;

    case 'category_detail':
        $file = $viewPath . 'category_detail.php';
        break;

    default:
        $file = $viewPath . 'dashboard.php';
}

if (file_exists($file)) {
    include $file;
} else {
    echo "<h3>Halaman tidak ditemukan.</h3>";
}
?>
