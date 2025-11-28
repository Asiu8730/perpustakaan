<?php
require_once __DIR__ . '/../../controllers/BorrowController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

// Hanya admin
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

// Ambil filter dari query string
$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;
$category = isset($_GET['category']) && $_GET['category'] !== '' ? intval($_GET['category']) : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
if ($limit < 1) $limit = 20;

$categories = CategoriesController::getAllCategories();
$data = BorrowController::getMostBorrowedBooksFiltered($start, $end, $category, $limit);

?><base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Laporan - Buku Paling Sering Dipinjam</h1>

    <div class="no-print" style="display:flex;gap:10px;align-items:center;margin-bottom:18px;">
            <form method="GET" class="search-form" style="display:inline-flex;align-items:center;">
            <input type="hidden" name="page" value="reports_most_borrowed">
            <label style="font-size:13px;">Dari</label>
            <input type="date" name="start" value="<?= htmlspecialchars($start ?? '') ?>">
            <label style="font-size:13px;">Sampai</label>
            <input type="date" name="end" value="<?= htmlspecialchars($end ?? '') ?>">
            <label style="font-size:13px;">Kategori</label>
            <select name="category">
                <option value="">Semua</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($category !== null && $category == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label style="font-size:13px;">Limit</label>
            <input type="number" name="limit" value="<?= $limit ?>" min="1" style="width:80px;">
                <button type="submit" class="update-btn">Filter</button>
        </form>
            <button onclick="window.print()" class="btn-blue no-print" style="margin-left:12px;">Print Laporan</button>
    </div>

    <div class="report-area p-0">
        <table class="books-table report-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Book ID</th>
                    <th>Judul</th>
                    <th>Author</th>
                    <th>Kategori</th>
                    <th style="width:150px;">Total Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="6">Tidak ada data untuk filter ini.</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['book_id']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['category_name'] ?? '-') ?></td>
                            <td style="text-align:center;font-weight:700;"><?= intval($row['total_borrowed']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p style="margin-top:18px;font-size:13px;color:#6b7280;">Dicetak: <?= date('Y-m-d H:i:s') ?></p>
</div>

<!-- Print styles -->
<style>
    @media print {
        /* Hide everything except report area */
        body * { visibility: hidden; }
        .report-area, .report-area * { visibility: visible; }
        .report-area { position: absolute; left: 0; top: 0; width: 100%; }
        /* Remove admin action buttons */
        .no-print, .action-btn, .delete-btn, .update-btn { display:none !important; }
    }

    /* On screen: make printed table look nice */
    .report-area { background: #272727ff; padding: 0px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04); }
    .report-table th { background:#272727ff; padding:8px; text-align:left; }
    .report-table td { padding:8px; border-top:1px solid #eee; }

    /* ensure back button visible on screen but not printed */
    .no-print { display:inline-flex; }
    @media print { .no-print { display:none !important; } }
</style>
