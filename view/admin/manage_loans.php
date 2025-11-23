<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';

// =======================
// PROSES KONFIRMASI ADMIN
// =======================
if (isset($_POST['confirm'])) {
    $loan_id = $_POST['loan_id'];
    $return_date = $_POST['return_date'] ?? null;
    $action_type = $_POST['action_type'] ?? '';

    if ($action_type === 'peminjaman') {
        BorrowController::updateStatus($loan_id, 'dipinjam', $return_date);

    } elseif ($action_type === 'pengembalian') {
        BorrowController::updateStatus($loan_id, 'dikembalikan');
    }

    header("Location: ../public/dashboard_admin.php?page=loans");
    exit();
}

// =======================
// SEARCH
// =======================
$keyword = $_GET['search'] ?? '';

// =======================
// PAGINATION
// =======================
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

if ($keyword !== '') {
    $borrows = BorrowController::searchBorrows($keyword, $limit, $offset);
    $total_data = BorrowController::countSearchBorrows($keyword);
} else {
    $borrows = BorrowController::getPaginatedBorrows($limit, $offset);
    $total_data = BorrowController::countBorrows();
}

$total_pages = ceil($total_data / $limit);
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Kelola Peminjaman Buku</h1>

    <!-- SEARCH BAR -->
    <form method="GET" class="search-form">
        <input type="hidden" name="page" value="loans">

        <input type="text" name="search" placeholder="Cari nama user / judul buku..."
               value="<?= htmlspecialchars($keyword) ?>">

        <button type="submit">Search</button>

        <?php if ($keyword !== ''): ?>
            <a href="dashboard_admin.php?page=loans" class="reset-btn">Reset</a>
        <?php endif; ?>
    </form>

    <table class="books-table">
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php 
        $no = $offset + 1;
        while ($row = $borrows->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['borrow_date']); ?></td>
            <td><?= htmlspecialchars($row['return_date'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['status']); ?></td>

            <td>
                <?php if ($row['status'] === 'menunggu_konfirmasi_admin'): ?>
                    <button class="action-btn update-btn"
                        onclick="openConfirmModal('<?= $row['id'] ?>',
                                                  '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>',
                                                  '<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>',
                                                  'peminjaman')">Konfirmasi Peminjaman
                    </button>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_pengembalian'): ?>
                    <button class="action-btn update-btn"
                        onclick="openConfirmModal('<?= $row['id'] ?>',
                                                  '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>',
                                                  '<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>',
                                                  'pengembalian')">Konfirmasi Pengembalian
                    </button>

                <?php elseif ($row['status'] === 'dipinjam'): ?>
                    <span class="status warning">Dipinjam (Tenggat: <?= $row['return_date']; ?>)</span>

                <?php elseif ($row['status'] === 'dikembalikan'): ?>
                    <span class="status selesai">Selesai</span>

                <?php else: ?>
                    <span class="status">-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="dashboard_admin.php?page=loans&p=<?= $page - 1 ?>&search=<?= $keyword ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="dashboard_admin.php?page=loans&p=<?= $i ?>&search=<?= $keyword ?>"
               class="<?= ($i == $page) ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="dashboard_admin.php?page=loans&p=<?= $page + 1 ?>&search=<?= $keyword ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL KONFIRMASI (SAMA DENGAN MODAL TAMBAH/EDIT BUKU) -->
<div id="confirmModal" class="modal">
  <div class="modal-content">

    <span class="close" onclick="closeConfirmModal()">&times;</span>

    <h3 id="modalTitle">Konfirmasi</h3>

    <form method="POST" class="books-form">

        <input type="hidden" name="loan_id" id="loan_id">
        <input type="hidden" name="action_type" id="action_type">

        <label>Nama Buku</label>
        <input type="text" id="book_title" readonly>

        <label>Nama Peminjam</label>
        <input type="text" id="borrower_name" readonly>

        <div id="returnDateContainer" style="display:none;">
            <label>Tanggal Pengembalian</label>
            <input type="date" name="return_date" id="return_date">
        </div>

        <button type="submit" name="confirm" class="btn-blue">Konfirmasi</button>
    </form>

  </div>
</div>

<script src="assets/js/admin/loans.js"></script>
