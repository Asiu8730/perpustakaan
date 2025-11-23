<?php
require_once __DIR__ . '/../config/database.php';

class UserController {

    public static function addUser($username, $email, $password, $role) {
        global $conn;
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);
        return $stmt->execute();
    }

    public static function updateUser($id, $username, $email, $role) {
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        return $stmt->execute();
    }

    public static function deleteUser($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function getPaginatedUsers($limit, $offset, $keyword = '', $sort = '') {
        global $conn;

        $keyword = "%$keyword%";

        $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ? ";

        switch ($sort) {
            case 'az': $sql .= "ORDER BY username ASC "; break;
            case 'za': $sql .= "ORDER BY username DESC "; break;
            case 'newest': $sql .= "ORDER BY id DESC "; break;
            case 'oldest': $sql .= "ORDER BY id ASC "; break;
            default: $sql .= "ORDER BY id DESC "; break;
        }

        $sql .= "LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $keyword, $keyword, $limit, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function countUsers($keyword = '') {
        global $conn;

        $keyword = "%$keyword%";

        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE username LIKE ? OR email LIKE ?");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
