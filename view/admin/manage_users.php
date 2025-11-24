<?php
require_once __DIR__ . '/../../controllers/UserController.php';

/* ================================
   SEARCH + SORT
================================ */
$keyword = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

/* ================================
   PAGINATION
================================ */
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
if ($page < 1) $page = 1;

$limit = 10;
$offset = ($page - 1) * $limit;

$users = UserController::getPaginatedUsers($limit, $offset, $keyword, $sort);
$total_users = UserController::countUsers($keyword);
$total_pages = ceil($total_users / $limit);

/* ================================
   ACTIONS
================================ */
if (isset($_POST['add'])) {
    UserController::addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role']);
    header("Location: dashboard_admin.php?page=users");
    exit();
}

if (isset($_POST['update'])) {
    $password = ($_POST['password'] !== "") ? $_POST['password'] : null;

    UserController::updateUser(
        $_POST['id'],
        $_POST['username'],
        $_POST['email'],
        $_POST['role'],
        $password
    );

    header("Location: dashboard_admin.php?page=users");
    exit();
}

if (isset($_GET['delete'])) {
    UserController::deleteUser($_GET['delete']);
    header("Location: dashboard_admin.php?page=users");
    exit();
}
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/admin/books.css">
<link rel="stylesheet" href="assets/css/admin/manage_users.css">

<div class="books-container">
    <h1>Kelola User</h1>

    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah User</button>

    <!-- SEARCH + SORT -->
    <form method="GET" class="search-form">
        <input type="hidden" name="page" value="users">

        <input type="text" name="search" placeholder="Cari user..."
               value="<?= htmlspecialchars($keyword) ?>">

        <select name="sort">
            <option value="">Urutkan</option>
            <option value="az" <?= ($sort == 'az') ? 'selected' : '' ?>>A → Z</option>
            <option value="za" <?= ($sort == 'za') ? 'selected' : '' ?>>Z → A</option>
            <option value="newest" <?= ($sort == 'newest') ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= ($sort == 'oldest') ? 'selected' : '' ?>>Terlama</option>
        </select>

        <button type="submit">Cari</button>

        <!-- RESET MUNCUL HANYA SAAT ADA SEARCH -->
        <?php if ($keyword !== '' || $sort !== ''): ?>
            <a class="reset-btn" href="dashboard_admin.php?page=users">Reset</a>
        <?php endif; ?>
    </form>

    <h3>Daftar User</h3>
    <table class="books-table">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($users as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>

            <td>
                <button type="button" class="action-btn update-btn"
                        onclick="openEditModal(
                            '<?= $row['id'] ?>',
                            '<?= htmlspecialchars($row['username']) ?>',
                            '<?= htmlspecialchars($row['email']) ?>',
                            '<?= $row['role'] ?>'
                        )">
                    Edit
                </button>

                <a href="dashboard_admin.php?page=users&delete=<?= $row['id'] ?>"
                   class="action-btn delete-btn"
                   onclick="return confirm('Hapus user ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="dashboard_admin.php?page=users&p=<?= $page - 1 ?>&search=<?= $keyword ?>&sort=<?= $sort ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="dashboard_admin.php?page=users&p=<?= $i ?>&search=<?= $keyword ?>&sort=<?= $sort ?>"
               class="<?= ($i == $page) ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="dashboard_admin.php?page=users&p=<?= $page + 1 ?>&search=<?= $keyword ?>&sort=<?= $sort ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>

    <h3>Tambah User</h3>
    <form method="POST" class="books-form">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <button type="submit" name="add" class="btn-blue">Tambah</button>
    </form>
  </div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>

    <h3>Edit User</h3>
    <form method="POST" class="books-form">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="username" id="edit_username">
        <input type="email" name="email" id="edit_email">
        <input type="password" name="password" id="edit_password" placeholder="Isi jika ingin ubah password">
        <select name="role" id="edit_role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <button type="submit" name="update" class="btn-blue">Update</button>
    </form>
  </div>
</div>

<script src="assets/js/admin/user.js"></script>
