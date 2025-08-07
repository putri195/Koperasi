-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Agu 2025 pada 08.40
-- Versi server: 5.7.39
-- Versi PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_koperasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cash_book`
--

CREATE TABLE `cash_book` (
  `id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text,
  `transaction_type` enum('Simpanan Pokok','Simpanan Wajib','Simpanan Sukarela','Pinjaman','Angsuran','Modal Awal','Bunga','Denda') NOT NULL,
  `reference_table` varchar(50) DEFAULT NULL,
  `reference_id` varchar(20) DEFAULT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `credit` decimal(15,2) DEFAULT '0.00',
  `balance` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `cash_book`
--

INSERT INTO `cash_book` (`id`, `transaction_date`, `description`, `transaction_type`, `reference_table`, `reference_id`, `debit`, `credit`, `balance`, `created_at`, `updated_at`) VALUES
(1, '2025-07-30', 'Simpanan pokok Putri', 'Simpanan Pokok', 'savings', 'SP-0001', 50000.00, 0.00, 50000.00, '2025-07-29 18:08:42', '2025-07-29 18:08:42'),
(4, '2025-07-30', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0001', 20000.00, 0.00, 70000.00, '2025-07-29 18:36:03', '2025-07-29 18:36:03'),
(5, '2025-07-30', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0001', 40000.00, 0.00, 110000.00, '2025-07-29 18:37:02', '2025-07-29 18:37:02'),
(6, '2025-07-30', 'Simpanan pokok Natun', 'Simpanan Pokok', 'savings', 'SP-0002', 50000.00, 0.00, 160000.00, '2025-07-30 01:56:07', '2025-07-30 01:56:07'),
(7, '2025-07-31', 'Simpanan wajib bulan juli Putri', 'Simpanan Wajib', 'savings', 'SW-0002', 20000.00, 0.00, 180000.00, '2025-07-30 18:39:48', '2025-07-30 18:39:48'),
(8, '2025-07-31', 'Simpanan sukarela natun', 'Simpanan Sukarela', 'savings', 'SSK-0002', 7000000.00, 0.00, 7180000.00, '2025-07-30 18:48:13', '2025-07-30 18:48:13'),
(9, '2024-12-08', 'Simpanan pokok awal Muhtar', 'Simpanan Pokok', 'savings', 'SP-0003', 50000.00, 0.00, 7230000.00, '2025-07-31 23:48:36', '2025-07-31 23:48:36'),
(10, '2025-01-17', 'Simpanan pokok awal', 'Simpanan Pokok', 'savings', 'SP-0004', 50000.00, 0.00, 7280000.00, '2025-07-31 23:49:17', '2025-07-31 23:49:17'),
(11, '2025-02-25', 'Simpanan pokok awal Febri', 'Simpanan Pokok', 'savings', 'SP-0005', 50000.00, 0.00, 7330000.00, '2025-07-31 23:49:52', '2025-07-31 23:49:52'),
(12, '2025-01-20', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0003', 20000.00, 0.00, 7350000.00, '2025-07-31 23:53:58', '2025-07-31 23:53:58'),
(13, '2025-03-01', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0004', 20000.00, 0.00, 7370000.00, '2025-07-31 23:55:23', '2025-07-31 23:55:23'),
(14, '2025-02-01', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0005', 20000.00, 0.00, 7390000.00, '2025-07-31 23:56:13', '2025-07-31 23:56:13'),
(15, '2025-01-01', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0006', 20000.00, 0.00, 7410000.00, '2025-07-31 23:58:20', '2025-07-31 23:58:20'),
(16, '2025-02-19', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0007', 20000.00, 0.00, 7430000.00, '2025-07-31 23:59:10', '2025-07-31 23:59:10'),
(17, '2025-03-09', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0008', 20000.00, 0.00, 7450000.00, '2025-07-31 23:59:31', '2025-07-31 23:59:31'),
(18, '2025-04-13', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0009', 20000.00, 0.00, 7470000.00, '2025-07-31 23:59:49', '2025-07-31 23:59:49'),
(19, '2025-05-31', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0010', 20000.00, 0.00, 7490000.00, '2025-08-01 00:00:10', '2025-08-01 00:00:10'),
(20, '2025-06-23', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0011', 20000.00, 0.00, 7510000.00, '2025-08-01 00:00:32', '2025-08-01 00:00:32'),
(21, '2025-07-30', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0012', 20000.00, 0.00, 7530000.00, '2025-08-01 00:00:59', '2025-08-01 00:00:59'),
(22, '2025-08-01', 'Simpanan wajib Natun', 'Simpanan Wajib', 'savings', 'SW-0013', 20000.00, 0.00, 7550000.00, '2025-08-01 00:01:28', '2025-08-01 00:01:28'),
(23, '2025-01-06', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0014', 20000.00, 0.00, 7570000.00, '2025-08-01 00:02:44', '2025-08-01 00:02:44'),
(24, '2025-02-09', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0015', 20000.00, 0.00, 7590000.00, '2025-08-01 00:03:02', '2025-08-01 00:03:02'),
(25, '2025-03-08', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0016', 20000.00, 0.00, 7610000.00, '2025-08-01 00:03:20', '2025-08-01 00:03:20'),
(26, '2025-04-11', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0017', 20000.00, 0.00, 7630000.00, '2025-08-01 00:03:39', '2025-08-01 00:03:39'),
(27, '2025-05-13', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0018', 20000.00, 0.00, 7650000.00, '2025-08-01 00:04:36', '2025-08-01 00:04:36'),
(28, '2025-06-16', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0019', 20000.00, 0.00, 7670000.00, '2025-08-01 00:04:58', '2025-08-01 00:04:58'),
(29, '2025-08-01', 'Simpanan wajib Putri', 'Simpanan Wajib', 'savings', 'SW-0020', 20000.00, 0.00, 7690000.00, '2025-08-01 00:05:18', '2025-08-01 00:05:18'),
(30, '2025-02-23', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0021', 20000.00, 0.00, 7710000.00, '2025-08-01 00:06:30', '2025-08-01 00:06:30'),
(31, '2025-03-10', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0022', 20000.00, 0.00, 7730000.00, '2025-08-01 00:06:47', '2025-08-01 00:06:47'),
(32, '2025-04-21', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0023', 20000.00, 0.00, 7750000.00, '2025-08-01 00:07:15', '2025-08-01 00:07:15'),
(33, '2025-05-01', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0024', 20000.00, 0.00, 7770000.00, '2025-08-01 00:08:02', '2025-08-01 00:08:02'),
(34, '2025-06-28', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0025', 20000.00, 0.00, 7790000.00, '2025-08-01 00:08:26', '2025-08-01 00:08:26'),
(35, '2025-07-23', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0026', 20000.00, 0.00, 7810000.00, '2025-08-01 00:08:54', '2025-08-01 00:08:54'),
(36, '2025-08-01', 'Simpanan wajib Muhtar', 'Simpanan Wajib', 'savings', 'SW-0027', 20000.00, 0.00, 7830000.00, '2025-08-01 00:09:10', '2025-08-01 00:09:10'),
(37, '2025-01-26', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0028', 20000.00, 0.00, 7850000.00, '2025-08-01 00:10:04', '2025-08-01 00:10:04'),
(38, '2025-02-04', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0029', 20000.00, 0.00, 7870000.00, '2025-08-01 00:10:32', '2025-08-01 00:10:32'),
(39, '2025-04-12', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0030', 20000.00, 0.00, 7890000.00, '2025-08-01 00:11:03', '2025-08-01 00:11:03'),
(40, '2025-05-05', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0031', 20000.00, 0.00, 7910000.00, '2025-08-01 00:11:21', '2025-08-01 00:11:21'),
(41, '2025-06-10', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0032', 20000.00, 0.00, 7930000.00, '2025-08-01 00:11:44', '2025-08-01 00:11:44'),
(42, '2025-07-17', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0033', 20000.00, 0.00, 7950000.00, '2025-08-01 00:12:08', '2025-08-01 00:12:08'),
(43, '2025-08-01', 'Simpanan wajib Windi', 'Simpanan Wajib', 'savings', 'SW-0034', 20000.00, 0.00, 7970000.00, '2025-08-01 00:12:26', '2025-08-01 00:12:26'),
(44, '2025-01-27', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0035', 20000.00, 0.00, 7990000.00, '2025-08-01 00:14:02', '2025-08-01 00:14:02'),
(45, '2025-03-14', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0036', 20000.00, 0.00, 8010000.00, '2025-08-01 00:14:25', '2025-08-01 00:14:25'),
(46, '2025-04-23', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0037', 20000.00, 0.00, 8030000.00, '2025-08-01 00:14:49', '2025-08-01 00:14:49'),
(47, '2025-05-04', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0038', 20000.00, 0.00, 8050000.00, '2025-08-01 00:15:41', '2025-08-01 00:15:41'),
(48, '2025-06-20', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0039', 20000.00, 0.00, 8070000.00, '2025-08-01 00:16:02', '2025-08-01 00:16:02'),
(49, '2025-07-20', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0040', 20000.00, 0.00, 8090000.00, '2025-08-01 00:16:24', '2025-08-01 00:16:24'),
(50, '2025-08-01', 'Simpanan wajib Febri', 'Simpanan Wajib', 'savings', 'SW-0041', 20000.00, 0.00, 8110000.00, '2025-08-01 00:16:54', '2025-08-01 00:16:54'),
(51, '2025-01-05', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0003', 150000.00, 0.00, 8260000.00, '2025-08-01 00:19:45', '2025-08-01 00:19:45'),
(52, '2025-02-07', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0004', 200000.00, 0.00, 8460000.00, '2025-08-01 00:20:23', '2025-08-01 00:20:23'),
(53, '2025-03-15', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0005', 100000.00, 0.00, 8560000.00, '2025-08-01 00:20:53', '2025-08-01 00:20:53'),
(54, '2025-04-21', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0006', 300000.00, 0.00, 8860000.00, '2025-08-01 00:21:20', '2025-08-01 00:21:20'),
(55, '2025-08-01', 'Simpanan sukarela Putri', 'Simpanan Sukarela', 'savings', 'SSK-0007', 50000.00, 0.00, 8910000.00, '2025-08-01 00:22:10', '2025-08-01 00:22:10'),
(56, '2025-01-26', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0008', 500000.00, 0.00, 9410000.00, '2025-08-01 00:22:47', '2025-08-01 00:22:47'),
(57, '2025-03-19', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0009', 250000.00, 0.00, 9660000.00, '2025-08-01 00:23:08', '2025-08-01 00:23:08'),
(58, '2025-02-25', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0010', 350000.00, 0.00, 10010000.00, '2025-08-01 00:23:58', '2025-08-01 00:23:58'),
(59, '2025-04-26', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0011', 100000.00, 0.00, 10110000.00, '2025-08-01 00:24:17', '2025-08-01 00:24:17'),
(60, '2025-05-18', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0012', 1500000.00, 0.00, 11610000.00, '2025-08-01 00:25:18', '2025-08-01 00:25:18'),
(61, '2025-06-07', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0013', 50000.00, 0.00, 11660000.00, '2025-08-01 00:25:44', '2025-08-01 00:25:44'),
(62, '2025-08-01', 'Simpanan sukarela Natun', 'Simpanan Sukarela', 'savings', 'SSK-0014', 700000.00, 0.00, 12360000.00, '2025-08-01 00:26:24', '2025-08-01 00:26:24'),
(63, '2025-01-01', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0015', 1000000.00, 0.00, 13360000.00, '2025-08-01 00:27:14', '2025-08-01 00:27:14'),
(64, '2025-02-17', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0016', 600000.00, 0.00, 13960000.00, '2025-08-01 00:27:37', '2025-08-01 00:27:37'),
(65, '2025-03-23', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0017', 100000.00, 0.00, 14060000.00, '2025-08-01 00:27:56', '2025-08-01 00:27:56'),
(66, '2025-04-04', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0018', 300000.00, 0.00, 14360000.00, '2025-08-01 00:28:13', '2025-08-01 00:28:13'),
(67, '2025-05-06', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0019', 250000.00, 0.00, 14610000.00, '2025-08-01 00:29:03', '2025-08-01 00:29:03'),
(68, '2025-06-20', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0020', 850000.00, 0.00, 15460000.00, '2025-08-01 00:29:26', '2025-08-01 00:29:26'),
(69, '2025-07-10', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0021', 150000.00, 0.00, 15610000.00, '2025-08-01 00:29:47', '2025-08-01 00:29:47'),
(70, '2025-08-26', 'Simpanan sukarela Muhtar', 'Simpanan Sukarela', 'savings', 'SSK-0022', 200000.00, 0.00, 15810000.00, '2025-08-01 00:30:12', '2025-08-01 00:30:12'),
(71, '2025-01-27', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0023', 400000.00, 0.00, 16210000.00, '2025-08-01 00:32:02', '2025-08-01 00:32:02'),
(72, '2025-02-03', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0024', 100000.00, 0.00, 16310000.00, '2025-08-01 00:32:24', '2025-08-01 00:32:24'),
(73, '2025-03-30', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0025', 150000.00, 0.00, 16460000.00, '2025-08-01 00:32:51', '2025-08-01 00:32:51'),
(74, '2025-04-19', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0026', 350000.00, 0.00, 16810000.00, '2025-08-01 00:33:16', '2025-08-01 00:33:16'),
(75, '2025-05-17', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0027', 600000.00, 0.00, 17410000.00, '2025-08-01 00:33:46', '2025-08-01 00:33:46'),
(76, '2025-06-07', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0028', 650000.00, 0.00, 18060000.00, '2025-08-01 00:34:18', '2025-08-01 00:34:18'),
(77, '2025-07-10', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0029', 700000.00, 0.00, 18760000.00, '2025-08-01 00:34:36', '2025-08-01 00:34:36'),
(78, '2025-08-01', 'Simpanan sukarela Windi', 'Simpanan Sukarela', 'savings', 'SSK-0030', 2000000.00, 0.00, 20760000.00, '2025-08-01 00:34:56', '2025-08-01 00:34:56'),
(79, '2025-01-19', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0031', 900000.00, 0.00, 21660000.00, '2025-08-01 00:36:17', '2025-08-01 00:36:17'),
(80, '2025-02-12', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0032', 100000.00, 0.00, 21760000.00, '2025-08-01 00:36:35', '2025-08-01 00:36:35'),
(81, '2025-03-29', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0033', 200000.00, 0.00, 21960000.00, '2025-08-01 00:36:53', '2025-08-01 00:36:53'),
(82, '2025-04-27', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0034', 500000.00, 0.00, 22460000.00, '2025-08-01 00:37:13', '2025-08-01 00:37:13'),
(83, '2025-05-11', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0035', 300000.00, 0.00, 22760000.00, '2025-08-01 00:37:30', '2025-08-01 00:37:30'),
(84, '2025-06-26', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0036', 400000.00, 0.00, 23160000.00, '2025-08-01 00:37:46', '2025-08-01 00:37:46'),
(85, '2025-07-17', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0037', 650000.00, 0.00, 23810000.00, '2025-08-01 00:38:04', '2025-08-01 00:38:04'),
(86, '2025-08-01', 'Simpanan sukarela Febri', 'Simpanan Sukarela', 'savings', 'SSK-0038', 150000.00, 0.00, 23960000.00, '2025-08-01 00:38:17', '2025-08-01 00:38:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `installments`
--

CREATE TABLE `installments` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `installment_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `status` enum('Belum Bayar','Lunas','Telat') DEFAULT 'Belum Bayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `loan_date` date NOT NULL,
  `duration_months` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `remaining_amount` decimal(15,2) DEFAULT NULL,
  `status` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loan_interests`
--

CREATE TABLE `loan_interests` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `interest_date` date NOT NULL,
  `status` enum('Belum Bayar','Lunas') DEFAULT 'Belum Bayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `installment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loan_penalties`
--

CREATE TABLE `loan_penalties` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `penalty_date` date NOT NULL,
  `status` enum('Belum Bayar','Lunas') DEFAULT 'Belum Bayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `loan_settings`
--

CREATE TABLE `loan_settings` (
  `id` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `due_day` tinyint(2) NOT NULL,
  `penalty_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `loan_settings`
--

INSERT INTO `loan_settings` (`id`, `interest_rate`, `due_day`, `penalty_amount`, `created_at`, `updated_at`) VALUES
(1, 2.00, 10, 10000.00, '2025-08-05 22:03:08', '2025-08-05 22:03:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_number` varchar(50) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif',
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `birth_date` date NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `position` enum('Anggota','Ketua','Wakil Ketua','Sekretaris','Bendahara','Pengawas','Manajer','Staf') NOT NULL DEFAULT 'Anggota',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `members`
--

INSERT INTO `members` (`id`, `user_id`, `member_number`, `status`, `gender`, `birth_date`, `phone`, `address`, `position`, `created_at`, `updated_at`) VALUES
(1, 3, 'KOP-2025-001', 'Aktif', 'Perempuan', '2004-05-19', '085923163479', 'Taman Puspa Sarirogo N1-03, Sidoarjo', 'Wakil Ketua', '2025-07-17 07:57:55', '2025-07-20 23:47:27'),
(3, 4, 'KOP-2025-003', 'Aktif', 'Laki-laki', '2004-03-30', '082333151697', 'Sumenep', 'Ketua', '2025-07-17 21:11:32', '2025-07-20 19:09:25'),
(4, 2, 'KOP-2025-002', 'Aktif', 'Perempuan', '2004-03-09', '085712398498', 'Kalianget, Sumenep', 'Sekretaris', '2025-07-20 19:06:06', '2025-07-20 19:14:07'),
(6, 1, 'KOP-2025-004', 'Aktif', 'Perempuan', '2004-05-22', '085712345678', 'Bangkalan', 'Anggota', '2025-07-22 01:45:22', '2025-07-22 01:45:22'),
(8, 5, 'KOP-2025-005', 'Aktif', 'Perempuan', '2004-02-20', '085912345679', 'Madiun', 'Anggota', '2025-07-23 22:06:49', '2025-07-23 22:06:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(20) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `type` enum('Pokok','Wajib','Sukarela') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `saving_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `savings`
--

INSERT INTO `savings` (`id`, `custom_id`, `member_id`, `type`, `amount`, `saving_date`, `created_at`, `updated_at`) VALUES
(3, 'SP-0001', 1, 'Pokok', 50000.00, '2025-07-30', '2025-07-29 18:08:42', '2025-07-29 18:08:42'),
(6, 'SW-0001', 1, 'Wajib', 20000.00, '2025-07-30', '2025-07-29 18:36:03', '2025-07-29 18:36:03'),
(7, 'SSK-0001', 1, 'Sukarela', 40000.00, '2025-07-30', '2025-07-29 18:37:02', '2025-07-29 18:37:02'),
(8, 'SP-0002', 4, 'Pokok', 50000.00, '2025-07-30', '2025-07-30 01:56:07', '2025-07-30 01:56:07'),
(9, 'SW-0002', 4, 'Wajib', 20000.00, '2025-07-31', '2025-07-30 18:39:48', '2025-07-30 18:39:48'),
(10, 'SSK-0002', 4, 'Sukarela', 7000000.00, '2025-07-31', '2025-07-30 18:48:13', '2025-07-30 18:48:13'),
(11, 'SP-0003', 3, 'Pokok', 50000.00, '2024-12-08', '2025-07-31 23:48:36', '2025-07-31 23:48:36'),
(12, 'SP-0004', 6, 'Pokok', 50000.00, '2025-01-17', '2025-07-31 23:49:17', '2025-07-31 23:49:17'),
(13, 'SP-0005', 8, 'Pokok', 50000.00, '2025-02-25', '2025-07-31 23:49:52', '2025-07-31 23:49:52'),
(14, 'SW-0003', 3, 'Wajib', 20000.00, '2025-01-20', '2025-07-31 23:53:58', '2025-07-31 23:53:58'),
(15, 'SW-0004', 6, 'Wajib', 20000.00, '2025-03-01', '2025-07-31 23:55:23', '2025-07-31 23:55:23'),
(16, 'SW-0005', 8, 'Wajib', 20000.00, '2025-02-01', '2025-07-31 23:56:13', '2025-07-31 23:56:13'),
(17, 'SW-0006', 4, 'Wajib', 20000.00, '2025-01-01', '2025-07-31 23:58:20', '2025-07-31 23:58:20'),
(18, 'SW-0007', 4, 'Wajib', 20000.00, '2025-02-19', '2025-07-31 23:59:10', '2025-07-31 23:59:10'),
(19, 'SW-0008', 4, 'Wajib', 20000.00, '2025-03-09', '2025-07-31 23:59:31', '2025-07-31 23:59:31'),
(20, 'SW-0009', 4, 'Wajib', 20000.00, '2025-04-13', '2025-07-31 23:59:49', '2025-07-31 23:59:49'),
(21, 'SW-0010', 4, 'Wajib', 20000.00, '2025-05-31', '2025-08-01 00:00:10', '2025-08-01 00:00:10'),
(22, 'SW-0011', 4, 'Wajib', 20000.00, '2025-06-23', '2025-08-01 00:00:32', '2025-08-01 00:00:32'),
(23, 'SW-0012', 4, 'Wajib', 20000.00, '2025-07-30', '2025-08-01 00:00:59', '2025-08-01 00:00:59'),
(24, 'SW-0013', 4, 'Wajib', 20000.00, '2025-08-01', '2025-08-01 00:01:28', '2025-08-01 00:01:28'),
(25, 'SW-0014', 1, 'Wajib', 20000.00, '2025-01-06', '2025-08-01 00:02:44', '2025-08-01 00:02:44'),
(26, 'SW-0015', 1, 'Wajib', 20000.00, '2025-02-09', '2025-08-01 00:03:02', '2025-08-01 00:03:02'),
(27, 'SW-0016', 1, 'Wajib', 20000.00, '2025-03-08', '2025-08-01 00:03:20', '2025-08-01 00:03:20'),
(28, 'SW-0017', 1, 'Wajib', 20000.00, '2025-04-11', '2025-08-01 00:03:39', '2025-08-01 00:03:39'),
(29, 'SW-0018', 1, 'Wajib', 20000.00, '2025-05-13', '2025-08-01 00:04:36', '2025-08-01 00:04:36'),
(30, 'SW-0019', 1, 'Wajib', 20000.00, '2025-06-16', '2025-08-01 00:04:58', '2025-08-01 00:04:58'),
(31, 'SW-0020', 1, 'Wajib', 20000.00, '2025-08-01', '2025-08-01 00:05:18', '2025-08-01 00:05:18'),
(32, 'SW-0021', 3, 'Wajib', 20000.00, '2025-02-23', '2025-08-01 00:06:30', '2025-08-01 00:06:30'),
(33, 'SW-0022', 3, 'Wajib', 20000.00, '2025-03-10', '2025-08-01 00:06:47', '2025-08-01 00:06:47'),
(34, 'SW-0023', 3, 'Wajib', 20000.00, '2025-04-21', '2025-08-01 00:07:15', '2025-08-01 00:07:15'),
(35, 'SW-0024', 3, 'Wajib', 20000.00, '2025-05-01', '2025-08-01 00:08:02', '2025-08-01 00:08:02'),
(36, 'SW-0025', 3, 'Wajib', 20000.00, '2025-06-28', '2025-08-01 00:08:26', '2025-08-01 00:08:26'),
(37, 'SW-0026', 3, 'Wajib', 20000.00, '2025-07-23', '2025-08-01 00:08:54', '2025-08-01 00:08:54'),
(38, 'SW-0027', 3, 'Wajib', 20000.00, '2025-08-01', '2025-08-01 00:09:10', '2025-08-01 00:09:10'),
(39, 'SW-0028', 6, 'Wajib', 20000.00, '2025-01-26', '2025-08-01 00:10:04', '2025-08-01 00:10:04'),
(40, 'SW-0029', 6, 'Wajib', 20000.00, '2025-02-04', '2025-08-01 00:10:32', '2025-08-01 00:10:32'),
(41, 'SW-0030', 6, 'Wajib', 20000.00, '2025-04-12', '2025-08-01 00:11:03', '2025-08-01 00:11:03'),
(42, 'SW-0031', 6, 'Wajib', 20000.00, '2025-05-05', '2025-08-01 00:11:21', '2025-08-01 00:11:21'),
(43, 'SW-0032', 6, 'Wajib', 20000.00, '2025-06-10', '2025-08-01 00:11:44', '2025-08-01 00:11:44'),
(44, 'SW-0033', 6, 'Wajib', 20000.00, '2025-07-17', '2025-08-01 00:12:08', '2025-08-01 00:12:08'),
(45, 'SW-0034', 6, 'Wajib', 20000.00, '2025-08-01', '2025-08-01 00:12:26', '2025-08-01 00:12:26'),
(46, 'SW-0035', 8, 'Wajib', 20000.00, '2025-01-27', '2025-08-01 00:14:02', '2025-08-01 00:14:02'),
(47, 'SW-0036', 8, 'Wajib', 20000.00, '2025-03-14', '2025-08-01 00:14:25', '2025-08-01 00:14:25'),
(48, 'SW-0037', 8, 'Wajib', 20000.00, '2025-04-23', '2025-08-01 00:14:49', '2025-08-01 00:14:49'),
(49, 'SW-0038', 8, 'Wajib', 20000.00, '2025-05-04', '2025-08-01 00:15:41', '2025-08-01 00:15:41'),
(50, 'SW-0039', 8, 'Wajib', 20000.00, '2025-06-20', '2025-08-01 00:16:02', '2025-08-01 00:16:02'),
(51, 'SW-0040', 8, 'Wajib', 20000.00, '2025-07-20', '2025-08-01 00:16:24', '2025-08-01 00:16:24'),
(52, 'SW-0041', 8, 'Wajib', 20000.00, '2025-08-01', '2025-08-01 00:16:54', '2025-08-01 00:16:54'),
(53, 'SSK-0003', 1, 'Sukarela', 150000.00, '2025-01-05', '2025-08-01 00:19:45', '2025-08-01 00:19:45'),
(54, 'SSK-0004', 1, 'Sukarela', 200000.00, '2025-02-07', '2025-08-01 00:20:23', '2025-08-01 00:20:23'),
(55, 'SSK-0005', 1, 'Sukarela', 100000.00, '2025-03-15', '2025-08-01 00:20:53', '2025-08-01 00:20:53'),
(56, 'SSK-0006', 1, 'Sukarela', 300000.00, '2025-04-21', '2025-08-01 00:21:20', '2025-08-01 00:21:20'),
(57, 'SSK-0007', 1, 'Sukarela', 50000.00, '2025-08-01', '2025-08-01 00:22:10', '2025-08-01 00:22:10'),
(58, 'SSK-0008', 4, 'Sukarela', 500000.00, '2025-01-26', '2025-08-01 00:22:47', '2025-08-01 00:22:47'),
(59, 'SSK-0009', 4, 'Sukarela', 250000.00, '2025-03-19', '2025-08-01 00:23:08', '2025-08-01 00:23:08'),
(60, 'SSK-0010', 4, 'Sukarela', 350000.00, '2025-02-25', '2025-08-01 00:23:58', '2025-08-01 00:23:58'),
(61, 'SSK-0011', 4, 'Sukarela', 100000.00, '2025-04-26', '2025-08-01 00:24:17', '2025-08-01 00:24:17'),
(62, 'SSK-0012', 4, 'Sukarela', 1500000.00, '2025-05-18', '2025-08-01 00:25:18', '2025-08-01 00:25:18'),
(63, 'SSK-0013', 4, 'Sukarela', 50000.00, '2025-06-07', '2025-08-01 00:25:44', '2025-08-01 00:25:44'),
(64, 'SSK-0014', 4, 'Sukarela', 700000.00, '2025-08-01', '2025-08-01 00:26:24', '2025-08-01 00:26:24'),
(65, 'SSK-0015', 3, 'Sukarela', 1000000.00, '2025-01-01', '2025-08-01 00:27:14', '2025-08-01 00:27:14'),
(66, 'SSK-0016', 3, 'Sukarela', 600000.00, '2025-02-17', '2025-08-01 00:27:37', '2025-08-01 00:27:37'),
(67, 'SSK-0017', 3, 'Sukarela', 100000.00, '2025-03-23', '2025-08-01 00:27:56', '2025-08-01 00:27:56'),
(68, 'SSK-0018', 3, 'Sukarela', 300000.00, '2025-04-04', '2025-08-01 00:28:13', '2025-08-01 00:28:13'),
(69, 'SSK-0019', 3, 'Sukarela', 250000.00, '2025-05-06', '2025-08-01 00:29:03', '2025-08-01 00:29:03'),
(70, 'SSK-0020', 3, 'Sukarela', 850000.00, '2025-06-20', '2025-08-01 00:29:26', '2025-08-01 00:29:26'),
(71, 'SSK-0021', 3, 'Sukarela', 150000.00, '2025-07-10', '2025-08-01 00:29:47', '2025-08-01 00:29:47'),
(72, 'SSK-0022', 3, 'Sukarela', 200000.00, '2025-08-26', '2025-08-01 00:30:12', '2025-08-01 00:30:12'),
(73, 'SSK-0023', 6, 'Sukarela', 400000.00, '2025-01-27', '2025-08-01 00:32:02', '2025-08-01 00:32:02'),
(74, 'SSK-0024', 6, 'Sukarela', 100000.00, '2025-02-03', '2025-08-01 00:32:24', '2025-08-01 00:32:24'),
(75, 'SSK-0025', 6, 'Sukarela', 150000.00, '2025-03-30', '2025-08-01 00:32:51', '2025-08-01 00:32:51'),
(76, 'SSK-0026', 6, 'Sukarela', 350000.00, '2025-04-19', '2025-08-01 00:33:16', '2025-08-01 00:33:16'),
(77, 'SSK-0027', 6, 'Sukarela', 600000.00, '2025-05-17', '2025-08-01 00:33:46', '2025-08-01 00:33:46'),
(78, 'SSK-0028', 6, 'Sukarela', 650000.00, '2025-06-07', '2025-08-01 00:34:18', '2025-08-01 00:34:18'),
(79, 'SSK-0029', 6, 'Sukarela', 700000.00, '2025-07-10', '2025-08-01 00:34:36', '2025-08-01 00:34:36'),
(80, 'SSK-0030', 6, 'Sukarela', 2000000.00, '2025-08-01', '2025-08-01 00:34:56', '2025-08-01 00:34:56'),
(81, 'SSK-0031', 8, 'Sukarela', 900000.00, '2025-01-19', '2025-08-01 00:36:17', '2025-08-01 00:36:17'),
(82, 'SSK-0032', 8, 'Sukarela', 100000.00, '2025-02-12', '2025-08-01 00:36:35', '2025-08-01 00:36:35'),
(83, 'SSK-0033', 8, 'Sukarela', 200000.00, '2025-03-29', '2025-08-01 00:36:53', '2025-08-01 00:36:53'),
(84, 'SSK-0034', 8, 'Sukarela', 500000.00, '2025-04-27', '2025-08-01 00:37:13', '2025-08-01 00:37:13'),
(85, 'SSK-0035', 8, 'Sukarela', 300000.00, '2025-05-11', '2025-08-01 00:37:30', '2025-08-01 00:37:30'),
(86, 'SSK-0036', 8, 'Sukarela', 400000.00, '2025-06-26', '2025-08-01 00:37:46', '2025-08-01 00:37:46'),
(87, 'SSK-0037', 8, 'Sukarela', 650000.00, '2025-07-17', '2025-08-01 00:38:04', '2025-08-01 00:38:04'),
(88, 'SSK-0038', 8, 'Sukarela', 150000.00, '2025-08-01', '2025-08-01 00:38:17', '2025-08-01 00:38:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `keycloak_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'anggota',
  `email_verified_at` timestamp NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `keycloak_id`, `name`, `username`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_photo`) VALUES
(1, '8d96e762-5df4-4e9a-be71-3ce0fe4269cf', 'Windi Wulandari', 'windi123', 'windiwulandari@gmail.com', 'anggota', '2025-07-15 00:35:15', '$2y$12$WQ2Fhjmc4c3iXMAnkvxeROFTlKltN.WJUBV0E8LzuzuRF.URRgrE6', NULL, '2025-07-15 00:35:15', '2025-07-15 00:35:15', NULL),
(2, '43d85ee2-0d58-4c83-9116-e5483902b791', 'Hasanatun Fajariya', 'natun64', 'fajariyahasanatun@gmail.com', 'anggota', '2025-07-15 00:59:05', '$2y$12$M2OCNHu/x1DKWMQA2Xix2.Z8gJQGLcT2QYt3qDPzdZEWxVSpafRy.', NULL, '2025-07-15 00:59:05', '2025-07-15 00:59:05', NULL),
(3, '6a3addd7-9bc6-4f4d-a652-b7a94890cc5e', 'Putri Qurratu Aini', 'putri123', 'putriqurratu05@gmail.com', 'admin', '2025-07-15 22:03:47', '$2y$12$26o0dkwrFJUpdV05e8G7.OvTw9/l0zZPhTIQI.i0inuEg0UhAOK9u', NULL, '2025-07-15 22:03:47', '2025-07-21 20:39:09', 'storage/profile_photos/profile_1753085412.jpg'),
(4, 'fc6d42b0-c21b-4ef0-9180-a8e365f3900b', 'Muhammad Muhtar Riyansyah', 'riyansyah19', 'riyansyah3001@gmail.com', 'anggota', '2025-07-17 20:41:19', '$2y$12$fI8nwY.3lHJqF230JaEyX.qXpPqKozRAiBukAlpuEfRA6ZVAkAaN2', NULL, '2025-07-17 20:41:20', '2025-07-17 21:34:36', NULL),
(5, '5c06a9ea-deb0-4db0-85ef-46ed701118e6', 'Riska Febri Wahyuningtyas', 'febri123', 'febri123@gmail.com', 'anggota', '2025-07-22 19:27:42', '$2y$12$Tht7Q6HGFdj4NfPSN1kk/e9NEVfKLrZdOutuloS/nxkkJXx7e6hfu', NULL, '2025-07-22 19:27:43', '2025-07-23 21:11:49', 'storage/profile_photos/profile_1753330309.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cash_book`
--
ALTER TABLE `cash_book`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indeks untuk tabel `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indeks untuk tabel `loan_interests`
--
ALTER TABLE `loan_interests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `fk_installment` (`installment_id`);

--
-- Indeks untuk tabel `loan_penalties`
--
ALTER TABLE `loan_penalties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indeks untuk tabel `loan_settings`
--
ALTER TABLE `loan_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `custom_id` (`custom_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cash_book`
--
ALTER TABLE `cash_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT untuk tabel `installments`
--
ALTER TABLE `installments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `loan_interests`
--
ALTER TABLE `loan_interests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `loan_penalties`
--
ALTER TABLE `loan_penalties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `loan_settings`
--
ALTER TABLE `loan_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `installments`
--
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loan_interests`
--
ALTER TABLE `loan_interests`
  ADD CONSTRAINT `fk_installment` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_interests_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loan_penalties`
--
ALTER TABLE `loan_penalties`
  ADD CONSTRAINT `loan_penalties_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
