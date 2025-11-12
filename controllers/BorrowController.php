<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/CartController.php';

class BorrowController {

    public static function confirmBorrow($user_id = null) {
        global $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) return false;
        $user_id = $_SESSION['user_id'];

        // 🟡 Ambil data buku dari tabel carts
        $cartQuery = $conn->prepare("SELECT book_id FROM carts WHERE user_id = ?");
        $cartQuery->bind_param("i", $user_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        if ($cartResult->num_rows === 0) {
            return false; // Tidak ada buku di keranjang
        }

        $today = date('Y-m-d');

        while ($row = $cartResult->fetch_assoc()) {
            $book_id = $row['book_id'];

            // 🟢 Masukkan ke tabel borrows
            $stmt = $conn->prepare("INSERT INTO borrows (user_id, book_id, borrow_date, status) VALUES (?, ?, ?, 'dipinjam')");
            $stmt->bind_param("iis", $user_id, $book_id, $today);
            $stmt->execute();

            // 🔵 Update status buku
            $stmt2 = $conn->prepare("UPDATE books SET status='Tidak Tersedia' WHERE id=?");
            $stmt2->bind_param("i", $book_id);
            $stmt2->execute();

            // 🔔 Buat notifikasi untuk admin
            $adminQuery = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
            if ($adminQuery && $adminQuery->num_rows > 0) {
                $admin = $adminQuery->fetch_assoc();
                $admin_id = $admin['id'];

                $msg = "User ID $user_id telah meminjam buku ID $book_id";
                $stmt3 = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
                $stmt3->bind_param("is", $admin_id, $msg);
                $stmt3->execute();
            }
        }

        // 🧹 Kosongkan keranjang user setelah konfirmasi
        $clearCart = $conn->prepare("DELETE FROM carts WHERE user_id = ?");
        $clearCart->bind_param("i", $user_id);
        $clearCart->execute();

        return true;
    }

    public static function getAllBorrows() {
        global $conn;
        $sql = "SELECT borrows.*, users.username, books.title 
                FROM borrows
                LEFT JOIN users ON borrows.user_id = users.id
                LEFT JOIN books ON borrows.book_id = books.id
                ORDER BY borrows.id DESC";
        return $conn->query($sql);
    }

    public static function updateStatus($id, $status, $return_date = null) {
        global $conn;
        $stmt = $conn->prepare("UPDATE borrows SET status=?, return_date=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $return_date, $id);
        return $stmt->execute();
    }

    public static function requestReturn($loan_id) {
        global $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) return false;

        $user_id = $_SESSION['user_id'];

        // Update status peminjaman jadi menunggu konfirmasi admin
        $stmt = $conn->prepare("UPDATE borrows SET status='menunggu_konfirmasi_pengembalian' WHERE id=?");
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();
        $stmt->close();

        // Cari admin
        $adminQuery = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
        if ($adminQuery && $adminQuery->num_rows > 0) {
            $admin = $adminQuery->fetch_assoc();
            $admin_id = $admin['id'];

            // Buat notifikasi untuk admin
            $msg = "User ID $user_id mengajukan pengembalian buku pada peminjaman ID $loan_id";
            $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
            $stmt2->bind_param("is", $admin_id, $msg);
            $stmt2->execute();
            $stmt2->close();
        }

        return true;
    }
}
?>
