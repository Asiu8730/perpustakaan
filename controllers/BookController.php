<?php
require_once __DIR__ . '/../config/database.php';

class BookController {

    public static function getAllBooks($sort = '') {
        global $conn;
        $sql = "SELECT books.*, categories.name AS category_name 
                FROM books 
                LEFT JOIN categories ON books.category_id = categories.id ";

        switch ($sort) {
            case 'title_asc':  $sql .= "ORDER BY books.title ASC"; break;
            case 'title_desc': $sql .= "ORDER BY books.title DESC"; break;
            case 'newest':     $sql .= "ORDER BY books.id DESC"; break;
            case 'oldest':     $sql .= "ORDER BY books.id ASC"; break;
            default:           $sql .= "ORDER BY books.id DESC"; break;
        }

        $res = $conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function getCategories() {
        global $conn;
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function addBook($title, $author, $publisher, $category_id, $publish_date, $description, $cover = null, $status = 'Tersedia', $stock = 1) {
        global $conn;
        if (empty($cover)) $cover = 'no_cover.png';

        $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, category_id, publish_date, description, cover, status, stock)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) throw new Exception("Prepare addBook failed: " . $conn->error);
        $stmt->bind_param("sssissssi", $title, $author, $publisher, $category_id, $publish_date, $description, $cover, $status, $stock);
        $ok = $stmt->execute();
        if (!$ok) throw new Exception("Execute addBook failed: " . $stmt->error);
        $stmt->close();
        return $ok;
    }

    public static function updateBook($id, $title, $author, $publisher, $category_id, $publish_date, $description, $cover = null, $status = 'Tersedia', $stock = 1) {
        global $conn;

        if ($cover) {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, description=?, cover=?, status=?, stock=? 
                WHERE id=?");
            $stmt->bind_param("sssissssii", $title, $author, $publisher, $category_id, $publish_date, $description, $cover, $status, $stock, $id);
        } else {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, description=?, status=?, stock=? 
                WHERE id=?");
            $stmt->bind_param("sssisssii", $title, $author, $publisher, $category_id, $publish_date, $description, $status, $stock, $id);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function deleteBook($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function searchBooks($keyword) {
        global $conn;
        $like = "%" . $conn->real_escape_string($keyword) . "%";
        $stmt = $conn->prepare("SELECT books.*, categories.name AS category_name
                                FROM books
                                LEFT JOIN categories ON books.category_id = categories.id
                                WHERE books.title LIKE ? OR books.author LIKE ? OR books.publisher LIKE ? OR categories.name LIKE ?
                                ORDER BY books.id DESC");
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getBooksByCategory($category_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function setStatus($id, $status) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // Kurangi stock (dipanggil saat admin set status jadi dipinjam)
    public static function decrementStock($id, $amount = 1) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET stock = GREATEST(stock - ?, 0), status = CASE WHEN stock - ? <= 0 THEN 'Tidak Tersedia' ELSE status END WHERE id = ?");
        $stmt->bind_param("iii", $amount, $amount, $id);
        return $stmt->execute();
    }

    // Tambah stock (saat dikembalikan)
    public static function incrementStock($id, $amount = 1) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET stock = stock + ?, status = 'Tersedia' WHERE id = ?");
        $stmt->bind_param("ii", $amount, $id);
        return $stmt->execute();
    }
}
