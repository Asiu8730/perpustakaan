<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';

global $conn;
$user_id = $_SESSION['user_id'];

// Ambil semua buku yang dipinjam oleh user
$result = $conn->query("SELECT borrows.*, books.title 
                        FROM borrows 
                        LEFT JOIN books ON borrows.book_id = books.id 
                        WHERE borrows.user_id = $user_id 
                        ORDER BY borrows.id DESC");
?>

<link rel="stylesheet" href="assets/css/user/borrowed_books.css">
<link rel="stylesheet" href="assets/css/global.css">

<div class="borrowed-container">
    <h2>Buku yang Sedang Dipinjam</h2>

    <table class="borrowed-table">
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['borrow_date']); ?></td>
            <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $row['status']))); ?></td>
            <td>
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="loan_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="return_request" class="btn-warning">
                            Konfirmasi Pengembalian
                        </button>
                    </form>
                <?php else: ?>
                    <span class="status"><?= htmlspecialchars($row['status']); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
// Ketika user klik tombol konfirmasi
if (isset($_POST['return_request'])) {
    $loan_id = $_POST['loan_id'];
    BorrowController::requestReturn($loan_id);

    echo "<script>
        alert('Permintaan pengembalian berhasil dikirim ke admin.');
        window.location.href = 'dashboard_user.php?page=borrowed_books';
    </script>";
}
?>
