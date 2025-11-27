<?php
// If the admin requested a print of the reports page, render the report file
// as a standalone print page and exit early (prevents sidebar/layout from being printed)
if (isset($_GET['page']) && $_GET['page'] === 'reports_most_borrowed' && isset($_GET['print']) && $_GET['print'] == '1') {
    include __DIR__ . '/reports_most_borrowed.php';
    exit; // stop further layout output so print page is clean
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/admin/sidebar.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li onclick="location.href='/reca/perpustakaan/public/dashboard_admin.php?page=books'">Kelola Buku</li>
            <li onclick="location.href='/reca/perpustakaan/public/dashboard_admin.php?page=users'">Kelola User</li>
            <li onclick="location.href='/reca/perpustakaan/public/dashboard_admin.php?page=categories'">Kelola Kategori</li>
            <li onclick="location.href='/reca/perpustakaan/public/dashboard_admin.php?page=loans'">Kelola Peminjaman</li>
            <li onclick="location.href='/reca/perpustakaan/public/dashboard_admin.php?page=reports_most_borrowed'">Laporan - Buku Paling Populer</li>
            <li onclick="location.href='/reca/perpustakaan/public/logout.php'">Logout</li>
        </ul>
    </div>

    <!-- Konten Dinamis -->
    <div class="main-content">
        <?php
        $page = $_GET['page'] ?? 'books';
        switch ($page) {
            case 'books':
                include __DIR__ . '/manage_books.php';
                break;
            case 'users':
                include __DIR__ . '/manage_users.php';
                break;
            case 'categories':
                include __DIR__ . '/manage_categories.php';
                break;
            case 'loans':
                include __DIR__ . '/manage_loans.php';
                break;
            default:
                include __DIR__ . '/home.php';
                break;
            case 'notifications':
                include __DIR__ . '/notification.php';
                break;
            case 'reports_most_borrowed':
                include __DIR__ . '/reports_most_borrowed.php';
                break;

        }
        ?>
    </div>
</body>
</html>
