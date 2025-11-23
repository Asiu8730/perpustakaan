<link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/guest/navbar.css">

<header class="navbar">
    <div class="navbar-left">
        <img src="/reca/perpustakaan/uploads/logo/logo.jpg" alt="Logo" class="logo">
        <h1><a href="/reca/perpustakaan/public/dashboard_guest.php" class="home-link">Perpustakaan</a></h1>
    </div>

    <div class="navbar-center">
        <form method="GET" action="/reca/perpustakaan/public/dashboard_guest.php">
            <input type="hidden" name="page" value="search">
            <input id="searchInput" type="text" name="q" placeholder="Cari buku, penulis, atau kategori...">
        </form>

        <div id="searchResults" class="search-results"></div>
    </div>

    <div class="navbar-right">
        <a href="/reca/perpustakaan/public/login.php" class="btn-login">Login</a>
        <a href="/reca/perpustakaan/public/register.php" class="btn-register">Daftar</a>
    </div>
</header>
