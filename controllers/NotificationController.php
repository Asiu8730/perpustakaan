<?php
require_once __DIR__ . '/../config/database.php';

class NotificationController {

    // Ambil semua notifikasi untuk admin
    public static function getAllNotifications() {
        global $conn;
        $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Tandai notifikasi sudah dibaca
    public static function markAsRead($id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // Hapus notifikasi (opsional)
    public static function deleteNotification($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
