<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once __DIR__ . '/../../controllers/BookController.php';

// Tambah buku
if (isset($_POST['add'])) {
    BookController::addBook($_POST['title'], $_POST['author'], $_POST['publisher'], $_POST['year'], $_POST['stock']);
    header("Location: manage_books.php");
    exit();
}

// Update buku
if (isset($_POST['update'])) {
    BookController::updateBook($_POST['id'], $_POST['title'], $_POST['author'], $_POST['publisher'], $_POST['year'], $_POST['stock']);
    header("Location: manage_books.php");
    exit();
}

// Hapus buku
if (isset($_GET['delete'])) {
    BookController::deleteBook($_GET['delete']);
    header("Location: manage_books.php");
    exit();
}

$books = BookController::getAllBooks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Buku</title>
    <link rel="stylesheet" href="../public/assets/css/navbar.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background: #2c3e50; color: #fff; }
        form { margin-top: 20px; }
        input, button { padding: 8px; margin: 5px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard_admin.php">Dashboard</a></li>
            <li><a href="manage_books.php">Kelola Buku</a></li>
            <li><a href="manage_users.php">Kelola User</a></li>
            <li><a href="../public/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Kelola Buku</h1>

        <!-- Form tambah buku -->
        <h3>Tambah Buku</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Judul Buku" required>
            <input type="text" name="author" placeholder="Penulis" required>
            <input type="text" name="publisher" placeholder="Penerbit" required>
            <input type="number" name="year" placeholder="Tahun" required>
            <input type="number" name="stock" placeholder="Stok" required>
            <button type="submit" name="add">Tambah</button>
        </form>

        <!-- Daftar buku -->
        <h3>Daftar Buku</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $books->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['author'] ?></td>
                <td><?= $row['publisher'] ?></td>
                <td><?= $row['year'] ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <!-- Edit -->
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="title" value="<?= $row['title'] ?>">
                        <input type="text" name="author" value="<?= $row['author'] ?>">
                        <input type="text" name="publisher" value="<?= $row['publisher'] ?>">
                        <input type="number" name="year" value="<?= $row['year'] ?>">
                        <input type="number" name="stock" value="<?= $row['stock'] ?>">
                        <button type="submit" name="update">Update</button>
                    </form>
                    <!-- Hapus -->
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus buku ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
