-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 14, 2025 at 02:26 AM
-- Server version: 8.0.44-0ubuntu0.24.04.2
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akuntansi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_detail`
--

CREATE TABLE `jurnal_detail` (
  `id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `akun_coa` varchar(100) DEFAULT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `kredit` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurnal_detail`
--

INSERT INTO `jurnal_detail` (`id`, `transaksi_id`, `akun_coa`, `debit`, `kredit`) VALUES
(15, 10, '101 - Kas Besar', 0.00, 10.00),
(16, 11, '101 - Kas Besar', 5.00, 0.00),
(17, 11, '102 - Bank BCA', 0.00, 5.00),
(18, 12, '101 - Kas Besar', 2.00, 0.00),
(19, 12, '101 - Kas Besar', 0.00, 2.00),
(20, 13, '102 - Bank BCA', 2.00, 0.00),
(21, 13, '101 - Kas Besar', 2.00, 4.00),
(24, 15, '101 - Kas Besar', 2.00, 0.00),
(25, 15, '101 - Kas Besar', 0.00, 2.00),
(26, 16, '101 - Kas Besar', 1.00, 0.00),
(27, 16, '101 - Kas Besar', 0.00, 1.00),
(28, 17, '101 - Kas Besar', 2.00, 0.00),
(29, 17, '101 - Kas Besar', 0.00, 2.00),
(30, 18, '101 - Kas Besar', 2.00, 0.00),
(31, 18, '101 - Kas Besar', 0.00, 2.00),
(32, 19, '101 - Kas Besar', 3.00, 0.00),
(33, 19, '101 - Kas Besar', 0.00, 3.00),
(34, 20, '101 - Kas Besar', 2.00, 0.00),
(35, 20, '101 - Kas Besar', 0.00, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_transaksi` varchar(50) DEFAULT NULL,
  `nomor_bukti` varchar(50) DEFAULT NULL,
  `deskripsi` text,
  `file_bukti` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `tanggal`, `jenis_transaksi`, `nomor_bukti`, `deskripsi`, `file_bukti`, `created_at`) VALUES
(10, '2025-12-12', 'expense', '123566', '121246', '1765545434_693c15da6145a.png', '2025-12-12 13:17:14'),
(11, '2025-12-12', 'expense', '1533', 'Pembelian air galon', '1765546048_693c18404c29a.pdf', '2025-12-12 13:27:28'),
(12, '2025-12-11', 'expense', '12121', '13212', NULL, '2025-12-12 13:35:29'),
(13, '2025-12-22', 'sell', 'asda', 'asd', NULL, '2025-12-12 13:36:13'),
(15, '2025-12-09', 'buy', 'adsa', 'asd', NULL, '2025-12-12 13:36:49'),
(16, '2025-12-11', 'sell', 'as', 'as', NULL, '2025-12-12 13:37:03'),
(17, '2025-12-18', 'expense', 'asd', 'asd', NULL, '2025-12-12 13:37:13'),
(18, '2025-12-17', 'sell', 'asd', 'asd', NULL, '2025-12-12 13:37:36'),
(19, '2025-12-02', 'buy', 'sad', 'asd', NULL, '2025-12-12 13:37:48'),
(20, '2025-12-12', 'sell', '11232', 'jnnj', NULL, '2025-12-12 13:39:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD CONSTRAINT `jurnal_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
