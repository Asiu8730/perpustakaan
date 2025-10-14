<?php
require_once __DIR__ . '/../config/database.php';

class BookController {
    // Ambil semua buku (dengan nama kategori)
    public static function getAllBooks($sort = '') {
        global $conn;
        $sql = "SELECT books.*, categories.name AS category_name 
            FROM books 
            LEFT JOIN categories ON books.category_id = categories.id ";

    // Gunakan switch untuk sorting
    switch ($sort) {
        case 'title_asc':
            $sql .= "ORDER BY books.title ASC";
            break;
        case 'title_desc':
            $sql .= "ORDER BY books.title DESC";
            break;
        case 'newest':
            $sql .= "ORDER BY books.id DESC";
            break;
        case 'oldest':
            $sql .= "ORDERBY books.id ASC"; // <-- salah ketik disini? harus pakai spasi.
            break;
        default:
            $sql .= "ORDER BY books.id DESC";
            break;
    }

        return $conn->query($sql);
    }

    // Ambil semua kategori (result mysqli)
    public static function getCategories() {
        global $conn;
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        return $conn->query($sql);
    }

    // Tambah buku (prepared statement)
    public static function addBook($title, $author, $publisher, $category_id, $publish_date, $stock, $cover = null ) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, category_id, publish_date, stock, cover)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare addBook failed: " . $conn->error);
        }
        // types: s (title), s (author), s (publisher), i (category_id), s (publish_date), i (stock), s (cover)
        $stmt->bind_param("sssisis", $title, $author, $publisher, $category_id, $publish_date, $stock, $cover);
        $ok = $stmt->execute();
        if (!$ok) {
            throw new Exception("Execute addBook failed: " . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }

    // Update buku
    public static function updateBook($id, $title, $author, $publisher, $category_id, $publish_date, $stock, $cover = null) {
    global $conn;

    if ($cover) {
        // Jika user upload cover baru
        $stmt = $conn->prepare("UPDATE books 
            SET title=?, author=?, publisher=?, category_id=?, publish_date=?, stock=?, cover=? 
            WHERE id=?");
        if (!$stmt) {
            throw new Exception("Prepare updateBook failed: " . $conn->error);
        }
        $stmt->bind_param("sssisisi", $title, $author, $publisher, $category_id, $publish_date, $stock, $cover, $id);
    } else {
        // Jika tidak ada cover baru
        $stmt = $conn->prepare("UPDATE books 
            SET title=?, author=?, publisher=?, category_id=?, publish_date=?, stock=? 
            WHERE id=?");
        if (!$stmt) {
            throw new Exception("Prepare updateBook failed: " . $conn->error);
        }
        $stmt->bind_param("sssisii", $title, $author, $publisher, $category_id, $publish_date, $stock, $id);
    }

    $ok = $stmt->execute();
    if (!$ok) {
        throw new Exception("Execute updateBook failed: " . $stmt->error);
    }

    $stmt->close();
    return $ok;
}


    // Hapus buku
    public static function deleteBook($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        if (!$stmt) {
            throw new Exception("Prepare deleteBook failed: " . $conn->error);
        }
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        if (!$ok) {
            throw new Exception("Execute deleteBook failed: " . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }

    // Search buku (judul/penulis/penerbit/kategori)
    public static function searchBooks($keyword) {
        global $conn;
        $like = "%" . $conn->real_escape_string($keyword) . "%";
        $stmt = $conn->prepare("SELECT books.*, categories.name AS category_name
                                FROM books
                                LEFT JOIN categories ON books.category_id = categories.id
                                WHERE books.title LIKE ? OR books.author LIKE ? OR books.publisher LIKE ? OR categories.name LIKE ?
                                ORDER BY books.id DESC");
        if (!$stmt) {
            throw new Exception("Prepare searchBooks failed: " . $conn->error);
        }
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        return $stmt->get_result();
    }
}
