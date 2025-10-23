<?php
require_once __DIR__ . '/../config/database.php';

class BookController {

    // Ambil semua buku (dengan nama kategori) -> mengembalikan array atau mysqli_result tergantung kebutuhan
    // Kita buat mengembalikan array (fetch_all) supaya mudah dipakai di view
    public static function getAllBooks($sort = '') {
        global $conn;
        $sql = "SELECT books.*, categories.name AS category_name 
                FROM books 
                LEFT JOIN categories ON books.category_id = categories.id ";

        // Sorting
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
                $sql .= "ORDER BY books.id ASC";
                break;
            default:
                $sql .= "ORDER BY books.id DESC";
                break;
        }

        $res = $conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil semua kategori (mengembalikan array)
    public static function getCategories() {
        global $conn;
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $res = $conn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

      // Tambah buku (dengan kolom status)
    public static function addBook($title, $author, $publisher, $category_id, $publish_date, $stock, $cover = null, $status = 'Tersedia') {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, category_id, publish_date, stock, cover, status)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare addBook failed: " . $conn->error);
        }

        // urutan: sss is i s s (string, string, string, int, string, int, string, string)
        $stmt->bind_param("sssisiss", $title, $author, $publisher, $category_id, $publish_date, $stock, $cover, $status);
        $ok = $stmt->execute();

        if (!$ok) {
            throw new Exception("Execute addBook failed: " . $stmt->error);
        }

        $stmt->close();
        return $ok;
    }

    // Update buku (dengan kolom status)
    public static function updateBook($id, $title, $author, $publisher, $category_id, $publish_date, $stock, $cover = null, $status = 'Tersedia') {
        global $conn;

        if ($cover) {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, stock=?, cover=?, status=? 
                WHERE id=?");
            if (!$stmt) {
                throw new Exception("Prepare updateBook failed: " . $conn->error);
            }
            $stmt->bind_param("sssisissi", $title, $author, $publisher, $category_id, $publish_date, $stock, $cover, $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE books 
                SET title=?, author=?, publisher=?, category_id=?, publish_date=?, stock=?, status=? 
                WHERE id=?");
            if (!$stmt) {
                throw new Exception("Prepare updateBook failed: " . $conn->error);
            }
            $stmt->bind_param("sssisisi", $title, $author, $publisher, $category_id, $publish_date, $stock, $status, $id);
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

    // Search buku (judul/penulis/penerbit/kategori) -> mengembalikan array
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
        $res = $stmt->get_result();
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil buku berdasarkan kategori -> mengembalikan array
    public static function getBooksByCategory($category_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ? ORDER BY id DESC");
        if (!$stmt) {
            throw new Exception("Prepare getBooksByCategory failed: " . $conn->error);
        }
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
