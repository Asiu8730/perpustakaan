<?php
require_once __DIR__ . '/../../controllers/CartController.php';
require_once __DIR__ . '/../../controllers/BorrowController.php';
require_once __DIR__ . '/../../controllers/BookController.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Konfirmasi pinjam
if (isset($_POST['confirm'])) {
    if (BorrowController::confirmBorrow($user_id)) {
        echo "<script>alert('Peminjaman berhasil dikonfirmasi ke admin!'); window.location='dashboard_user.php?page=cart';</script>";
        exit();
    }
}

// Hapus dari keranjang
if (isset($_GET['remove'])) {
    CartController::removeFromCart($_GET['remove']);
}

$books = CartController::getCartItems();
?>

<link rel="stylesheet" href="assets/css/user/cart.css">
<link rel="stylesheet" href="assets/css/global.css">

<?php include __DIR__ . '/../templates/header.php'; ?>
<div class="cart-container">
    <h2 class="cart-title">🛒 Keranjang Buku Kamu</h2>

    <?php if (empty($books)): ?>
        <p class="empty-cart">Keranjang masih kosong 📚</p>

    <?php else: ?>
        <div class="cart-list">
            <?php foreach ($books as $book): ?>
                <div class="cart-card">

                    <div class="cart-info">
                        <img src="../uploads/covers/<?= htmlspecialchars($book['cover']); ?>" class="cart-cover">

                        <div class="cart-text">
                            <h3><?= htmlspecialchars($book['title']); ?></h3>
                            <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']); ?></p>
                        </div>
                    </div>

                    <div class="cart-action">
                        <a href="dashboard_user.php?page=cart&remove=<?= $book['id']; ?>" class="btn-delete">
                            Hapus
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tombol Konfirmasi kecil & di tengah -->
        <form method="POST" class="confirm-wrapper">
            <button type="submit" name="confirm" class="btn-confirm-small">
                Konfirmasi ke Petugas
            </button>
        </form>

    <?php endif; ?>
</div>
