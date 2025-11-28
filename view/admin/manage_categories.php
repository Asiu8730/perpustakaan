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

// Search
$keyword = $_GET['search'] ?? "";

// Pagination
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if (!empty($keyword)) {
    // Search manual
    global $conn;
    $like = "%".$keyword."%";
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name LIKE ? ORDER BY id DESC");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $total_categories = count($categories);
    $total_pages = 1;
} else {
    // Normal pagination
    $categories = CategoriesController::getPaginatedCategories($limit, $offset);
    $total_categories = CategoriesController::countCategories();
    $total_pages = ceil($total_categories / $limit);
}

?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Kelola Kategori</h1>
    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah Kategori</button>

    <!-- SEARCH -->
    <form method="GET" class="search-form" style="margin-top:15px;">
        <input type="hidden" name="page" value="categories">

        <input type="text" name="search" placeholder="Cari kategori..."
                value="<?= htmlspecialchars($keyword) ?>">

        <button type="submit">Search</button>

        <?php if (!empty($keyword)): ?>
            <a href="dashboard_admin.php?page=categories" class="reset-btn">Reset</a>
        <?php endif; ?>
    </form>

    <table class="books-table">
        <tr>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <button type="button" class="action-btn update-btn"
                        onclick="openEditModal('<?= $row['id'] ?>', '<?= htmlspecialchars($row['name']) ?>')">
                        Edit
                    </button>

                    <a href="dashboard_admin.php?page=categories&delete=<?= $row['id'] ?>"
                        class="action-btn delete-btn"
                        onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2" style="text-align:center;">Tidak ada kategori</td></tr>
        <?php endif; ?>
    </table>

    <!-- PAGINATION -->
    <?php if (empty($keyword)): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="dashboard_admin.php?page=categories&p=<?= $page - 1 ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="dashboard_admin.php?page=categories&p=<?= $i ?>"
                class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="dashboard_admin.php?page=categories&p=<?= $page + 1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="modal">
    <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>
    <h3>Tambah Kategori</h3>

    <form method="POST" class="books-form">
        <input type="text" name="name" placeholder="Nama kategori" required>
        <button type="submit" name="add" class="btn-blue">Tambah</button>
    </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal">
    <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Kategori</h3>

    <form method="POST" class="books-form">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="name" id="edit_name" required>
        <button type="submit" name="update" class="btn-blue">Update</button>
    </form>
    </div>
</div>

<script src="assets/js/admin/category.js"></script>