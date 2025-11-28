-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Nov 2025 pada 04.02
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
  `stock` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam','Tidak Tersedia') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id`, `cover`, `title`, `author`, `publisher`, `category`, `publish_date`, `description`, `stock`, `created_at`, `category_id`, `status`) VALUES
(42, '1763949876_a66212293ba7f4a09bc19545046200c3.jpg', 'Alam Menabjubkan', 'Arcturus Publishing', 'Gramedia Pustaka Utama ', NULL, '2025-11-24', 'Buku ini menyajikan berbagai aktivitas yang dapat membantu anak-anak belajar tentang alam dan lingkungan sekitar mereka.', 4, '2025-11-24 02:04:36', 2, 'Tersedia'),
(43, '1763950370_116452_f.jpg', 'Buku Jago Sepak Bola Untuk Pemula', 'Reki Siaga Agustina, M.Pd., AIFO-P', 'CemerlangMediaPublishing ', NULL, '2020-07-16', 'Sepak bola adalah salah satu olahraga yang populer di sgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddindonesia, bahkan dunia. salah satu olahraga yang bisa menempati hati masyarakat dengan baik. ', 4, '2025-11-24 02:12:50', 5, 'Tersedia');

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
  `method` enum('web','langsung') DEFAULT 'web'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `status`, `method`) VALUES
(69, 25, 43, '2025-11-27', '2025-12-04', 'dikembalikan', 'web'),
(70, 25, 42, '2025-11-27', '2025-11-30', 'dikembalikan', 'web'),
(71, 13, 43, '2025-11-27', '2025-11-29', 'dikembalikan', 'web');

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
(64, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 39', 0, '2025-11-24 02:30:02'),
(65, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 55', 0, '2025-11-24 02:54:09'),
(66, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 54', 0, '2025-11-24 03:01:29'),
(67, 10, 'User ID 23 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-24 09:13:51'),
(69, 10, 'User ID 23 mengajukan pengembalian buku pada peminjaman ID 56', 0, '2025-11-24 09:14:18'),
(70, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 55', 0, '2025-11-24 09:29:25'),
(72, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 57', 0, '2025-11-24 09:31:17'),
(73, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 09:33:30'),
(74, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 58', 0, '2025-11-24 09:34:55'),
(75, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 10:16:30'),
(76, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 59', 0, '2025-11-24 10:17:03'),
(77, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 10:17:56'),
(78, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 50', 0, '2025-11-25 12:30:32'),
(79, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 51', 0, '2025-11-24 13:22:09'),
(80, 13, 'Hari ini batas pengembalian buku \'a\'. Harap dikembalikan sebelum pukul 16:00 sore.', 1, '2025-11-24 14:22:41'),
(81, 13, 'Hari ini batas pengembalian buku \'u\'. Harap dikembalikan sebelum pukul 16:00 sore.', 1, '2025-11-24 14:22:41'),
(82, 13, 'Anda terlambat 1 hari mengembalikan buku: a', 1, '2025-11-25 14:27:54'),
(83, 13, 'Anda terlambat 1 hari mengembalikan buku: u', 1, '2025-11-25 14:27:54'),
(84, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 61', 0, '2025-11-25 14:39:35'),
(85, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 55', 0, '2025-11-25 14:42:27'),
(86, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-25 14:42:27'),
(87, 13, 'Hari ini batas pengembalian buku \'s\'. Harap dikembalikan sebelum pukul 16:00 sore.', 1, '2025-11-25 14:43:12'),
(88, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 64', 0, '2025-11-25 14:54:30'),
(89, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 63', 0, '2025-11-25 14:54:31'),
(90, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-25 16:00:45'),
(91, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 42', 0, '2025-11-25 16:00:45'),
(92, 13, 'Tenggat waktu pengembalian buku \'Alam Menabjubkan\' tinggal 2 hari lagi!', 1, '2025-11-25 16:04:30'),
(93, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-25 16:17:32'),
(94, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 42', 0, '2025-11-25 16:17:32'),
(95, 10, 'User ID 25 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-27 14:42:17'),
(96, 10, 'User ID 25 meminta konfirmasi peminjaman untuk buku ID 42', 0, '2025-11-27 14:42:17'),
(97, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-27 14:42:57');

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
(10, 'admin', '$2y$10$SUMB5UJb8FiABqw1aCjqcOzgadjbP.fU0WGhbIrsPToiWMQJerOAm', 'admin@admin.com', 'admin', '2025-10-01 08:27:24', 'default.png'),
(13, 'user', '$2y$10$0BO4bHUhc9NR8sEdHQZJQ.oeM1T5GbCNMwN2jjHtvjY7qNqbhHERC', 'user@user.com', 'user', '2025-11-03 04:29:58', '1764215288_logo-permabudhi.png'),
(25, 'q', '$2y$10$dr.0ApkNRFwjcrCCKt/fJutmQyKnJWq88od5orTTreOAt4frImTSi', 'qwe@gmail.com', 'user', '2025-11-25 09:00:57', 'default.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

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
