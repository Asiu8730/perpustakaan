<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../public/assets/css/navbar.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard_admin.php">Dashboard</a></li>
            <li><a href="manage_books.php">Kelola Buku</a></li>
            <li><a href="manage_users.php">Kelola User</a></li>
            <li><a href="borrows.php">Peminjaman</a></li>
            <li><a href="notifications.php">Notifikasi</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Konten utama -->
    <div class="main-content">
        <h1>Selamat Datang, Admin <?= $_SESSION['username'] ?? '' ?></h1>
        <p>Silakan pilih menu di sidebar.</p>
    </div>
</body>
</html>
