<!DOCTYPE html>
<html>
<head>
    <title>Login - Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        
    </style>
</head>
<body>

    <a href="dashboard_guest.php" class="back-btn">← Kembali</a>

    <div class="form-container">
        <h2>Login</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p><?= $message ?></p>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>

</body>
</html>
