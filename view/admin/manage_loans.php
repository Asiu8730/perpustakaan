<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';
// BorrowController::autoDeleteReturnedBorrows();

// Proses konfirmasi dari admin
    if (isset($_POST['confirm'])) {
        $loan_id = $_POST['loan_id'];
        $return_date = $_POST['return_date'] ?? null;
        $action_type = $_POST['action_type'] ?? '';
    if ($action_type === 'peminjaman') {
        // Admin menyetujui peminjaman → ubah jadi 'dipinjam'
        BorrowController::updateStatus($loan_id, 'dipinjam', $return_date);
    } elseif ($action_type === 'pengembalian') {
        // Admin menyetujui pengembalian → ubah jadi 'dikembalikan'
        BorrowController::updateStatus($loan_id, 'dikembalikan');
    }


    header("Location: ../public/dashboard_admin.php?page=loans");
    exit();
}

$borrows = BorrowController::getAllBorrows();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Kelola Peminjaman Buku</h1>
    <p>Daftar semua permintaan dan status pinjaman</p>

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
            <td><?= htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if ($row['status'] === 'menunggu_konfirmasi_admin'): ?>
                    <button type="button" class="action-btn update-btn"
                        onclick="openConfirmModal('<?= $row['id'] ?>','<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>','peminjaman')">
                        Konfirmasi Peminjaman
                    </button>

                <?php elseif ($row['status'] === 'menunggu_konfirmasi_pengembalian'): ?>
                    <button type="button" class="action-btn update-btn"
                        onclick="openConfirmModal('<?= $row['id'] ?>','<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>','pengembalian')">
                        Konfirmasi Pengembalian
                    </button>

                <?php elseif ($row['status'] === 'dipinjam'): ?>
                    <span class="status warning">Sedang Dipinjam (Tenggat: <?= htmlspecialchars($row['return_date']); ?>)</span>

                <?php elseif ($row['status'] === 'dikembalikan'): ?>
                    <span class="status selesai">Selesai</span>

                <?php else: ?>
                    <span class="status">-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal -->
<div id="confirmModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeConfirmModal()">&times;</span>
    <h3 id="modalTitle">Konfirmasi</h3>

    <form method="POST" action="">
        <input type="hidden" name="loan_id" id="loan_id">
        <input type="hidden" name="action_type" id="action_type">

        <label>Nama Buku</label>
        <input type="text" id="book_title" readonly>

        <label>Nama Peminjam</label>
        <input type="text" id="borrower_name" readonly>

        <div id="returnDateContainer" style="display:none;">
            <label for="return_date">Tanggal Pengembalian</label>
            <input type="date" name="return_date" id="return_date">
        </div>

        <button type="submit" name="confirm" class="btn-primary">Konfirmasi</button>
    </form>
  </div>
</div>

<script>
function openConfirmModal(id, title, borrower, type) {
    document.getElementById("loan_id").value = id;
    document.getElementById("book_title").value = title;
    document.getElementById("borrower_name").value = borrower;
    document.getElementById("action_type").value = type;

    const modal = document.getElementById("confirmModal");
    const returnDateContainer = document.getElementById("returnDateContainer");
    const modalTitle = document.getElementById("modalTitle");
    const returnDateInput = document.getElementById("return_date");

    if (type === "peminjaman") {
        returnDateContainer.style.display = "block";
        modalTitle.textContent = "Konfirmasi Peminjaman Buku";

        // ❌ HAPUS otomatis set tanggal 7 hari
        returnDateInput.value = ""; // kosong, biar admin isi manual

    } else if (type === "pengembalian") {
        returnDateContainer.style.display = "none";
        modalTitle.textContent = "Konfirmasi Pengembalian Buku";
    }

    modal.style.display = "block";
}

</script>

