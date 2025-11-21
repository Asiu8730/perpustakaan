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
    <h2 class="borrowed-title">📚 Daftar Buku yang Dipinjam</h2>

    <div class="borrowed-list">
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="borrow-card">

            <div class="borrow-info">
                <h3><?= htmlspecialchars($row['title']); ?></h3>

                <p><strong>Tanggal Pinjam:</strong> <?= htmlspecialchars($row['borrow_date']); ?></p>

                <p><strong>Tenggat:</strong> 
                    <?php if (!empty($row['return_date'])): ?>
                        <?= htmlspecialchars($row['return_date']); ?>
                    <?php else: ?>
                        <em>-</em>
                    <?php endif; ?>
                </p>

                <span class="status-pill <?= strtolower($row['status']); ?>">
                    <?= ucwords(str_replace('_', ' ', $row['status'])); ?>
                </span>
            </div>

            <div class="borrow-action">
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <form method="POST">
                        <input type="hidden" name="loan_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="return_request" class="btn-return">
                            Ajukan Pengembalian
                        </button>
                    </form>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_admin'): ?>
                    <span class="status waiting">Menunggu Konfirmasi Admin</span>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_pengembalian'): ?>
                    <span class="status waiting">Menunggu Konfirmasi Pengembalian</span>

                <?php elseif ($row['status'] === 'dikembalikan'): ?>
                    <span class="status done">Dikembalikan</span>

                <?php else: ?>
                    <span class="status">-</span>
                <?php endif; ?>
            </div>

        </div>
        <?php endwhile; ?>
    </div>
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
