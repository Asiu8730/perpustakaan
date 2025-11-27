<?php
require_once __DIR__ . '/../config/database.php';

class NotificationController {

    // Simpan notifikasi baru
    public static function addNotification($user_id, $message) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        return $stmt->execute();
    }

    // Hitung notifikasi yang belum dibaca
    public static function countUnread($user_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS jml 
        FROM notifications 
        WHERE user_id = ? AND is_read = 0
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['jml'];
}

    // Tandai notifikasi sebagai "sudah dibaca"
    public static function markAllRead($user_id) {
        global $conn;

        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    // Hitung total notifikasi untuk user
    public static function countByUser($user_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM notifications WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return intval($res['total'] ?? 0);
    }

    // Ambil notifikasi user dengan pagination
    public static function getByUserPaginated($user_id, $limit, $offset) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("iii", $user_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
