-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Nov 2025 pada 07.04
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
(42, '1763949876_a66212293ba7f4a09bc19545046200c3.jpg', 'Alam Menabjubkan', 'Arcturus Publishing', 'Gramedia Pustaka Utama ', NULL, '2025-11-24', 'Buku ini menyajikan berbagai aktivitas yang dapat membantu anak-anak belajar tentang alam dan lingkungan sekitar mereka.', 1, '2025-11-24 02:04:36', 2, 'Tersedia'),
(43, '1763950370_116452_f.jpg', 'Buku Jago Sepak Bola Untuk Pemula', 'Reki Siaga Agustina, M.Pd., AIFO-P', 'CemerlangMediaPublishing ', NULL, '2020-07-16', 'Sepak bola adalah salah satu olahraga yang populer di indonesia, bahkan dunia. salah satu olahraga yang bisa menempati hati masyarakat dengan baik. ', 1, '2025-11-24 02:12:50', 5, 'Tersedia'),
(44, '1763950926_logo-permabudhi.png', 'q', 'q', 'q', NULL, '2025-11-24', 'qw', 1, '2025-11-24 02:22:06', 5, 'Tersedia'),
(45, '1763950944_978-602-73649-8-1.jpg', 'w', 'w', 'w', NULL, '2025-11-24', 'w', 1, '2025-11-24 02:22:24', 5, 'Tersedia'),
(46, '1763950960_no-logo.png', 'e', 'e', 'e', NULL, '2025-11-24', 'e', 1, '2025-11-24 02:22:40', 5, 'Tersedia'),
(47, '1763950973_Desain-Cover-PAI_00001.jpg', 'r', 'r', 'r', NULL, '2025-11-24', 'r', 1, '2025-11-24 02:22:53', 5, 'Tersedia'),
(48, '1763950991_a66212293ba7f4a09bc19545046200c3.jpg', 't', 't', 't', NULL, '2025-11-24', 't', 1, '2025-11-24 02:23:11', 5, 'Tersedia'),
(49, '1763951015_obat.jpg', 'y', 'y', 'y', NULL, '2025-11-24', 'y', 1, '2025-11-24 02:23:35', 5, 'Tersedia'),
(50, '1763951068_Screenshot 2025-11-19 072321.png', 'u', 'u', 'u', NULL, '2025-11-24', 'u', 0, '2025-11-24 02:24:28', 5, 'Tidak Tersedia'),
(51, '1763951091_Screenshot (26).png', 'i', 'i', 'i', NULL, '0012-12-12', 'i', 1, '2025-11-24 02:24:51', 5, 'Tersedia'),
(52, '1763951108_Screenshot (19).png', 'o', 'o', 'o', NULL, '2025-11-24', 'o', 1, '2025-11-24 02:25:08', 5, 'Tersedia'),
(53, '1763951123_Screenshot (26).png', 'p', 'p', 'p', NULL, '2025-11-24', 'p', 1, '2025-11-24 02:25:23', 5, 'Tersedia'),
(54, '1763951143_Screenshot 2025-07-12 102027.png', 'a', 'a', 'a', NULL, '0012-12-12', 'a', 0, '2025-11-24 02:25:43', 5, 'Tidak Tersedia'),
(55, '1763951157_Screenshot (25).png', 's', 's', 's', NULL, '0012-12-12', 's', 1, '2025-11-24 02:25:57', 5, 'Tersedia');

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
(56, 23, 43, '2025-11-24', '2025-11-27', 'dikembalikan', 'web'),
(57, 24, 55, '2025-11-24', '2025-11-27', 'dikembalikan', 'web'),
(58, 24, 54, '2025-11-24', NULL, 'dikembalikan', 'web'),
(59, 24, 54, '2025-11-24', '2025-11-26', 'dikembalikan', 'web'),
(60, 13, 54, '2025-11-24', '2025-11-24', 'dipinjam', 'web'),
(61, 13, 50, '2025-11-25', '2025-11-24', 'dipinjam', 'web');

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
(56, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 46', 0, '2025-11-21 13:23:05'),
(57, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 19', 0, '2025-11-23 00:12:16'),
(58, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 28', 0, '2025-11-23 00:12:16'),
(59, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 36', 0, '2025-11-24 02:30:02'),
(60, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 38', 0, '2025-11-24 02:30:02'),
(61, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 37', 0, '2025-11-24 02:30:02'),
(62, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 40', 0, '2025-11-24 02:30:02'),
(63, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 41', 0, '2025-11-24 02:30:02'),
(64, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 39', 0, '2025-11-24 02:30:02'),
(65, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 55', 0, '2025-11-24 02:54:09'),
(66, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 54', 0, '2025-11-24 03:01:29'),
(67, 10, 'User ID 23 meminta konfirmasi peminjaman untuk buku ID 43', 0, '2025-11-24 09:13:51'),
(68, 23, 'Tenggat waktu pengembalian buku \'Buku Jago Sepak Bola Untuk Pemula\' tinggal 3 hari lagi!', 0, '2025-11-24 09:14:14'),
(69, 10, 'User ID 23 mengajukan pengembalian buku pada peminjaman ID 56', 0, '2025-11-24 09:14:18'),
(70, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 55', 0, '2025-11-24 09:29:25'),
(71, 24, 'Tenggat waktu pengembalian buku \'s\' tinggal 3 hari lagi!', 1, '2025-11-24 09:31:03'),
(72, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 57', 0, '2025-11-24 09:31:17'),
(73, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 09:33:30'),
(74, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 58', 0, '2025-11-24 09:34:55'),
(75, 10, 'User ID 24 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 10:16:30'),
(76, 10, 'User ID 24 mengajukan pengembalian buku pada peminjaman ID 59', 0, '2025-11-24 10:17:03'),
(77, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 54', 0, '2025-11-24 10:17:56'),
(78, 10, 'User ID 13 meminta konfirmasi peminjaman untuk buku ID 50', 0, '2025-11-25 12:30:32');

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
(13, 'qwe', '$2y$10$4AUf6LIH/F535QR5kJ9KXeIKy3BkKQ0M9szhizJs6XQquoSj3kmBa', 'qwe@gmail.com', 'user', '2025-11-03 04:29:58', '1763954266_Screenshot_2025-11-19_072321.png'),
(14, 'q', '$2y$10$pwxDy6dR4Vcg//Rq1z0Z9./e1RDee2klr1YE.kXoJ.GpzJZzyrese', 'q@q.com', 'admin', '2025-11-22 22:54:41', 'default.png'),
(15, 'Asiu', '$2y$10$.m57XNkECjn4Xux62vXcyOHn2T/vDOVNe6rB.sUAeU4cRaL.Ok7SO', 'asiu8730@gmail.com', 'user', '2025-11-23 16:02:17', 'default.png'),
(16, 'karbit', '$2y$10$S9OblG4e3SeRcoLYl21kb.FTyhcvsiARcITknHrZ0jz7ck/9BbD3S', 'karbit@karbit.com', 'user', '2025-11-23 16:03:54', '1763913861_WhatsApp_Image_2025-01-20_at_09_16_25.jpeg'),
(17, '1', '$2y$10$StGWYpr6ycMRdXqOs5XK9O7fXpnN4tWb3bX.CXm8PsR1QVlBegFVy', '1@1.com', 'admin', '2025-11-23 17:43:22', 'default.png'),
(18, '123', '$2y$10$lrK9Qudi1qxmRsFaWpM60./93Hbr39.gz1mnEMvV/09psDPwGrIKO', '12@12.com', 'user', '2025-11-23 17:44:01', 'default.png'),
(19, 'ewq', '$2y$10$jjprDc70jq6AGDynkw0M7epQFPTvp/8Mw6GAz5T93ii8RTsB0FxLy', 'ewq@ewq.com', 'user', '2025-11-23 17:44:30', 'default.png'),
(20, 'wqe', '$2y$10$BBxht9S7q68tFBDu1uSvR.YTnWgbp4U1Ybc3NGjafx4zF4TBKaG.S', 'wqe@wqe.com', 'user', '2025-11-23 17:44:59', 'default.png'),
(21, 'as', '$2y$10$QeaOqVMRS6430ebfAxslTeK0XQhe.p5k9R.fWsTCqbMyfuuAwzx3q', 'as@as.com', 'user', '2025-11-23 17:45:22', 'default.png'),
(23, 'aku', '$2y$10$uu6SjtBXskMRRluGrQrGYuzU5SYeeIl/antQ366kDY2c562Nx2/hu', 'aku@aku.com', 'user', '2025-11-24 02:13:21', '1763950418_logo-permabudhi.png'),
(24, 'apa', '$2y$10$dz3IQE5dgIMyW1WO8einke6Pc7F.zTrq1jDxJj1MntOEPTH0BYkga', 'apa@apa.com', 'user', '2025-11-24 02:29:04', 'default.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
