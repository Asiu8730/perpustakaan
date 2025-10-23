<?php
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

// Folder upload cover
$upload_dir = __DIR__ . '/../../uploads/covers/';
if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

// Tambah buku
if (isset($_POST['add'])) {
    $cover_name = null;
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
        $_POST['stock'],
        $cover_name,
        $_POST['status']
    );
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Update buku
if (isset($_POST['update'])) {
    $cover_name = null;
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
        $_POST['stock'],
        $cover_name,
        $_POST['status']
    );
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Hapus buku
if (isset($_GET['delete'])) {
    BookController::deleteBook($_GET['delete']);
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Ambil data buku & kategori
$sort = $_GET['sort'] ?? '';
$books = BookController::getAllBooks($sort);
$categories = CategoriesController::getAllCategories();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/books.css">

<div class="books-container">
    <h1>Kelola Buku</h1>
    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah Buku</button>

    <h3>Daftar Buku</h3>
    <table class="books-table">
        <tr>
            <th>Cover</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Kategori</th>
            <th>Tanggal Terbit</th>
            <th>Stok</th>
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
                    <td><?= htmlspecialchars($row['stock']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <button type="button" class="action-btn update-btn"
                            onclick="openEditModal(
                                '<?= $row['id'] ?>',
                                '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($row['author'], ENT_QUOTES) ?>',
                                '<?= htmlspecialchars($row['publisher'], ENT_QUOTES) ?>',
                                '<?= $row['category_id'] ?>',
                                '<?= $row['publish_date'] ?>',
                                '<?= $row['stock'] ?>',
                                '<?= $row['status'] ?>'
                            )">Edit</button>
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
        <input type="number" name="stock" placeholder="Stok" required>
        <label for="cover">Cover Buku</label>
        <input type="file" name="cover" accept="image/*">

        <label for="status">Status</label>
        <select name="status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Dipinjam">Dipinjam</option>
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
        <input type="number" name="stock" id="edit_stock" required>
        <label for="cover">Ganti Cover (opsional)</label>
        <input type="file" name="cover" accept="image/*">

        <label for="status">Status</label>
        <select name="status" id="edit_status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Tidak Tersedia">Tidak Tersedia</option>
        </select>

        <button type="submit" name="update">Update</button>
    </form>
  </div>
</div>

<script>
function openAddModal() { document.getElementById("addModal").style.display = "block"; }
function closeAddModal() { document.getElementById("addModal").style.display = "none"; }

function openEditModal(id, title, author, publisher, category, publish_date, stock, status) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_title").value = title;
    document.getElementById("edit_author").value = author;
    document.getElementById("edit_publisher").value = publisher;
    document.getElementById("edit_category").value = category;
    document.getElementById("edit_publish_date").value = publish_date;
    document.getElementById("edit_stock").value = stock;
    document.getElementById("edit_status").value = status;
    document.getElementById("editModal").style.display = "block";
}
function closeEditModal() { document.getElementById("editModal").style.display = "none"; }
</script>
