<?php
require_once __DIR__ . '/../../controllers/UserController.php';

// Tambah user
if (isset($_POST['add'])) {
    UserController::addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role']);
    header("Location: ../public/dashboard_admin.php?page=users");
    exit();
}

// Update user
if (isset($_POST['update'])) {
    UserController::updateUser($_POST['id'], $_POST['username'], $_POST['email'], $_POST['role']);
    header("Location: ../public/dashboard_admin.php?page=users");
    exit();
}

// Hapus user
if (isset($_GET['delete'])) {
    UserController::deleteUser($_GET['delete']);
    header("Location: ../public/dashboard_admin.php?page=users");
    exit();
}

$users = UserController::getAllUsers();
?>

<base href="/reca/perpustakaan/public/">
<link rel="stylesheet" href="assets/css/books.css"> <!-- gunakan css buku biar sama -->

<div class="books-container"> <!-- pakai class yang sama biar konsisten -->
    <h1>Kelola User</h1>
    <button type="button" class="action-btn update-btn" onclick="openAddModal()">+ Tambah User</button>

    <h3>Daftar User</h3>
    <table class="books-table"> <!-- class tabel sama -->
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['role'] ?></td>
            <td>
                <button type="button" class="action-btn update-btn"
                        onclick="openEditModal('<?= $row['id'] ?>',
                                               '<?= $row['username'] ?>',
                                               '<?= $row['email'] ?>',
                                               '<?= $row['role'] ?>')">
                    Edit
                </button>
                <a href="../public/dashboard_admin.php?page=users&delete=<?= $row['id'] ?>" 
                   class="action-btn delete-btn"
                   onclick="return confirm('Hapus user ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal Tambah User -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>
    <h3>Tambah User</h3>
    <form method="POST" class="books-form"> <!-- pakai style form buku -->
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit" name="add" class="btn-blue">Tambah</button>
    </form>
  </div>
</div>

<!-- Modal Edit User -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit User</h3>
    <form method="POST" class="books-form">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="username" id="edit_username" required>
        <input type="email" name="email" id="edit_email" required>
        <select name="role" id="edit_role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit" name="update" class="btn-blue">Update</button>
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
function openEditModal(id, username, email, role) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_username").value = username;
    document.getElementById("edit_email").value = email;
    document.getElementById("edit_role").value = role;
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
