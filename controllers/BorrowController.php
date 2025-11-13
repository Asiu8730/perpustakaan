<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/CartController.php';

class BorrowController {

    public static function confirmBorrow($user_id = null) {
        global $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) return false;
        $user_id = $_SESSION['user_id'];

        // Ambil buku dari keranjang
        $cartQuery = $conn->prepare("SELECT book_id FROM carts WHERE user_id = ?");
        $cartQuery->bind_param("i", $user_id);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();

        if ($cartResult->num_rows === 0) return false;

        $today = date('Y-m-d');

        while ($row = $cartResult->fetch_assoc()) {
            $book_id = $row['book_id'];

            // Masukkan data ke borrows dengan status menunggu konfirmasi admin
            $stmt = $conn->prepare("
                INSERT INTO borrows (user_id, book_id, borrow_date, status)
                VALUES (?, ?, ?, 'menunggu_konfirmasi_admin')
            ");
            $stmt->bind_param("iis", $user_id, $book_id, $today);
            $stmt->execute();

            // Kirim notifikasi ke admin
            $adminQuery = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
            if ($adminQuery && $adminQuery->num_rows > 0) {
                $admin = $adminQuery->fetch_assoc();
                $admin_id = $admin['id'];
                $msg = "User ID $user_id meminta konfirmasi peminjaman untuk buku ID $book_id";
                $stmt3 = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
                $stmt3->bind_param("is", $admin_id, $msg);
                $stmt3->execute();
            }
        }

        // Kosongkan keranjang
        $clear = $conn->prepare("DELETE FROM carts WHERE user_id = ?");
        $clear->bind_param("i", $user_id);
        $clear->execute();

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

    // 🔹 Ubah status pinjaman
    public static function updateStatus($id, $status, $return_date = null) {
    global $conn;

    // ✅ Jika admin menyetujui peminjaman
    if ($status === 'dipinjam') {
        // Ambil ID buku
        $getBorrow = $conn->prepare("SELECT book_id FROM borrows WHERE id=?");
        $getBorrow->bind_param("i", $id);
        $getBorrow->execute();
        $res = $getBorrow->get_result()->fetch_assoc();
        $book_id = $res['book_id'] ?? null;

        // ⚠️ Gunakan tanggal dari admin, bukan otomatis 7 hari
        if (!$return_date) {
            // Kalau admin tidak isi tanggal, jangan ubah field return_date
            $stmt = $conn->prepare("UPDATE borrows SET status=? WHERE id=?");
            $stmt->bind_param("si", $status, $id);
        } else {
            $stmt = $conn->prepare("UPDATE borrows SET status=?, return_date=? WHERE id=?");
            $stmt->bind_param("ssi", $status, $return_date, $id);
        }
        $stmt->execute();

        // Update status buku jadi tidak tersedia
        if ($book_id) {
            $conn->query("UPDATE books SET status='Tidak Tersedia' WHERE id=$book_id");
        }
    }

    // ✅ Jika admin menyetujui pengembalian
    elseif ($status === 'dikembalikan') {
        // Ambil ID buku
        $getBook = $conn->prepare("SELECT book_id FROM borrows WHERE id=?");
        $getBook->bind_param("i", $id);
        $getBook->execute();
        $res = $getBook->get_result()->fetch_assoc();
        $book_id = $res['book_id'] ?? null;

        // Update status pinjaman
        $stmt = $conn->prepare("UPDATE borrows SET status=?, return_date=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $return_date, $id);
        $stmt->execute();

        // Kembalikan status buku
        if ($book_id) {
            $conn->query("UPDATE books SET status='Tersedia' WHERE id=$book_id");
        }
    }

    return true;
}


    public static function requestReturn($loan_id) {
        global $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) return false;

        $user_id = $_SESSION['user_id'];

        // Update status ke menunggu konfirmasi pengembalian
        $stmt = $conn->prepare("UPDATE borrows SET status='menunggu_konfirmasi_pengembalian' WHERE id=?");
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();

        // Notifikasi admin
        $adminQuery = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
        if ($adminQuery && $adminQuery->num_rows > 0) {
            $admin = $adminQuery->fetch_assoc();
            $admin_id = $admin['id'];
            $msg = "User ID $user_id mengajukan pengembalian buku pada peminjaman ID $loan_id";
            $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
            $stmt2->bind_param("is", $admin_id, $msg);
            $stmt2->execute();
        }

        return true;
    }


//     // 🧹 Hapus histori pinjaman yang sudah dikembalikan lebih dari 2 menit
// public static function autoDeleteReturnedBorrows() {
//     global $conn;
//     // Hapus pinjaman yang sudah dikembalikan lebih dari 2 menit
//     $sql = "DELETE FROM borrows WHERE status = 'dikembalikan' AND TIMESTAMPDIFF(MINUTE, updated_at, NOW()) > 2";
//     $conn->query($sql);
// }

}
?>
