-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Nov 2025 pada 07.43
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
(19, '1760580325_logo-permabudhi.png', 'asd', 'asd', 'asd', NULL, '2333-03-12', 'asd', '2025-10-16 02:05:25', 5, 'Tersedia'),
(20, '1760580343_logo-permabudhi.png', 'qwe', 'qwe', 'qwe', NULL, '0000-00-00', NULL, '2025-10-16 02:05:43', 4, 'Tersedia'),
(21, '1760580360_logo-permabudhi.png', 'zxc', 'zxc', 'zxc', NULL, '0123-03-12', NULL, '2025-10-16 02:06:00', 3, 'Tersedia'),
(22, '1760580376_logo-permabudhi.png', 'sdf', 'sdf', 'sdf', NULL, '0123-03-12', 'dsa', '2025-10-16 02:06:16', 2, 'Tersedia'),
(28, '1762154960_2024_Acer_Consumer_Option_01_3840x2400.jpg', 'qwe', 'qwe', 'qwe', NULL, '0002-03-12', 'qwe', '2025-11-03 07:29:20', 5, 'Tersedia'),
(29, '1762154999_2024_Acer_Consumer_Option_01_3840x2400.jpg', 'qwe', 'qwe', 'qwe', NULL, '0123-03-12', 'ase', '2025-11-03 07:29:59', 2, 'Tersedia'),
(32, '1762155421_2024_Acer_Consumer_Option_02_3840x2400.jpg', 'sad', 'asd', 'asd', NULL, '0002-03-12', 'sda', '2025-11-03 07:37:01', 5, 'Tersedia'),
(33, '1762155688_2024_Acer_Consumer_Option_02_3840x2400.jpg', 'asd', 'asd', 'asd', NULL, '0023-03-12', 'asd', '2025-11-03 07:41:28', 4, 'Tidak Tersedia'),
(34, '1763428518_logo-permabudhi.png', 'Kujang', 'budi', 'sinarmas', NULL, '2007-02-06', 'tentang alam', '2025-11-18 01:15:18', 2, 'Tidak Tersedia');

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
  `status` varchar(100) NOT NULL DEFAULT 'menunggu_konfirmasi_admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `status`) VALUES
(39, 13, 32, '2025-11-17', NULL, 'dikembalikan'),
(40, 13, 28, '2025-11-17', NULL, 'dikembalikan'),
(41, 13, 19, '2025-11-17', NULL, 'dikembalikan'),
(42, 13, 28, '2025-11-17', NULL, 'dikembalikan'),
(43, 13, 28, '2025-11-19', '2025-11-21', 'dikembalikan'),
(44, 13, 19, '2025-11-19', '2025-11-21', 'dikembalikan'),
(45, 13, 32, '2025-11-19', '2025-11-20', 'dikembalikan'),
(46, 13, 28, '2025-11-19', '2025-11-19', 'dikembalikan'),
(47, 13, 32, '2025-11-21', '2025-11-25', 'dikembalikan');

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
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 13, 'Buku \'qwe\' harus dikembalikan besok!', 1, '2025-11-17 10:33:33'),
(40, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-17 10:48:07'),
(41, 13, 'Tenggat waktu pengembalian buku \'asd\' tinggal 3 hari lagi!', 1, '2025-11-17 10:48:25'),
(42, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 41', 0, '2025-11-17 10:56:04'),
(43, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 40', 0, '2025-11-17 10:56:06'),
(44, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-17 10:59:18'),
(45, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 42', 0, '2025-11-17 10:59:46'),
(46, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-19 15:46:40'),
(47, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-19 15:46:40'),
(48, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 44', 0, '2025-11-19 15:47:05'),
(49, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 43', 0, '2025-11-19 15:47:08'),
(50, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-19 15:48:29'),
(51, 13, 'Tenggat waktu pengembalian buku \'sad\' tinggal 3 hari lagi!', 1, '2025-11-19 15:48:49'),
(52, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 45', 0, '2025-11-19 15:49:40'),
(53, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-19 15:55:58'),
(54, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 32', 0, '2025-11-21 12:53:49'),
(55, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 47', 0, '2025-11-21 13:23:03'),
(56, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 46', 0, '2025-11-21 13:23:05');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

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
