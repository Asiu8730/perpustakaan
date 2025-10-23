<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$categories = CategoriesController::getAllCategories();
$booksByCategory = [];

foreach ($categories as $category) {
    $booksByCategory[$category['id']] = BookController::getBooksByCategory($category['id']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/user/users_dashboard.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../templates/header.php'; ?>
    <!-- Welcome -->
    <section class="welcome">
        <h2>Selamat Datang, <?= htmlspecialchars($username); ?> 👋</h2>
        <p>Temukan dan pinjam buku favoritmu dari koleksi kami 📚</p>
    </section>

    <!-- Buku per Kategori -->
    <main class="main-content">
        <?php foreach ($categories as $category): ?>
            <div class="category-section">
                <div class="category-header">
                    <h3><?= htmlspecialchars($category['name']); ?></h3>
                    <a href="../../public/category_detail.php?id=<?= $category['id']; ?>" class="see-more">Lihat Semua →</a>
                </div>

                <div class="book-row">
                    <?php 
                    $books = $booksByCategory[$category['id']];
                    if (empty($books)): ?>
                        <p class="no-books">Belum ada buku di kategori ini.</p>
                    <?php else:
                        foreach (array_slice($books, 0, 5) as $book): ?>
                            <div class="book-card">
                                <div class="book-image">
                                    <a href="/reca/perpustakaan/public/dashboard_user.php?page=book_detail&id=<?= $book['id']; ?>">
    <img src="../uploads/covers/<?= htmlspecialchars($book['cover'] ?: 'no_cover.png'); ?>" 
         alt="Cover Buku">
</a>


                                    <?php if (!empty($book['status']) && $book['status'] === 'Dipinjam'): ?>
                                        <span class="status-label">Dipinjam</span>
                                    <?php endif; ?>
                                </div>
                                <div class="book-info">
                                    <h4><?= htmlspecialchars($book['title']); ?></h4>
                                    <p><?= htmlspecialchars($book['author']); ?></p>
                                </div>
                            </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <a href="logout.php">Logout</a>
    </footer>
</body>
</html>
