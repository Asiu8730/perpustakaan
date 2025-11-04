<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';

// ======== PROSES KONFIRMASI (WAJIB DI ATAS SEBELUM OUTPUT) ========
if (isset($_POST['confirm'])) {
    $loan_id = $_POST['loan_id'];
    $return_date = $_POST['return_date'];
    
    // Update status peminjaman
    BorrowController::updateStatus($loan_id, 'dikembalikan', $return_date);

    // Redirect ulang agar tidak submit ulang form
    header("Location: ../public/dashboard_admin.php?page=loans");
    exit();
}

// ======== AMBIL DATA PEMINJAMAN ========
$borrows = BorrowController::getAllBorrows();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Kelola Peminjaman Buku</h1>
    <p>Daftar Buku yang Dipinjam</p>

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
        $no = 1;
        while ($row = $borrows->fetch_assoc()): 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['borrow_date']); ?></td>
            <td><?= htmlspecialchars($row['return_date'] ?? '-'); ?></td>
            <td>
                <span class="status <?= htmlspecialchars(strtolower($row['status'])); ?>">
                    <?= htmlspecialchars($row['status']); ?>
                </span>
            </td>
            <td>
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <button type="button" class="action-btn update-btn"
                        onclick="openConfirmModal(
                            '<?= $row['id'] ?>',
                            '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>'
                        )">
                        Konfirmasi
                    </button>
                <?php else: ?>
                    <span class="status selesai">Selesai</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeConfirmModal()">&times;</span>
    <h3>Konfirmasi Pengembalian Buku</h3>

    <form method="POST" action="">
        <input type="hidden" name="loan_id" id="loan_id">

        <label>Nama Buku</label>
        <input type="text" id="book_title" readonly>

        <label>Nama Peminjam</label>
        <input type="text" id="borrower_name" readonly>

        <label for="return_date">Tanggal Pengembalian</label>
        <input type="date" name="return_date" id="return_date" required>

        <button type="submit" name="confirm" class="btn-primary">Konfirmasi</button>
    </form>
  </div>
</div>

<script>
function openConfirmModal(id, title, borrower) {
    document.getElementById("loan_id").value = id;
    document.getElementById("book_title").value = title;
    document.getElementById("borrower_name").value = borrower;
    document.getElementById("confirmModal").style.display = "block";
}
function closeConfirmModal() {
    document.getElementById("confirmModal").style.display = "none";
}
</script>
