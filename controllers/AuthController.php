<?php
session_start();
require_once __DIR__ . '/../config/database.php';

class AuthController {
    public static function login($username, $password) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['username']= $user['username'];

                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            }
        }
        return "Username atau password salah!";
    }

    public static function register($username, $email, $password) {
    global $conn;

    // cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username atau Email sudah terdaftar!";
    }

    // kalau belum ada → lanjut insert
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $email, $hash);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Gagal registrasi: " . $stmt->error;
    }
}

    public static function logout() {
    session_start();          // pastikan session aktif
    session_unset();          // hapus semua session
    session_destroy();        // hancurkan session
    header("Location: login.php");
    exit();
}

}
