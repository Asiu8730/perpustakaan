<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/admin/navbar.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <div class="notification-container">
            
            <li><a href="/reca/perpustakaan/public/dashboard_admin.php?page=home">Dashboard</a></li>
            <li><a href="/reca/perpustakaan/public/dashboard_admin.php?page=books">Kelola Buku</a></li>
            <li><a href="/reca/perpustakaan/public/dashboard_admin.php?page=users">Kelola User</a></li>
            <li><a href="/reca/perpustakaan/public/dashboard_admin.php?page=categories">Kelola Kategori</a></li>
            <li><a href="/reca/perpustakaan/public/dashboard_admin.php?page=loans">Kelola Peminjaman</a></li>
            <li>
            <a href="/reca/perpustakaan/public/dashboard_admin.php?page=notifications" style="display:flex;justify-content:space-between;align-items:center;">
                Notifikasi
                <?php 
                    require_once __DIR__ . '/../../controllers/NotificationController.php';
                    $notifications = NotificationController::getAllNotifications();
                    $unread = array_filter($notifications, fn($n) => $n['is_read'] == 0);
                    if (count($unread) > 0): ?>
                        <span style="
                            background-color: red;
                            color: white;
                            border-radius: 10px;
                            padding: 2px 6px;
                            font-size: 12px;
                            margin-left: 8px;
                        ">
                            <?= count($unread); ?>
                        </span>
                <?php endif; ?>
            </a>
            </li>
            <li><a href="/reca/perpustakaan/public/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Konten Dinamis -->
    <div class="main-content">
        <?php
        $page = $_GET['page'] ?? 'home';
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

        }
        ?>
    </div>
</body>
</html>
