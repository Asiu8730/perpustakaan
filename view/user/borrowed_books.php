<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';
require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'];

// Pagination
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
if ($page < 1) $page = 1;

$limit = 5; 
$offset = ($page - 1) * $limit;

// Ambil data pinjaman
$borrowed = BorrowController::getPaginatedUserBorrows($user_id, $limit, $offset);
$total_borrowed = BorrowController::countUserBorrows($user_id);
$total_pages = ceil($total_borrowed / $limit);
?>

<link rel="stylesheet" href="assets/css/user/borrowed_books.css">
<link rel="stylesheet" href="assets/css/user/borrowed_pagination.css">
<link rel="stylesheet" href="assets/css/global.css">

<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="borrowed-container">
    <h2 class="borrowed-title">📚 Daftar Buku yang Dipinjam</h2>

    <div class="borrowed-list">
        <?php foreach ($borrowed as $row): ?>
        <div class="borrow-card">

            <div class="borrow-info">
                <h3><?= htmlspecialchars($row['title']); ?></h3>

                <p><strong>Tanggal Pinjam:</strong> <?= htmlspecialchars($row['borrow_date']); ?></p>

                <p><strong>Tenggat:</strong>
                    <?= $row['return_date'] ? htmlspecialchars($row['return_date']) : '<em>-</em>' ?>
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

                <?php endif; ?>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="dashboard_user.php?page=borrowed_books&p=<?= $page-1 ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="dashboard_user.php?page=borrowed_books&p=<?= $i ?>" 
               class="<?= ($i == $page) ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="dashboard_user.php?page=borrowed_books&p=<?= $page+1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

</div>

<?php
// Ajukan pengembalian
if (isset($_POST['return_request'])) {
    BorrowController::requestReturn($_POST['loan_id']);

    echo "<script>
        alert('Permintaan pengembalian berhasil dikirim.');
        window.location.href = 'dashboard_user.php?page=borrowed_books';
    </script>";
}
?>
