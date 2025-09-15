<!DOCTYPE html>
<html>
<head>
    <title>Register - Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/login.css">

</head>
<body>
    <div class="form-container">
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p style="color:red;"><?= $message ?></p>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
