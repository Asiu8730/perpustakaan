<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

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
    <title>Dashboard Guest | Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/guest/guest_dashboard.css">
    <link rel="stylesheet" href="assets/css/global.css">
</head>

<body>

<?php include __DIR__ . '/../templates/guest_header.php'; ?>

<section class="quick-category">
    <div class="category-wrapper">

        <button class="scroll-btn scroll-left hidden" id="scrollLeft">‹</button>

        <div class="category-scroll" id="categoryScroll">
            <?php foreach ($categories as $category): ?>
                <a href="/reca/perpustakaan/public/dashboard_guest.php?page=category_detail&id=<?= $category['id']; ?>" 
                   class="category-pill">
                    <?= htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <button class="scroll-btn scroll-right" id="scrollRight">›</button>

    </div>
</section>

<div class="page-inner">
  <main class="main-content">

    <?php foreach ($categories as $category): ?>
        <section class="category-section">

            <div class="category-header">
                <h3><?= htmlspecialchars($category['name']); ?></h3>
                <a href="/reca/perpustakaan/public/dashboard_guest.php?page=category_detail&id=<?= $category['id']; ?>" class="see-more">
                    Lihat Semua →
                </a>
            </div>

            <div class="book-row">
                <?php 
                $books = $booksByCategory[$category['id']];

                if (empty($books)): ?>
                    <p class="no-books">Tidak ada buku di kategori ini.</p>

                <?php else:
                    foreach (array_slice($books, 0, 7) as $book): ?>
                    <a href="/reca/perpustakaan/public/dashboard_guest.php?page=book_detail&id=<?= $book['id']; ?>" class="book-link">
                        <div class="book-card">
                            <div class="book-image">
                                <img src="../uploads/covers/<?= htmlspecialchars($book['cover'] ?: 'no_cover.png'); ?>" alt="Cover Buku">

                                <?php if ($book['status'] === 'Dipinjam'): ?>
                                    <span class="status-badge borrowed">Dipinjam</span>

                                <?php elseif ($book['status'] === 'Tersedia'): ?>
                                    <span class="status-badge available">Tersedia</span>

                                <?php elseif ($book['status'] === 'Tidak Tersedia'): ?>
                                    <span class="status-badge unavailable">Tidak Tersedia</span>

                                <?php endif; ?>

                            </div>

                            <div class="book-info">
                                <p class="book-author"><?= htmlspecialchars($book['author']); ?></p>
                                <h4 class="book-title"><?= htmlspecialchars($book['title']); ?></h4>
                            </div>
                        </div>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </section>
    <?php endforeach; ?>

</main>
</div>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="/reca/perpustakaan/uploads/logo/logo.jpg" alt="Logo">
            <h2>Perpustakaan</h2>
        </div>

        <p class="footer-copy">
            © <?= date("Y"); ?> Perpustakaan — All Rights Reserved.
        </p>

    </div>
</footer>

<script src="assets/js/user/category.js"></script>

</body>
</html>
