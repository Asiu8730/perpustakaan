<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../public/login.php");
    exit();
}

// Update status pinjaman
if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $status = $_GET['status'];
    BorrowController::updateStatus($id, $status);
    header("Location: dashboard_admin.php?page=borrows");
    exit();
}

$borrows = BorrowController::getAllBorrows();
?>

<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Daftar Peminjaman Buku</h1>
    <table class="books-table">
        <tr>
            <th>Nama User</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = $borrows->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['title']); ?></td>
                <td><?= htmlspecialchars($row['borrow_date']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] === 'dipinjam'): ?>
                        <a href="dashboard_admin.php?page=borrows&update=<?= $row['id']; ?>&status=dikembalikan" class="action-btn update-btn">Konfirmasi Pengembalian</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
