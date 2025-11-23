<?php
require_once __DIR__ . '/../config/database.php';

class CategoriesController {
    public static function getAllCategories() {
        global $conn;
        $query = "SELECT * FROM categories ORDER BY id DESC";
        return $conn->query($query);
        $result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function addCategory($name) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public static function updateCategory($id, $name) {
        global $conn;
        $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public static function deleteCategory($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function getCategoryById($id) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    public static function getPaginatedCategories($limit, $offset) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function countCategories() {
        global $conn;
        return $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
    }

    public static function searchCategories($keyword, $limit, $offset) {
    global $conn;
    $key = "%".$keyword."%";

    $stmt = $conn->prepare("
        SELECT * FROM categories 
        WHERE name LIKE ?
        ORDER BY id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("sii", $key, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public static function countSearchCategories($keyword) {
    global $conn;
    $key = "%".$keyword."%";
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM categories WHERE name LIKE ?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}


}
    