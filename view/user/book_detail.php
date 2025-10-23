<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';

if (!isset($_GET['id'])) {
    echo "<p>ID Buku tidak ditemukan.</p>";
    exit();
}

$id = $_GET['id'];
global $conn;
$stmt = $conn->prepare("SELECT books.*, categories.name AS category_name 
                        FROM books 
                        LEFT JOIN categories ON books.category_id = categories.id
                        WHERE books.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    echo "<p>Buku tidak ditemukan.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']); ?> - Detail Buku</title>
    <link rel="stylesheet" href="assets/css/user/book_detail.css">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../templates/header.php'; ?>
<div class="book-detail-container">
    <div class="book-cover">
        <img src="../uploads/covers/<?= htmlspecialchars($book['cover'] ?: 'no_cover.png'); ?>" alt="Cover Buku">
    </div>

    <div class="book-info">
        <h2><?= htmlspecialchars($book['title']); ?></h2>
        <p><span>Penulis:</span> <?= htmlspecialchars($book['author']); ?></p>
        <p><span>Penerbit:</span> <?= htmlspecialchars($book['publisher']); ?></p>
        <p><span>Kategori:</span> <?= htmlspecialchars($book['category_name']); ?></p>
        <p><span>Tanggal Terbit:</span> <?= htmlspecialchars($book['publish_date']); ?></p>
        <p><span>Stok:</span> <?= htmlspecialchars($book['stock']); ?></p>
        <p><span>Status:</span> 
            <span class="status <?= strtolower($book['status']); ?>">
                <?= htmlspecialchars($book['status']); ?>
            </span>
        </p>
        <p><span>Deskripsi:</span><br><?= nl2br(htmlspecialchars($book['description'])); ?></p>

        <div class="action-buttons">
            <a href="#" class="borrow-btn">Pinjam Buku</a>
            <a href="dashboard_user.php" class="back-btn">Kembali</a>
        </div>
    </div>
</div>

</body>
</html>
