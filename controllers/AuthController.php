<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

class AuthController {

    // === LOGIN ===
    public static function login($username, $password) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {

                // Simpan semua data ke session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email']    = $user['email'] ?? '';
                $_SESSION['photo']    = $user['photo'] ?? 'default.png';

                // Arahkan berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: ../public/dashboard_admin.php");
                } else {
                    header("Location: ../public/dashboard_user.php");
                }
                exit();
            }
        }
        return "Username atau password salah!";
    }

    // === REGISTER ===
    public static function register($username, $email, $password) {
        global $conn;

        // Cek apakah username/email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Username atau Email sudah terdaftar!";
        }

        // Insert user baru
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, photo) VALUES (?, ?, ?, 'user', 'default.png')");
        $stmt->bind_param("sss", $username, $email, $hash);

        return $stmt->execute() ? true : "Gagal registrasi: " . $stmt->error;
    }

    // === LOGOUT ===
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
