<?php
require_once __DIR__ . '/../../controllers/NotificationController.php';
$notifications = NotificationController::getAllNotifications();
?>

<link rel="stylesheet" href="assets/css/admin/books.css">

<div class="books-container">
    <h1>Notifikasi Admin 🔔</h1>
    <table class="books-table">
        <tr>
            <th>No</th>
            <th>Pesan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php if (!empty($notifications)): ?>
            <?php $no = 1; foreach ($notifications as $notif): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($notif['message']); ?></td>
                    <td><?= htmlspecialchars($notif['created_at']); ?></td>
                    <td><?= $notif['is_read'] ? 'Dibaca' : 'Belum Dibaca'; ?></td>
                    <td>
                        <?php if (!$notif['is_read']): ?>
                            <a href="../public/dashboard_admin.php?page=notifications&read=<?= $notif['id']; ?>" class="action-btn update-btn">Tandai Dibaca</a>
                        <?php endif; ?>
                        <a href="../public/dashboard_admin.php?page=notifications&delete=<?= $notif['id']; ?>" class="action-btn delete-btn">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">Belum ada notifikasi</td></tr>
        <?php endif; ?>
    </table>
</div>

<?php
// Tindakan setelah klik
if (isset($_GET['read'])) {
    NotificationController::markAsRead($_GET['read']);
    header("Location: ../public/dashboard_admin.php?page=notifications");
    exit;
}

if (isset($_GET['delete'])) {
    NotificationController::deleteNotification($_GET['delete']);
    header("Location: ../public/dashboard_admin.php?page=notifications");
    exit;
}
?>
