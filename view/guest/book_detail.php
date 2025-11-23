<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_GET['id'])) {
    echo "<p>ID Buku tidak ditemukan.</p>";
    exit();
}

$id = intval($_GET['id']);

// Ambil detail buku
global $conn;
$stmt = $conn->prepare("
    SELECT books.*, categories.name AS category_name 
    FROM books 
    LEFT JOIN categories ON books.category_id = categories.id
    WHERE books.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stock = intval($book['stock'] ?? 0);
$stmt->close();

if (!$book) {
    echo "<p>Buku tidak ditemukan.</p>";
    exit();
}

// RULE STATUS
$disable = false;
$disable_msg = "";

if ($book['status'] === "Tidak Tersedia" || $stock <= 0) {
    $disable = true;
    $disable_msg = "Buku tidak tersedia";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']); ?> - Detail Buku</title>
    <link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/user/book_detail.css">
    <link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/global.css">
</head>

<body>

    <?php include __DIR__ . '/../templates/guest_header.php'; ?>

    <div class="book-detail-container">
        
        <div class="book-cover">
            <img src="../uploads/covers/<?= htmlspecialchars($book['cover'] ?: 'no_cover.png'); ?>" alt="Cover Buku">
        </div>

        <div class="book-info">
            <h2><?= htmlspecialchars($book['title']); ?></h2>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']); ?></p>
            <p><strong>Penerbit:</strong> <?= htmlspecialchars($book['publisher']); ?></p>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($book['category_name']); ?></p>
            <p><strong>Tanggal Terbit:</strong> <?= htmlspecialchars($book['publish_date']); ?></p>

            <p><strong>Status:</strong>
                <span class="status <?= strtolower($book['status']); ?>">
                    <?= htmlspecialchars($book['status']); ?>
                </span>
            </p>

            <p><strong>Stock:</strong> <?= htmlspecialchars($stock); ?></p>

            <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($book['description'])); ?></p>

            <div class="action-buttons">

                <?php if ($disable): ?>
                    <button class="borrow-btn disabled"
                        style="background:#b30000; cursor:not-allowed;" disabled>
                        ❌ <?= $disable_msg; ?>
                    </button>

                <?php else: ?>
                    <!-- TOMBOL BOOKING → PAKSA LOGIN -->
                    <a href="/reca/perpustakaan/public/login.php"
                        class="borrow-btn"
                        style="background:#2563eb;">
                        Booking (Login Dulu)
                    </a>
                <?php endif; ?>

                <a href="/reca/perpustakaan/public/dashboard_guest.php" class="back-btn">Kembali</a>
            </div>
        </div>
    </div>

</body>
</html>
