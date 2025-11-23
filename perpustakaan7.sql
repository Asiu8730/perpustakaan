-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2025 at 09:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `books`
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
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `cover`, `title`, `author`, `publisher`, `category`, `publish_date`, `description`, `stock`, `created_at`, `category_id`, `status`) VALUES
(36, '1763855774_WhatsApp Image 2025-01-20 at 09.16.25.jpeg', 'karbit', 'welino', 'kepeng', NULL, '2067-07-06', 'karbit liar', 11, '2025-11-22 23:56:14', 6, 'Dipinjam'),
(37, '1763913935_WhatsApp Image 2025-01-20 at 09.16.25.jpeg', 'q', 'q', 'q', NULL, '0012-12-12', 'q', 2, '2025-11-23 16:05:35', 4, 'Dipinjam'),
(38, '1763913963_WhatsApp Image 2025-01-20 at 09.16.25.jpeg', 'w', 'w', 'w', NULL, '0011-11-11', 'w', 1, '2025-11-23 16:06:03', 5, 'Dipinjam'),
(39, '1763913993_WhatsApp Image 2025-01-05 at 05.05.57_4800dbad.jpg', 'e', 'e', 'e', NULL, '0013-12-12', 'e', 1, '2025-11-23 16:06:33', 2, 'Tersedia'),
(40, '1763914015_IMG_20220914_094033.jpg', 'r', 'r', 'r', NULL, '0012-12-12', 'r', 0, '2025-11-23 16:06:55', 2, 'Tidak Tersedia'),
(41, '1763914044_IMG_20220914_094033.jpg', 't', 't', 't', NULL, '0003-02-23', 'wer', 1, '2025-11-23 16:07:24', 1, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `borrows`
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
-- Dumping data for table `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `status`) VALUES
(50, 13, 36, '2025-11-23', '2025-11-28', 'dipinjam'),
(51, 13, 38, '2025-11-23', '2025-11-29', 'dipinjam'),
(52, 13, 37, '2025-11-23', '2025-11-29', 'dipinjam'),
(53, 13, 40, '2025-11-23', '0001-12-12', 'dipinjam'),
(54, 13, 41, '2025-11-23', '2025-11-27', 'dikembalikan'),
(55, 13, 39, '2025-11-23', '2025-11-25', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Agama'),
(2, 'Alam'),
(3, 'Novel'),
(4, 'Komik'),
(5, 'Olahraga'),
(6, 'Teknologi');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
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
(66, 10, 'User ID 13 mengajukan pengembalian buku pada peminjaman ID 54', 0, '2025-11-24 03:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `photo`) VALUES
(10, 'asd', '$2y$10$2loF8bIuEVgm5fJ.SJH8MO0Ax337NhE7lERfWEIyWiI5yJG71PyHe', 'asd@gamil.com', 'admin', '2025-10-01 08:27:24', 'default.png'),
(13, 'qwe', '$2y$10$4AUf6LIH/F535QR5kJ9KXeIKy3BkKQ0M9szhizJs6XQquoSj3kmBa', 'qwe@gmail.com', 'user', '2025-11-03 04:29:58', '1762145602_2024_Acer_Consumer_Option_02_3840x2400.jpg'),
(14, 'q', '$2y$10$pwxDy6dR4Vcg//Rq1z0Z9./e1RDee2klr1YE.kXoJ.GpzJZzyrese', 'q@q.com', 'admin', '2025-11-22 22:54:41', 'default.png'),
(15, 'Asiu', '$2y$10$.m57XNkECjn4Xux62vXcyOHn2T/vDOVNe6rB.sUAeU4cRaL.Ok7SO', 'asiu8730@gmail.com', 'user', '2025-11-23 16:02:17', 'default.png'),
(16, 'karbit', '$2y$10$S9OblG4e3SeRcoLYl21kb.FTyhcvsiARcITknHrZ0jz7ck/9BbD3S', 'karbit@karbit.com', 'user', '2025-11-23 16:03:54', '1763913861_WhatsApp_Image_2025-01-20_at_09_16_25.jpeg'),
(17, '1', '$2y$10$StGWYpr6ycMRdXqOs5XK9O7fXpnN4tWb3bX.CXm8PsR1QVlBegFVy', '1@1.com', 'admin', '2025-11-23 17:43:22', 'default.png'),
(18, '123', '$2y$10$lrK9Qudi1qxmRsFaWpM60./93Hbr39.gz1mnEMvV/09psDPwGrIKO', '12@12.com', 'user', '2025-11-23 17:44:01', 'default.png'),
(19, 'ewq', '$2y$10$jjprDc70jq6AGDynkw0M7epQFPTvp/8Mw6GAz5T93ii8RTsB0FxLy', 'eewq@ewq.com', 'user', '2025-11-23 17:44:30', 'default.png'),
(20, 'wqe', '$2y$10$BBxht9S7q68tFBDu1uSvR.YTnWgbp4U1Ybc3NGjafx4zF4TBKaG.S', 'wqe@wqe.com', 'user', '2025-11-23 17:44:59', 'default.png'),
(21, 'as', '$2y$10$QeaOqVMRS6430ebfAxslTeK0XQhe.p5k9R.fWsTCqbMyfuuAwzx3q', 'as@as.com', 'user', '2025-11-23 17:45:22', 'default.png'),
(22, 'ds', '$2y$10$k/6eFjpyVSk.en83tarVkedW4VTJdVKxZW6tIJB26AKa6tKizNOZO', 'ds@ds.com', 'user', '2025-11-23 17:54:25', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
