<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

if (!isset($_GET['id'])) {
    echo "Kategori tidak ditemukan.";
    exit();
}

$category_id = $_GET['id'];
$category = CategoriesController::getCategoryById($category_id);
$books = BookController::getBooksByCategory($category_id);

if (!$category) {
    echo "Kategori tidak valid.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category['name']); ?></title>
    <link rel="stylesheet" href="assets/css/user/users_dashboard.css">
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>

<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="category-container">
    <h2 class="title">Kategori: <?= htmlspecialchars($category['name']); ?></h2>

    <div class="books-grid">
        <?php if (empty($books)): ?>
            <p>Tidak ada buku dalam kategori ini.</p>
        <?php else: ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <div class="book-image">
                <a href="/reca/perpustakaan/public/dashboard_user.php?page=book_detail&id=<?= $book['id']; ?>">
                    <img src="../uploads/covers/<?= htmlspecialchars($book['cover'] ?: 'no_cover.png'); ?>" alt="Cover Buku">
                </a>

                <?php if ($book['status'] === 'Dipinjam'): ?>
                    <span class="status-badge borrowed">Dipinjam</span>
                <?php elseif ($book['status'] === 'Tersedia'): ?>
                    <span class="status-badge available">Tersedia</span>
                <?php endif; ?>
                </div>
                    <div class="book-info">
                        <h4 class="book-title"><?= htmlspecialchars($book['title']); ?></h4>
                        <p class="book-author"><?= htmlspecialchars($book['author']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
