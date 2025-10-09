<?php
require_once __DIR__ . '/../../controllers/BookController.php';
require_once __DIR__ . '/../../controllers/CategoriesController.php';

// Tambah buku
if (isset($_POST['add'])) {
    BookController::addBook(
        $_POST['title'],
        $_POST['author'],
        $_POST['publisher'],
        $_POST['category'],
        $_POST['publish_date'],
        $_POST['stock']
    );
    header("Location: ../public/dashboard_admin.php?page=books");
    exit();
}

// Update buku
if (isset($_POST['update'])) {
    BookController::updateBook(
        $_POST['id'],
        $_POST['title'],
        $_POST['author'],
        $_POST['publisher'],
        $_POST['category'],
        $_POST['publish_date'],
        $_POST['stock']
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
$books = BookController::getAllBooks();
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
    <form method="POST" class="books-form">
        <input type="text" name="title" placeholder="Judul Buku" required>
        <input type="text" name="author" placeholder="Penulis" required>
        <input type="text" name="publisher" placeholder="Penerbit" required>

        <select name="category" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
            $categories = CategoriesController::getAllCategories();
            while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="date" name="publish_date" required>
        <input type="number" name="stock" placeholder="Stok" required>
        <button type="submit" name="add" class="btn-primary">Tambah</button>
    </form>
  </div>
</div>

<!-- Modal Edit Buku -->
<div id="editModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Buku</h3>
    <form method="POST" class="books-form">
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
        <button type="submit" name="update" class="btn-primary">Update</button>
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
