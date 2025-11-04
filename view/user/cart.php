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

<div class="cart-container">
    <h1>Keranjang Buku Saya</h1>

    <?php if (empty($books)): ?>
        <p>Keranjang kamu kosong 📚</p>
    <?php else: ?>
        <table class="cart-table">
            <tr>
                <th>Cover</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><img src="../uploads/covers/<?= htmlspecialchars($book['cover']); ?>" width="60"></td>
                    <td><?= htmlspecialchars($book['title']); ?></td>
                    <td><?= htmlspecialchars($book['author']); ?></td>
                    <td>
                        <a href="dashboard_user.php?page=cart&remove=<?= $book['id']; ?>" class="btn-delete">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <form method="POST">
            <button type="submit" name="confirm" class="btn-confirm">Konfirmasi ke Admin</button>
        </form>
    <?php endif; ?>
</div>
