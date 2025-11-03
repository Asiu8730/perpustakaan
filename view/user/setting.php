<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Ambil data user
$user = UserController::getUserById($user_id);
if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

// Inisialisasi variabel (dari DB sebagai default)
$message = "";
$errors = [];

$username   = $user['username'] ?? '';
$email      = $user['email'] ?? '';
$photo_name = $user['photo'] ?? 'default.png';

// Folder upload foto
$targetDir = __DIR__ . '/../../uploads/users/';
if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = trim($_POST['username'] ?? $username);
    $email_input = trim($_POST['email'] ?? $email);

    // Validasi input
    if ($username_input === '') $errors[] = "Username tidak boleh kosong.";
    if ($email_input === '' || !filter_var($email_input, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";

    // Password baru (opsional)
    $password_hashed = null;
    if (!empty($_POST['password'])) {
        $plain = $_POST['password'];
        if (strlen($plain) < 6) {
            $errors[] = "Password minimal 6 karakter.";
        } else {
            $password_hashed = password_hash($plain, PASSWORD_BCRYPT);
        }
    }

    // Upload foto profil baru (opsional)
    $new_photo_filename = $photo_name;
    if (!empty($_FILES['photo']['name'])) {
        $orig = basename($_FILES['photo']['name']);
        $ext = pathinfo($orig, PATHINFO_EXTENSION);
        $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
        $new_photo_filename = time() . '_' . $safeBase . ($ext ? '.' . $ext : '');
        $targetPath = $targetDir . $new_photo_filename;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $errors[] = "Gagal mengupload foto profil.";
        } else {
            // hapus foto lama (kecuali default)
            if (!empty($photo_name) && $photo_name !== 'default.png' && file_exists($targetDir . $photo_name)) {
                @unlink($targetDir . $photo_name);
            }
        }
    }

    // Update data jika tidak ada error
    if (empty($errors)) {
        $ok = UserController::updateUserProfile(
            $user_id,
            $username_input,
            $email_input,
            $password_hashed,
            $new_photo_filename
        );

        if ($ok) {
            // Update data session
            $_SESSION['username'] = $username_input;
            $_SESSION['email'] = $email_input;
            $_SESSION['photo'] = $new_photo_filename;

            $message = "Profil berhasil diperbarui!";
            $username = $username_input;
            $email = $email_input;
            $photo_name = $new_photo_filename;
        } else {
            $errors[] = "Gagal memperbarui profil (database error).";
        }
    }
}

// Header template
include __DIR__ . '/../templates/header.php';
?>

<link rel="stylesheet" href="/reca/perpustakaan/public/assets/css/user/setting.css">

<div class="settings-page">
    <div class="setting-container">
        <h2>Pengaturan Akun</h2>

        <?php if (!empty($message)): ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-message" style="color:red;margin-bottom:10px;">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="setting-form">
            <div class="profile-section">
                <img src="/reca/perpustakaan/uploads/users/<?= htmlspecialchars($photo_name ?? 'default.png'); ?>"
                     alt="Foto Profil" class="profile-photo">
                <input type="file" name="photo" accept="image/*">
            </div>

            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

            <label>Password Baru (opsional):</label>
            <input type="password" name="password" placeholder="Isi jika ingin mengganti password">

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>
</div>
