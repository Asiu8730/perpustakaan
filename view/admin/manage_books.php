<?php
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

if ($_SESSION['role'] !== 'admin') die("Akses ditolak");


// Folder upload cover
$upload_dir = __DIR__ . '/../../uploads/covers/';
if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

// Tambah buku
if (isset($_POST['add'])) {
    $cover_name = null;
    $stock = intval($_POST['stock'] ?? 1);
    if (!empty($_FILES['cover']['name'])) {
        $cover_name = time() . '_' . basename($_FILES['cover']['name']);
        move_uploaded_file($_FILES['cover']['tmp_name'], $upload_dir . $cover_name);
    }

    BookController::addBook(
        $_POST['title'],
        $_POST['author'],
        $_POST['publisher'],
        $_POST['category'],
        $_POST['publish_date'],
        $_POST['description'] ?? '',
        $cover_name,
        $_POST['status'],
        $stock
    );
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Update buku
if (isset($_POST['update'])) {
    $cover_name = null;
    $stock = intval($_POST['stock'] ?? 1);
    if (!empty($_FILES['cover']['name'])) {
        $cover_name = time() . '_' . basename($_FILES['cover']['name']);
        move_uploaded_file($_FILES['cover']['tmp_name'], $upload_dir . $cover_name);
    }

    BookController::updateBook(
        $_POST['id'],
        $_POST['title'],
        $_POST['author'],
        $_POST['publisher'],
        $_POST['category'],
        $_POST['publish_date'],
        $_POST['description'],
        $cover_name,
        $_POST['status'],
        $stock
    );
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Hapus buku
if (isset($_GET['delete'])) {
    $result = BookController::deleteBook($_GET['delete']);

    if ($result['status'] === 'error') {
        echo "<script>alert('".$result['message']."');</script>";
    }

    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Ambil data buku & kategori

$search = $_GET['search'] ?? '';
$sort   = $_GET['sort']   ?? '';
$categories = CategoriesController::getAllCategories();

// Pagination
$limit = 5;
$page_now = isset($_GET['p']) ? intval($_GET['p']) : 1;
if ($page_now < 1) $page_now = 1;

$offset = ($page_now - 1) * $limit;

$total_books = BookController::countBooks();
$total_pages = ceil($total_books / $limit);

// 🟢 Tambahkan ini!
$sort = $_GET['sort'] ?? '';  // DEFAULT VALUE untuk mencegah warning
$books = BookController::getBooksPaginated($limit, $offset, $sort, $search);
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<class="books-container">
    <h1>Kelola Buku</h1>
    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah Buku</button>
    <!-- SEARCH & SORT -->
    <form class="search-form" method="GET" action="dashboard_admin.php">
        <input type="hidden" name="page" value="books">

        <input type="text" name="search" placeholder="Cari buku..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

        <select name="sort">
            <option value="">-- Urutkan --</option>
            <option value="newest" <?= ($sort=='newest'?'selected':'') ?>>Terbaru</option>
            <option value="oldest" <?= ($sort=='oldest'?'selected':'') ?>>Terlama</option>
            <option value="title_asc" <?= ($sort=='title_asc'?'selected':'') ?>>Judul A-Z</option>
            <option value="title_desc" <?= ($sort=='title_desc'?'selected':'') ?>>Judul Z-A</option>
        </select>

        <button type="submit">Cari</button>

        <?php if(isset($_GET['search']) || isset($_GET['sort'])): ?>
            <a class="reset-btn" href="dashboard_admin.php?page=books">Reset</a>
        <?php endif; ?>
    </form>
    <!-- END SEARCH & SORT -->
    <h3>Daftar Buku</h3>
    <table class="books-table">
    <tr>
        <th>Cover</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Penerbit</th>
        <th>Kategori</th>
        <th>Tanggal Terbit</th>
        <th>Deskripsi</th>
        <th>Stock</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php if (!empty($books)): ?>
        <?php foreach ($books as $row): ?>
            <tr>
                <td>
                    <?php if (!empty($row['cover'])): ?>
                        <img src="../uploads/covers/<?= htmlspecialchars($row['cover']); ?>" width="60" height="80" style="object-fit:cover;border-radius:4px;">
                    <?php else: ?>
                        <img src="../public/assets/img/no_cover.png" width="60" height="80">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['title']); ?></td>
                <td><?= htmlspecialchars($row['author']); ?></td>
                <td><?= htmlspecialchars($row['publisher']); ?></td>
                <td><?= htmlspecialchars($row['category_name'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($row['publish_date']); ?></td>
                <td style="max-width:200px;"><?= nl2br(htmlspecialchars($row['description'] ?? '-')); ?></td>
                <td><?= htmlspecialchars($row['stock'] ?? 0); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td>
                <button type="button" class="action-btn update-btn"
                    onclick="openEditModal(
                        '<?= $row['id'] ?>',
                        '<?= htmlspecialchars($row['title']) ?>',
                        '<?= htmlspecialchars($row['author']) ?>',
                        '<?= htmlspecialchars($row['publisher']) ?>',
                        '<?= $row['category_id'] ?>',
                        '<?= $row['publish_date'] ?>',
                        `<?= htmlspecialchars($row['description']) ?>`,
                        '<?= $row['status'] ?>',
                        '<?= $row['stock'] ?>'
                    )"
                > Edit </button>

                    <a href="../public/dashboard_admin.php?page=books&delete=<?= $row['id']; ?>"
                       class="action-btn delete-btn"
                       onclick="return confirm('Hapus buku ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">Belum ada buku</td></tr>
    <?php endif; ?>
</table>

<div class="pagination">
    <?php 
        $extra = "&sort=$sort&search=$search";
    ?>

    <?php if ($page_now > 1): ?>
        <a href="dashboard_admin.php?page=books&p=<?= $page_now - 1 ?><?= $extra ?>">&laquo; Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="dashboard_admin.php?page=books&p=<?= $i ?><?= $extra ?>"
           class="<?= ($i == $page_now) ? 'active' : '' ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page_now < $total_pages): ?>
        <a href="dashboard_admin.php?page=books&p=<?= $page_now + 1 ?><?= $extra ?>">Next &raquo;</a>
    <?php endif; ?>
</div>


<!-- Modal Tambah Buku -->
<div id="addModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>
    <h3>Tambah Buku</h3>

    <form method="POST" enctype="multipart/form-data" class="books-form">
        <input type="text" name="title" placeholder="Judul Buku" required>
        <input type="text" name="author" placeholder="Penulis" required>
        <input type="text" name="publisher" placeholder="Penerbit" required>
        <select name="category" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="publish_date" required>

        <label for="description">Deskripsi Buku</label>
        <textarea name="description" id="add_description" rows="3" required></textarea>

        <label for="cover">Cover Buku</label>
        <input type="file" name="cover" accept="image/*">

        <label for="stock">Stok (buku tersedia)</label>
        <input type="number" name="stock" min="0" value="1" required>

        <label for="status">Status</label>
        <select name="status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Tidak Tersedia">Tidak Tersedia</option>
        </select>

        <button type="submit" name="add" class="btn-primary">Tambah</button>
    </form>
  </div>
</div>

<!-- Modal Edit Buku -->
<div id="editModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Buku</h3>

    <form method="POST" enctype="multipart/form-data" class="books-form">
        <input type="hidden" name="id" id="edit_id">

        <input type="text" name="title" id="edit_title" required>
        <input type="text" name="author" id="edit_author" required>
        <input type="text" name="publisher" id="edit_publisher" required>

        <select name="category" id="edit_category" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <input type="date" name="publish_date" id="edit_publish_date" required>

        <label>Deskripsi Buku</label>
        <textarea name="description" id="edit_description" rows="3" required></textarea>

        
        <label>Ganti Cover (opsional)</label>
        <input type="file" name="cover" accept="image/*">

        <label>Stok Buku (Jumlah tersedia)</label>
        <input type="number" name="stock" id="edit_stock" min="0" required>

        <label>Status</label>
        <select name="status" id="edit_status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Tidak Tersedia">Tidak Tersedia</option>
        </select>

        <button type="submit" name="update">Update</button>
    </form>
  </div>
</div>

<script src="assets/js/admin/manage.js">
</script>
