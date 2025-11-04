<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/user/navbar.css">

<header class="navbar">
    <div class="navbar-left">
        <img src="/reca/perpustakaan/uploads/logo/logo.jpg" alt="Logo" class="logo">
        <h1><a href="/reca/perpustakaan/public/dashboard_user.php" class="home-link">Perpustakaan</a></h1>
    </div>

    <div class="navbar-center">
        <form method="GET" action="/reca/perpustakaan/public/search_books.php">
            <input type="text" name="q" placeholder="Cari buku, penulis, atau kategori...">
        </form>
    </div>

    <div class="navbar-right">
        <div class="user-menu">
            <img src="/reca/perpustakaan/uploads/users/<?= htmlspecialchars($_SESSION['photo'] ?? 'default.png'); ?>" 
                 alt="User" class="user-photo" onclick="toggleUserDropdown()">

            <div id="userDropdown" class="user-dropdown">
                <div class="user-info">
                    <img src="/reca/perpustakaan/uploads/users/<?= htmlspecialchars($_SESSION['photo'] ?? 'default.png'); ?>" 
                         alt="User" class="user-photo-small">
                    <div>
                        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong><br>
                        <small><?= htmlspecialchars($_SESSION['email'] ?? ''); ?></small>
                    </div>
                </div>
                <ul>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=cart">Daftar Pinjam</a></li>
                    <li><a href="/reca/perpustakaan/public/dashboard_user.php?page=setting">Akun</a></li>
                    <li><a href="#">Ulasan Buku</a></li>
                    <li><a href="/reca/perpustakaan/public/logout.php" class="logout-link">Keluar Akun</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdown");
    dropdown.classList.toggle("show");
}
window.addEventListener("click", (event) => {
    if (!event.target.closest(".user-menu")) {
        document.getElementById("userDropdown")?.classList.remove("show");
    }
});
</script>
