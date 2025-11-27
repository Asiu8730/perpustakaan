<?php
require_once __DIR__ . '/../../controllers/NotificationController.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "Silakan login untuk melihat notifikasi.";
    exit();
}

// 🔔 tandai semua sudah dibaca saat halaman dibuka (opsional)
NotificationController::markAllRead($user_id);

// Pagination
$perPage = 10;
$page = max(1, intval($_GET['p'] ?? 1));
$offset = ($page - 1) * $perPage;

$total = NotificationController::countByUser($user_id);
$notifications = NotificationController::getByUserPaginated($user_id, $perPage, $offset);
$totalPages = max(1, (int) ceil($total / $perPage));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="assets/css/user/notification.css">
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>

<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="notif-container">
    <h2>Notifikasi</h2>

    <?php if (empty($notifications)): ?>
        <p>Tidak ada notifikasi.</p>
    <?php else: ?>
        <?php foreach ($notifications as $n): ?>
            <div class="notif-item">
                <div><?= htmlspecialchars($n['message']); ?></div>
                <div class="notif-time"><?= htmlspecialchars($n['created_at']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <div class="pagination" aria-label="Pagination">
            <?php if ($page > 1): ?>
                <a href="/reca/perpustakaan/public/dashboard_user.php?page=notification&p=<?= $page-1 ?>">‹ Prev</a>
            <?php else: ?>
                <span style="opacity:.5">‹ Prev</span>
            <?php endif; ?>

            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="/reca/perpustakaan/public/dashboard_user.php?page=notification&p=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="/reca/perpustakaan/public/dashboard_user.php?page=notification&p=<?= $page+1 ?>">Next ›</a>
            <?php else: ?>
                <span style="opacity:.5">Next ›</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
