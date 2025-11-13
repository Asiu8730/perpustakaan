-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Nov 2025 pada 09.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `publish_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam','Tidak Tersedia') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id`, `cover`, `title`, `author`, `publisher`, `category`, `publish_date`, `description`, `created_at`, `category_id`, `status`) VALUES
(19, '1760580325_logo-permabudhi.png', 'asd', 'asd', 'asd', NULL, '2333-03-12', NULL, '2025-10-16 02:05:25', 5, 'Tidak Tersedia'),
(20, '1760580343_logo-permabudhi.png', 'qwe', 'qwe', 'qwe', NULL, '0000-00-00', NULL, '2025-10-16 02:05:43', 4, 'Tersedia'),
(21, '1760580360_logo-permabudhi.png', 'zxc', 'zxc', 'zxc', NULL, '0123-03-12', NULL, '2025-10-16 02:06:00', 3, 'Tersedia'),
(22, '1760580376_logo-permabudhi.png', 'sdf', 'sdf', 'sdf', NULL, '0123-03-12', NULL, '2025-10-16 02:06:16', 2, 'Tidak Tersedia'),
(28, '1762154960_2024_Acer_Consumer_Option_01_3840x2400.jpg', 'qwe', 'qwe', 'qwe', NULL, '0002-03-12', 'qwe', '2025-11-03 07:29:20', 5, 'Tersedia'),
(29, '1762154999_2024_Acer_Consumer_Option_01_3840x2400.jpg', 'qwe', 'qwe', 'qwe', NULL, '0123-03-12', 'ase', '2025-11-03 07:29:59', 2, 'Tersedia'),
(32, '1762155421_2024_Acer_Consumer_Option_02_3840x2400.jpg', 'sad', 'asd', 'asd', NULL, '0002-03-12', 'sda', '2025-11-03 07:37:01', 5, 'Tidak Tersedia'),
(33, '1762155688_2024_Acer_Consumer_Option_02_3840x2400.jpg', 'asd', 'asd', 'asd', NULL, '0023-03-12', 'asd', '2025-11-03 07:41:28', 4, 'Tidak Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrows`
--

CREATE TABLE `borrows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'menunggu_konfirmasi_admin',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Agama'),
(2, 'Alam'),
(3, 'Novel'),
(4, 'Komik'),
(5, 'Olahraga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 10, 'User ID 13 telah meminjam buku ID 19', 0, '2025-11-11 07:12:53'),
(2, 10, 'User ID 13 telah meminjam buku ID 28', 0, '2025-11-12 13:02:05'),
(3, 10, 'User ID 13 telah meminjam buku ID 32', 0, '2025-11-12 13:02:05'),
(4, 10, 'User ID 13 telah meminjam buku ID 32', 0, '2025-11-13 06:52:19'),
(5, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 19', 0, '2025-11-13 07:07:10'),
(6, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-13 07:16:11'),
(7, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-13 07:16:11'),
(8, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-13 07:24:03'),
(9, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-13 07:32:39'),
(10, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-13 07:32:39'),
(11, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-13 08:39:42'),
(12, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 26', 0, '2025-11-13 08:40:41'),
(13, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-13 08:47:56'),
(14, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 20', 0, '2025-11-13 08:47:56'),
(15, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 28', 0, '2025-11-13 08:49:44'),
(16, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 27', 0, '2025-11-13 10:24:52'),
(17, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-13 10:34:46'),
(18, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-13 10:34:46'),
(19, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-13 10:34:46'),
(20, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 20', 0, '2025-11-13 10:50:30'),
(21, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 21', 0, '2025-11-13 10:50:30'),
(22, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 29', 0, '2025-11-13 10:50:30'),
(23, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 34', 0, '2025-11-13 11:17:56'),
(24, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 33', 0, '2025-11-13 11:17:59'),
(25, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 32', 0, '2025-11-13 11:18:01'),
(26, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 31', 0, '2025-11-13 11:18:02'),
(27, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 30', 0, '2025-11-13 11:18:04'),
(28, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 29', 0, '2025-11-13 11:18:05'),
(29, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-13 11:25:23'),
(30, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 35', 0, '2025-11-13 11:26:01'),
(31, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-13 14:35:27'),
(32, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-13 14:35:27'),
(33, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-13 14:46:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `photo`) VALUES
(10, 'asd', '$2y$10$2loF8bIuEVgm5fJ.SJH8MO0Ax337NhE7lERfWEIyWiI5yJG71PyHe', 'asd@gamil.com', 'admin', '2025-10-01 08:27:24', 'default.png'),
(13, 'qwe', '$2y$10$4AUf6LIH/F535QR5kJ9KXeIKy3BkKQ0M9szhizJs6XQquoSj3kmBa', 'qwe@gmail.com', 'user', '2025-11-03 04:29:58', '1762145602_2024_Acer_Consumer_Option_02_3840x2400.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indeks untuk tabel `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ketidakleluasaan untuk tabel `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
