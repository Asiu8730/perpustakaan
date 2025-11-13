<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

class CartController {

    public static function addToCart($book_id) {
    global $conn;

    if (!isset($_SESSION['user_id'])) {
        return ['status' => 'error', 'message' => 'User belum login'];
    }

    $user_id = $_SESSION['user_id'];

    // 🚫 Cek apakah buku sedang menunggu konfirmasi dari admin
    $checkBorrow = $conn->prepare("
        SELECT id FROM borrows 
        WHERE user_id = ? 
        AND book_id = ? 
        AND (status = 'menunggu_konfirmasi_admin' OR status = 'dipinjam' OR status = 'menunggu_konfirmasi_pengembalian')
    ");
    // 🚫 Cegah menambahkan buku yang sedang dalam proses pinjam
$checkBorrow = $conn->prepare("
    SELECT id FROM borrows 
    WHERE user_id = ? AND book_id = ? 
    AND (status = 'menunggu_konfirmasi_admin' OR status = 'dipinjam' OR status = 'menunggu_konfirmasi_pengembalian')
");
$checkBorrow->bind_param("ii", $user_id, $book_id);
$checkBorrow->execute();
$borrowCheck = $checkBorrow->get_result();

if ($borrowCheck->num_rows > 0) {
    return ['status' => 'error', 'message' => 'Buku ini sedang dalam proses peminjaman atau menunggu konfirmasi admin.'];
}


    // 🟡 Cek apakah sudah ada di keranjang
    $stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return ['status' => 'exists', 'message' => 'Buku sudah ada di keranjang'];
    }

    // 🟢 Tambahkan ke tabel carts
    $stmt = $conn->prepare("INSERT INTO carts (user_id, book_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $book_id);

    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Buku berhasil ditambahkan ke keranjang'];
    } else {
        return ['status' => 'error', 'message' => 'Gagal menambahkan buku ke keranjang'];
    }
}


    public static function removeFromCart($book_id) {
        global $conn;
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
    }

    public static function getCartItems() {
        global $conn;
        if (!isset($_SESSION['user_id'])) return [];

        $user_id = $_SESSION['user_id'];
        $sql = "SELECT books.* 
                FROM carts 
                JOIN books ON carts.book_id = books.id 
                WHERE carts.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function clearCart() {
        global $conn;
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
}
?>
