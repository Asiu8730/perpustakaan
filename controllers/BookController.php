<?php
require_once __DIR__ . '/../config/database.php';

class BookController {

    // Ambil semua buku (dengan nama kategori)
    public static function getAllBooks($sort = '') {
        global $conn;
        $sql = "SELECT books.*, categories.name AS category_name 
                FROM books 
                LEFT JOIN categories ON books.category_id = categories.id ";

        switch ($sort) {
            case 'title_asc':  $sql .= "ORDER BY books.title ASC"; break;
            case 'title_desc': $sql .= "ORDERBY books.title DESC"; break;
            case 'newest':     $sql .= "ORDER BY books.id DESC"; break;
            case 'oldest':     $sql .= "ORDER BY books.id ASC"; break;
            default:           $sql .= "ORDER BY books.id DESC"; break;
        }

        $res = $conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil kategori
    public static function getCategories() {
        global $conn;
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Tambah buku
    public static function addBook($title, $author, $publisher, $category_id, $publish_date, $description, $cover = null, $status = 'Tersedia') {
    global $conn;

    // kalau tidak upload cover, pakai default
    if (empty($cover)) {
        $cover = 'no_cover.png';
    }

    $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, category_id, publish_date, description, cover, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare addBook failed: " . $conn->error);
    }

    // s=string, i=integer
    $stmt->bind_param("sssissss", $title, $author, $publisher, $category_id, $publish_date, $description, $cover, $status);

    $ok = $stmt->execute();

    if (!$ok) {
        throw new Exception("Execute addBook failed: " . $stmt->error);
    }

    $stmt->close();
    return $ok;
}


    // Update buku
    public static function updateBook($id, $title, $author, $publisher, $category_id, $publish_date, $description, $cover = null, $status = 'Tersedia')
 {
        global $conn;

        if ($cover) {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, description=?, cover=?, status=? 
                WHERE id=?");
            $stmt->bind_param("sssissssi", $title, $author, $publisher, $category_id, $publish_date, $description, $cover, $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, description=?, status=? 
                WHERE id=?");
            $stmt->bind_param("sssisssi", $title, $author, $publisher, $category_id, $publish_date, $description, $status, $id);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Hapus buku
    public static function deleteBook($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Search buku
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

    // Ambil buku per kategori
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
}
