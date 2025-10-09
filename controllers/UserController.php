<?php
require_once __DIR__ . '/../config/database.php';

class UserController {
    public static function getAllUsers() {
        global $conn;
        $sql = "SELECT * FROM users";
        return $conn->query($sql);
    }

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
}
