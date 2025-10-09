<?php
require_once __DIR__ . '/../../controllers/CategoriesController.php';

// Tambah kategori
if (isset($_POST['add'])) {
    CategoriesController::addCategory($_POST['name']);
    header("Location: ../public/dashboard_admin.php?page=categories");
    exit();
}

// Update kategori
if (isset($_POST['update'])) {
    CategoriesController::updateCategory($_POST['id'], $_POST['name']);
    header("Location: ../public/dashboard_admin.php?page=categories");
    exit();
}

// Hapus kategori
if (isset($_GET['delete'])) {
    CategoriesController::deleteCategory($_GET['delete']);
    header("Location: ../public/dashboard_admin.php?page=categories");
    exit();
}

$categories = CategoriesController::getAllCategories();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/books.css"> <!-- pakai css buku biar konsisten -->

<div class="books-container">
    <h1>Kelola Kategori</h1>
    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah Kategori</button>


    <h3>Daftar Kategori</h3>
    <table class="books-table">
        <tr>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $categories->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td>
                <button type="button" class="action-btn update-btn"
                        onclick="openEditModal('<?= $row['id'] ?>', '<?= $row['name'] ?>')">
                    Edit
                </button>
                <a href="../public/dashboard_admin.php?page=categories&delete=<?= $row['id'] ?>" 
                   class="action-btn delete-btn"
                   onclick="return confirm('Hapus kategori ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal Tambah Kategori -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>
    <h3>Tambah Kategori</h3>
    <form method="POST" class="books-form">
        <input type="text" name="name" placeholder="Nama Kategori" required>
        <button type="submit" name="add">Tambah</button>
    </form>
  </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Kategori</h3>
    <form method="POST" class="books-form">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="name" id="edit_name" required>
        <button type="submit" name="update">Update</button>
    </form>
  </div>
</div>

<script>
function openAddModal() {
    document.getElementById("addModal").style.display = "block";
}
function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}
function openEditModal(id, name) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("editModal").style.display = "block";
}
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
window.onclick = function(event) {
    let addModal = document.getElementById("addModal");
    let editModal = document.getElementById("editModal");
    if (event.target == addModal) addModal.style.display = "none";
    if (event.target == editModal) editModal.style.display = "none";
}
</script>