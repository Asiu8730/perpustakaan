<?php
require_once __DIR__ . '/../config/database.php';

class CategoriesController {
    public static function getAllCategories() {
        global $conn;
        $query = "SELECT * FROM categories ORDER BY id DESC";
        return $conn->query($query);
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
}
    