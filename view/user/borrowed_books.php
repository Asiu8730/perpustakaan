<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';
require_once __DIR__ . '/../../config/database.php';

global $conn;
if (session_status() === PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['user_id'];

// Ambil semua peminjaman user
$query = $conn->prepare("
    SELECT borrows.*, books.title 
    FROM borrows
    LEFT JOIN books ON borrows.book_id = books.id
    WHERE borrows.user_id = ?
    ORDER BY borrows.id DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<link rel="stylesheet" href="assets/css/user/borrowed_books.css">
<link rel="stylesheet" href="assets/css/global.css">

<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="borrowed-container">
    <h2>Daftar Buku yang Dipinjam</h2>

    <table class="borrowed-table">
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tenggat Pengembalian</th>
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
            <td>
                <?php if (!empty($row['return_date'])): ?>
                    <?= htmlspecialchars($row['return_date']); ?>
                <?php else: ?>
                    <em>-</em>
                <?php endif; ?>
            </td>
            <td>
                <span class="status <?= strtolower($row['status']); ?>">
                    <?= ucwords(str_replace('_', ' ', $row['status'])); ?>
                </span>
            </td>
            <td>
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="loan_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="return_request" class="btn-warning">
                            Ajukan Pengembalian
                        </button>
                    </form>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_admin'): ?>
                    <span class="status pending">Menunggu Konfirmasi Admin</span>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_pengembalian'): ?>
                    <span class="status pending">Menunggu Konfirmasi Pengembalian</span>

                <?php elseif ($row['status'] === 'dikembalikan'): ?>
                    <span class="status selesai">Dikembalikan</span>

                <?php else: ?>
                    <span class="status">-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
// Ketika user klik tombol konfirmasi pengembalian
if (isset($_POST['return_request'])) {
    $loan_id = $_POST['loan_id'];
    BorrowController::requestReturn($loan_id);

    echo "<script>
        alert('Permintaan pengembalian berhasil dikirim ke admin.');
        window.location.href = 'dashboard_user.php?page=borrowed_books';
    </script>";
}
?>
