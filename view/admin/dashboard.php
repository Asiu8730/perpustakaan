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
            <li><a href="../public/dashboard_admin.php?page=home">Dashboard</a></li>
            <li><a href="../public/dashboard_admin.php?page=books">Kelola Buku</a></li>
            <li><a href="../public/dashboard_admin.php?page=users">Kelola User</a></li>
            <li><a href="../public/dashboard_admin.php?page=categories">Kelola Kategori</a></li>
            <li><a href="../public/dashboard_admin.php?page=loans">Kelola Peminjaman</a></li>
            <li><a href="../public/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Konten Dinamis -->
    <div class="main-content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'books':
                    include __DIR__ . '/manage_books.php';
                    break;
                case 'users':
                    include __DIR__ . '/manage_users.php';
                    break;
                case 'categories';
                    include __DIR__ . '/manage_categories.php';
                    break;
                case 'loans';
                    include __DIR__. '/manage_loans.php';
                    break;
                default:
                    include __DIR__ . '/home.php';
                    break;
            }
        } else {
            include __DIR__ . '/home.php';
        }
        ?>
    </div>
</body>
</html>

