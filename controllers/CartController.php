<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

class CartController {

    public static function addToCart($book_id) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!in_array($book_id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $book_id;
        }

        if (in_array($book_id, $_SESSION['cart'])) {
            return ['status' => 'exists', 'message' => 'Buku sudah ada di keranjang'];
        }

        // ✅ Jika belum ada, tambahkan
        $_SESSION['cart'][] = $book_id;
        return ['status' => 'success', 'message' => 'Buku berhasil ditambahkan ke keranjang'];
    }

    public static function removeFromCart($book_id) {
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($id) => $id != $book_id);
        }
        $key = array_search($book_id, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reset index
            return true;
        }
        return false;
    }

    public static function clearCart() {
        unset($_SESSION['cart']);
    }

    public static function getCartItems() {
        global $conn;
        if (empty($_SESSION['cart'])) return [];
        $ids = implode(",", array_map('intval', $_SESSION['cart']));
        $res = $conn->query("SELECT * FROM books WHERE id IN ($ids)");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        return $_SESSION['cart'] ?? [];
    }

    public static function confirmBorrow($book_id) {
    global $conn;
    session_start();

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User belum login");
    }

    $user_id = $_SESSION['user_id'];
    $msg = "User ID $user_id meminjam buku ID $book_id";

    // Simpan ke tabel peminjaman (misal loans)
    $conn->query("INSERT INTO loans (book_id, user_id, status, borrow_date) VALUES ($book_id, $user_id, 'Menunggu Konfirmasi', NOW())");

    // Simpan notifikasi ke admin
    $conn->query("INSERT INTO notifications (user_id, message, created_at) VALUES ($user_id, '$msg', NOW())");
    }

}
?>
