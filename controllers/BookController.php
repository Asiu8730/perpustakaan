<?php
require_once __DIR__ . '/../config/database.php';

class BookController {
    public static function getAllBooks() {
        global $conn;
        $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
        return $result;
    }

    public static function addBook($title, $author, $publisher, $year, $stock) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $author, $publisher, $year, $stock);
        return $stmt->execute();
    }

    public static function getBookById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function updateBook($id, $title, $author, $publisher, $year, $stock) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, publisher=?, year=?, stock=? WHERE id=?");
        $stmt->bind_param("sssiii", $title, $author, $publisher, $year, $stock, $id);
        return $stmt->execute();
    }

    public static function deleteBook($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
