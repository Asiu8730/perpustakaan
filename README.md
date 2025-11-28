# Perpustakaan (Aplikasi Web)

Aplikasi manajemen perpustakaan sederhana (PHP + MySQL) — menampilkan buku, kategori, keranjang peminjaman, notifikasi, dan panel admin untuk kelola buku/user/kategori/peminjaman.

## Fitur utama
- Autentikasi user/admin
- Kelola buku (CRUD), kategori, user (admin)
- Keranjang & proses peminjaman
- Alur konfirmasi peminjaman & pengembalian (user ↔ admin)
- Notifikasi (deadline, konfirmasi)
- Pagination dan layout responsif
- Upload cover / foto user

## Prerequisites
- XAMPP / PHP >= 8
- MySQL / MariaDB
- Browser

## Instalasi cepat
1. Salin folder ke: `c:\xampp\htdocs\reca\perpustakaan`
2. Buat database MySQL. Impor file dump SQL (misal `perpustakaan.sql`) via phpMyAdmin.
    - Database name yang digunakan: `perpustakaan`
    - Jika ingin cepat impor lewat command-line (MySQL):
       ```bash
       mysql -u root -p perfustakaan < perpustakaan.sql
       ```
3. Update konfigurasi DB:
   - `config/database.php` → sesuaikan host, user, password, database.
4. Pastikan folder upload ada & writable:
   - `uploads/covers/`, `uploads/users/`, `uploads/logo/`
5. Buka aplikasi:
   - Guest: `http://localhost/reca/perpustakaan/public/dashboard_guest.php`
   - User: `http://localhost/reca/perpustakaan/public/dashboard_user.php`
   - Admin: `http://localhost/reca/perpustakaan/public/dashboard_admin.php`

## Akun default (untuk testing)
- Admin
  - username: `admin`
  - email: `admin@admin.com`
  - password: `admin123`
- User
  - username: `user`
  - email: `user@user.com`
  - password: `user123`

## Struktur penting
- `/view/` — tampilan (user, admin, guest)
- `/public/assets/` — CSS / JS / gambar
- `/controllers/` — logika aplikasi (BookController, BorrowController, NotificationController, dll)
- `/config/database.php` — koneksi DB
- `/uploads/` — file cover / foto

## Konfigurasi & troubleshooting cepat
- Notifikasi tidak muncul → cek `notifications` table dan fungsi di `BorrowController::notifyDeadline`
- Upload file gagal → periksa permission folder `uploads/`
- Error query → cek konfigurasi DB & struktur tabel sesuai dump SQL

