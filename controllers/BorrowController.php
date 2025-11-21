<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/CartController.php';
require_once __DIR__ . '/NotificationController.php';

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

    // Ambil borrow record dulu
    $getBorrow = $conn->prepare("SELECT book_id, return_date FROM borrows WHERE id=?");
    $getBorrow->bind_param("i", $id);
    $getBorrow->execute();
    $res = $getBorrow->get_result()->fetch_assoc();
    $book_id = $res['book_id'] ?? null;
    $old_return_date = $res['return_date'];

    if ($status === 'dipinjam') {
        // bila admin memilih return_date maka set, else biarkan
        if ($return_date) {
            $stmt = $conn->prepare("UPDATE borrows SET status=?, return_date=? WHERE id=?");
            $stmt->bind_param("ssi", $status, $return_date, $id);
        } else {
            $stmt = $conn->prepare("UPDATE borrows SET status=? WHERE id=?");
            $stmt->bind_param("si", $status, $id);
        }
        $stmt->execute();

        // kurangi stock
        if ($book_id) {
            // ambil stock sekarang
            $s = $conn->query("SELECT stock FROM books WHERE id=$book_id")->fetch_assoc();
            $stock = intval($s['stock'] ?? 0);
            $stock = max(0, $stock - 1);
            // update stock dan status buku
            $newStatus = ($stock <= 0) ? 'Tidak Tersedia' : 'Dipinjam';
            $upd = $conn->prepare("UPDATE books SET stock=?, status=? WHERE id=?");
            $upd->bind_param("isi", $stock, $newStatus, $book_id);
            $upd->execute();
        }
    }
    elseif ($status === 'dikembalikan') {
        // keep return_date yang lama supaya tidak hilang
        $useDate = $old_return_date ?: $return_date;
        $stmt = $conn->prepare("UPDATE borrows SET status=?, return_date=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $useDate, $id);
        $stmt->execute();

        if ($book_id) {
            // tambah stock
            $s = $conn->query("SELECT stock FROM books WHERE id=$book_id")->fetch_assoc();
            $stock = intval($s['stock'] ?? 0);
            $stock = $stock + 1;
            $newStatus = ($stock > 0) ? 'Tersedia' : 'Tidak Tersedia';
            $upd = $conn->prepare("UPDATE books SET stock=?, status=? WHERE id=?");
            $upd->bind_param("isi", $stock, $newStatus, $book_id);
            $upd->execute();
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

    public static function checkUserBookStatus($user_id, $book_id) {
        global $conn;

        $stmt = $conn->prepare("
            SELECT status 
            FROM borrows 
            WHERE user_id = ? AND book_id = ?
            AND status IN (
                'menunggu_konfirmasi_admin',
                'dipinjam',
                'menunggu_konfirmasi_pengembalian'
            )
            ORDER BY id DESC LIMIT 1
        ");

        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['status'] ?? null; // null = tidak ada pinjaman aktif
    }

    public static function checkDueDatesForUser($user_id) {
    global $conn;

    // Ambil semua peminjaman yang masih berjalan
    $stmt = $conn->prepare("
        SELECT borrows.*, books.title 
        FROM borrows
        LEFT JOIN books ON borrows.book_id = books.id
        WHERE borrows.user_id = ?
        AND borrows.status = 'dipinjam'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $due_date = $row['return_date'];
        $book_title = $row['title'];
        $borrow_id = $row['id'];

        if (!$due_date) continue;

        $today = date('Y-m-d');
        $days_left = (strtotime($due_date) - strtotime($today)) / 86400;

        $message = null;

        if ($days_left == 1) {
            $message = "Besok adalah batas pengembalian buku: $book_title";
        } 
        elseif ($days_left == 0) {
            $message = "Hari ini adalah batas pengembalian buku: $book_title";
        }
        elseif ($days_left < 0) {
            $late = abs($days_left);
            $message = "Anda terlambat $late hari mengembalikan buku: $book_title";
        }

        if ($message) {
            // Cek agar notif tidak duplikat
            $check = $conn->prepare("
                SELECT id FROM notifications 
                WHERE user_id = ? 
                AND message = ?
                LIMIT 1
            ");
            $check->bind_param("is", $user_id, $message);
            $check->execute();
            $exists = $check->get_result()->num_rows > 0;

            if (!$exists) {
                $ins = $conn->prepare("
                    INSERT INTO notifications (user_id, message, created_at) 
                    VALUES (?, ?, NOW())
                ");
                $ins->bind_param("is", $user_id, $message);
                $ins->execute();
            }
        }
    }
}

    public static function notifyDeadline($user_id = null) {
    global $conn;

    if (!isset($_SESSION)) session_start();

    // ❗ Cegah duplikasi — hanya 1x per session
    if (!empty($_SESSION['deadline_notified'])) {
        return;
    }

    $today = date('Y-m-d');

    $sql = "
        SELECT borrows.id, borrows.return_date, books.title
        FROM borrows
        JOIN books ON borrows.book_id = books.id
        WHERE borrows.user_id = ?
          AND borrows.status = 'dipinjam'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $due_date = $row['return_date'];
        $book_title = $row['title'];

        // 3 hari sebelum jatuh tempo
        if (strtotime($due_date) - strtotime($today) <= (3 * 24 * 60 * 60)) {

            // Cek apakah notifikasi sudah dibuat sebelumnya
            $check = $conn->prepare("
                SELECT id FROM notifications
                WHERE user_id = ? AND message LIKE ?
                LIMIT 1
            ");
            $msgLike = "%$book_title%";
            $check->bind_param("is", $user_id, $msgLike);
            $check->execute();
            $exist = $check->get_result();

            if ($exist->num_rows == 0) {
                // Buat notifikasi baru
                $msg = "Tenggat waktu pengembalian buku '$book_title' tinggal 3 hari lagi!";
                $notif = $conn->prepare("
                    INSERT INTO notifications (user_id, message)
                    VALUES (?, ?)
                ");
                $notif->bind_param("is", $user_id, $msg);
                $notif->execute();
            }
        }
    }

    // ❗ Tandai bahwa notifikasi deadline sudah diproses dalam session ini
    $_SESSION['deadline_notified'] = true;
}


}
?>
