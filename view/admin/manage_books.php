<?php
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

// Tambah buku
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
        $cover_name
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
        $cover_name
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
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$books = BookController::getAllBooks($sort);
$categories = CategoriesController::getAllCategories();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/books.css">

<div class="books-container">
    <h1>Kelola Buku</h1>

   <!-- Tombol Tambah Buku -->
<button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah Buku</button>

    <!-- Daftar Buku -->
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
            <th>Aksi</th>
        </tr>
        <?php while ($row = $books->fetch_assoc()): ?>
        <tr>
            <td><?php if (!empty($row['cover'])): ?>
            <img src="../uploads/covers/<?= htmlspecialchars($row['cover']) ?>" width="60" height="80" style="object-fit:cover;border-radius:4px;">
            <?php else: ?>
            <span>-</span>
            <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= htmlspecialchars($row['publisher']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['publish_date']) ?></td>
            <td><?= htmlspecialchars($row['stock']) ?></td>
            <td>
    <button type="button" class="action-btn update-btn"
        onclick="openEditModal(
            '<?= $row['id'] ?>',
            '<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>',
            '<?= htmlspecialchars($row['author'], ENT_QUOTES) ?>',
            '<?= htmlspecialchars($row['publisher'], ENT_QUOTES) ?>',
            '<?= $row['category_id'] ?>',
            '<?= $row['publish_date'] ?>',
            '<?= $row['stock'] ?>'
        )">
        Edit
    </button>
    <a href="../public/dashboard_admin.php?page=books&delete=<?= $row['id'] ?>" 
       class="action-btn delete-btn"
       onclick="return confirm('Hapus buku ini?')">Hapus</a>
</td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal Tambah Buku -->
<div id="addModal" class="modal" style="display:none;">
  <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Tambah Buku</h3>
    <form method="GET" action="../public/dashboard_admin.php" style="margin-bottom: 15px;">
    <input type="hidden" name="page" value="books">
    <label for="sort">Urutkan berdasarkan:</label>
    <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="">-- Pilih Urutan --</option>
        <option value="title_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : '' ?>>Nama (A-Z)</option>
        <option value="title_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') ? 'selected' : '' ?>>Nama (Z-A)</option>
        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Terbaru</option>
        <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Terlama</option>
    </select>
    </form>
        <form method="POST" enctype="multipart/form-data" class="books-form">
                <input type="text" name="title" placeholder="Judul Buku" required>
                <input type="text" name="author" placeholder="Penulis" required>
                <input type="text" name="publisher" placeholder="Penerbit" required>
                <select name="category" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="date" name="publish_date" required>
                <input type="number" name="stock" placeholder="Stok" required>

                <!-- Tambahan baru -->
                <label for="cover">Cover Buku</label>
                <input type="file" name="cover" accept="image/*">

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
                <?php
                $categories2 = CategoriesController::getAllCategories();
                while ($cat = $categories2->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
            <input type="date" name="publish_date" id="edit_publish_date" required>
            <input type="number" name="stock" id="edit_stock" required>

            <!-- Tambahan baru -->
            <label for="cover">Ganti Cover (opsional)</label>
            <input type="file" name="cover" accept="image/*">

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
    console.log("Modal Tambah dibuka"); // debug
    document.getElementById("addModal").style.display = "block";
}

function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}

window.onclick = function(event) {
    let modal = document.getElementById("addModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

function openEditModal(id, title, author, publisher, category, publish_date, stock) {
    console.log("Modal Edit dibuka:", id, title); // Debug

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_title").value = title;
    document.getElementById("edit_author").value = author;
    document.getElementById("edit_publisher").value = publisher;
    document.getElementById("edit_category").value = category;
    document.getElementById("edit_publish_date").value = publish_date;
    document.getElementById("edit_stock").value = stock;

    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// Tutup modal jika klik luar
window.onclick = function(event) {
    let modal = document.getElementById("editModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>
