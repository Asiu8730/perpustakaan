<?php
require_once __DIR__ . '/../../controllers/NotificationController.php';

if (!isset($_SESSION)) session_start();
$user_id = $_SESSION['user_id'];

$notifications = NotificationController::getUserNotifications($user_id);

// Tandai sebagai sudah dibaca
NotificationController::markAllRead($user_id);
?>

<link rel="stylesheet" href="assets/css/user/notification.css">
<link rel="stylesheet" href="assets/css/global.css">
<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="notif-container">
    <h2>Notifikasi</h2>

    <?php if ($notifications->num_rows === 0): ?>
        <p class="no-notif">Tidak ada notifikasi</p>
    <?php endif; ?>

    <?php while ($row = $notifications->fetch_assoc()): ?>
        <div class="notif-item <?= $row['is_read'] ? 'read' : 'unread' ?>">
            <p><?= htmlspecialchars($row['message']); ?></p>
            <span class="date"><?= $row['created_at']; ?></span>
        </div>
    <?php endwhile; ?>
</div>
