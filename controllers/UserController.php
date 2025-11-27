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

    public static function updateUser($id, $username, $email, $role, $password = null) {
    global $conn;

    // Jika password tidak diisi → jangan update password
    if ($password === null || $password === "") {
        $stmt = $conn->prepare("
            UPDATE users SET username=?, email=?, role=? WHERE id=?
        ");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        return $stmt->execute();
    }

    // Jika password diisi → hash & update
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("
        UPDATE users SET username=?, email=?, role=?, password=? WHERE id=?
    ");
    $stmt->bind_param("ssssi", $username, $email, $role, $hashed, $id);
    return $stmt->execute();
}

    public static function deleteUser($id) {
        global $conn;
        try {
            // Start a transaction so we can delete related rows first (to satisfy FK constraints)
            $conn->begin_transaction();

            // Delete dependent borrows records first to avoid foreign key constraint errors
            $stmt = $conn->prepare("DELETE FROM borrows WHERE user_id = ?");
            if ($stmt === false) {
                $conn->rollback();
                return false;
            }
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                $stmt->close();
                $conn->rollback();
                return false;
            }
            $stmt->close();

            // Now delete the user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt === false) {
                $conn->rollback();
                return false;
            }
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                $stmt->close();
                $conn->rollback();
                return false;
            }
            $stmt->close();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on any error and return false instead of letting an exception bubble up
            $conn->rollback();
            return false;
        }
    }

public static function getUserById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

    public static function updateUserProfile($id, $username, $email, $password = null, $photo = null) {
        global $conn;

        $fields = "username=?, email=?";
        $types = "ssi";
        $params = [$username, $email, $id];

        if ($password !== null) {
            $fields .= ", password=?";
            $types = "sssi";
            $params = [$username, $email, password_hash($password, PASSWORD_BCRYPT), $id];
        }

        if ($photo !== null) {
            $fields .= ", photo=?";
            $types = "sssi";
            $params = [$username, $email, $photo, $id];
        }

        $sql = "UPDATE users SET $fields WHERE id=?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param($types, ...$params);

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
