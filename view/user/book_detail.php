<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/BorrowController.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Validasi ID buku
if (!isset($_GET['id'])) {
    echo "<p>ID Buku tidak ditemukan.</p>";
    exit();
}

$id = $_GET['id'];

// Ambil detail buku
global $conn;
$stmt = $conn->prepare("SELECT books.*, categories.name AS category_name 
                        FROM books 
                        LEFT JOIN categories ON books.category_id = categories.id
                        WHERE books.id = ?");
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

elseif ($book['status'] === "Tidak Tersedia" || $stock <= 0) {
    $disable = true;
    $disable_msg = "Buku tidak tersedia";
}

// Cek status peminjaman user terhadap buku ini
$user_id = $_SESSION['user_id'] ?? null;
$borrow_status = null;

if ($user_id) {
    $borrow_status = BorrowController::checkUserBookStatus($user_id, $book['id']);
}

// Cek apakah buku sudah ada di keranjang
$inCart = false;
if ($user_id) {
    $checkCart = $conn->prepare("SELECT id FROM carts WHERE user_id = ? AND book_id = ?");
    $checkCart->bind_param("ii", $user_id, $book['id']);
    $checkCart->execute();
    $inCart = $checkCart->get_result()->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']); ?> - Detail Buku</title>
    <link rel="stylesheet" href="assets/css/user/book_detail.css">
    <link rel="stylesheet" href="assets/css/global.css">
</head>

<body>
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

            <!-- STATUS BUKU MENGIKUTI USER -->
            <p><span>Status:</span> 
                <?php if ($borrow_status): ?>
                    <span class="status danger">
                        <?= ucwords(str_replace('_',' ', $borrow_status)); ?>
                    </span>
                <?php else: ?>
                    <span class="status <?= strtolower($book['status']); ?>">
                        <?= htmlspecialchars($book['status']); ?>
                    </span>
                <?php endif; ?>
            </p>
            <p><span>Stock:</span> <?= htmlspecialchars($stock); ?></p>

            <p><span>Deskripsi:</span><br><?= nl2br(htmlspecialchars($book['description'])); ?></p>

            <div class="action-buttons">

                <?php 
                // RULE DISABLE BUTTON
                $disable = false;
                $disable_msg = "";

                if ($borrow_status) {
                    $disable = true;
                    $disable_msg = "Buku sedang dalam proses peminjaman";
                } 
                elseif ($book['status'] === "Tidak Tersedia") {
                    $disable = true;
                    $disable_msg = "Buku tidak tersedia";
                }
                elseif ($inCart) {
                    $disable = true;
                    $disable_msg = "Buku sudah di keranjang";
                }
                ?>

                <?php if ($disable): ?>
                    <button class="borrow-btn disabled" 
                        style="background:#b30000; cursor:not-allowed;" disabled>
                        ❌ <?= $disable_msg; ?>
                    </button>
                <?php else: ?>
                    <button type="button" 
                        class="borrow-btn" 
                        id="addToCartBtn" 
                        data-id="<?= $book['id']; ?>">
                        Booking
                    </button>
                <?php endif; ?>

                <a href="dashboard_user.php" class="back-btn">Kembali</a>
            </div>
        </div>
    </div>

    <script src="assets/js/user/confirm_book.js"></script>
</body>
</html>
