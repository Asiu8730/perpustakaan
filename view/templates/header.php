<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../../controllers/NotificationController.php';
$notif_count = NotificationController::countUnread($_SESSION['user_id']);
?>

<link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/user/navbar.css">

<header class="navbar">
    <div class="navbar-left">
        <img src="/reca/perpustakaan/uploads/logo/logo.jpg" alt="Logo" class="logo">
        <h1><a href="/reca/perpustakaan/public/dashboard_user.php" class="home-link">Perpustakaan</a></h1>
    </div>

    <div class="navbar-center">
    <form method="GET" action="/reca/perpustakaan/public/dashboard_user.php">
        <input type="hidden" name="page" value="search">
        <input id="searchInput" type="text" name="q" placeholder="Cari buku, penulis, atau kategori...">
    </form>

    <!-- 🟦 Dropdown Hasil Pencarian -->
    <div id="searchResults" class="search-results"></div>
    </div>

    <div class="navbar-right">
        <div class="user-menu">

            <!-- Foto Profil + Badge Notifikasi -->
            <div class="user-menu">
            <div class="notif-wrapper">
                <img src="/reca/perpustakaan/uploads/users/<?= htmlspecialchars($_SESSION['photo']); ?>" 
                    alt="User" class="user-photo" onclick="toggleUserDropdown()">

                <?php if ($notif_count > 0): ?>
                    <span class="notif-badge"><?= $notif_count ?></span>
                <?php endif; ?>
            </div>



            <div id="userDropdown" class="user-dropdown">
                <div class="user-info">
                    <img src="/reca/perpustakaan/uploads/users/<?= htmlspecialchars($_SESSION['photo'] ?? 'default.png'); ?>" 
                        class="user-photo-small">
                    <div>
                        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong><br>
                        <small><?= htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
                    </div>
                </div>
                <ul>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=borrowed">Daftar Pinjam</a></li>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=cart">Keranjang</a></li>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=setting">Akun</a></li>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=notification">Notifikasi</a></li>
                    <li><a href="/reca/perpustakaan/public/logout.php" class="logout-link">Keluar Akun</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>


<script src="assets/js/user/navbar.js"></script>
